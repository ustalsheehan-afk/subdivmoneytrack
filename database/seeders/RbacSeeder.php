<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class RbacSeeder extends Seeder
{
    public function run(): void
    {
        $guardName = config('auth.defaults.guard', 'web');
        $rolesHaveGuardName = Schema::hasTable('roles') && Schema::hasColumn('roles', 'guard_name');

        $roles = collect(['super_admin', 'admin', 'staff', 'auditor'])
            ->mapWithKeys(function ($name) use ($rolesHaveGuardName, $guardName) {
                $attrs = ['name' => $name];
                if ($rolesHaveGuardName) {
                    $attrs['guard_name'] = $guardName;
                }
                return [$name => Role::firstOrCreate($attrs)];
            });

        $permissionKeyColumn = Schema::hasTable('permissions') && Schema::hasColumn('permissions', 'key')
            ? 'key'
            : (Schema::hasTable('permissions') && Schema::hasColumn('permissions', 'name') ? 'name' : null);
        $permissionsHaveGuardName = Schema::hasTable('permissions') && Schema::hasColumn('permissions', 'guard_name');

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

        $permissions = collect($permissionKeys)->mapWithKeys(function ($key) use ($permissionKeyColumn, $permissionsHaveGuardName, $guardName) {
            if (!$permissionKeyColumn) {
                return [$key => null];
            }

            $attrs = [$permissionKeyColumn => $key];
            if ($permissionsHaveGuardName) {
                $attrs['guard_name'] = $guardName;
            }
            return [$key => Permission::firstOrCreate($attrs)];
        });

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

                'users.view',
                'users.create',
                'users.update',
                'users.delete',
            ],
            'staff' => [
                'access_admin_panel',
                'manage_residents',
                'approve_requests',
                'payments_penalties',
                'manage_support',

                'dashboard.view',
                'residents.view',
                'residents.update',
                'requests.view',
                'requests.approve',
                'payments.record',
                'support.view',
                'support.reply',
                'reservations.view',
            ],
            'auditor' => [
                'access_admin_panel',
                'view_audit_logs',
                'export_logs',
                'view_reports',

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
            ],
        ];

        foreach ($map as $roleName => $keys) {
            $role = $roles[$roleName];
            $permissionIds = collect($keys)->map(fn ($k) => $permissions[$k]?->id)->filter()->values()->all();
            $role->permissions()->syncWithoutDetaching($permissionIds);
        }

        $adminRole = $roles['admin'] ?? null;
        if ($adminRole) {
            User::query()
                ->where('role', 'admin')
                ->whereNull('role_id')
                ->update(['role_id' => $adminRole->id]);
        }

        $superAdminRole = $roles['super_admin'] ?? null;
        if ($superAdminRole) {
            User::query()
                ->where('role', '!=', 'resident')
                ->whereNull('role_id')
                ->update(['role_id' => $superAdminRole->id]);
        }
    }
}
