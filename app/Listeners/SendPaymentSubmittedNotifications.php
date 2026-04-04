<?php

namespace App\Listeners;

use App\Events\PaymentSubmitted;
use App\Models\User;
use App\Services\NotificationService;

class SendPaymentSubmittedNotifications
{
    public function __construct(private NotificationService $notifications)
    {
    }

    public function handle(PaymentSubmitted $event): void
    {
        $payment = $event->payment->loadMissing(['resident', 'due', 'penalty']);
        $resident = $payment->resident;

        if (! $resident) {
            return;
        }

        $itemTitle = $payment->penalty_id
            ? ($payment->penalty?->reason ?? 'Penalty')
            : ($payment->due?->title ?? 'Association Fee');

        $this->notifications->notifyResident(
            resident: $resident,
            type: NotificationService::TYPE_PAYMENT_SUBMITTED,
            category: NotificationService::CATEGORY_PAYMENT,
            entityType: 'payment',
            entityId: $payment->id,
            title: 'Payment Submitted',
            message: "Your payment of PHP " . number_format((float) $payment->amount, 2) . " for '{$itemTitle}' is under review.",
            link: route('resident.payments.index'),
            dedupWindowHours: 24,
        );

        User::query()
            ->where(function ($query) {
                $query->where('role', 'admin')
                    ->orWhereHas('rbacRole', fn ($roleQuery) => $roleQuery->where('name', 'admin'));
            })
            ->get()
            ->each(function (User $admin) use ($resident, $payment, $itemTitle) {
                $this->notifications->notifyAdmin(
                    admin: $admin,
                    type: NotificationService::TYPE_PAYMENT_PENDING_REVIEW,
                    category: NotificationService::CATEGORY_PAYMENT,
                    entityType: 'payment',
                    entityId: $payment->id,
                    title: 'Payment Awaiting Review',
                    message: "{$resident->full_name} submitted a payment of PHP " . number_format((float) $payment->amount, 2) . " for '{$itemTitle}'.",
                    link: route('admin.payments.index'),
                    dedupWindowHours: 24,
                );
            });
    }
}
