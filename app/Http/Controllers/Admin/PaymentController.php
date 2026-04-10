<?php

namespace App\Http\Controllers\Admin;

use App\Events\PaymentStatusChanged;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Due;
use App\Models\Resident;
use App\Models\Penalty;
use App\Services\SmsService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Services\FileService;

use App\Traits\LogsActivity;

class PaymentController extends Controller
{
    use LogsActivity;

    public function __construct()
    {
        $this->middleware('permission:payments.view')->only(['index', 'show', 'review', 'receipt', 'downloadReceipt', 'getData', 'getDuesByResident']);
        $this->middleware('permission:payments.record')->only(['create', 'store', 'confirm', 'approve']);
        $this->middleware('permission:payments.update')->only(['edit', 'update', 'bulkAction', 'bulkApprovePayments', 'updateStatus', 'reject']);
        $this->middleware('permission:payments.delete')->only(['destroy']);
        $this->middleware('permission:payments.export')->only([]);
    }

    /* =========================
       Status & Method Constants
       ========================= */
    public const STATUS_PENDING  = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    public const METHOD_CASH          = 'cash';
    public const METHOD_GCASH         = 'gcash';
    public const METHOD_BANK_TRANSFER = 'bank transfer';

    // ==============================
    // LIST + SEARCH + FILTER + SORT
    // ==============================
    public function index(Request $request)
    {
        $status = $request->get('status');
        $method = $request->get('method');
        $source = $request->get('source');

        $payments = Payment::with(['resident', 'due'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($method, fn($q) => $q->where('payment_method', $method))
            ->when($source, fn($q) => $q->where('source', $source))
            ->latest()
            ->paginate(20);

        // Stats calculation (Approved only for totals)
        $totalCollectedYear = Payment::where('status', Payment::STATUS_APPROVED)
            ->whereYear('date_paid', now()->year)
            ->sum('amount');

        $pendingAmount = Payment::where('status', Payment::STATUS_PENDING)->sum('amount');
        $pendingCount = Payment::where('status', self::STATUS_PENDING)->count();

        // This Month
        $thisMonth = Payment::where('status', Payment::STATUS_APPROVED)
            ->whereMonth('date_paid', now()->month)
            ->whereYear('date_paid', now()->year)
            ->sum('amount');

        // Growth calculation (MoM)
        $lastMonth = Payment::where('status', Payment::STATUS_APPROVED)
            ->whereMonth('date_paid', now()->subMonth()->month)
            ->whereYear('date_paid', now()->subMonth()->year)
            ->sum('amount');

        $growth = 0;
        $direction = 'neutral';

        if ($lastMonth > 0) {
            $growth = (($thisMonth - $lastMonth) / $lastMonth) * 100;
            $direction = $growth >= 0 ? 'up' : 'down';
        } elseif ($thisMonth > 0) {
            $growth = 100;
            $direction = 'up';
        }

        $paymentMethods = Payment::distinct()->pluck('payment_method')->filter()->values();

        return view('admin.payments.index', compact(
            'payments', 
            'totalCollectedYear', 
            'pendingAmount', 
            'pendingCount',
            'thisMonth',
            'growth',
            'direction',
            'paymentMethods'
        ));
    }

    // ==============================
    // BULK ACTIONS
    // ==============================
    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return back()->with('error', 'No payments selected.');
        }

        switch ($action) {
            case 'approve':
                $approvedCount = 0;

                DB::transaction(function () use ($ids, &$approvedCount) {
                    $payments = Payment::with(['due', 'resident', 'penalty'])
                        ->whereIn('id', $ids)
                        ->where('status', self::STATUS_PENDING)
                        ->lockForUpdate()
                        ->get();

                    foreach ($payments as $payment) {
                        $payment->update([
                            'status' => self::STATUS_APPROVED,
                            'date_paid' => $payment->date_paid ?? now(),
                        ]);

                        if ($payment->due) {
                            $payment->due->refresh();
                        }

                        $this->handlePenaltyAndMarkDue($payment->fresh(['due', 'resident', 'penalty']));
                        $approvedCount++;
                    }
                });

                if ($approvedCount === 0) {
                    return back()->with('error', 'No pending payments were selected.');
                }

                return back()->with('success', "{$approvedCount} payments approved successfully.");
            
            case 'reject':
                $count = count($ids);
                Payment::whereIn('id', $ids)->update(['status' => self::STATUS_REJECTED]);
                foreach(Payment::whereIn('id', $ids)->get() as $payment) {
                    $payment->due->markPaidIfFullyCollected();
                }
                return back()->with('success', "{$count} payments rejected successfully.");

            case 'export':
                $count = count($ids);
                // Minimal CSV Export logic
                $filename = "payments_export_" . date('Ymd_His') . ".csv";
                $headers = [
                    "Content-type"        => "text/csv",
                    "Content-Disposition" => "attachment; filename=$filename",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                ];
                
                $columns = ['ID', 'Resident', 'Amount', 'Date Paid', 'Method', 'Status', 'Due Title'];

                $callback = function() use ($ids, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    $payments = Payment::with(['resident', 'due'])->whereIn('id', $ids)->get();

                    foreach ($payments as $payment) {
                        fputcsv($file, [
                            $payment->id,
                            $payment->resident ? $payment->resident->full_name : 'N/A',
                            $payment->amount,
                            $payment->date_paid ? $payment->date_paid->format('Y-m-d') : '-',
                            $payment->payment_method,
                            $payment->status,
                            $payment->due ? $payment->due->title : '-'
                        ]);
                    }
                    fclose($file);
                };
                
                return response()->stream($callback, 200, $headers);

            case 'delete':
                 $count = count($ids);
                 Payment::destroy($ids);
                 return back()->with('success', "{$count} payments deleted successfully.");

            default:
                return back()->with('error', 'Invalid action.');
        }
    }

    public function bulkApprovePayments(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return back()->with('error', 'No payments selected.');
        }

        $approvedCount = 0;

        DB::transaction(function () use ($ids, &$approvedCount) {
            $payments = Payment::with(['due', 'resident', 'penalty'])
                ->whereIn('id', $ids)
                ->where('status', self::STATUS_PENDING)
                ->lockForUpdate()
                ->get();

            foreach ($payments as $payment) {
                $payment->update([
                    'status' => self::STATUS_APPROVED,
                    'date_paid' => $payment->date_paid ?? now(),
                ]);

                if ($payment->due) {
                    $payment->due->refresh();
                }

                $this->handlePenaltyAndMarkDue($payment->fresh(['due', 'resident', 'penalty']));
                $approvedCount++;
            }
        });

        if ($approvedCount === 0) {
            return back()->with('error', 'No pending payments were selected.');
        }

        return back()->with('success', "{$approvedCount} payments approved successfully.");
    }

    // ==============================
    // SHOW (DRAWER)
    // ==============================
    public function show(Request $request, $id)
    {
        $payment = Payment::with(['resident', 'due', 'penalties'])->findOrFail($id);

        if ($request->ajax()) {
            return view('admin.payments.partials.drawer', compact('payment'));
        }

        // Redirect to index with active_id to open drawer
        return redirect()->route('admin.payments.index', ['active_id' => $id]);
    }

    // ==============================
    // GET DATA FOR UNIVERSAL DRAWER
    // ==============================
    public function getData($id)
    {
        $payment = Payment::with(['resident', 'due'])->findOrFail($id);

        // Status Badge Logic
        $status = $payment->status;
        $badgeClass = '';
        $dotClass = '';

        if ($status === self::STATUS_APPROVED) {
            $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-100';
            $dotClass = 'bg-emerald-500';
        } elseif ($status === self::STATUS_REJECTED) {
            $badgeClass = 'bg-red-50 text-red-700 border-red-100';
            $dotClass = 'bg-red-500';
        } else {
            $badgeClass = 'bg-yellow-50 text-yellow-700 border-yellow-100';
            $dotClass = 'bg-yellow-500';
        }

        return response()->json([
            'id' => str_pad($payment->id, 6, '0', STR_PAD_LEFT),
            'customTitle' => 'Payment #' . str_pad($payment->id, 6, '0', STR_PAD_LEFT),
            'resident_name' => $payment->resident->first_name . ' ' . $payment->resident->last_name,
            'resident_type' => ucfirst($payment->resident->status ?? 'Resident'),
            'resident_photo' => $payment->resident->photo ? asset('storage/' . $payment->resident->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($payment->resident->first_name . ' ' . $payment->resident->last_name) . '&background=random',
            'resident_property' => 'Block ' . ($payment->resident->block ?? '?') . ' Lot ' . ($payment->resident->lot ?? '?'),
            'resident_profile_url' => route('admin.residents.show', $payment->resident->id),
            'status' => $payment->status,
            'status_text' => ucfirst($payment->status),
            'status_badge_class' => $badgeClass,
            'status_dot_class' => $dotClass,
            'is_pending' => $payment->status === 'pending',
            'amount' => '₱' . number_format($payment->amount, 2),
            'date' => Carbon::parse($payment->date_paid)->format('M d, Y'),
            'time' => Carbon::parse($payment->date_paid)->format('g:i A'),
            'method' => ucfirst($payment->payment_method),
            'due_title' => $payment->penalty_id ? ($payment->penalty->reason ?? 'Penalty') : ($payment->due->title ?? 'N/A'),
            'reference_no' => $payment->reference_no,
            'transaction_id' => str_pad($payment->id, 8, '0', STR_PAD_LEFT),
            'proof_url' => $payment->proof ? asset('storage/' . $payment->proof) : null,
            'proof_image' => $payment->proof ? asset('storage/' . $payment->proof) : null,
            'receipt_url' => $payment->status === 'approved' ? route('admin.payments.receipt', $payment->id) : null,
            'edit_url' => route('admin.payments.edit', $payment->id),
            'verify_url' => route('admin.payments.approve', $payment->id),
            'reject_url' => route('admin.payments.reject', $payment->id),
        ]);
    }

    // ==============================
    // REVIEW PAYMENT (GET)
    // ==============================
    public function review($id)
    {
        $payment = Payment::with(['resident', 'due'])->findOrFail($id);
        
        // This is for reviewing an EXISTING payment
        // We reuse the review view but with different data structure if needed
        return view('admin.payments.review', [
            'data' => [
                'resident_id' => $payment->resident_id,
                'due_id' => $payment->due_id,
                'amount' => $payment->amount,
                'date_paid' => $payment->date_paid,
                'payment_method' => $payment->payment_method,
            ],
            'resident' => $payment->resident,
            'due' => $payment->due,
            'proofPath' => $payment->proof,
            'is_existing' => true,
            'payment' => $payment
        ]);
    }

    // ==============================
    // CREATE FORM
    // ==============================
    public function create()
    {
        $residents = Resident::all();
        $dues = collect();
        return view('admin.payments.form', compact('residents', 'dues'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'due_id' => 'required|exists:dues,id',
            'amount' => 'required|numeric|min:0.01',
            'date_paid' => 'required|date',
            'payment_method' => 'required|string',
            'proof' => 'nullable|image|max:2048',
        ]);

        $proofPath = null;
        if ($request->hasFile('proof')) {
            $proofPath = FileService::storeAndSync($request->file('proof'), 'temp_proofs');
        }

        $resident = \App\Models\Resident::findOrFail($validated['resident_id']);
        $due = \App\Models\Due::findOrFail($validated['due_id']);

        return view('admin.payments.review', [
            'data' => $validated,
            'resident' => $resident,
            'due' => $due,
            'proofPath' => $proofPath
        ]);
    }

    public function confirm(Request $request)
    {
        $data = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'due_id' => 'required|exists:dues,id',
            'amount' => 'required|numeric',
            'date_paid' => 'required|date',
            'payment_method' => 'required|string',
            'temp_proof' => 'nullable|string'
        ]);

        $payment = new Payment();
        $payment->resident_id = $data['resident_id'];
        $payment->due_id = $data['due_id'];
        $payment->amount = $data['amount'];
        $payment->date_paid = $data['date_paid'];
        $payment->payment_method = $data['payment_method'];
        $payment->status = 'approved'; // Recorded by admin is auto-approved

        if ($data['temp_proof']) {
            $newPath = str_replace('temp_proofs/', 'payments/', $data['temp_proof']);
            
            // Move in storage
            Storage::disk('public')->move($data['temp_proof'], $newPath);
            
            // Sync to public (delete old temp, copy new payment)
            FileService::deleteAndSync($data['temp_proof']);
            FileService::syncToPublic($newPath);

            $payment->proof = $newPath;
        }

        $payment->save();

        // Update balance logic here if needed
        // Generate receipt
        return redirect()->route('admin.payments.receipt', $payment->id)
            ->with('success', 'Payment recorded successfully and receipt generated.');
    }

    public function receipt($id, Request $request)
    {
        $payment = Payment::with(['resident', 'due'])->findOrFail($id);
        
        if ($request->has('download')) {
            // Simplified for now, usually requires a PDF library like DomPDF
            // For now we just return the view which the browser can print to PDF
            return view('admin.payments.receipt', compact('payment'));
        }

        return view('admin.payments.receipt', compact('payment'));
    }

    // ==============================
    // EDIT FORM
    // ==============================
    public function edit($id)
    {
        $payment = Payment::with(['resident', 'due'])->findOrFail($id);
        $residents = Resident::all();
        $dues = $payment->resident ? $payment->resident->dues()->get() : collect();
        return view('admin.payments.form', compact('payment', 'residents', 'dues'));
    }

    // ==============================
    // UPDATE PAYMENT
    // ==============================
    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $data = $request->validate([
            'resident_id'    => 'required|exists:residents,id',
            'due_id'         => 'required|exists:dues,id',
            'amount'         => 'required|numeric|min:0',
            'date_paid'      => 'required|date',
            'payment_method' => 'required|string|max:100',
            'status'         => 'required|in:'.self::STATUS_PENDING.','.self::STATUS_APPROVED.','.self::STATUS_REJECTED,
            'proof'          => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('proof')) {
            FileService::deleteAndSync($payment->proof);
            $data['proof'] = FileService::storeAndSync($request->file('proof'), 'proofs');
        }

        $payment->update($data);
        $this->handlePenaltyAndMarkDue($payment);

        return redirect()->route('admin.payments.index')
                         ->with('success', 'Payment updated successfully.');
    }

    // ==============================
    // DELETE PAYMENT
    // ==============================
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);

        FileService::deleteAndSync($payment->proof);

        $payment->delete();

        return redirect()->route('admin.payments.index')
                         ->with('success', 'Payment deleted successfully.');
    }

    // ==============================
    // APPROVE PAYMENT
    // ==============================
    public function approve(Payment $payment, SmsService $smsService)
    {
        if ($payment->status !== self::STATUS_PENDING) {
            return back()->with('error', 'Only pending payments can be approved.');
        }

        \Illuminate\Support\Facades\DB::transaction(function() use ($payment) {
            $payment->update([
                'status' => self::STATUS_APPROVED,
                'date_paid' => now()
            ]);
            
            // Recalculating due status
            $due = $payment->due;
            if ($due) {
                $due->update(['status' => $due->dynamic_status]);
            }

            $this->handlePenaltyAndMarkDue($payment);
        });
        event(new PaymentStatusChanged($payment->fresh(['resident', 'due', 'penalty']), self::STATUS_APPROVED));

        $this->logActivity('approved', 'payments', 'Approved payment of ₱' . number_format($payment->amount, 2) . ' from ' . $payment->resident->full_name, [
            'payment_id' => $payment->id,
            'resident_id' => $payment->resident_id
        ]);

        return back()->with('success', 'Payment approved and resident notified.');
    }

    // ==============================
    // REJECT PAYMENT
    // ==============================
    public function reject(Payment $payment)
    {
        if ($payment->status !== self::STATUS_PENDING) {
            return back()->with('error', 'Only pending payments can be rejected.');
        }

        $payment->update(['status' => self::STATUS_REJECTED]);
        $this->handlePenaltyAndMarkDue($payment);
        event(new PaymentStatusChanged($payment->fresh(['resident', 'due', 'penalty']), self::STATUS_REJECTED));

        $this->logActivity('rejected', 'payments', 'Rejected payment of ₱' . number_format($payment->amount, 2) . ' from ' . $payment->resident->full_name, [
            'payment_id' => $payment->id,
            'resident_id' => $payment->resident_id
        ]);

        return back()->with('success', 'Payment rejected and resident notified.');
    }

    // ==============================
    // AJAX: GET DUES BY RESIDENT
    // ==============================
    public function getDuesByResident($residentId)
    {
        $resident = Resident::findOrFail($residentId);
        $dues = $resident->dues()->get(['id', 'title', 'amount']);
        return response()->json($dues);
    }

    // ==============================
    // UPDATE STATUS (Generic)
    // ==============================
    public function updateStatus(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $status = $request->input('status');

        if (!in_array($status, [self::STATUS_PENDING, self::STATUS_APPROVED, self::STATUS_REJECTED])) {
            return back()->with('error', 'Invalid status provided.');
        }

        $payment->update(['status' => $status]);
        $this->handlePenaltyAndMarkDue($payment);
        event(new PaymentStatusChanged($payment->fresh(['resident', 'due', 'penalty']), $status));

        return back()->with('success', "Payment status updated to {$status}.");
    }

    // ==============================
    // PUBLIC: VERIFY RECEIPT
    // ==============================
    public function verifyReceipt($id)
    {
        $payment = Payment::with(['resident', 'due'])->find($id);
        
        if (!$payment || $payment->status !== self::STATUS_APPROVED) {
            return view('admin.payments.verify-failed');
        }

        return view('admin.payments.verify-success', compact('payment'));
    }

    // ==============================
    // DOWNLOAD RECEIPT
    // ==============================
    public function downloadReceipt($id)
    {
        $payment = Payment::with(['resident', 'due'])->findOrFail($id);
        
        if ($payment->status !== self::STATUS_APPROVED) {
            return back()->with('error', 'Receipts are only available for approved payments.');
        }

        return view('admin.payments.receipt', compact('payment'));
    }

    // ==============================
    // Helper: Handle Penalties & Mark Due Paid
    // ==============================
    public function handlePenaltyAndMarkDue(Payment $payment)
    {
        // -----------------------------
        // IF PAYMENT IS FOR A PENALTY
        // -----------------------------
        if ($payment->penalty_id) {
            $penalty = $payment->penalty;
            if ($penalty) {
                if ($payment->status === self::STATUS_APPROVED) {
                    $penalty->update(['status' => 'paid']);
                } elseif ($payment->status === self::STATUS_REJECTED) {
                    // Check if it's still overdue or just unpaid
                    $newStatus = ($penalty->due_date && Carbon::now()->gt($penalty->due_date)) ? 'overdue' : 'unpaid';
                    $penalty->update(['status' => $newStatus]);
                }
            }
            // No further processing for penalty payments (no late fees on late fees for now)
            return;
        }

        $due = $payment->due;
        if (!$due) return;

        // -----------------------------
        // Remove old penalties for this payment
        // -----------------------------
        $payment->penalties()->delete();

        // -----------------------------
        // PAYMENT-BASED PENALTY (late payment)
        // Only if payment approved and late
        // -----------------------------
        if ($payment->status === self::STATUS_APPROVED && Carbon::parse($payment->date_paid)->gt($due->due_date)) {
            $daysLate = Carbon::parse($payment->date_paid)->diffInDays($due->due_date);
            $penaltyAmount = $daysLate * 10;

            Penalty::create([
                'resident_id' => $payment->resident_id,
                'payment_id'  => $payment->id,
                'due_id'      => $due->id,
                'type'        => 'late_payment',
                'reason'      => "Late payment ({$daysLate} days late)",
                'amount'      => $penaltyAmount,
                'date_issued' => now(),
                'due_date'    => now()->addDays(7), // Give 7 days to settle penalty
                'status'      => 'unpaid',
            ]);

            // Notify Resident about New Penalty
            \App\Models\Notification::create([
                'resident_id' => $payment->resident_id,
                'title' => '⚠️ New Penalty Issued',
                'message' => "A penalty of ₱" . number_format($penaltyAmount, 2) . " has been issued for late payment of '{$due->title}'.",
                'type' => 'alert',
                'link' => route('resident.penalties.index'),
                'is_read' => false,
            ]);
        }

        // -----------------------------
        // SYSTEM-GENERATED PENALTY (overdue unpaid)
        // -----------------------------
        if ($payment->status !== self::STATUS_APPROVED && Carbon::today()->gt($due->due_date)) {
            $exists = Penalty::where('due_id', $due->id)
                ->where('type', 'overdue')
                ->exists();

            if (!$exists) {
                Penalty::create([
                    'resident_id' => $payment->resident_id,
                    'due_id'      => $due->id,
                    'type'        => 'overdue',
                    'reason'      => "Overdue unpaid fee",
                    'amount'      => 50,
                    'date_issued' => now(),
                    'status'      => 'unpaid',
                ]);
            }
        }

        // -----------------------------
        // Automatically sync due status and paid_amount
        // -----------------------------
        $due->markPaidIfFullyCollected();

        // -----------------------------
        // NOTIFICATIONS (In-App & SMS)
        // -----------------------------
        $resident = $payment->resident;
        $itemTitle = $payment->penalty_id ? ($payment->penalty->reason ?? 'Penalty') : ($payment->due->title ?? 'Association Fee');

        if ($resident) {
            if ($payment->status === self::STATUS_APPROVED) {
                // In-App
                \App\Models\Notification::create([
                    'resident_id' => $resident->id,
                    'title' => '✅ Payment Approved',
                    'message' => "Your payment for '{$itemTitle}' has been verified and approved.",
                    'type' => 'payment',
                    'link' => route('resident.payments.index'),
                    'is_read' => false,
                ]);

                // SMS
                if ($resident->contact_number) {
                    try {
                        $smsService = app(SmsService::class);
                        $message = "Hi {$resident->first_name}, your payment of PHP " . number_format($payment->amount, 2) . " for '{$itemTitle}' has been approved. Thank you!";
                        $smsService->send($resident->contact_number, $message);
                    } catch (\Exception $e) {
                        // Log SMS error but don't stop execution
                        \Illuminate\Support\Facades\Log::error("Failed to send SMS for payment {$payment->id}: " . $e->getMessage());
                    }
                }
            } elseif ($payment->status === self::STATUS_REJECTED) {
                // In-App
                \App\Models\Notification::create([
                    'resident_id' => $resident->id,
                    'title' => '❌ Payment Rejected',
                    'message' => "Your payment for '{$itemTitle}' has been rejected. Please check your reference number.",
                    'type' => 'payment',
                    'link' => route('resident.payments.index'),
                    'is_read' => false,
                ]);
            }
        }
    }
}
