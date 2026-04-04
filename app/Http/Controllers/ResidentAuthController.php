<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ResidentAuthController extends Controller
{
    /**
     * Show the form to change the password for residents.
     */
    public function showChangePasswordForm()
    {
        return view('auth.resident-change-password');
    }

    /**
     * Update the resident's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $user->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        return redirect()->route('resident.home')->with('success', 'Password updated successfully. Welcome!');
    }
}
