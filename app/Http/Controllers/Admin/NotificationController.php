<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:notifications.view');
    }

    public function markAllRead()
    {
        Notification::where('admin_id', Auth::id())
            ->where('role', Notification::ROLE_ADMIN)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'All notifications marked as read.');
    }

    public function getSystemNotifications()
    {
        $user = Auth::user();
        
        if ($user && $user->can('dashboard.view')) {
            $overdueDues = \App\Models\Due::where('status', 'unpaid')->where('due_date', '<', now())->count();
            $urgentRequests = \App\Models\ServiceRequest::where('status', 'pending')->where('priority', 'High')->count();
            $pendingRequests = \App\Models\ServiceRequest::where('status', 'pending')->where('priority', '!=', 'High')->count();

            return response()->json([
                'requests' => [
                    'count' => $urgentRequests + $pendingRequests,
                    'priority' => $urgentRequests > 0 ? 'critical' : 'normal'
                ],
                'payments' => [
                    'count' => \App\Models\Payment::where('status', 'pending')->count(),
                    'priority' => 'normal'
                ],
                'dues' => [
                    'count' => $overdueDues,
                    'priority' => $overdueDues > 0 ? 'critical' : 'normal'
                ],
                'reservations' => [
                    'count' => \App\Models\AmenityReservation::where('status', 'pending')->count() 
                              + \App\Models\Notification::where('admin_id', $user->id)
                                  ->where('type', 'reservation')
                                  ->where('is_read', false)
                                  ->count(),
                    'priority' => \App\Models\AmenityReservation::where('status', 'pending')->exists() ? 'normal' : 'normal'
                ],
                'messages' => [
                    'count' => \App\Models\MessageThread::where('status', 'pending')->count(),
                    'priority' => \App\Models\MessageThread::where('priority', 'urgent')->where('status', 'pending')->exists() ? 'critical' : 'normal'
                ],
                'system_notifications' => [
                    'count' => \App\Models\Notification::where('admin_id', $user->id)
                        ->where('role', \App\Models\Notification::ROLE_ADMIN)
                        ->where('is_read', false)
                        ->count(),
                    'priority' => 'normal'
                ],
            ]);
        }

        return response()->json([]);
    }
}
