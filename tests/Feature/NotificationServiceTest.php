<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\Resident;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_deduplicates_resident_notifications_within_window(): void
    {
        $resident = Resident::factory()->create();
        $service = app(NotificationService::class);

        $service->notifyResident(
            resident: $resident,
            type: NotificationService::TYPE_PAYMENT_REMINDER,
            category: NotificationService::CATEGORY_REMINDER,
            entityType: 'due',
            entityId: 99,
            title: 'Reminder',
            message: 'Payment due soon.',
            link: '/resident/payments',
            dedupWindowHours: 24,
        );

        $service->notifyResident(
            resident: $resident,
            type: NotificationService::TYPE_PAYMENT_REMINDER,
            category: NotificationService::CATEGORY_REMINDER,
            entityType: 'due',
            entityId: 99,
            title: 'Reminder',
            message: 'Payment due soon.',
            link: '/resident/payments',
            dedupWindowHours: 24,
        );

        $this->assertDatabaseCount('notifications', 1);
        $this->assertDatabaseHas('notifications', [
            'resident_id' => $resident->id,
            'role' => Notification::ROLE_RESIDENT,
            'type' => NotificationService::TYPE_PAYMENT_REMINDER,
            'entity_type' => 'due',
            'entity_id' => 99,
        ]);
    }

    public function test_it_separates_admin_and_resident_notification_streams(): void
    {
        $resident = Resident::factory()->create();
        $admin = User::factory()->create(['role' => 'admin']);
        $service = app(NotificationService::class);

        $service->notifyResident(
            resident: $resident,
            type: NotificationService::TYPE_PAYMENT_REMINDER,
            category: NotificationService::CATEGORY_REMINDER,
            entityType: 'due',
            entityId: 100,
            title: 'Reminder',
            message: 'Resident reminder.',
            link: '/resident/payments',
        );

        $service->notifyAdmin(
            admin: $admin,
            type: NotificationService::TYPE_PAYMENT_COMPLETED,
            category: NotificationService::CATEGORY_PAYMENT,
            entityType: 'payment',
            entityId: 200,
            title: 'Payment Completed',
            message: 'Resident paid.',
            link: '/admin/payments',
        );

        $this->assertCount(1, $service->getResidentDropdownNotifications($resident));
        $this->assertCount(1, $service->getAdminDropdownNotifications($admin));

        $this->assertDatabaseHas('notifications', [
            'resident_id' => $resident->id,
            'role' => Notification::ROLE_RESIDENT,
            'type' => NotificationService::TYPE_PAYMENT_REMINDER,
        ]);

        $this->assertDatabaseHas('notifications', [
            'admin_id' => $admin->id,
            'role' => Notification::ROLE_ADMIN,
            'type' => NotificationService::TYPE_PAYMENT_COMPLETED,
        ]);
    }
}
