<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role_id')) {
                $table->foreignId('role_id')->nullable()->after('role')->constrained('roles')->nullOnDelete();
            }
        });

        if (!Schema::hasTable('roles')) {
            return;
        }

        $guardName = config('auth.defaults.guard', 'web');
        $rolesHaveGuardName = Schema::hasColumn('roles', 'guard_name');

        $roleNames = ['super_admin', 'admin', 'staff', 'auditor'];
        foreach ($roleNames as $name) {
            $match = ['name' => $name];
            $values = ['created_at' => now(), 'updated_at' => now()];
            if ($rolesHaveGuardName) {
                $match['guard_name'] = $guardName;
                $values['guard_name'] = $guardName;
            }
            DB::table('roles')->updateOrInsert($match, $values);
        }

        if (Schema::hasColumn('users', 'role')) {
            $adminRoleQuery = DB::table('roles')->where('name', 'admin');
            if ($rolesHaveGuardName) {
                $adminRoleQuery->where('guard_name', $guardName);
            }
            $adminRoleId = $adminRoleQuery->value('id');
            if ($adminRoleId) {
                DB::table('users')
                    ->where('role', 'admin')
                    ->whereNull('role_id')
                    ->update(['role_id' => $adminRoleId]);
            }
        }

        if (Schema::hasTable('permissions') && Schema::hasTable('role_permissions')) {
            $permissionKeyColumn = Schema::hasColumn('permissions', 'key')
                ? 'key'
                : (Schema::hasColumn('permissions', 'name') ? 'name' : null);

            if (!$permissionKeyColumn) {
                return;
            }

            $permissionsHaveGuardName = Schema::hasColumn('permissions', 'guard_name');

            $permissionKeys = [
                '*',
                'access_admin_panel',
                'manage_users',
                'manage_residents',
                'approve_requests',
                'payments_penalties',
                'manage_support',
                'manage_invitations',
                'view_audit_logs',
                'export_logs',
                'view_reports',
                'system_settings',
            ];

            foreach ($permissionKeys as $key) {
                $match = [$permissionKeyColumn => $key];
                $values = ['created_at' => now(), 'updated_at' => now()];
                if ($permissionsHaveGuardName) {
                    $match['guard_name'] = $guardName;
                    $values['guard_name'] = $guardName;
                }
                DB::table('permissions')->updateOrInsert($match, $values);
            }

            $roleQuery = fn (string $name) => tap(DB::table('roles')->where('name', $name), function ($q) use ($rolesHaveGuardName, $guardName) {
                if ($rolesHaveGuardName) {
                    $q->where('guard_name', $guardName);
                }
            })->value('id');

            $permIdQuery = fn (string $key) => tap(DB::table('permissions')->where($permissionKeyColumn, $key), function ($q) use ($permissionsHaveGuardName, $guardName) {
                if ($permissionsHaveGuardName) {
                    $q->where('guard_name', $guardName);
                }
            });
            $permId = fn (string $key) => $permIdQuery($key)->value('id');

            $map = [
                'super_admin' => ['*'],
                'admin' => [
                    'access_admin_panel',
                    'manage_users',
                    'manage_residents',
                    'approve_requests',
                    'payments_penalties',
                    'manage_support',
                    'manage_invitations',
                    'view_audit_logs',
                    'export_logs',
                    'view_reports',
                    'system_settings',
                ],
                'staff' => [
                    'access_admin_panel',
                    'manage_residents',
                    'approve_requests',
                    'payments_penalties',
                    'manage_support',
                ],
                'auditor' => [
                    'access_admin_panel',
                    'view_audit_logs',
                    'export_logs',
                    'view_reports',
                ],
            ];

            foreach ($map as $roleName => $keys) {
                $rid = $roleQuery($roleName);
                if (!$rid) {
                    continue;
                }

                foreach ($keys as $k) {
                    $pid = $permId($k);
                    if (!$pid) {
                        continue;
                    }

                    DB::table('role_permissions')->updateOrInsert(
                        ['role_id' => $rid, 'permission_id' => $pid],
                        ['created_at' => now(), 'updated_at' => now()]
                    );
                }
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'role_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropConstrainedForeignId('role_id');
            });
        }
    }
};
