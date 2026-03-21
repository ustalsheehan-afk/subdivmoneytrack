<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Resident;
use App\Models\Invitation;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InvitationController extends Controller
{
    /**
     * List invitations (Dashboard)
     */
    public function index()
    {
        $invitations = Invitation::orderBy('created_at', 'desc')->get();

        $stats = [
            'all' => $invitations->count(),
            'pending' => $invitations->where('status', Invitation::STATUS_PENDING)->count(),
            'accepted' => $invitations->where('status', Invitation::STATUS_ACCEPTED)->count(),
            'expired' => $invitations->where('status', Invitation::STATUS_EXPIRED)->count(),
            'expiring_soon' => $invitations->where('status', Invitation::STATUS_PENDING)
                ->where('expires_at', '<=', now()->addHours(24))
                ->where('expires_at', '>', now())
                ->count(),
        ];

        return view('admin.invitations.index', compact('invitations', 'stats'));
    }

    /**
     * Get invitation details for AJAX preview.
     */
    public function show($id)
    {
        $invitation = Invitation::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $invitation->id,
                'first_name' => $invitation->first_name,
                'last_name' => $invitation->last_name,
                'email' => $invitation->email,
                'phone' => $invitation->phone ?? 'N/A',
                'token' => $invitation->token,
                'status' => $invitation->status,
                'is_expired' => $invitation->isExpired(),
                'expires_at' => $invitation->expires_at->format('M d, Y h:i A'),
                'last_sent' => $invitation->last_sent_at ? $invitation->last_sent_at->diffForHumans() : 'Never',
                'registration_link' => route('register.invitation', ['token' => $invitation->token]),
                'platform_name' => config('app.name', 'Subdivision Dues System'),
                'activity' => [
                    [
                        'icon_bg' => 'bg-gray-900',
                        'title' => 'Invitation Created',
                        'time' => $invitation->created_at->diffForHumans(),
                    ],
                    [
                        'icon_bg' => 'bg-blue-500',
                        'title' => 'Status: ' . ucfirst($invitation->status),
                        'time' => $invitation->updated_at->diffForHumans(),
                    ],
                ],
            ]
        ]);
    }

    /**
     * 1. CREATE INVITATION
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:invitations,email|unique:users,email',
            'phone' => 'nullable|string|max:20',
        ]);

        $invitation = Invitation::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'token' => Str::random(40),
            'status' => Invitation::STATUS_PENDING,
            'expires_at' => Carbon::now()->addDays(7),
            'last_sent_at' => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'token' => $invitation->token,
            'link' => route('register.invitation', ['token' => $invitation->token]),
            'message' => 'Invitation created successfully.'
        ]);
    }

    /**
     * 2. RESEND
     */
    public function resend($id)
    {
        $invitation = Invitation::findOrFail($id);

        if ($invitation->status === Invitation::STATUS_ACCEPTED) {
            return response()->json(['success' => false, 'message' => 'Already accepted.'], 422);
        }

        if ($invitation->isExpired()) {
            return response()->json(['success' => false, 'message' => 'Expired. Please renew.'], 422);
        }

        $invitation->update(['last_sent_at' => Carbon::now()]);

        return response()->json([
            'success' => true,
            'link' => route('register.invitation', ['token' => $invitation->token]),
            'message' => 'Invitation resent.'
        ]);
    }

    /**
     * 3. RENEW
     * - Generate NEW token
     * - Reset expires_at
     * - Reset status to pending
     * - Clear accepted_at
     */
    public function renew($id)
    {
        $invitation = Invitation::findOrFail($id);

        if ($invitation->status === Invitation::STATUS_ACCEPTED) {
            return response()->json(['success' => false, 'message' => 'Already accepted.'], 422);
        }

        $invitation->update([
            'token' => Str::random(40),
            'expires_at' => Carbon::now()->addDays(7),
            'status' => Invitation::STATUS_PENDING,
            'accepted_at' => null,
            'last_sent_at' => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'token' => $invitation->token,
            'link' => route('register.invitation', ['token' => $invitation->token]),
            'message' => 'Invitation renewed successfully.'
        ]);
    }

    /**
     * CANCEL
     */
    public function cancel($id)
    {
        $invitation = Invitation::findOrFail($id);
        
        $invitation->update(['status' => Invitation::STATUS_CANCELLED]);

        return response()->json(['success' => true, 'message' => 'Cancelled.']);
    }
}
