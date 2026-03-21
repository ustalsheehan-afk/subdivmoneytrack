<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invitation;
use App\Models\User;
use App\Models\Resident;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Throwable;

class InvitationRegistrationController extends Controller
{
    /**
     * 4. ACCESS LINK
     * - Validation check for token access
     */
    public function show($token)
    {
        // Find by token
        $invitation = Invitation::where('token', trim($token))->first();

        // 1. If not found -> invalid
        if (!$invitation) {
            return view('auth.invitation-invalid', [
                'type' => 'invalid',
                'message' => 'Invalid invitation token.'
            ]);
        }

        // 2. If status == accepted -> already registered
        if ($invitation->status === Invitation::STATUS_ACCEPTED) {
            return view('auth.invitation-invalid', [
                'type' => 'accepted',
                'message' => 'This invitation has already been used to create an account.'
            ]);
        }

        // 3. If status == cancelled -> invalid
        if ($invitation->status === Invitation::STATUS_CANCELLED) {
            return view('auth.invitation-invalid', [
                'type' => 'cancelled',
                'message' => 'This invitation has been cancelled by the administrator.'
            ]);
        }

        // 4. If expires_at <= now -> expired
        if ($invitation->isExpired()) {
            return view('auth.invitation-invalid', [
                'type' => 'expired',
                'message' => 'This invitation link has expired.'
            ]);
        }

        // Success
        return view('auth.register-invitation', [
            'invitation' => $invitation,
        ]);
    }

    /**
     * 5. REGISTER
     * - Strict processing flow with transaction
     */
    public function register(Request $request, $token)
    {
        $invitation = Invitation::where('token', trim($token))->first();

        // Final validity check
        if (!$invitation || !$invitation->isValid()) {
            return back()->with('error', 'Invalid or expired invitation.');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'block' => 'required|string|max:50',
            'lot' => 'required|string|max:50',
            'move_in_date' => 'required|date',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            DB::beginTransaction();

            // Create User
            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $invitation->email,
                'password' => Hash::make($request->password),
                'role' => 'resident',
                'active' => true,
            ]);

            // Create Resident (FULL DATA)
            Resident::create([
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $invitation->email,
                'contact_number' => $request->contact_number,
                'block' => $request->block,
                'lot' => $request->lot,
                'move_in_date' => $request->move_in_date,
                'status' => 'active',
            ]);

            // Mark invitation as accepted
            $invitation->update([
                'status' => Invitation::STATUS_ACCEPTED,
                'accepted_at' => Carbon::now(),
            ]);

            DB::commit();

            // Clear any existing sessions (admin or resident) to ensure they see the login page
            auth()->logout();
            auth()->guard('admin')->logout();
            auth()->guard('resident')->logout();
            
            // Invalidate the current session and regenerate the token for safety
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            
            return redirect('/resident/login')->with('success', 'Registration successful! You can now log in to your resident portal.');

        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Registration Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Registration failed. Please try again or contact support.');
        }
    }
}
