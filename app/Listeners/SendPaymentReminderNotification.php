<?php

namespace App\Listeners;

use App\Events\PaymentReminderTriggered;
use App\Services\NotificationService;

class SendPaymentReminderNotification
{
    public function __construct(private NotificationService $notifications)
    {
    }

    public function handle(PaymentReminderTriggered $event): void
    {
        $resident = $event->resident;

        $message = $event->overdueCount > 0
            ? "You have {$event->overdueCount} overdue payment(s). Please settle your account as soon as possible."
            : "You have {$event->unpaidCount} upcoming payment(s). Please arrange payment before the due date.";

        $this->notifications->notifyResident(
            resident: $resident,
            type: $event->overdueCount > 0
                ? NotificationService::TYPE_PAYMENT_OVERDUE
                : NotificationService::TYPE_PAYMENT_REMINDER,
            category: NotificationService::CATEGORY_REMINDER,
            entityType: 'resident',
            entityId: $resident->id,
            title: $event->overdueCount > 0 ? 'Overdue Payment Reminder' : 'Upcoming Payment Reminder',
            message: $message,
            link: route('resident.payments.index'),
            dedupWindowHours: 24,
        );
    }
}
