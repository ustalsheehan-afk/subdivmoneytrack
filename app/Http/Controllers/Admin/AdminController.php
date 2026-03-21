<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.dashboard'); // Make sure this view exists
    }

    /**
     * Example: Show a general admin info page.
     */
    public function info()
    {
        return view('admin.info'); // Optional view for future use
    }

    /**
     * Example: Handle an admin-wide action
     */
    public function settings(Request $request)
    {
        // Logic to update admin settings
        return back()->with('success', 'Settings updated!');
    }
}
