<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Due;
use App\Models\Penalty;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class DuesController extends Controller
{
    /**
     * Display a listing of dues for the resident.
     */
    public function index()
    {
        $user = Auth::user();
        $resident = $user?->resident;

        if (!$resident) {
            return redirect()->route('resident.dashboard')->with('error', 'Resident profile not found.');
        }

        // Get all dues and attach payment info
        $dues = Due::where('resident_id', $resident->id)
            ->orderBy('due_date', 'desc')
            ->get()
            ->map(function ($due) use ($resident) {
                // Get the latest payment for this due
                $payment = Payment::where('resident_id', $resident->id)
                    ->where('due_id', $due->id)
                    ->latest()
                    ->first();

                $due->paid_amount = $payment->amount ?? 0;
                
                // If the due is marked as paid in the dues table, respect that.
                // Otherwise, check the latest payment status.
                if ($due->status === 'paid') {
                    $due->display_status = 'paid';
                } elseif ($payment) {
                    if ($payment->status === 'approved') {
                        $due->display_status = 'paid';
                    } elseif ($payment->status === 'pending') {
                        $due->display_status = 'pending';
                    } else {
                        $due->display_status = 'unpaid';
                    }
                } else {
                    $due->display_status = 'unpaid';
                }

                $due->date_paid = $payment->date_paid ?? null;
                $due->isOverdue = $due->due_date < Carbon::today() && !in_array($due->display_status, ['paid', 'pending']);

                return $due;
            });

        // Get all penalties, latest first by date_issued
        $penalties = Penalty::where('resident_id', $resident->id)
            ->orderBy('date_issued', 'desc') // ✅ latest penalties first
            ->get();

        // Prepare summary
        $summary = [
            'outstanding_dues' => $dues->where('display_status', 'unpaid')->sum('amount'),
            'total_paid'       => $dues->where('display_status', 'paid')->sum('amount'),
            'penalties'        => $penalties->sum('amount'),
        ];

        return view('resident.dues.index', compact('dues', 'penalties', 'summary'));
    }

    /**
     * Display the dues statement for the resident.
     */
    public function statement()
    {
        $user = Auth::user();
        $resident = $user?->resident;

        if (!$resident) {
            return redirect()->route('resident.dashboard')->with('error', 'Resident profile not found.');
        }

        $dues = Due::where('resident_id', $resident->id)
            ->orderBy('due_date', 'desc')
            ->get()
            ->map(function ($due) use ($resident) {
                $payment = Payment::where('resident_id', $resident->id)
                    ->where('due_id', $due->id)
                    ->first();

                $due->paid_amount = $payment->amount ?? 0;
                $due->status = $payment->status ?? 'Unpaid';
                $due->date_paid = $payment->date_paid ?? null;

                return $due;
            });

        return view('resident.dues.statement', compact('dues'));
    }

    /**
     * Download the dues statement as PDF.
     */
    public function downloadStatement()
    {
        $user = Auth::user();
        $resident = $user?->resident;

        if (!$resident) {
            return redirect()->route('resident.dashboard')->with('error', 'Resident profile not found.');
        }

        $dues = Due::where('resident_id', $resident->id)
            ->orderBy('due_date', 'desc')
            ->get()
            ->map(function ($due) use ($resident) {
                $payment = Payment::where('resident_id', $resident->id)
                    ->where('due_id', $due->id)
                    ->first();

                $due->paid_amount = $payment->amount ?? 0;
                $due->status = $payment->status ?? 'Unpaid';
                $due->date_paid = $payment->date_paid ?? null;

                return $due;
            });

        $pdf = Pdf::loadView('resident.dues.statement-pdf', compact('dues', 'resident'));
        return $pdf->download('statement-of-account.pdf');
    }
}
