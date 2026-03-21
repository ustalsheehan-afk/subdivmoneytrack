<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Due;
use App\Models\Payment;

class ResidentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $resident = $user->homeowner; // assuming `homeowner` is the resident profile

        // Redirect if the resident hasn't completed their profile
        if (!$resident) {
            return redirect()->route('resident.profile.setup')
                ->with('error', 'Please complete your homeowner information.');
        }

        // Outstanding dues
        $unpaidDues = Due::whereDoesntHave('payments', function ($query) use ($resident) {
            $query->where('homeowner_id', $resident->id);
        })->get();

        $outstandingAmount = $unpaidDues->sum('amount');

        // Recent payments
        $recentPayments = Payment::where('homeowner_id', $resident->id)
            ->latest('date_paid')
            ->take(5)
            ->get();

        // Total paid this year
        $totalPaid = Payment::where('homeowner_id', $resident->id)
            ->whereYear('date_paid', now()->year)
            ->sum('amount');

        // Current penalties
        $penalties = Payment::where('homeowner_id', $resident->id)
            ->where('status', 'pending')
            ->sum('penalty'); // assuming you have a penalty column

        return view('resident.dashboard', compact(
            'user',
            'resident',
            'unpaidDues',
            'recentPayments',
            'outstandingAmount',
            'totalPaid',
            'penalties'
        ));
    }
}
