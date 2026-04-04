<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Resident;
use App\Models\MessageThread;
use App\Models\User;
use App\Models\Notification;
use App\Models\ActivityLog;
use App\Models\Penalty;
use App\Services\SupportIntelligenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    protected $intelligence;

    public function __construct(SupportIntelligenceService $intelligence)
    {
        $this->intelligence = $intelligence;
        $this->middleware('permission:support.view')->only(['index', 'show']);
        $this->middleware('permission:support.reply')->only(['reply']);
        $this->middleware('permission:support.close')->only(['updateStatus', 'performAction']);
    }

    public function index(Request $request)
    {
        $query = MessageThread::with(['resident', 'latestMessage', 'assignedAdmin'])
            ->withCount(['messages as unread_count' => function ($q) {
                $q->where('is_read', false)->where('sender_type', Resident::class);
            }]);

        // Status Filters
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Category Filters
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Priority Filters
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Assignment Filter
        if ($request->filled('assigned_to')) {
            if ($request->assigned_to === 'me') {
                $query->where('assigned_to', Auth::id());
            } elseif ($request->assigned_to === 'unassigned') {
                $query->whereNull('assigned_to');
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('resident', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            })->orWhere('subject', 'like', "%{$search}%");
        }

        $threads = $query->orderBy('priority', 'desc')
                        ->orderBy('last_message_at', 'desc')
                        ->get();

        if ($request->ajax()) {
            return response()->json([
                'threads' => $threads->map(function($t) {
                    $deadline = $t->metadata['sla_deadline'] ?? null;
                    $isOverdue = $deadline ? now()->gt($deadline) : false;
                    
                    return [
                        'id' => $t->id,
                        'resident_name' => $t->resident->full_name,
                        'initials' => substr($t->resident->first_name, 0, 1) . substr($t->resident->last_name, 0, 1),
                        'category' => ucfirst($t->category),
                        'intent' => $t->intent,
                        'priority' => $t->priority,
                        'assigned_to' => $t->assignedAdmin?->name,
                        'preview' => $t->latestMessage->body ?? 'No messages',
                        'time' => $t->last_message_at->diffForHumans(null, true),
                        'status' => $t->status,
                        'unread' => $t->unread_count > 0,
                        'overdue' => $isOverdue
                    ];
                })
            ]);
        }

        return view('admin.messages.index', compact('threads'));
    }

    public function show(MessageThread $thread)
    {
        $thread->load(['resident.user', 'messages.sender', 'assignedAdmin']);
        
        // Mark messages as read
        $thread->messages()->where('sender_type', Resident::class)->update(['is_read' => true]);

        // Mark associated notifications as read
        \App\Models\Notification::where('admin_id', Auth::id())
            ->where('link', 'like', '%' . route('admin.messages.index') . '%')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Intelligence data
        $suggestions = $this->intelligence->getSuggestions($thread);
        $templates = $this->intelligence->getTemplatesByCategory($thread->category ?? 'general');
        $actions = $this->intelligence->getContextualActions($thread);

        // Resident Context
        $resident = $thread->resident;
        $context = [
            'payment_status' => $resident->payment_status,
            'total_balance' => (float) $resident->total_balance,
            'past_requests_count' => $resident->requests()->count(),
            'violations_count' => $resident->penalties()->where('status', Penalty::STATUS_PENDING)->count(),
        ];

        if (request()->ajax()) {
            return response()->json([
                'id' => $thread->id,
                'name' => $thread->resident->full_name,
                'firstName' => $thread->resident->first_name,
                'unit' => "Blk {$resident->block} Lot {$resident->lot}",
                'initials' => substr($thread->resident->first_name, 0, 1) . substr($thread->resident->last_name, 0, 1),
                'category' => ucfirst($thread->intent ?? $thread->category),
                'intent' => $thread->intent,
                'status' => $thread->status,
                'priority' => $thread->priority,
                'assigned_to' => $thread->assignedAdmin?->name,
                'suggestions' => $suggestions,
                'templates' => $templates,
                'actions' => $actions,
                'resident_context' => $context,
                'messages' => $thread->messages->map(function($m) {
                    return [
                        'id' => $m->id,
                        'body' => $m->body,
                        'is_admin' => $m->isFromAdmin(),
                        'is_internal' => $m->is_internal,
                        'time' => $m->created_at->format('h:i A'),
                        'attachment' => $m->attachment ? asset('storage/' . $m->attachment) : null
                    ];
                })
            ]);
        }

        return view('admin.messages.show', compact('thread'));
    }

    public function reply(Request $request, MessageThread $thread)
    {
        $request->validate([
            'body' => 'required|string',
            'is_internal' => 'boolean',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $message = DB::transaction(function () use ($request, $thread) {
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('messages', 'public');
            }

            $isInternal = $request->boolean('is_internal');

            $msg = $thread->messages()->create([
                'sender_type' => User::class,
                'sender_id' => Auth::id(),
                'body' => $request->body,
                'attachment' => $attachmentPath,
                'is_internal' => $isInternal,
            ]);

            if (!$isInternal) {
                $thread->update([
                    'status' => 'replied',
                    'last_message_at' => now(),
                ]);

                // Notification for Resident
                Notification::create([
                    'resident_id' => $thread->resident_id,
                    'title' => 'New Reply from Support',
                    'message' => 'An administrator has replied to your inquiry: "' . $thread->subject . '"',
                    'type' => 'message',
                    'link' => route('resident.messages.show', $thread->id)
                ]);
            }

            // Activity Log
            ActivityLog::create([
                'causer_id' => Auth::id(),
                'causer_type' => User::class,
                'action' => $isInternal ? 'added_internal_note' : 'replied',
                'module' => 'messages',
                'description' => ($isInternal ? 'Admin added internal note regarding ' : 'Admin replied to resident ') . $thread->resident->full_name . ' in thread #' . $thread->id,
                'metadata' => [
                    'thread_id' => $thread->id,
                    'role' => Auth::user()->rbacRole->name ?? Auth::user()->role ?? null,
                    'ip' => request()?->ip(),
                ]
            ]);

            return $msg;
        });

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'body' => $message->body,
                    'is_admin' => true,
                    'is_internal' => $message->is_internal,
                    'time' => $message->created_at->format('h:i A'),
                    'attachment' => $message->attachment ? asset('storage/' . $message->attachment) : null
                ]
            ]);
        }

        return back()->with('success', 'Message sent successfully.');
    }

    public function performAction(Request $request, MessageThread $thread)
    {
        $request->validate([
            'action' => 'required|string',
        ]);

        $action = $request->action;
        $result = DB::transaction(function() use ($thread, $action) {
            $description = "Performed action: $action";
            $autoReply = null;

            switch($action) {
                case 'mark_in_progress':
                    $thread->update(['status' => 'in_progress']);
                    $autoReply = "I've marked this request as 'In Progress'. Our team is working on it.";
                    break;
                case 'mark_completed':
                    $thread->update(['status' => 'closed']);
                    $autoReply = "This request has been marked as 'Completed'. Thank you!";
                    break;
                case 'assign_to_me':
                    $thread->update(['assigned_to' => Auth::id()]);
                    $description = "Assigned thread to self";
                    break;
                case 'check_availability':
                    $autoReply = "I'm checking the availability for you. One moment please.";
                    break;
                case 'approve_reservation':
                    $thread->update(['status' => 'closed']);
                    $autoReply = "Your reservation has been approved! You can now view it in your 'My Reservations' section.";
                    break;
                case 'reject_reservation':
                    $thread->update(['status' => 'closed']);
                    $autoReply = "I'm sorry, but we cannot fulfill this reservation request at this time. Please check the app for available slots.";
                    break;
                case 'assign_staff':
                    $autoReply = "I've assigned a staff member to handle your maintenance request. They will be in touch shortly.";
                    break;
                case 'send_payment_link':
                    $autoReply = "You can settle your balance directly through the 'Payments' section in the app. Here is a quick link: " . route('resident.payments.index');
                    break;
                case 'mark_as_paid':
                    $thread->update(['status' => 'closed']);
                    $autoReply = "I've verified your payment. Thank you! Your account is now up to date.";
                    break;
                case 'escalate':
                    $thread->update(['priority' => 'high']);
                    $autoReply = "I've escalated your concern to our senior management team. We will get back to you as soon as possible.";
                    break;
                case 'close_thread':
                    $thread->update(['status' => 'closed']);
                    $autoReply = "This inquiry has been marked as resolved. If you have any other concerns, feel free to start a new thread.";
                    break;
                // Add more contextual actions here
            }

            if ($autoReply) {
                $thread->messages()->create([
                    'sender_type' => User::class,
                    'sender_id' => Auth::id(),
                    'body' => $autoReply,
                ]);
            }

            ActivityLog::create([
                'causer_id' => Auth::id(),
                'causer_type' => User::class,
                'action' => 'workflow_action',
                'module' => 'messages',
                'description' => $description,
                'metadata' => [
                    'thread_id' => $thread->id,
                    'action' => $action,
                    'role' => Auth::user()->rbacRole->name ?? Auth::user()->role ?? null,
                    'ip' => request()?->ip(),
                ]
            ]);

            return true;
        });

        return response()->json(['success' => true]);
    }

    public function updateStatus(Request $request, MessageThread $thread)
    {
        $request->validate([
            'status' => 'required|in:pending,replied,closed,in_progress'
        ]);

        $thread->update(['status' => $request->status]);

        ActivityLog::create([
            'causer_id' => Auth::id(),
            'causer_type' => User::class,
            'action' => 'updated_status',
            'module' => 'messages',
            'description' => "Updated status of thread #{$thread->id} to {$request->status}",
            'metadata' => [
                'thread_id' => $thread->id,
                'status' => $request->status,
                'role' => Auth::user()->rbacRole->name ?? Auth::user()->role ?? null,
                'ip' => request()?->ip(),
            ]
        ]);

        return response()->json(['success' => true]);
    }
}
