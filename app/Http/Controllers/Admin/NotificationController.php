<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markAllRead()
    {
        Notification::where('admin_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'All notifications marked as read.');
    }

    public function getSystemNotifications()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
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
                    'count' => \App\Models\AmenityReservation::where('status', 'pending')->count(),
                    'priority' => 'normal'
                ],
                'messages' => [
                    'count' => \App\Models\MessageThread::where('status', 'pending')->count(),
                    'priority' => 'normal'
                ],
                'system_notifications' => [
                    'count' => \App\Models\Notification::where('admin_id', $user->id)->where('is_read', false)->count(),
                    'priority' => 'normal'
                ],
            ]);
        } else {
            $resident = $user->resident;
            if (!$resident) return response()->json([]);

            $unpaidDues = \App\Models\Due::where('resident_id', $resident->id)->whereIn('status', ['unpaid', 'pending'])->get();
            $overdueCount = $unpaidDues->where('due_date', '<', now())->count();
            $warningCount = $unpaidDues->where('due_date', '>=', now())->where('due_date', '<=', now()->addDays(3))->count();
            
            return response()->json([
                'payments' => [
                    'count' => $unpaidDues->count(),
                    'priority' => $overdueCount > 0 ? 'critical' : ($warningCount > 0 ? 'warning' : 'normal')
                ],
                'requests' => [
                    'count' => \App\Models\ServiceRequest::where('resident_id', $resident->id)->whereIn('status', ['in progress', 'completed', 'approved', 'rejected'])->where('updated_at', '>', now()->subDays(7))->count(),
                    'priority' => 'normal'
                ],
                'reservations' => [
                    'count' => \App\Models\AmenityReservation::where('resident_id', $user->id)->whereIn('status', ['approved', 'rejected'])->where('updated_at', '>', now()->subDays(7))->count(),
                    'priority' => 'normal'
                ],
                'messages' => [
                    'count' => \App\Models\MessageThread::where('resident_id', $resident->id)
                        ->whereHas('messages', function($q) {
                            $q->where('is_read', false)->whereIn('sender_type', [\App\Models\User::class, \App\Models\Admin::class]);
                        })->count(),
                    'priority' => 'normal'
                ],
            ]);
        }
    }
}
