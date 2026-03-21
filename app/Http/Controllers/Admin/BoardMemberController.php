<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoardMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BoardMemberController extends Controller
{
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
            $data['photo'] = $request->file('photo')->store('board-members', 'public');
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
            $data['photo'] = $request->file('photo')->store('board-members', 'public');
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
}
