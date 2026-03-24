<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProfileController extends Controller
{
    /**
     * Show resident profile.
     */
    public function index()
    {
        $user = Auth::user();
        $resident = $user?->resident;

        if (!$resident) {
            return redirect()->route('resident.dashboard')->with('error', 'Resident profile not found.');
        }

        return view('resident.profile.index', [
            'resident' => $resident,
        ]);
    }

    /**
     * Show edit form.
     */
    public function edit()
    {
        $user = Auth::user();
        $resident = $user?->resident;

        if (!$resident) {
            return redirect()->route('resident.dashboard')->with('error', 'Resident profile not found.');
        }

        return view('resident.profile.edit', [
            'resident' => $resident,
        ]);
    }

    /**
     * Minimal account settings page.
     */
    public function settings()
    {
        $user = Auth::user();
        $resident = $user?->resident;

        if (!$resident) {
            return redirect()->route('resident.dashboard')->with('error', 'Resident profile not found.');
        }

        return view('resident.profile.settings', [
            'resident' => $resident,
        ]);
    }

    /**
     * Update resident profile.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $resident = $user?->resident;

        if (!$resident) {
             abort(403, 'Resident profile not found.');
        }

        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'contact_number' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id, // Validate against users table
            'block' => 'required|integer|min:1',
            'lot' => 'required|integer|min:1',
            'move_in_date' => 'required|date',
            'status' => 'required|string|in:active,inactive',
            'membership_type' => 'nullable|string|max:255',
            'property_type' => 'nullable|string|max:255',
            'lot_area' => 'nullable|numeric|min:0',
            'floor_area' => 'nullable|numeric|min:0',
            'password' => 'nullable|string|min:6',
            'photo' => 'nullable|image|max:2048',
        ]);

        try {
            DB::transaction(function () use ($request, $data, $user, $resident) {
                // 1. Update User (Auth info)
                $userUpdate = [
                    'email' => $data['email'],
                    'name' => $data['first_name'] . ' ' . $data['last_name'],
                ];
                
                if (!empty($data['password'])) {
                    $userUpdate['password'] = Hash::make($data['password']);
                }
                
                $user->update($userUpdate);

                // 2. Update Resident (Profile info)
                $residentUpdate = [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'email' => $data['email'], // Keep synced
                    'contact_number' => $data['contact_number'],
                    'block' => $data['block'],
                    'lot' => $data['lot'],
                    'move_in_date' => $data['move_in_date'],
                    'status' => $data['status'],
                    'membership_type' => $data['membership_type'] ?? $resident->membership_type,
                    'property_type' => $data['property_type'] ?? $resident->property_type,
                    'lot_area' => $data['lot_area'] ?? $resident->lot_area,
                    'floor_area' => $data['floor_area'] ?? $resident->floor_area,
                ];

                if (!empty($data['password'])) {
                    $residentUpdate['password'] = Hash::make($data['password']); // Keep synced for now (legacy support)
                }

                // Handle profile photo upload
                if ($request->hasFile('photo')) {
                    // Delete old photo if exists
                    if ($resident->photo && Storage::disk('public')->exists($resident->photo)) {
                        Storage::disk('public')->delete($resident->photo);
                    }
                    $residentUpdate['photo'] = $request->file('photo')->store('residents', 'public');
                }

                $resident->update($residentUpdate);
            });
        } catch (Throwable $e) {
             return back()->with('error', 'Failed to update profile: ' . $e->getMessage());
        }

        return redirect()->route('resident.profile.index')
                         ->with('success', 'Profile updated successfully.');
    }
}
