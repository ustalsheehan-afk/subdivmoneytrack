<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AnnouncementController extends Controller
{
    // Active announcements
    public function index(Request $request)
    {
        $query = Announcement::where('status', 'active')
            ->withCount('readers');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('month')) {
            $query->whereMonth('date_posted', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('date_posted', $request->year);
        }

        $query->orderByDesc('is_pinned')->orderByDesc('date_posted');
        $announcements = $query->get();
        $totalResidents = \App\Models\User::where('role', 'resident')->count();
        $draftsCount = Announcement::where('status', 'draft')->count();

        if ($request->ajax() && $request->has('load_more')) {
            return view('admin.announcements.partials.list', compact('announcements', 'totalResidents', 'draftsCount'))->render();
        }

        return view('admin.announcements.index', compact('announcements', 'totalResidents', 'draftsCount'));
    }

    public function create()
    {
        $categories = ['Maintenance', 'Meeting', 'Security', 'Event', 'Finance', 'Emergency'];
        return view('admin.announcements.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $isDraft = $request->input('status') === 'draft';

        $rules = [
            'title'        => $isDraft ? 'nullable|string|max:255' : 'required|string|max:255',
            'content'      => $isDraft ? 'nullable|string' : 'required|string',
            'category'     => $isDraft ? 'nullable|string' : 'required|string',
            'date_posted'  => $isDraft ? 'nullable' : 'required',
            'is_pinned'    => 'nullable|boolean',
            'pin_duration' => 'nullable|integer',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $request->validate($rules);

        $data = $request->only(['title', 'content', 'category', 'date_posted', 'is_pinned', 'status']);
        
        if ($request->filled('date_posted')) {
            $data['date_posted'] = Carbon::parse($request->date_posted, config('app.timezone'));
        } else {
            $data['date_posted'] = now();
        }

        // Ensure non-null values for DB constraints when saving as draft
        if ($isDraft) {
            $data['title'] = $data['title'] ?? 'Untitled Draft';
            $data['content'] = $data['content'] ?? '';
            $data['category'] = $data['category'] ?? 'General';
            $data['status'] = 'draft';
        } else {
            $data['status'] = 'active';
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('announcements', 'public');
            $data['image'] = $path;
        }

        if ($request->boolean('is_pinned') && $request->filled('pin_duration')) {
            $data['is_pinned'] = true;
            $data['pin_expires_at'] = Carbon::now()->addDays((int)$request->pin_duration);
        } else {
            $data['is_pinned'] = false;
            $data['pin_expires_at'] = null;
        }

        Announcement::create($data);

        if ($data['status'] === 'draft') {
            return redirect()->route('admin.announcements.drafts')
                ->with('success', 'Announcement saved as draft.');
        }

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement published successfully.');
    }

    public function edit(Announcement $announcement)
    {
        $categories = ['Maintenance', 'Meeting', 'Security', 'Event', 'Finance', 'Emergency'];
        return view('admin.announcements.edit', compact('announcement', 'categories'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $isDraft = $request->input('status') === 'draft';

        $rules = [
            'title'        => $isDraft ? 'nullable|string|max:255' : 'required|string|max:255',
            'content'      => $isDraft ? 'nullable|string' : 'required|string',
            'category'     => $isDraft ? 'nullable|string' : 'required|string',
            'date_posted'  => $isDraft ? 'nullable' : 'required',
            'is_pinned'    => 'nullable|boolean',
            'pin_duration' => 'nullable|integer',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $request->validate($rules);

        $data = $request->only(['title', 'content', 'category', 'date_posted', 'is_pinned']);
        
        // Ensure non-null values for DB constraints when saving as draft
        if ($isDraft) {
            $data['title'] = $data['title'] ?? ($announcement->title ?? 'Untitled Draft');
            $data['content'] = $data['content'] ?? ($announcement->content ?? '');
            $data['category'] = $data['category'] ?? ($announcement->category ?? 'General');
            $data['status'] = 'draft';
        }

        if ($request->filled('date_posted')) {
            $data['date_posted'] = Carbon::parse($request->date_posted, config('app.timezone'));
        }

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($announcement->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($announcement->image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($announcement->image);
            }
            $path = $request->file('image')->store('announcements', 'public');
            $data['image'] = $path;
        }

        if ($request->boolean('is_pinned') && $request->filled('pin_duration')) {
            $data['is_pinned'] = true;
            $data['pin_expires_at'] = Carbon::now()->addDays((int)$request->pin_duration);
        } else {
            $data['is_pinned'] = false;
            $data['pin_expires_at'] = null;
        }

        if ($request->has('status')) {
            $data['status'] = $request->status;
        }

        $announcement->update($data);

        if ($announcement->status === 'draft') {
            return redirect()->route('admin.announcements.drafts')
                ->with('success', 'Draft updated successfully.');
        }

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    public function togglePin(Announcement $announcement)
    {
        if ($announcement->is_pinned) {
            $announcement->update([
                'is_pinned' => false,
                'pin_expires_at' => null,
            ]);
            $message = 'Announcement unpinned successfully.';
        } else {
            $announcement->update([
                'is_pinned' => true,
                'pin_expires_at' => now()->addDays(7),
            ]);
            $message = 'Announcement pinned successfully.';
        }

        return redirect()->back()->with('success', $message);
    }

    public function public(Request $request)
    {
        $query = Announcement::where('status', 'active');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $query->orderByDesc('is_pinned')->orderByDesc('date_posted');
        $announcements = $query->paginate(10);

        return view('public.announcements.index', compact('announcements'));
    }

    // Trashed announcements
    public function trashed(Request $request)
    {
        $query = Announcement::where('status', 'trashed');

        if ($request->filled('month')) {
            $query->whereMonth('date_posted', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('date_posted', $request->year);
        }

        $announcements = $query->orderByDesc('date_posted')->get();
        $totalResidents = \App\Models\User::where('role', 'resident')->count();

        return view('admin.announcements.trashed-announcements', compact('announcements', 'totalResidents'));
    }

    // Archived announcements
    public function archive(Request $request)
    {
        $query = Announcement::where('status', 'archived');

        if ($request->filled('month')) {
            $query->whereMonth('date_posted', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('date_posted', $request->year);
        }

        $announcements = $query->orderByDesc('date_posted')->get();
        $totalResidents = \App\Models\User::where('role', 'resident')->count();

        return view('admin.announcements.archived-announcements', compact('announcements', 'totalResidents'));
    }

    // Drafts announcements
    public function drafts(Request $request)
    {
        $query = Announcement::where('status', 'draft');

        if ($request->filled('month')) {
            $query->whereMonth('date_posted', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('date_posted', $request->year);
        }

        $announcements = $query->orderByDesc('date_posted')->get();
        $totalResidents = \App\Models\User::where('role', 'resident')->count();

        return view('admin.announcements.drafts', compact('announcements', 'totalResidents'));
    }

    // Restore trashed announcement
    public function restore($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->update(['status' => 'active']);
        return redirect()->back()->with('success', 'Announcement restored successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        // Instead of hard deleting, move it to trash
        $announcement->update(['status' => 'trashed']);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement moved to trash successfully.');
    }

    public function archiveOne(Announcement $announcement)
    {
        // Move to archive status
        $announcement->update(['status' => 'archived']);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement moved to archive successfully.');
    }

    // Bulk restore trashed announcements
    public function bulkRestore(Request $request)
    {
        $request->validate([
            'announcements' => 'required|array',
            'announcements.*' => 'exists:announcements,id',
        ]);

        Announcement::whereIn('id', $request->announcements)
            ->update(['status' => 'active']);

        return redirect()->back()->with('success', 'Selected announcements have been restored.');
    }

    // Bulk Archive announcements
    public function bulkArchive(Request $request)
    {
        $request->validate([
            'announcements' => 'required|array',
            'announcements.*' => 'exists:announcements,id',
        ]);

        Announcement::whereIn('id', $request->announcements)
            ->update(['status' => 'archived']);

        return redirect()->back()->with('success', 'Selected announcements have been archived.');
    }

    // Bulk Trash announcements
    public function bulkTrash(Request $request)
    {
        $request->validate([
            'announcements' => 'required|array',
            'announcements.*' => 'exists:announcements,id',
        ]);

        Announcement::whereIn('id', $request->announcements)
            ->update(['status' => 'trashed']);

        return redirect()->back()->with('success', 'Selected announcements moved to trash.');
    }

    // Bulk Permanent Delete
    public function bulkForceDelete(Request $request)
    {
        $request->validate([
            'announcements' => 'required|array',
            'announcements.*' => 'exists:announcements,id',
        ]);

        Announcement::whereIn('id', $request->announcements)->delete();

        return redirect()->back()->with('success', 'Selected announcements permanently deleted.');
    }

    // Permanent delete
    public function forceDelete($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete(); 

        return redirect()->back()->with('success', 'Announcement permanently deleted.');
    }

    // Show single announcement
    public function show($id)
    {
        $announcement = Announcement::findOrFail($id);
        
        if (request()->ajax()) {
            return view('admin.announcements.partials.drawer', compact('announcement'))->render();
        }

        return view('admin.announcements.show', compact('announcement'));
    }

    // Badge color helper
    public static function categoryColor($category)
    {
        return match($category) {
            'Emergency' => 'bg-red-500 text-white',
            'Meeting'   => 'bg-blue-500 text-white',
            'Maintenance' => 'bg-yellow-400 text-black',
            'Security' => 'bg-gray-700 text-white',
            'Event'    => 'bg-green-500 text-white',
            'Finance'  => 'bg-purple-500 text-white',
            default    => 'bg-gray-300 text-black',
        };
    }

    // Human-readable date
    public static function humanDate($date)
    {
        if (!$date) return '-';
        $dt = Carbon::parse($date);
        if ($dt->isToday()) return 'Today';
        if ($dt->isYesterday()) return 'Yesterday';
        return $dt->format('M d, Y');
    }

    /**
 * Expire pinned announcements whose pin_expires_at has passed
 */
public static function expirePins(): void
{
    \App\Models\Announcement::whereNotNull('pin_expires_at')
        ->where('pin_expires_at', '<', now())
        ->update(['is_pinned' => false, 'pin_expires_at' => null]);
}

}
