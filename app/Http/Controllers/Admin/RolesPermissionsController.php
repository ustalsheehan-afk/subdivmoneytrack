<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class RolesPermissionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:roles.view')->only(['index']);
        $this->middleware('permission:roles.update')->only(['toggle']);
    }

    public function index()
    {
        $roles = Role::with('permissions')->orderBy('name')->get();
        $permissionKeyColumn = Schema::hasColumn('permissions', 'key')
            ? 'key'
            : (Schema::hasColumn('permissions', 'name') ? 'name' : null);

        $permissions = $permissionKeyColumn
            ? Permission::orderBy($permissionKeyColumn)->get()
            : Permission::query()->get();

        return view('admin.system.roles-permissions', compact('roles', 'permissions'));
    }

    public function toggle(Request $request, Role $role)
    {
        $request->validate([
            'permission_key' => 'required|string',
            'enabled' => 'required|boolean',
        ]);

        if (Auth::user()?->role_id === $role->id && $request->boolean('enabled') === false) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot remove your own permissions.',
            ], 403);
        }

        $permissionKeyColumn = Schema::hasColumn('permissions', 'key')
            ? 'key'
            : (Schema::hasColumn('permissions', 'name') ? 'name' : null);

        if (!$permissionKeyColumn) {
            return response()->json([
                'success' => false,
                'message' => 'Permissions table is missing key column.',
            ], 500);
        }

        $permission = Permission::where($permissionKeyColumn, $request->permission_key)->first();
        if (!$permission) {
            return response()->json([
                'success' => false,
                'message' => 'Permission key not found.',
            ], 422);
        }

        if ($request->boolean('enabled')) {
            $role->permissions()->syncWithoutDetaching([$permission->id]);
        } else {
            $role->permissions()->detach([$permission->id]);
        }

        ActivityLog::create([
            'causer_id' => Auth::id(),
            'causer_type' => get_class(Auth::user()),
            'action' => 'updated_role_permission',
            'module' => 'rbac',
            'description' => 'Updated permission ' . $request->permission_key . ' for role ' . $role->name . ' (' . ($request->boolean('enabled') ? 'enabled' : 'disabled') . ')',
            'metadata' => [
                'role_id' => $role->id,
                'role_name' => $role->name,
                'permission_key' => $request->permission_key,
                'enabled' => $request->boolean('enabled'),
                'role' => Auth::user()->rbacRole->name ?? Auth::user()->role ?? null,
                'ip' => request()?->ip(),
            ],
        ]);

        return response()->json([
            'success' => true,
        ]);
    }
}
