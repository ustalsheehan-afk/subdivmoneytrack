<?php

namespace App\Listeners;

use App\Events\PaymentStatusChanged;
use App\Services\NotificationService;

class SendPaymentStatusNotifications
{
    public function __construct(private NotificationService $notifications)
    {
    }

    public function handle(PaymentStatusChanged $event): void
    {
        $payment = $event->payment->loadMissing(['resident', 'due', 'penalty']);
        $resident = $payment->resident;

        if (! $resident) {
            return;
        }

        $itemTitle = $payment->penalty_id
            ? ($payment->penalty?->reason ?? 'Penalty')
            : ($payment->due?->title ?? 'Association Fee');

        if ($event->status === 'approved') {
            $this->notifications->notifyAdmins(
                type: NotificationService::TYPE_PAYMENT_COMPLETED,
                category: NotificationService::CATEGORY_PAYMENT,
                entityType: 'payment',
                entityId: $payment->id,
                title: 'Payment Completed',
                message: "{$resident->full_name} completed a payment of PHP " . number_format((float) $payment->amount, 2) . " for '{$itemTitle}'.",
                link: route('admin.payments.index'),
                dedupWindowHours: 24,
            );

            return;
        }

        if ($event->status === 'rejected') {
            $this->notifications->notifyAdmins(
                type: NotificationService::TYPE_PAYMENT_FAILED,
                category: NotificationService::CATEGORY_PAYMENT,
                entityType: 'payment',
                entityId: $payment->id,
                title: 'Payment Failed',
                message: "Payment #{$payment->id} for {$resident->full_name} was marked as rejected.",
                link: route('admin.payments.index'),
                dedupWindowHours: 24,
            );
        }
    }
}
