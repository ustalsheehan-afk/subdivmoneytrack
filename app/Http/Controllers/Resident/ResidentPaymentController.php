<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Due;
use App\Models\Penalty;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ResidentPaymentController extends Controller
{
    /**
     * Display a listing of resident's dues and payments.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $resident = $user?->resident;

        if (!$resident) {
            return redirect()->route('resident.dashboard')->with('error', 'Resident profile not found.');
        }

        // Get all dues with payments, newest first
        $dues = Due::where('resident_id', $resident->id)
            ->with('payments')
            ->orderBy('due_date', 'desc')
            ->get();

        // Get all penalties
        $penalties = Penalty::where('resident_id', $resident->id)
            ->orderBy('date_issued', 'desc')
            ->get();

        // Add helper fields for each due
        $dues->transform(function ($due) {
            $due->collected = $due->totalCollected();
            $due->outstanding = max($due->amount - $due->collected, 0);

            // Get the latest payment to determine current status
            $latestPayment = $due->payments()->latest()->first();
            
            if ($due->status === Due::STATUS_PAID) {
                $due->display_status = 'paid';
            } elseif ($latestPayment) {
                if ($latestPayment->status === 'approved') {
                    $due->display_status = 'paid';
                } elseif ($latestPayment->status === 'pending') {
                    $due->display_status = 'pending';
                } else {
                    $due->display_status = 'unpaid';
                }
            } else {
                $due->display_status = 'unpaid';
            }

            $due->isOverdue = $due->display_status === 'unpaid' && $due->due_date < now();
            return $due;
        });

        // Group dues by month (e.g., "December 2025")
        $duesGrouped = $dues->groupBy(function ($due) {
            return Carbon::parse($due->due_date)->format('F Y');
        });

        // Summary calculations
        $summary = [
            'total_dues'      => $dues->sum('amount'),
            'total_paid'      => $dues->sum('collected'),
            'outstanding_dues' => $dues->sum('outstanding'),
            'total_overdue'   => $dues->where('isOverdue', true)->sum('amount'),
            'total_penalties' => $penalties->where('status', '!=', 'paid')->sum('amount'),
        ];

        return view('resident.payments.index', compact('duesGrouped', 'summary', 'penalties', 'dues'));
    }

    /**
     * Show payment page/form for a specific due or penalty.
     */
    public function pay(Request $request, $id)
    {
        $user = Auth::user();
        $resident = $user?->resident;

        if (!$resident) {
            abort(403, 'Resident profile not found.');
        }

        $type = $request->query('type', 'due');
        $item = null;
        $existingPending = null;

        if ($type === 'penalty') {
            $item = Penalty::findOrFail($id);
            if ($item->resident_id !== $resident->id) abort(403, 'Unauthorized action.');
            
            $existingPending = Payment::where('penalty_id', $item->id)
                ->where('resident_id', $resident->id)
                ->where('status', Payment::STATUS_PENDING)
                ->first();
        } else {
            $item = Due::findOrFail($id);
            if ($item->resident_id !== $resident->id) abort(403, 'Unauthorized action.');

            $existingPending = Payment::where('due_id', $item->id)
                ->where('resident_id', $resident->id)
                ->where('status', Payment::STATUS_PENDING)
                ->first();
        }

        if ($existingPending) {
            return redirect()->route('resident.payments.index')
                ->with('error', 'You already have a pending payment for this item. Please wait for admin approval.');
        }

        return view('resident.payments.pay', [
            'item' => $item,
            'type' => $type,
            'resident' => $resident
        ]);
    }

    /**
     * Process payment for a specific due or penalty.
     */
    public function processPayment(Request $request, $id)
    {
        $user = Auth::user();
        $resident = $user?->resident;

        if (!$resident) {
            return redirect()->route('resident.dashboard')->with('error', 'Resident profile not found.');
        }

        $type = $request->query('type', 'due');
        $item = null;
        $amount = 0;
        $title = '';

        if ($type === 'penalty') {
            $item = Penalty::findOrFail($id);
            if ($item->resident_id !== $resident->id) abort(403, 'Unauthorized action.');
            if ($item->status === 'paid') return redirect()->route('resident.payments.index')->with('error', 'This penalty is already paid.');
            
            $amount = $item->amount;
            $title = $item->reason;
        } else {
            $item = Due::findOrFail($id);
            if ($item->resident_id !== $resident->id) abort(403, 'Unauthorized action.');
            if ($item->status === Due::STATUS_PAID) return redirect()->route('resident.payments.index')->with('error', 'This due is already paid.');
            
            $amount = max($item->amount - $item->totalCollected(), 0);
            $title = $item->title;
        }

        // Prevent duplicate pending submissions
        $query = Payment::where('resident_id', $resident->id)->where('status', Payment::STATUS_PENDING);
        if ($type === 'penalty') $query->where('penalty_id', $id);
        else $query->where('due_id', $id);

        if ($query->exists()) {
            return redirect()->route('resident.payments.index')->with('error', 'A payment for this item is already pending approval.');
        }

        $request->validate([
            'reference_no' => 'required|string|max:255',
            'proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
        ]);

        $path = $request->file('proof')->store('proofs', 'public');

        // Create Pending Payment
        $paymentData = [
            'resident_id'    => $resident->id,
            'reference_no'   => $request->reference_no,
            'amount'         => $amount,
            'date_paid'      => now(),
            'payment_method' => 'gcash', 
            'proof'          => $path,
            'source'         => Payment::SOURCE_RESIDENT,
            'status'         => Payment::STATUS_PENDING,
        ];

        if ($type === 'penalty') $paymentData['penalty_id'] = $id;
        else $paymentData['due_id'] = $id;

        $payment = Payment::create($paymentData);

        // Create Notification for Resident
        \App\Models\Notification::create([
            'resident_id' => $resident->id,
            'title' => '💰 Payment Submitted',
            'message' => "Your payment of PHP " . number_format($amount, 2) . " for '{$title}' is under review.",
            'type' => 'payment',
            'link' => route('resident.payments.index'),
            'is_read' => false,
        ]);

        // Notify Admins
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            \App\Models\Notification::create([
                'admin_id' => $admin->id,
                'title' => '💰 New Payment Submitted',
                'message' => "{$resident->full_name} has submitted a payment of PHP " . number_format($amount, 2) . " for '{$title}'.",
                'type' => 'payment',
                'link' => route('admin.payments.index'),
                'is_read' => false,
            ]);
        }

        return redirect()->route('resident.payments.index')
            ->with('success', 'Payment submitted! Please wait for admin approval.');
    }
}
