<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoardMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BoardMemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:board_members.view')->only(['index']);
        $this->middleware('permission:board_members.create')->only(['create', 'store']);
        $this->middleware('permission:board_members.update')->only(['edit', 'update']);
        $this->middleware('permission:board_members.delete')->only(['destroy']);
    }

    private $positions = [
        'President',
        'Vice President',
        'Secretary',
        'Treasurer',
        'Auditor',
        'Public Relations Officer',
        'Board Member',
        'Adviser'
    ];

    public function index()
    {
        $members = BoardMember::orderBy('id')->get();
        return view('admin.board.index', compact('members'));
    }

    public function create()
    {
        $positions = $this->positions;
        return view('admin.board.create', compact('positions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'bio' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'facebook' => 'nullable|url|max:255',
        ]);

        $data = $request->all();
        $data['order_index'] = 0; // Default

        if ($request->hasFile('photo')) {
            Storage::disk('public')->makeDirectory('board-members');
            $file = $request->file('photo');
            $filename = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
            $path = $file->storePubliclyAs('board-members', $filename, 'public');
            $data['photo'] = $path;
        }

        BoardMember::create($data);

        return redirect()->route('admin.board.index')->with('success', 'Board member added successfully!');
    }

    public function edit(BoardMember $board)
    {
        $positions = $this->positions;
        return view('admin.board.edit', compact('board', 'positions'));
    }

    public function update(Request $request, BoardMember $board)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'bio' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'facebook' => 'nullable|url|max:255',
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($board->photo) {
                Storage::disk('public')->delete($board->photo);
            }
            Storage::disk('public')->makeDirectory('board-members');
            $file = $request->file('photo');
            $filename = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
            $path = $file->storePubliclyAs('board-members', $filename, 'public');
            $data['photo'] = $path;
        }

        $board->update($data);

        return redirect()->route('admin.board.index')->with('success', 'Board member updated successfully!');
    }

    public function destroy(BoardMember $board)
    {
        if ($board->photo) {
            Storage::disk('public')->delete($board->photo);
        }
        $board->delete();

        return redirect()->route('admin.board.index')->with('success', 'Board member deleted successfully!');
    }

    public function toggleStatus(BoardMember $board)
    {
        $board->update(['is_active' => !$board->is_active]);
        return response()->json(['success' => true]);
    }

    public function photo(string $path)
    {
        $path = ltrim($path, '/');

        // Prevent path traversal & keep scope tight to board member uploads.
        if (!str_starts_with($path, 'board-members/')) {
            abort(404);
        }

        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }

        // Use a filesystem path + response()->file() for broad compatibility and
        // to avoid relying on adapter-specific mimeType() methods.
        $fullPath = Storage::disk('public')->path($path);

        if (!is_file($fullPath)) {
            abort(404);
        }

        return response()->file($fullPath, [
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }
}
