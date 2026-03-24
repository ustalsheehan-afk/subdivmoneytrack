<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationModuleController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::where('admin_id', Auth::id());

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $isRead = $request->status === 'read';
            $query->where('is_read', $isRead);
        }

        $notifications = $query->latest()->paginate(20);

        return view('admin.messages.notifications', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->admin_id !== Auth::id()) {
            abort(403);
        }

        $notification->update(['is_read' => true]);

        if ($notification->link) {
            return redirect($notification->link);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead()
    {
        Notification::where('admin_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'All notifications marked as read.');
    }
}
