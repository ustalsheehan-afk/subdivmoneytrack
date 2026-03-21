<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Controllers\Admin\AnnouncementController;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // ✅ Generate overdue penalties daily
        $schedule->command('penalties:generate-overdue')->daily();

        // ✅ Expire pinned announcements daily at midnight
        $schedule->call(function () {
            // Use a proper instance method instead of static call
            $controller = app(AnnouncementController::class);
            if (method_exists($controller, 'expirePinnedAnnouncements')) {
                $controller->expirePinnedAnnouncements();
            }
        })->dailyAt('00:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
