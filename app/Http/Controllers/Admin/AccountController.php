<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Traits\LogsActivity;

class AccountController extends Controller
{
    use LogsActivity;

    public function __construct()
    {
        $this->middleware('permission:users.view')->only(['index']);
        $this->middleware('permission:users.create')->only(['create', 'store']);
        $this->middleware('permission:users.update')->only(['toggle', 'reset']);
    }

    /**
     * 🧾 Display all accounts (only admin + resident)
     */
    public function index()
    {
        $accounts = User::with('rbacRole')
            ->whereIn('role', ['admin', 'resident'])
            ->orderBy('id', 'desc')
            ->get();

        $roles = Role::orderBy('name')->get();

        return view('admin.accounts.index', compact('accounts', 'roles'));
    }

    /**
     * ➕ Show create account form
     */
    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return view('admin.accounts.create', compact('roles'));
    }

    /**
     * 💾 Store a new account
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role_id' => 'required|integer|exists:roles,id',
            'active' => 'nullable|boolean',
        ]);

        $role = Role::find($request->role_id);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'role_id' => $request->role_id,
            'active' => (bool) $request->boolean('active', true),
        ]);

        $this->logActivity('assigned_role', 'accounts', 'Assigned role ' . ($role?->name ?? 'unknown') . ' to user: ' . $user->name, [
            'user_id' => $user->id,
            'role_id' => $role?->id,
            'role_name' => $role?->name,
        ]);

        return redirect()->route('admin.accounts.index')
            ->with('success', 'Account successfully created!');
    }

    /**
     * 🔄 Reset password
     */
    public function reset($id)
    {
        $user = User::findOrFail($id);
        $user->password = Hash::make('password123'); // Default reset
        $user->save();

        return back()->with('success', 'Password has been reset to "password123".');
    }

    /**
     * 🚫 Toggle account active/inactive
     */
    public function toggle($id)
    {
        $user = User::findOrFail($id);
        $user->active = !$user->active;
        $user->save();

        return back()->with('success', 'Account status updated successfully.');
    }
}
