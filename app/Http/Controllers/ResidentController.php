<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Resident;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ResidentController extends Controller
{
    /**
     * Display a listing of the residents with search, sorting & pagination.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'name'); // default sort column
        $sortDir = $request->input('sort_dir', 'asc'); // default sort direction

        $allowedSorts = ['name', 'email', 'move_in_date'];

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'name';
        }

        $query = User::with('resident')->where('role', 'resident');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('resident', function ($sub) use ($search) {
                      $sub->where('block_lot', 'like', "%{$search}%")
                          ->orWhere('contact', 'like', "%{$search}%")
                          ->orWhere('contact_number', 'like', "%{$search}%")
                          ->orWhere('address', 'like', "%{$search}%");
                  });
            });
        }

        // Apply sorting
        if ($sortBy === 'move_in_date') {
            $query->join('residents', 'users.id', '=', 'residents.user_id')
                  ->orderBy('residents.move_in_date', $sortDir)
                  ->select('users.*');
        } else {
            $query->orderBy($sortBy, $sortDir);
        }

        $residents = $query->paginate(10)->appends([
            'search' => $search,
            'sort_by' => $sortBy,
            'sort_dir' => $sortDir,
        ]);

        if ($request->ajax()) {
            return view('admin.residents.partials.table', compact('residents', 'search', 'sortBy', 'sortDir'))->render();
        }

        return view('admin.residents.index', compact('residents', 'search', 'sortBy', 'sortDir'));
    }

    /**
     * Show the form for creating a new resident.
     */
    public function create()
    {
        return view('admin.residents.create');
    }

    /**
     * Store a newly created resident in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'contact' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'block_lot' => 'nullable|string|max:255',
            'move_in_date' => 'nullable|date',
            'password' => 'required|string|min:6|confirmed',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('residents', 'public');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'resident',
        ]);

        Resident::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'contact' => $validated['contact'] ?? $validated['contact_number'] ?? null,
            'contact_number' => $validated['contact_number'] ?? null,
            'address' => $validated['address'] ?? null,
            'block_lot' => $validated['block_lot'] ?? null,
            'move_in_date' => $validated['move_in_date'] ?? null,
            'photo' => $validated['photo'] ?? null,
        ]);

        return redirect()->route('admin.residents.index')
                         ->with('success', 'Resident created successfully.');
    }

    /**
     * Show the form for editing the specified resident.
     */
    public function edit($id)
    {
        $resident = User::with('resident')->findOrFail($id);
        return view('admin.residents.edit', compact('resident'));
    }

    /**
     * Update the specified resident in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $resident = $user->resident;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$user->id}",
            'contact' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'block_lot' => 'nullable|string|max:255',
            'move_in_date' => 'nullable|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('residents', 'public');

            // Delete old photo if exists
            if ($resident && $resident->photo) {
                Storage::disk('public')->delete($resident->photo);
            }
        }

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($resident) {
            $resident->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'contact' => $validated['contact'] ?? $validated['contact_number'] ?? $resident->contact,
                'contact_number' => $validated['contact_number'] ?? $resident->contact_number,
                'address' => $validated['address'],
                'block_lot' => $validated['block_lot'],
                'move_in_date' => $validated['move_in_date'],
                'photo' => $validated['photo'] ?? $resident->photo,
            ]);
        } else {
            Resident::create([
                'user_id' => $user->id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'contact' => $validated['contact'] ?? $validated['contact_number'] ?? null,
                'contact_number' => $validated['contact_number'] ?? null,
                'address' => $validated['address'],
                'block_lot' => $validated['block_lot'],
                'move_in_date' => $validated['move_in_date'],
                'photo' => $validated['photo'] ?? null,
            ]);
        }

        return redirect()->route('admin.residents.index')
                         ->with('success', 'Resident updated successfully.');
    }

    /**
     * Remove the specified resident from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->resident) {
            if ($user->resident->photo) {
                Storage::disk('public')->delete($user->resident->photo);
            }
            $user->resident->delete();
        }

        $user->delete();

        return redirect()->route('admin.residents.index')
                         ->with('success', 'Resident deleted successfully.');
    }
}
