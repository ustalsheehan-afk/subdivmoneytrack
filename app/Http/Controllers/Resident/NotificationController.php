<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
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
