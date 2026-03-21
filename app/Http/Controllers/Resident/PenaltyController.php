<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penalty;

class PenaltyController extends Controller
{
    /**
     * Display a listing of the penalties for the resident.
     */
    public function index()
    {
        // Get all penalties for the authenticated resident
        $user = auth()->user();
        $resident = $user->resident;

        if (!$resident) {
            abort(403, 'Resident profile not found.');
        }

        // Auto-update statuses to overdue if due_date passed
        Penalty::where('resident_id', $resident->id)
            ->where('status', 'unpaid')
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->update(['status' => 'overdue']);

        // Eager load resident relationship, though normally it's the same as the logged-in user
        $penalties = Penalty::with('resident')
                            ->where('resident_id', $resident->id)
                            ->latest()
                            ->get();

        return view('resident.penalties.index', compact('penalties'));
    }

    /**
     * Optional: show a single penalty detail.
     */
    public function show($id)
    {
        $user = auth()->user();
        $resident = $user->resident;

        if (!$resident) {
            abort(403, 'Resident profile not found.');
        }

        $penalty = Penalty::with(['resident', 'due'])
                          ->where('resident_id', $resident->id)
                          ->findOrFail($id);

        return view('resident.penalties.show', compact('penalty'));
    }
}
