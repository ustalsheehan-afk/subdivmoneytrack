<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Announcement;
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
                $notifications = \App\Models\Notification::where('admin_id', auth()->id())
                    ->latest()
                    ->take(10)
                    ->get();
                $unreadCount = \App\Models\Notification::where('admin_id', auth()->id())
                    ->where('is_read', false)
                    ->count();
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
