<?php

namespace App\Providers;

use App\Events\BillingStatementCreated;
use App\Events\PaymentReminderTriggered;
use App\Events\PaymentStatusChanged;
use App\Events\PaymentSubmitted;
use App\Listeners\SendBillingStatementNotification;
use App\Listeners\SendPaymentReminderNotification;
use App\Listeners\SendPaymentStatusNotifications;
use App\Listeners\SendPaymentSubmittedNotifications;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        PaymentSubmitted::class => [
            SendPaymentSubmittedNotifications::class,
        ],
        PaymentStatusChanged::class => [
            SendPaymentStatusNotifications::class,
        ],
        BillingStatementCreated::class => [
            SendBillingStatementNotification::class,
        ],
        PaymentReminderTriggered::class => [
            SendPaymentReminderNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
