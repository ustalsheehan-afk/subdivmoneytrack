<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Announcement;
use App\Models\Notification;
use App\Services\NotificationService;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Skip DB queries when running Artisan commands
        if ($this->app->runningInConsole()) {
            return;
        }

        try {
            // Cache the schema check result in a static variable to avoid repeated checks
            static $canRun = null;

            if ($canRun === null) {
                $canRun = Schema::hasTable('announcements') &&
                          Schema::hasColumn('announcements', 'pin_expires_at');
            }

            if ($canRun) {
                // Expire pinned announcements automatically
                Announcement::whereNotNull('pin_expires_at')
                    ->whereDate('pin_expires_at', '<', Carbon::today())
                    ->update([
                        'is_pinned' => false,
                        'pin_expires_at' => null,
                    ]);
            }

        } catch (\Exception $e) {
            // DB not ready or offline, silently skip
        }

        // Share notifications with admin layout
        view()->composer('layouts.admin', function ($view) {
            if (auth()->check()) {
                // Check if the column exists to avoid SQL errors during migration/sync
                static $hasAdminId = null;
                if ($hasAdminId === null) {
                    $hasAdminId = Schema::hasColumn('notifications', 'admin_id');
                }

                if ($hasAdminId) {
                    $notifications = app(NotificationService::class)
                        ->getAdminDropdownNotifications(auth()->user(), 10);
                    $unreadCount = Notification::where('admin_id', auth()->id())
                        ->where('role', Notification::ROLE_ADMIN)
                        ->where('is_read', false)
                        ->count();
                } else {
                    $notifications = collect();
                    $unreadCount = 0;
                }
                
                $view->with(compact('notifications', 'unreadCount'));
            }
        });
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
}
