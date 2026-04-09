<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Resident;
use App\Models\Invitation;
use App\Services\NotificationService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class InvitationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:invitations.view')->only(['index', 'show']);
        $this->middleware('permission:invitations.create')->only(['store']);
        $this->middleware('permission:invitations.resend')->only(['resend', 'renew']);
        $this->middleware('permission:invitations.delete')->only(['cancel']);
    }

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
        Log::info('Invitation store called', ['email' => $request->email, 'resident_id' => $request->resident_id]);
        $baseRules = [
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
        ];

        if ($request->filled('resident_id')) {
            $request->validate(array_merge($baseRules, [
                'resident_id' => 'required|integer|exists:residents,id',
            ]));

            $resident = Resident::find($request->resident_id);
            $email = trim((string) $request->email);
            $phone = $request->phone;
            $firstName = $resident?->first_name;
            $lastName = $resident?->last_name;

            if (!$firstName || !$lastName) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resident record is missing first name/last name.',
                ], 422);
            }

            $payload = [
                'resident_id' => $resident->id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'phone' => $phone,
            ];
        } else {
            $request->validate(array_merge($baseRules, [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
            ]));

            $payload = [
                'resident_id' => null,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => trim((string) $request->email),
                'phone' => $request->phone,
            ];
        }

        $existing = Invitation::where('email', $payload['email'])
            ->where('status', '!=', Invitation::STATUS_ACCEPTED)
            ->orderByDesc('id')
            ->first();

        $token = Str::random(64);
        $expiresAt = Carbon::now()->addDays(7);

        if ($existing) {
            $existing->update([
                'resident_id' => $payload['resident_id'],
                'first_name' => $payload['first_name'],
                'last_name' => $payload['last_name'],
                'phone' => $payload['phone'],
                'token' => $token,
                'status' => Invitation::STATUS_PENDING,
                'expires_at' => $expiresAt,
                'accepted_at' => null,
                'last_sent_at' => Carbon::now(),
            ]);
            $invitation = $existing;
            Log::info('Invitation updated for existing email', ['email' => $payload['email'], 'token' => $token]);
        } else {
            $invitation = Invitation::create([
                'resident_id' => $payload['resident_id'],
                'first_name' => $payload['first_name'],
                'last_name' => $payload['last_name'],
                'email' => $payload['email'],
                'phone' => $payload['phone'],
                'token' => $token,
                'status' => Invitation::STATUS_PENDING,
                'expires_at' => $expiresAt,
                'last_sent_at' => Carbon::now(),
            ]);
            Log::info('New invitation created', ['email' => $payload['email'], 'token' => $token]);
        }

        if (!$invitation->exists) {
            Log::error('Invitation creation failed', ['payload' => $payload]);
            return redirect()->route('admin.invitations.index')->with('error', 'Failed to create invitation record.');
        }

        // Send invitation via Email and SMS
        $notificationService = new NotificationService();
        $registrationLink = route('register.invitation', ['token' => $invitation->token]);
        $delivery = $notificationService->sendInvitation($invitation, $registrationLink);
        
        Log::info('Invitation notification dispatched', [
            'invitation_id' => $invitation->id,
            'delivery' => $delivery,
        ]);

        $emailAttempted = (bool) ($delivery['email']['attempted'] ?? false);
        $emailSuccess = (bool) ($delivery['email']['success'] ?? false);
        $smsAttempted = (bool) ($delivery['sms']['attempted'] ?? false);
        $smsSuccess = (bool) ($delivery['sms']['success'] ?? false);
        $smsError = trim((string) ($delivery['sms']['error'] ?? ''));
        $smsIsAuthIssue = $smsAttempted && !$smsSuccess && str_contains(strtolower($smsError), 'unauthenticated');

        // If email worked, don't block the invitation flow on temporary SMS provider auth issues.
        if ($emailSuccess && $smsIsAuthIssue) {
            Log::warning('Invitation SMS skipped due provider authentication issue', [
                'invitation_id' => $invitation->id,
                'sms_error' => $smsError,
            ]);

            return redirect()->route('admin.invitations.index')->with('success', 'Invitation created and email sent successfully. SMS delivery failed due to PhilSMS authentication.');
        }

        if (($emailAttempted && !$emailSuccess) || ($smsAttempted && !$smsSuccess)) {
            if ($emailSuccess) {
                Log::warning('Invitation SMS failed but email succeeded', [
                    'invitation_id' => $invitation->id,
                    'sms_error' => $smsError,
                ]);

                return redirect()->route('admin.invitations.index')->with('success', 'Invitation created and email sent successfully. SMS delivery failed.');
            }

            $failedChannels = [];

            if ($emailAttempted && !$emailSuccess) {
                $failedChannels[] = 'Email failed';
            }

            if ($smsAttempted && !$smsSuccess) {
                // If it's the "Sender ID" error, it's usually an account configuration issue on PhilSMS side
                if (str_contains(strtolower($smsError), 'sender id') && str_contains(strtolower($smsError), 'authorized')) {
                    $failedChannels[] = 'SMS skipped (PhilSMS Sender ID not configured)';
                } else {
                    $failedChannels[] = $smsError !== '' ? "SMS failed ({$smsError})" : 'SMS failed';
                }
            }

            $message = 'Invitation created successfully!';
            $message .= " Notification details: " . implode(' | ', $failedChannels);
            $message .= ". Registration link: {$registrationLink}";

            // If email succeeded, it's a success
            if ($emailSuccess) {
                return redirect()->route('admin.invitations.index')->with('success', $message);
            }

            return redirect()->route('admin.invitations.index')->with('error', $message);
        }

        return redirect()->route('admin.invitations.index')->with('success', 'Invitation created and notification sent successfully.');
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

        // Send invitation via Email and SMS
        $notificationService = new NotificationService();
        $registrationLink = route('register.invitation', ['token' => $invitation->token]);
        $delivery = $notificationService->sendInvitation($invitation, $registrationLink);
        
        Log::info('Invitation re-sent', [
            'invitation_id' => $invitation->id,
            'delivery' => $delivery,
        ]);

        $emailSuccess = (bool) ($delivery['email']['success'] ?? false);
        $smsSuccess = (bool) ($delivery['sms']['success'] ?? false);
        $emailAttempted = (bool) ($delivery['email']['attempted'] ?? false);
        $smsAttempted = (bool) ($delivery['sms']['attempted'] ?? false);
        $smsError = trim((string) ($delivery['sms']['error'] ?? ''));
        $smsIsAuthIssue = $smsAttempted && !$smsSuccess && str_contains(strtolower($smsError), 'unauthenticated');

        $errors = [];

        if ($emailAttempted && !$emailSuccess) {
            $errors[] = 'Email failed';
        }

        if ($smsAttempted && !$smsSuccess) {
            // If it's the "Sender ID" error, it's usually an account configuration issue on PhilSMS side
            if (str_contains(strtolower($smsError), 'sender id') && str_contains(strtolower($smsError), 'authorized')) {
                $errors[] = 'SMS skipped (PhilSMS Sender ID not configured)';
            } elseif ($smsIsAuthIssue && $emailSuccess) {
                // Email already delivered; keep resend successful and avoid noisy auth failure messaging.
                Log::warning('Resend SMS skipped due provider authentication issue', [
                    'invitation_id' => $invitation->id,
                    'sms_error' => $smsError,
                ]);
            } else {
                $errors[] = $smsError !== '' ? "SMS failed ({$smsError})" : 'SMS failed';
            }
        }

        $success = empty($errors) || $emailSuccess; // Consider success if at least email worked

        if ($emailSuccess && !$smsSuccess) {
            return response()->json([
                'success' => true,
                'link' => route('register.invitation', ['token' => $invitation->token]),
                'message' => 'Invitation resent by email successfully.'
            ]);
        }

        return response()->json([
            'success' => $success,
            'link' => route('register.invitation', ['token' => $invitation->token]),
            'message' => empty($errors) ? 'Invitation resent.' : ('Invitation resent with issues: ' . implode(' | ', $errors))
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
