<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\Announcement;
use App\Models\ServiceRequest;
use App\Models\Due;
use App\Models\Penalty;
use App\Models\BoardMember;

class DashboardController extends Controller
{
    public function index()
    {
        // Use the resident guard to get the User model
        $user = Auth::guard('resident')->user();
        
        // Get the associated Resident profile
        $resident = $user->resident;

        // Abort if resident profile is missing
        if (!$resident) {
            abort(403, 'Resident profile not found for this user account.');
        }

        // Summary of dues and payments
        $nextDue = Due::where('resident_id', $resident->id)
            ->where('status', 'unpaid')
            ->orderBy('due_date')
            ->first();

        $summary = [
            'outstanding_dues' => Due::where('resident_id', $resident->id)
                ->where('status', 'unpaid')
                ->sum('amount'),

            'total_paid' => Payment::where('resident_id', $resident->id)
                ->whereYear('date_paid', now()->year)
                ->where('status', 'approved')
                ->sum('amount'),

            'penalties' => Penalty::where('resident_id', $resident->id)
                ->where('status', 'unpaid')
                ->sum('amount'),
            
            'next_due_date' => $nextDue?->due_date,
            'next_due_amount' => $nextDue?->amount ?? 0,
            'next_due_title' => $nextDue?->title ?? 'Association Dues',
            'next_due_id' => $nextDue?->id,
        ];

        // Recent Announcements
        $recentAnnouncements = Announcement::where('status', 'active')
            ->latest('date_posted')
            ->take(5)
            ->get();

        // Upcoming Events (filtered from announcements with 'Event' category)
        $upcomingEvents = Announcement::where('status', 'active')
            ->where('category', 'Event')
            ->where('date_posted', '>=', now()->startOfDay())
            ->orderBy('date_posted')
            ->take(5)
            ->get();

        // Recent Requests
        $recentRequests = ServiceRequest::where('resident_id', $resident->id)
            ->latest()
            ->take(5)
            ->get();

        $activeRequestsCount = ServiceRequest::where('resident_id', $resident->id)
            ->whereIn('status', ['pending', 'open', 'in_progress'])
            ->count();

        // ============================
        // ACTIVITY TIMELINE LOGIC
        // ============================
        $timeline = collect();

        // 1. Add Payments to timeline
        Payment::where('resident_id', $resident->id)
            ->latest('date_paid')
            ->take(3)
            ->get()
            ->each(function($p) use ($timeline) {
                $timeline->push([
                    'type' => 'payment',
                    'title' => 'Payment received',
                    'description' => '₱' . number_format($p->amount, 2) . ' for ' . ($p->due?->title ?? 'Association Dues'),
                    'date' => $p->date_paid,
                    'icon' => 'bi-check-circle-fill',
                    'color' => 'emerald',
                    'tag' => 'Personal'
                ]);
            });

        // 2. Add Requests to timeline
        ServiceRequest::where('resident_id', $resident->id)
            ->latest()
            ->take(3)
            ->get()
            ->each(function($r) use ($timeline) {
                $statusColor = $r->status === 'approved' ? 'emerald' : ($r->status === 'pending' ? 'orange' : 'blue');
                $timeline->push([
                    'type' => 'request',
                    'title' => 'Request: ' . $r->title,
                    'description' => 'Status updated to ' . ucfirst($r->status),
                    'date' => $r->updated_at,
                    'icon' => 'bi-tools',
                    'color' => $statusColor,
                    'tag' => 'Personal'
                ]);
            });

        // 3. Add Announcements to timeline
        Announcement::where('status', 'active')
            ->latest('date_posted')
            ->take(3)
            ->get()
            ->each(function($a) use ($timeline) {
                $isUrgent = str_contains(strtolower($a->category), 'urgent');
                $timeline->push([
                    'type' => 'announcement',
                    'title' => $a->title,
                    'description' => $a->category,
                    'date' => $a->date_posted,
                    'icon' => 'bi-megaphone',
                    'color' => $isUrgent ? 'rose' : 'blue',
                    'tag' => $isUrgent ? 'Urgent' : 'Community'
                ]);
            });

        $activityTimeline = $timeline->sortByDesc('date')->take(6);

        return view('resident.home', compact(
            'resident',
            'summary',
            'recentAnnouncements',
            'upcomingEvents',
            'recentRequests',
            'activeRequestsCount',
            'activityTimeline'
        ));
    }

    public function board()
    {
        $boardMembers = BoardMember::where('is_active', true)
            ->orderBy('order_index')
            ->get();
            
        return view('resident.about.board', compact('boardMembers'));
    }
}
