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
    public function index(Request $request)
    {
        $baseQuery = $this->invitationQuery($request);
        $filteredCount = (clone $baseQuery)->count();

        $invitations = (clone $baseQuery)
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $allInvitations = Invitation::query()->get();

        $stats = [
            'all' => $allInvitations->count(),
            'pending' => $allInvitations->where('status', Invitation::STATUS_PENDING)->count(),
            'accepted' => $allInvitations->where('status', Invitation::STATUS_ACCEPTED)->count(),
            'expired' => $allInvitations->where('status', Invitation::STATUS_EXPIRED)->count(),
            'expiring_soon' => $allInvitations->where('status', Invitation::STATUS_PENDING)
                ->where('expires_at', '<=', now()->addHours(24))
                ->where('expires_at', '>', now())
                ->count(),
        ];

        return view('admin.invitations.index', compact('invitations', 'stats', 'filteredCount'));
    }

    public function export(Request $request)
    {
        $filename = 'invitations-' . now()->format('Ymd-His') . '.csv';

        $query = $this->invitationQuery($request)
            ->orderByDesc('created_at');

        return response()->streamDownload(function () use ($query) {
            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($output, [
                'ID',
                'First Name',
                'Last Name',
                'Email',
                'Phone',
                'Status',
                'Email Delivery',
                'SMS Delivery',
                'Expires At',
                'Last Sent At',
                'Created At',
            ]);

            $query->chunk(200, function ($invitations) use ($output) {
                foreach ($invitations as $invitation) {
                    fputcsv($output, [
                        $invitation->id,
                        $invitation->first_name,
                        $invitation->last_name,
                        $invitation->email,
                        $invitation->phone,
                        $invitation->status,
                        $invitation->email_status,
                        $invitation->sms_status,
                        optional($invitation->expires_at)->format('Y-m-d H:i:s'),
                        optional($invitation->last_sent_at)->format('Y-m-d H:i:s'),
                        optional($invitation->created_at)->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($output);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Get invitation details for AJAX preview.
     */
    public function show($id)
    {
        $invitation = Invitation::findOrFail($id);

        $daysLeft = $invitation->expires_at
            ? now()->diffInDays($invitation->expires_at, false)
            : null;
        
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
                'days_left' => $daysLeft,
                'expires_at' => $invitation->expires_at->format('M d, Y h:i A'),
                'last_sent' => $invitation->last_sent_at ? $invitation->last_sent_at->diffForHumans() : 'Never',
                'registration_link' => route('register.invitation', ['token' => $invitation->token]),
                'platform_name' => config('app.name', 'Subdivision Dues System'),
                'email_status' => $invitation->email_status ?? Invitation::DELIVERY_PENDING,
                'sms_status' => $invitation->sms_status ?? Invitation::DELIVERY_PENDING,
                'created_at' => $invitation->created_at->format('M d, Y h:i A'),
                'updated_at' => $invitation->updated_at->format('M d, Y h:i A'),
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

                $smsReason = 'SMS delivery failed.';
                $normalizedSmsError = strtolower($smsError);

                if (str_contains($normalizedSmsError, 'sender id') && str_contains($normalizedSmsError, 'authorized')) {
                    $smsReason = 'SMS delivery failed: PhilSMS Sender ID is not authorized on your account.';
                } elseif (str_contains($normalizedSmsError, 'unauthenticated')) {
                    $smsReason = 'SMS delivery failed: PhilSMS API token authentication failed.';
                } elseif ($smsError !== '') {
                    $smsReason = "SMS delivery failed: {$smsError}";
                }

                return redirect()->route('admin.invitations.index')->with('success', "Invitation created and email sent successfully. {$smsReason}");
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

    private function invitationQuery(Request $request)
    {
        $query = Invitation::query();

        if ($request->filled('search')) {
            $search = trim((string) $request->search);

            $query->where(function ($builder) use ($search) {
                $builder->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status') && in_array($request->status, [
            Invitation::STATUS_PENDING,
            Invitation::STATUS_ACCEPTED,
            Invitation::STATUS_EXPIRED,
            Invitation::STATUS_CANCELLED,
        ], true)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('delivery')) {
            match ($request->delivery) {
                'email_sent' => $query->where('email_status', Invitation::DELIVERY_SENT),
                'sms_sent' => $query->where('sms_status', Invitation::DELIVERY_SENT),
                'pending' => $query->where(function ($builder) {
                    $builder->where('email_status', Invitation::DELIVERY_PENDING)
                        ->orWhere('sms_status', Invitation::DELIVERY_PENDING);
                }),
                'failed' => $query->where(function ($builder) {
                    $builder->where('email_status', Invitation::DELIVERY_FAILED)
                        ->orWhere('sms_status', Invitation::DELIVERY_FAILED);
                }),
                default => null,
            };
        }

        if ($request->filled('expiry')) {
            match ($request->expiry) {
                'active' => $query->where('status', Invitation::STATUS_PENDING)
                    ->where('expires_at', '>', now()),
                'expiring_soon' => $query->where('status', Invitation::STATUS_PENDING)
                    ->whereBetween('expires_at', [now(), now()->addHours(24)]),
                'expired' => $query->where(function ($builder) {
                    $builder->where('status', Invitation::STATUS_EXPIRED)
                        ->orWhere(function ($expiredBuilder) {
                            $expiredBuilder->where('status', Invitation::STATUS_PENDING)
                                ->where('expires_at', '<=', now());
                        });
                }),
                default => null,
            };
        }

        return $query;
    }
}
