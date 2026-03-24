<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DuesBatch;
use App\Models\Due;
use App\Models\Resident;
use App\Models\Payment;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Traits\LogsActivity;

class DuesBatchController extends Controller
{
    use LogsActivity;
    public function dashboard(Request $request)
    {
        $range = $request->get('range', 'month');
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        if ($range === 'quarter') {
            $startDate = now()->startOfQuarter();
            $endDate = now()->endOfQuarter();
        } elseif ($range === 'year') {
            $startDate = now()->startOfYear();
            $endDate = now()->endOfYear();
        }

        $batches = DuesBatch::whereBetween('billing_period_start', [$startDate, $endDate])->get();
        
        $totalActiveDues = DuesBatch::count();
        $totalExpected = DuesBatch::sum('total_expected');
        $totalCollected = Due::where('status', 'paid')->sum('amount');
        $pendingCollection = $totalExpected - $totalCollected;

        // Month-over-Month Growth (Sample logic)
        $lastMonthCollected = Due::where('status', 'paid')
            ->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->sum('amount');
        $growth = $lastMonthCollected > 0 ? (($totalCollected - $lastMonthCollected) / $lastMonthCollected) * 100 : 0;

        return view('admin.dues.dashboard', compact(
            'totalActiveDues', 
            'totalCollected', 
            'totalExpected', 
            'pendingCollection',
            'growth',
            'batches'
        ));
    }

    public function index(Request $request)
    {
        $year = $request->input('year', now()->year);
        $sortOption = $request->input('sort', 'newest');

        $query = DuesBatch::query();

        // 1. FILTER BY YEAR
        if ($year) {
            $query->whereYear('billing_period_start', $year);
        }

        // 2. SEARCH
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%");
            });
        }

        // 3. SORTING
        switch ($sortOption) {
            case 'amount_desc': $query->orderBy('total_expected', 'desc'); break;
            case 'amount_asc': $query->orderBy('total_expected', 'asc'); break;
            case 'oldest': $query->orderBy('billing_period_start', 'asc'); break;
            case 'newest':
            default: $query->orderBy('billing_period_start', 'desc'); break;
        }

        $batches = $query->get();

        // 4. GROUPING BY MONTH
        $groupedDues = $batches->groupBy(function($batch) {
            return $batch->billing_period_start ? $batch->billing_period_start->format('F Y') : 'Unknown Period';
        });

        // 5. YEAR STATS
        $yearStats = [
            'total_expected' => DuesBatch::whereYear('billing_period_start', $year)->sum('total_expected'),
            'total_collected' => Due::where('status', 'paid')
                ->whereHas('batch', function($q) use ($year) {
                    $q->whereYear('billing_period_start', $year);
                })->sum('amount'),
            'collection_rate' => 0
        ];

        if ($yearStats['total_expected'] > 0) {
            $yearStats['collection_rate'] = round(($yearStats['total_collected'] / $yearStats['total_expected']) * 100, 1);
        }

        // 6. MONTH-OVER-MONTH GROWTH
        $currentDate = now();
        $lastMonthDate = now()->subMonth();
        
        $currentMonthTotal = Due::where('status', 'paid')->whereMonth('created_at', $currentDate->month)->whereYear('created_at', $currentDate->year)->sum('amount');
        $lastMonthTotal = Due::where('status', 'paid')->whereMonth('created_at', $lastMonthDate->month)->whereYear('created_at', $lastMonthDate->year)->sum('amount');
        
        $monthComparison = [
            'current_month' => $currentDate->format('F'),
            'last_month' => $lastMonthDate->format('F'),
            'direction' => $currentMonthTotal >= $lastMonthTotal ? 'up' : 'down',
            'diff' => $lastMonthTotal > 0 ? round((abs($currentMonthTotal - $lastMonthTotal) / $lastMonthTotal) * 100, 1) : 0
        ];

        return view('admin.dues.index', compact(
            'groupedDues', 
            'yearStats', 
            'monthComparison', 
            'year', 
            'sortOption'
        ));
    }

    public function create()
    {
        $residents = Resident::where('status', 'active')->get();
        return view('admin.dues.create', compact('residents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string',
            'amount_type' => 'required|in:standard,custom',
            'amount' => 'required|numeric|min:0.01',
            'billing_period_start' => 'required|date',
            'due_date' => 'required|date|after_or_equal:billing_period_start',
            'frequency' => 'required|in:one_time,monthly,quarterly',
            'apply_to' => 'nullable|in:all,selected',
            'resident_ids' => 'required_if:apply_to,selected|array',
        ]);

        $applyTo = $validated['apply_to'] ?? 'selected';

        // Prevent Duplicate Batch (Same type and billing period)
        $exists = DuesBatch::where('type', $validated['type'])
            ->whereDate('billing_period_start', $validated['billing_period_start'])
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'A billing statement of this type already exists for the selected period.');
        }

        DB::transaction(function () use ($validated, $applyTo) {
            $residentIds = $applyTo === 'all' 
                ? Resident::where('status', 'active')->pluck('id') 
                : $validated['resident_ids'];

            $batch = DuesBatch::create([
                'title' => $validated['title'],
                'type' => $validated['type'],
                'billing_period_start' => $validated['billing_period_start'],
                'due_date' => $validated['due_date'],
                'frequency' => $validated['frequency'],
                'total_expected' => count($residentIds) * $validated['amount'],
                'created_by' => auth('admin')->id() ?? Admin::first()?->id,
            ]);

            $this->logActivity('created', 'dues', 'Created billing statement: ' . $batch->title, ['batch_id' => $batch->id]);

            foreach ($residentIds as $residentId) {
                Due::create([
                    'resident_id' => $residentId,
                    'batch_id' => $batch->id,
                    'title' => $validated['title'],
                    'amount' => $validated['amount'],
                    'paid_amount' => 0,
                    'status' => 'unpaid',
                    'month' => Carbon::parse($validated['billing_period_start'])->format('F Y'),
                    'due_date' => $validated['due_date'],
                    'type' => $validated['type'],
                    'frequency' => $validated['frequency'],
                    'billing_period_start' => $validated['billing_period_start'],
                ]);

                // Create In-App Notification for Resident
                \App\Models\Notification::create([
                    'resident_id' => $residentId,
                    'title' => '📢 New Billing Statement',
                    'message' => "A new billing statement '{$validated['title']}' for ₱" . number_format($validated['amount'], 2) . " has been generated.",
                    'type' => 'system',
                    'link' => route('resident.payments.index'),
                    'is_read' => false,
                ]);
            }
        });

        return redirect()->route('admin.dues.index')->with('success', 'Dues batch created successfully.');
    }

    public function markAsPaid(Request $request, Due $due)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|string|in:cash,bank_transfer,check,gcash',
        ]);

        // Standardize payment method for internal constants
        $paymentMethod = $validated['method'] === 'bank_transfer' ? 'bank transfer' : $validated['method'];

        // Prevent overpayment (using a small delta for floating point comparison)
        if ($validated['amount'] > ($due->balance + 0.01)) {
            return response()->json(['success' => false, 'message' => 'Payment amount (₱' . number_format($validated['amount'], 2) . ') exceeds the remaining balance (₱' . number_format($due->balance, 2) . ').'], 422);
        }

        $payment = DB::transaction(function () use ($due, $validated, $paymentMethod) {
            // Record Payment (Admin cash is auto-approved)
            $payment = Payment::create([
                'resident_id' => $due->resident_id,
                'due_id' => $due->id,
                'amount' => $validated['amount'],
                'date_paid' => now(),
                'payment_method' => $paymentMethod,
                'source' => Payment::SOURCE_ADMIN,
                'status' => Payment::STATUS_APPROVED,
                'reference_no' => 'ADMIN-' . strtoupper(uniqid()),
            ]);

            $this->logActivity('recorded_payment', 'dues', 'Recorded payment of ₱' . number_format($payment->amount, 2) . ' for ' . $due->resident->full_name, [
                'payment_id' => $payment->id,
                'due_id' => $due->id,
                'resident_id' => $due->resident_id
            ]);

            // Trigger notifications and penalties via PaymentController helper (which also syncs due->paid_amount)
            app(PaymentController::class)->handlePenaltyAndMarkDue($payment);

            return $payment;
        });

        return response()->json([
            'success' => true, 
            'message' => 'Payment recorded successfully.',
            'payment_id' => $payment->id
        ]);
    }

    public function bulkMarkAsPaid(Request $request)
    {
        $validated = $request->validate([
            'due_ids' => 'required|array',
            'due_ids.*' => 'exists:dues,id',
        ]);

        $count = 0;
        DB::transaction(function() use ($validated, &$count) {
            foreach ($validated['due_ids'] as $dueId) {
                $due = Due::findOrFail($dueId);
                $balance = $due->balance;

                if ($balance > 0) {
                    $payment = Payment::create([
                        'resident_id' => $due->resident_id,
                        'due_id' => $due->id,
                        'amount' => $balance,
                        'date_paid' => now(),
                        'payment_method' => Payment::METHOD_CASH,
                        'source' => Payment::SOURCE_ADMIN,
                        'status' => Payment::STATUS_APPROVED,
                        'reference_no' => 'BULK-' . strtoupper(uniqid()),
                    ]);
                    
                    // Trigger notifications and penalties via PaymentController helper (which also syncs due->paid_amount)
                    app(PaymentController::class)->handlePenaltyAndMarkDue($payment);
                    
                    $count++;
                }
            }
        });

        return back()->with('success', "Successfully recorded payments for $count residents.");
    }

    public function show($id)
    {
        $batch = DuesBatch::with(['residentDues' => function($query) {
            $query->with(['resident', 'payments' => function($p) {
                $p->where('status', Payment::STATUS_APPROVED);
            }]);
        }])->findOrFail($id);
        
        return view('admin.dues.show', compact('batch'));
    }

    public function edit($id)
    {
        $batch = DuesBatch::findOrFail($id);
        $residents = Resident::where('status', 'active')->get();
        return view('admin.dues.edit', compact('batch', 'residents'));
    }

    public function update(Request $request, $id)
    {
        $batch = DuesBatch::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
        ]);

        DB::transaction(function() use ($batch, $validated) {
            $batch->update($validated);

            // Also update all linked individual dues to match the new title and due date
            // Explicitly cast batch->id to string to avoid MySQL type conversion errors with UUIDs
            Due::where('batch_id', (string) $batch->id)->update([
                'title' => $validated['title'],
                'due_date' => $validated['due_date']
            ]);
        });

        return redirect()->route('admin.dues.index')->with('success', 'Billing statement updated successfully.');
    }

    public function destroy($id)
    {
        try {
            $batch = DuesBatch::findOrFail($id);
            
            DB::transaction(function() use ($batch) {
                // Delete associated dues first (explicitly cast to string to avoid UUID type errors)
                Due::where('batch_id', (string) $batch->id)->delete();
                
                // Finally delete the batch itself
                $batch->delete();
            });

            return redirect()->route('admin.dues.index')->with('success', 'Billing statement and all associated records deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.dues.index')->with('error', 'Failed to delete batch: ' . $e->getMessage());
        }
    }
}
