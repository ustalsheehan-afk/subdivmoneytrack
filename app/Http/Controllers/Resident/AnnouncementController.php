<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        // Get category filter if selected
        $category = $request->query('category');

        // Build the query
        $query = Announcement::where('status', 'active')
            ->latest()
            ->orderByDesc('is_pinned');

        if ($category) {
            $query->where('category', $category);
        }

        // Check for read status
        $user = auth()->user();
        $announcements = $query->paginate(10);
        
        if ($user) {
            $announcements->each(function ($announcement) use ($user) {
                $announcement->is_read = $announcement->readers()->where('user_id', $user->id)->exists();
            });
        }

        return view('resident.announcements.index', compact('announcements'));
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
