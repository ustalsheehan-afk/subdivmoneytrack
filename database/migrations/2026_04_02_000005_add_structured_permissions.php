<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('roles') || !Schema::hasTable('permissions') || !Schema::hasTable('role_permissions')) {
            return;
        }

        $guardName = config('auth.defaults.guard', 'web');
        $rolesHaveGuardName = Schema::hasColumn('roles', 'guard_name');
        $permissionsHaveGuardName = Schema::hasColumn('permissions', 'guard_name');

        $permissionKeyColumn = Schema::hasColumn('permissions', 'key')
            ? 'key'
            : (Schema::hasColumn('permissions', 'name') ? 'name' : null);

        if (!$permissionKeyColumn) {
            return;
        }

        $permissions = [
            'dashboard.view',

            'announcements.view',
            'announcements.create',
            'announcements.update',
            'announcements.delete',

            'support.view',
            'support.reply',
            'support.close',

            'notifications.view',
            'notifications.send',

            'residents.view',
            'residents.create',
            'residents.update',
            'residents.delete',

            'invitations.view',
            'invitations.create',
            'invitations.resend',
            'invitations.delete',

            'dues.view',
            'dues.create',
            'dues.update',
            'dues.delete',
            'dues.export',

            'payments.view',
            'payments.record',
            'payments.update',
            'payments.delete',
            'payments.export',

            'penalties.view',
            'penalties.create',
            'penalties.update',
            'penalties.delete',
            'penalties.export',

            'requests.view',
            'requests.approve',
            'requests.reject',
            'requests.update',

            'amenities.view',
            'amenities.create',
            'amenities.update',
            'amenities.delete',

            'reservations.view',
            'reservations.create',
            'reservations.approve',
            'reservations.cancel',
            'reservations.export',

            'board_members.view',
            'board_members.create',
            'board_members.update',
            'board_members.delete',

            'reports.view',
            'reports.export',

            'logs.view',
            'logs.export',

            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',

            'users.view',
            'users.create',
            'users.update',
            'users.delete',
        ];

        foreach ($permissions as $key) {
            $match = [$permissionKeyColumn => $key];
            $values = ['created_at' => now(), 'updated_at' => now()];
            if ($permissionsHaveGuardName) {
                $match['guard_name'] = $guardName;
                $values['guard_name'] = $guardName;
            }
            DB::table('permissions')->updateOrInsert($match, $values);
        }

        $roleId = function (string $name) use ($rolesHaveGuardName, $guardName) {
            $q = DB::table('roles')->where('name', $name);
            if ($rolesHaveGuardName) {
                $q->where('guard_name', $guardName);
            }
            return $q->value('id');
        };

        $permissionId = function (string $key) use ($permissionKeyColumn, $permissionsHaveGuardName, $guardName) {
            $q = DB::table('permissions')->where($permissionKeyColumn, $key);
            if ($permissionsHaveGuardName) {
                $q->where('guard_name', $guardName);
            }
            return $q->value('id');
        };

        $attach = function (int $rid, array $keys) use ($permissionId) {
            foreach ($keys as $key) {
                $pid = $permissionId($key);
                if (!$pid) {
                    continue;
                }
                DB::table('role_permissions')->updateOrInsert(
                    ['role_id' => $rid, 'permission_id' => $pid],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
        };

        $superAdminId = $roleId('super_admin');
        if ($superAdminId) {
            $attach($superAdminId, ['*']);
        }

        $adminId = $roleId('admin');
        if ($adminId) {
            $attach($adminId, array_values(array_filter($permissions, fn ($p) => $p !== 'roles.delete')));
        }

        $staffId = $roleId('staff');
        if ($staffId) {
            $attach($staffId, [
                'dashboard.view',
                'residents.view',
                'residents.update',
                'requests.view',
                'requests.approve',
                'payments.record',
                'support.view',
                'support.reply',
                'reservations.view',
            ]);
        }

        $auditorId = $roleId('auditor');
        if ($auditorId) {
            $attach($auditorId, [
                'dashboard.view',
                'announcements.view',
                'support.view',
                'notifications.view',
                'residents.view',
                'invitations.view',
                'dues.view',
                'payments.view',
                'penalties.view',
                'requests.view',
                'amenities.view',
                'reservations.view',
                'board_members.view',
                'reports.view',
                'reports.export',
                'logs.view',
                'logs.export',
                'roles.view',
                'users.view',
            ]);
        }
    }

    public function down(): void
    {
    }
};

