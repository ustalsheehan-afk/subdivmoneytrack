<?php

namespace App\Listeners;

use App\Events\BillingStatementCreated;
use App\Services\NotificationService;

class SendBillingStatementNotification
{
    public function __construct(private NotificationService $notifications)
    {
    }

    public function handle(BillingStatementCreated $event): void
    {
        $due = $event->due->loadMissing('resident');

        if (! $due->resident) {
            return;
        }

        $this->notifications->notifyResident(
            resident: $due->resident,
            type: NotificationService::TYPE_BILLING_CREATED,
            category: NotificationService::CATEGORY_BILLING,
            entityType: 'due',
            entityId: $due->id,
            title: 'New Billing Statement',
            message: "A new billing statement '{$due->title}' for PHP " . number_format((float) $due->amount, 2) . " is now available.",
            link: route('resident.payments.index'),
            dedupWindowHours: 24,
        );
    }
}
