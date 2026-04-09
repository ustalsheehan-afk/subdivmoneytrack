<?php

namespace App\Services;

use App\Models\Invitation;
use App\Models\Notification;
use App\Models\Resident;
use App\Models\User;
use App\Jobs\SendInvitationEmail;
use App\Jobs\SendInvitationSMS;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class NotificationService
{
    public const CATEGORY_PAYMENT = 'payment';
    public const CATEGORY_BILLING = 'billing';
    public const CATEGORY_REMINDER = 'reminder';
    public const CATEGORY_ALERT = 'alert';

    public const TYPE_PAYMENT_SUBMITTED = 'PAYMENT_SUBMITTED';
    public const TYPE_PAYMENT_PENDING_REVIEW = 'PAYMENT_PENDING_REVIEW';
    public const TYPE_PAYMENT_COMPLETED = 'PAYMENT_COMPLETED';
    public const TYPE_PAYMENT_FAILED = 'PAYMENT_FAILED';
    public const TYPE_PAYMENT_REMINDER = 'PAYMENT_REMINDER';
    public const TYPE_PAYMENT_OVERDUE = 'PAYMENT_OVERDUE';
    public const TYPE_BILLING_CREATED = 'BILLING_CREATED';

    /**
     * Send an invitation via Email and SMS using background jobs.
     */
    public function sendInvitation(Invitation $invitation, string $registrationLink): array
    {
        $sendAsync = (bool) env('INVITATION_NOTIFICATIONS_ASYNC', false);
        $results = [
            'email' => [
                'attempted' => false,
                'success' => false,
                'error' => null,
            ],
            'sms' => [
                'attempted' => false,
                'success' => false,
                'error' => null,
            ],
        ];

        // 1. Email
        if ($invitation->email) {
            $results['email']['attempted'] = true;

            if ($sendAsync) {
                SendInvitationEmail::dispatch($invitation, $registrationLink);
                $results['email']['success'] = true;
            } else {
                $emailResult = (new SendInvitationEmail($invitation, $registrationLink))->handle();
                $results['email']['success'] = (bool) ($emailResult['success'] ?? false);
                $results['email']['error'] = $emailResult['error'] ?? null;
            }
        }

        // 2. SMS
        if ($invitation->phone) {
            $results['sms']['attempted'] = true;

            if ($sendAsync) {
                SendInvitationSMS::dispatch($invitation, $registrationLink);
                $results['sms']['success'] = true;
            } else {
                $smsResult = (new SendInvitationSMS($invitation, $registrationLink))->handle();
                $results['sms']['success'] = (bool) ($smsResult['success'] ?? false);
                $results['sms']['error'] = $smsResult['error'] ?? null;
            }
        }

        // 3. Update last sent timestamp
        $invitation->update([
            'last_sent_at' => Carbon::now()
        ]);

        return $results;
    }

    public function notifyResident(
        Resident $resident,
        string $type,
        string $category,
        string $entityType,
        int|string|null $entityId,
        string $title,
        string $message,
        ?string $link = null,
        int $dedupWindowHours = 24,
    ): ?Notification {
        return $this->store(
            role: Notification::ROLE_RESIDENT,
            userId: $resident->user_id,
            residentId: $resident->id,
            adminId: null,
            type: $type,
            category: $category,
            entityType: $entityType,
            entityId: $entityId,
            title: $title,
            message: $message,
            link: $link,
            dedupWindowHours: $dedupWindowHours,
        );
    }

    public function notifyAdmin(
        User $admin,
        string $type,
        string $category,
        string $entityType,
        int|string|null $entityId,
        string $title,
        string $message,
        ?string $link = null,
        int $dedupWindowHours = 24,
    ): ?Notification {
        return $this->store(
            role: Notification::ROLE_ADMIN,
            userId: $admin->id,
            residentId: null,
            adminId: $admin->id,
            type: $type,
            category: $category,
            entityType: $entityType,
            entityId: $entityId,
            title: $title,
            message: $message,
            link: $link,
            dedupWindowHours: $dedupWindowHours,
        );
    }

    public function notifyAdmins(
        string $type,
        string $category,
        string $entityType,
        int|string|null $entityId,
        string $title,
        string $message,
        ?string $link = null,
        int $dedupWindowHours = 24,
    ): Collection {
        return User::query()
            ->where(function ($query) {
                $query->where('role', 'admin')
                    ->orWhereHas('rbacRole', fn ($roleQuery) => $roleQuery->where('name', 'admin'));
            })
            ->get()
            ->map(function (User $admin) use ($type, $category, $entityType, $entityId, $title, $message, $link, $dedupWindowHours) {
                return $this->notifyAdmin(
                    admin: $admin,
                    type: $type,
                    category: $category,
                    entityType: $entityType,
                    entityId: $entityId,
                    title: $title,
                    message: $message,
                    link: $link,
                    dedupWindowHours: $dedupWindowHours,
                );
            })
            ->filter();
    }

    public function getResidentDropdownNotifications(Resident $resident, int $limit = 10)
    {
        return Notification::query()
            ->forResident($resident)
            ->latest()
            ->get()
            ->unique(fn (Notification $notification) => $notification->normalized_deduplication_key)
            ->take($limit)
            ->values();
    }

    public function getAdminDropdownNotifications(User $admin, int $limit = 10)
    {
        return Notification::query()
            ->forAdmin($admin)
            ->latest()
            ->get()
            ->unique(fn (Notification $notification) => $notification->normalized_deduplication_key)
            ->take($limit)
            ->values();
    }

    private function store(
        string $role,
        ?int $userId,
        ?int $residentId,
        ?int $adminId,
        string $type,
        string $category,
        string $entityType,
        int|string|null $entityId,
        string $title,
        string $message,
        ?string $link,
        int $dedupWindowHours,
    ): ?Notification {
        $deduplicationKey = $this->makeDeduplicationKey($role, $userId, $type, $entityType, $entityId);

        $existing = Notification::query()
            ->where('deduplication_key', $deduplicationKey)
            ->when($dedupWindowHours > 0, fn ($query) => $query->where('created_at', '>=', now()->subHours($dedupWindowHours)))
            ->latest()
            ->first();

        if ($existing) {
            return null;
        }

        return Notification::create([
            'resident_id' => $residentId,
            'admin_id' => $adminId,
            'user_id' => $userId,
            'role' => $role,
            'title' => Str::of($title)->ascii()->toString(),
            'message' => Str::of($message)->ascii()->toString(),
            'type' => $type,
            'category' => $category,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'deduplication_key' => $deduplicationKey,
            'link' => $link,
            'is_read' => false,
        ]);
    }

    private function makeDeduplicationKey(
        string $role,
        ?int $userId,
        string $type,
        string $entityType,
        int|string|null $entityId,
    ): string {
        return sha1(implode('|', [
            $role,
            $userId,
            $type,
            $entityType,
            (string) $entityId,
        ]));
    }
}
