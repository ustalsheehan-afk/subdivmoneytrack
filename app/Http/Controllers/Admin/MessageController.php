<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MessageThread;
use App\Models\Message;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\ActivityLog;
use App\Models\Notification;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $query = MessageThread::with(['resident', 'latestMessage'])
            ->withCount(['messages as unread_count' => function ($q) {
                $q->where('is_read', false)->where('sender_type', Resident::class);
            }]);

        // Status Filters: All, Pending, Replied, Closed
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('resident', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            })->orWhere('subject', 'like', "%{$search}%");
        }

        $threads = $query->orderBy('last_message_at', 'desc')->get();

        if ($request->ajax()) {
            return response()->json([
                'threads' => $threads->map(function($t) {
                    return [
                        'id' => $t->id,
                        'resident_name' => $t->resident->full_name,
                        'initials' => substr($t->resident->first_name, 0, 1) . substr($t->resident->last_name, 0, 1),
                        'category' => ucfirst($t->category),
                        'preview' => $t->latestMessage->body ?? 'No messages',
                        'time' => $t->last_message_at->diffForHumans(null, true),
                        'status' => $t->status,
                        'unread' => $t->unread_count > 0
                    ];
                })
            ]);
        }

        return view('admin.messages.index', compact('threads'));
    }

    public function show(MessageThread $thread)
    {
        $thread->load(['resident', 'messages.sender']);
        
        // Mark messages as read
        $thread->messages()->where('sender_type', Resident::class)->update(['is_read' => true]);

        if (request()->ajax()) {
            return response()->json([
                'id' => $thread->id,
                'name' => $thread->resident->full_name,
                'firstName' => $thread->resident->first_name,
                'initials' => substr($thread->resident->first_name, 0, 1) . substr($thread->resident->last_name, 0, 1),
                'category' => ucfirst($thread->category),
                'status' => $thread->status,
                'messages' => $thread->messages->map(function($m) {
                    return [
                        'id' => $m->id,
                        'body' => $m->body,
                        'is_admin' => $m->isFromAdmin(),
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
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $message = DB::transaction(function () use ($request, $thread) {
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('messages', 'public');
            }

            $msg = $thread->messages()->create([
                'sender_type' => get_class(Auth::user()),
                'sender_id' => Auth::id(),
                'body' => $request->body,
                'attachment' => $attachmentPath,
            ]);

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

            // Activity Log
            ActivityLog::create([
                'causer_id' => Auth::id(),
                'causer_type' => get_class(Auth::user()),
                'action' => 'replied',
                'module' => 'messages',
                'description' => 'Admin replied to resident ' . $thread->resident->full_name . ' in thread #' . $thread->id,
                'metadata' => ['thread_id' => $thread->id]
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
                    'time' => $message->created_at->format('h:i A'),
                    'attachment' => $message->attachment ? asset('storage/' . $message->attachment) : null
                ]
            ]);
        }

        return back()->with('success', 'Reply sent successfully.');
    }

    public function updateStatus(Request $request, MessageThread $thread)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,replied,closed',
        ]);

        $thread->update(['status' => $request->status]);

        // Activity Log for Status Change
        ActivityLog::create([
            'causer_id' => Auth::id(),
            'causer_type' => get_class(Auth::user()),
            'action' => 'updated_status',
            'module' => 'messages',
            'description' => 'Changed thread #' . $thread->id . ' status to ' . $request->status,
            'metadata' => ['thread_id' => $thread->id, 'new_status' => $request->status]
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Status updated successfully.');
    }
}
