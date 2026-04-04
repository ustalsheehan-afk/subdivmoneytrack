<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');

        $baseQuery = Announcement::query();

        $baseQuery->where('status', 'active');

        if ($category && $category !== 'All') {
            $baseQuery->where('category', $category);
        }

        $user = auth()->user();

        $pinnedQuery = (clone $baseQuery)->where('is_pinned', true)
            ->orderByDesc('date_posted')
            ->orderByDesc('created_at');

        $pinned = $pinnedQuery->get();

        $announcementsQuery = (clone $baseQuery)
            ->where(function ($q) {
                $q->where('is_pinned', false)->orWhereNull('is_pinned');
            })
            ->orderByDesc('date_posted')
            ->orderByDesc('created_at');

        $announcements = $announcementsQuery->paginate(10)->withQueryString();
        
        if ($user) {
            $applyReadFlag = function ($announcement) use ($user) {
                $announcement->is_read = $announcement->readers()
                    ->where('user_id', $user->id)
                    ->exists();
            };

            $pinned->each($applyReadFlag);
            $announcements->getCollection()->each($applyReadFlag);
        }

        return view('resident.announcements.index', compact('announcements', 'pinned'));
    }

    public function markAsRead(Request $request, Announcement $announcement)
    {
        $user = auth()->user();
        
        if (!$announcement->readers()->where('user_id', $user->id)->exists()) {
            $announcement->readers()->attach($user->id, ['read_at' => now()]);
        }

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    public function show(Announcement $announcement)
    {
        // Auto-mark as read when viewing
        $user = auth()->user();
        if (!$announcement->readers()->where('user_id', $user->id)->exists()) {
            $announcement->readers()->attach($user->id, ['read_at' => now()]);
        }

        // Pass is_read state to view
        $announcement->is_read = true;

        return view('resident.announcements.show', compact('announcement'));
    }
}
