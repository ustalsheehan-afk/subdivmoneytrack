<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        if (!Schema::hasTable('permissions')) {
            return;
        }

        Gate::before(function ($user, string $ability) {
            if (method_exists($user, 'hasPermission') && $user->hasPermission('*')) {
                return true;
            }
            return null;
        });

        try {
            $permissionKeyColumn = Schema::hasColumn('permissions', 'key')
                ? 'key'
                : (Schema::hasColumn('permissions', 'name') ? 'name' : null);

            if (!$permissionKeyColumn) {
                return;
            }

            $keys = \Illuminate\Support\Facades\DB::table('permissions')->pluck($permissionKeyColumn)->all();
            foreach ($keys as $key) {
                Gate::define($key, function ($user) use ($key) {
                    return method_exists($user, 'hasPermission') && $user->hasPermission($key);
                });
            }
        } catch (\Throwable $e) {
            return;
        }
    }
}
