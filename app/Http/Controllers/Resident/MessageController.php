<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\MessageThread;
use App\Models\Resident;
use App\Models\User;
use App\Models\Notification;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index()
    {
        $resident = Auth::user()->resident;

        if (!$resident) {
            abort(403, 'Resident profile not found.');
        }

        $threads = MessageThread::where('resident_id', $resident->id)
            ->with(['latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        return view('resident.messages.index', compact('threads'));
    }

    public function create(Request $request)
    {
        $moduleType = $request->query('module_type');
        $moduleId = $request->query('module_id');
        $subject = $request->query('subject');
        
        return view('resident.messages.create', compact('moduleType', 'moduleId', 'subject'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|string',
            'body' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'module_type' => 'nullable|string',
            'module_id' => 'nullable|integer',
        ]);

        $resident = Auth::user()->resident;

        if (!$resident) {
            abort(403, 'Resident profile not found.');
        }

        $thread = DB::transaction(function () use ($request, $resident) {
            $thread = MessageThread::create([
                'resident_id' => $resident->id,
                'subject' => $request->subject,
                'category' => $request->category,
                'status' => 'pending',
                'module_type' => $request->module_type,
                'module_id' => $request->module_id,
                'last_message_at' => now(),
            ]);

            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('messages', 'public');
            }

            $thread->messages()->create([
                'sender_type' => Resident::class,
                'sender_id' => $resident->id,
                'body' => $request->body,
                'attachment' => $attachmentPath,
            ]);

            // Notify Admins
            $admins = User::where('role', 'admin')->get();
            $resident_profile = Auth::user()->resident;
            foreach ($admins as $admin) {
                Notification::create([
                    'admin_id' => $admin->id,
                    'title' => '💬 New Support Message',
                    'message' => "{$resident_profile->full_name} has sent a new support message regarding '{$request->category}'.",
                    'type' => 'system',
                    'link' => route('admin.messages.index'),
                    'is_read' => false,
                ]);
            }

            return $thread;
        });

        return redirect()->route('resident.messages.show', $thread->id)->with('success', 'Message sent successfully.');
    }

    public function show(MessageThread $thread)
    {
        $resident = Auth::user()->resident;

        if (!$resident || $thread->resident_id !== $resident->id) {
            abort(403);
        }
        
        $thread->load(['messages.sender']);
        
        // Mark admin messages as read
        $thread->messages()->whereIn('sender_type', [User::class, Admin::class])->update(['is_read' => true]);

        // Mark notifications as read
        Notification::where('resident_id', Auth::user()->id)
            ->where('link', 'like', '%' . route('resident.messages.show', $thread->id) . '%')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('resident.messages.show', compact('thread'));
    }

    public function reply(Request $request, MessageThread $thread)
    {
        $resident = Auth::user()->resident;

        if (!$resident || $thread->resident_id !== $resident->id) {
            abort(403);
        }

        $request->validate([
            'body' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        DB::transaction(function () use ($request, $thread, $resident) {
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('messages', 'public');
            }

            $thread->messages()->create([
                'sender_type' => Resident::class,
                'sender_id' => $resident->id,
                'body' => $request->body,
                'attachment' => $attachmentPath,
            ]);

            $thread->update([
                'status' => 'pending', // Reset to pending for admin attention
                'last_message_at' => now(),
            ]);

            // Notify Admins
            $admins = User::where('role', 'admin')->get();
            $resident_profile = Auth::user()->resident;
            foreach ($admins as $admin) {
                Notification::create([
                    'admin_id' => $admin->id,
                    'title' => '💬 New Reply from Resident',
                    'message' => "{$resident_profile->full_name} replied to: '{$thread->subject}'.",
                    'type' => 'system',
                    'link' => route('admin.messages.index'),
                    'is_read' => false,
                ]);
            }
        });

        return back()->with('success', 'Reply sent successfully.');
    }
}
