<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    /**
     * 🧾 Display all accounts (only admin + resident)
     */
    public function index()
    {
        $accounts = User::whereIn('role', ['admin', 'resident'])
                        ->orderBy('id', 'desc')
                        ->get();

        return view('admin.accounts.index', compact('accounts'));
    }

    /**
     * ➕ Show create account form
     */
    public function create()
    {
        return view('admin.accounts.create');
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
            'role' => 'required|in:resident,admin',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'active' => true,
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
