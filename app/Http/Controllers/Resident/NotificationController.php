<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Show all notifications for the authenticated resident.
     */
    public function index()
    {
        $resident = Auth::user()->resident;

        if ($resident) {
            $resident->notifications()
                ->where('role', Notification::ROLE_RESIDENT)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        return view('resident.notifications.index');
    }

    /**
     * Return all notifications for the authenticated resident (latest first).
     */
    public function apiIndex()
    {
        $resident = Auth::user()->resident;

        $notifications = $resident
            ? $resident->notifications()
                ->where('role', Notification::ROLE_RESIDENT)
                ->latest()
                ->get()
                ->map(function (Notification $notification) {
                    return [
                        'id' => $notification->id,
                        'title' => $notification->title,
                        'message' => $notification->message,
                        'link' => $notification->link,
                        'category' => $notification->category,
                        'is_read' => (bool) $notification->is_read,
                        'created_at' => optional($notification->created_at)->toIso8601String(),
                    ];
                })
                ->values()
            : collect();

        return response()->json([
            'notifications' => $notifications,
        ]);
    }

    /**
     * Mark all notifications as read for the authenticated resident.
     */
    public function markAllRead()
    {
        $resident = Auth::user()->resident;
        
        if ($resident) {
            $resident->notifications()
                ->where('role', Notification::ROLE_RESIDENT)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Mark a single notification as read and redirect to its link.
     */
    public function show($id)
    {
        $resident = Auth::user()->resident;
        $notification = $resident->notifications()
            ->where('role', Notification::ROLE_RESIDENT)
            ->findOrFail($id);
        
        $notification->update(['is_read' => true]);

        if ($notification->link) {
            return redirect($notification->link);
        }

        return back();
    }
}
