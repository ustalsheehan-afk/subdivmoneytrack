<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\Announcement;
use App\Models\ServiceRequest;
use App\Models\Due;
use App\Models\Penalty;
use App\Models\Notification;
use App\Models\BoardMember;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Mark all notifications as read for the authenticated resident.
     */
    public function markAllNotificationsAsRead()
    {
        $resident = auth()->user()?->resident;

        if (!$resident) {
            return back()->with('error', 'Resident profile not found.');
        }

        Notification::where('resident_id', $resident->id)
            ->where('role', Notification::ROLE_RESIDENT)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'All notifications marked as read.');
    }

    public function index()
    {
        // Use the unified web guard
        $user = Auth::user();
        
        // Get the associated Resident profile
        $resident = $user?->resident;

        // Redirect to profile setup if resident profile is missing
        if (!$resident) {
            return redirect()->route('resident.profile.edit')->with('info', 'Please complete your resident profile to access the dashboard.');
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

        // Upcoming Events (use event announcements when available; otherwise fallback to seeded sample events)
        $upcomingEvents = $this->getUpcomingEvents();

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

    private function getUpcomingEvents()
    {
        $eventAnnouncements = Announcement::where('status', 'active')
            ->where('category', 'Event')
            ->where('date_posted', '>=', now()->startOfDay())
            ->orderBy('date_posted')
            ->get();

        if ($eventAnnouncements->isNotEmpty()) {
            return $eventAnnouncements->map(function ($announcement, $index) {
                return [
                    'id' => $announcement->id,
                    'title' => $announcement->title,
                    'date' => $announcement->date_posted,
                    'time' => $announcement->date_posted->format('g:i A'),
                    'location' => 'Clubhouse',
                ];
            });
        }

        return collect([
            [
                'id' => 1,
                'title' => 'Community Meeting',
                'date' => Carbon::parse('2026-04-15'),
                'time' => '6:00 PM',
                'location' => 'Clubhouse',
            ],
            [
                'id' => 2,
                'title' => 'Garage Sale',
                'date' => Carbon::parse('2026-04-20'),
                'time' => '8:00 AM',
                'location' => 'Main Street',
            ],
        ]);
    }

    public function events()
    {
        $events = collect([
            [
                'id' => 1,
                'title' => 'Community Meeting',
                'date' => Carbon::parse('2026-04-15'),
                'time' => '6:00 PM',
                'location' => 'Clubhouse',
            ],
            [
                'id' => 2,
                'title' => 'Garage Sale',
                'date' => Carbon::parse('2026-04-20'),
                'time' => '8:00 AM',
                'location' => 'Main Street',
            ],
        ])->sortBy('date')->values();

        return view('resident.events.index', compact('events'));
    }

    public function board()
    {
        $boardMembers = BoardMember::where('is_active', true)
            ->orderBy('order_index')
            ->get();
            
        return view('resident.about.board', compact('boardMembers'));
    }
}
