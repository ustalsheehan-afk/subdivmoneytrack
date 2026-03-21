<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportMessage;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = SupportMessage::with('resident');

        // Filtering
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('message', 'like', "%{$search}%")
                  ->orWhereHas('resident', function($rq) use ($search) {
                      $rq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }
        if ($request->filled('date')) {
            $dateFilter = $request->date;
            if ($dateFilter === 'today') {
                $query->whereDate('created_at', now());
            } elseif ($dateFilter === 'week') {
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($dateFilter === 'month') {
                $query->whereMonth('created_at', now()->month);
            }
        }

        $messages = $query->latest()->paginate(15)->withQueryString();

        // Summary Counts
        $summary = [
            'pending' => SupportMessage::where('status', 'pending')->count(),
            'replied' => SupportMessage::where('status', 'replied')->count(),
            'unread' => SupportMessage::where('is_read_by_admin', false)->count(),
        ];

        return view('admin.support.index', compact('messages', 'summary'));
    }

    /**
     * Mark message as Under Review when opened (AJAX)
     */
    public function markAsRead($id)
    {
        $message = SupportMessage::findOrFail($id);
        
        // We can't change status to 'under review' because of enum constraint, 
        // so we use is_read_by_admin flag to track it.
        $message->update(['is_read_by_admin' => true]);

        return response()->json(['success' => true]);
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'admin_reply' => 'required|string|max:2000',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $message = SupportMessage::findOrFail($id);
        
        // Ensure we have a valid admin ID
        $adminId = auth('admin')->id() ?? auth()->id();

        if (!$adminId) {
            return back()->with('error', 'Unable to identify admin. Please re-login.');
        }

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('support_attachments', 'public');
        }

        $message->update([
            'admin_reply' => $request->admin_reply,
            'admin_attachment' => $attachmentPath,
            'status' => 'replied',
            'replied_at' => now(),
            'replied_by' => $adminId,
        ]);

        // Mark admin notification as read
        \App\Models\Notification::where('admin_id', $adminId)
            ->where('link', route('admin.support.index'))
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Notify Resident
        Notification::create([
            'resident_id' => $message->resident_id,
            'title' => '💬 New Support Reply',
            'message' => "The office has replied to your concern: '{$message->category}'",
            'type' => 'system',
            'link' => route('resident.support.index'),
            'is_read' => false,
        ]);

        return back()->with('success', 'Reply sent successfully.');
    }
}
