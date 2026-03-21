<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Due;
use App\Models\Resident;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DueController extends Controller
{
    /**
     * Display a listing of dues with advanced grouping and stats.
     */
    public function index(Request $request)
    {
        // Start Query
        $query = Due::query();

        // 1. FILTERS
        // Search (Title only, since we don't show residents)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        // Type
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Year
        $year = $request->input('year');
        if ($year !== null && $year !== '') {
            $query->whereYear('due_date', $year);
        }

        // Month
        if ($request->filled('month')) {
            $query->whereMonth('due_date', $request->month);
        }

        // Archived
        if ($request->boolean('archived')) {
            $query->whereNotNull('archived_at');
        } else {
            $query->whereNull('archived_at');
        }

        // 2. AGGREGATE GROUPING
        // We group by unique due characteristics to show "1 due" instead of resident details
        $query->select(
            'title',
            'billing_period_start',
            'billing_period_end', // Group by billing_period_end instead of due_date
            'type',
            'amount',
            'description', // Ensure description is selected
            'batch_id', // Use batch_id for identification
            DB::raw('MAX(id) as id'), // Placeholder ID for UI
            DB::raw('MIN(created_at) as created_at'), // accurate created date
            DB::raw('count(*) as total_residents'),
            DB::raw('sum(case when status = "paid" then 1 else 0 end) as paid_count'),
            DB::raw('sum(amount) as total_expected'),
            DB::raw('sum(case when status = "paid" then amount else 0 end) as total_collected')
        )
        ->groupBy('title', 'billing_period_start', 'billing_period_end', 'type', 'amount', 'description', 'batch_id');


        // 3. SORTING (Modified for Aggregates)
        $sortOption = $request->input('sort', 'newest');
        switch ($sortOption) {
            case 'amount_desc':
                $query->orderBy('amount', 'desc');
                break;
            case 'amount_asc':
                $query->orderBy('amount', 'asc');
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'oldest':
                $query->orderBy('billing_period_end', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('billing_period_end', 'desc');
                break;
        }

        // 4. GET DATA
        $dues = $query->get();

        // 5. STATS (Global for the Year context)
        $statsYear = $year ?: now()->year;
        $yearStats = [
            'total_expected' => Due::whereYear('billing_period_end', $statsYear)->sum('amount'),
            'total_collected' => Due::whereYear('billing_period_end', $statsYear)->where('status', 'paid')->sum('amount'),
            'collection_rate' => 0,
        ];
        
        if ($yearStats['total_expected'] > 0) {
            $yearStats['collection_rate'] = round(($yearStats['total_collected'] / $yearStats['total_expected']) * 100, 1);
        }

        // Month-over-Month Growth
        $currentDate = now();
        $lastMonthDate = now()->subMonth();
        $currentStats = $this->getMonthlyStats($currentDate->year, $currentDate->month);
        $lastStats = $this->getMonthlyStats($lastMonthDate->year, $lastMonthDate->month);

        $monthComparison = [
            'current_month' => $currentDate->format('F'),
            'last_month' => $lastMonthDate->format('F'),
            'direction' => ($currentStats['rate'] >= $lastStats['rate']) ? 'up' : 'down',
            'diff' => abs($currentStats['rate'] - $lastStats['rate']),
        ];

        return view('admin.dues.index', compact(
            'dues', 
            'yearStats', 
            'monthComparison', 
            'year',
            'sortOption'
        ));
    }

    /**
     * Show the create due form.
     */
    public function create()
    {
        return view('admin.dues.create');
    }

    /**
     * Show the edit due form.
     */
    public function edit($id)
    {
        $due = Due::findOrFail($id);

        return view('admin.dues.edit', compact('due'));
    }

    private function getMonthlyStats($year, $month)
    {
        $total = Due::whereYear('billing_period_end', $year)->whereMonth('billing_period_end', $month)->sum('amount');
        $collected = Due::whereYear('billing_period_end', $year)->whereMonth('billing_period_end', $month)->where('status', 'paid')->sum('amount');
        
        return [
            'total' => $total,
            'collected' => $collected,
            'rate' => $total > 0 ? round(($collected / $total) * 100, 1) : 0
        ];
    }

    /**
     * Store a newly created due in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'type' => 'required|in:' . implode(',', [
                Due::TYPE_MONTHLY_HOA,
                Due::TYPE_REGULAR_FEES,
                Due::TYPE_SPECIAL_ASSESSMENTS,
            ]),
            'frequency' => 'required|in:' . implode(',', [
                Due::FREQUENCY_MONTHLY,
                Due::FREQUENCY_ONE_TIME,
                Due::FREQUENCY_QUARTERLY,
            ]),
            'billing_period_start' => 'nullable|date',
            'billing_period_end' => 'nullable|date|after_or_equal:billing_period_start',
            'apply_all_months' => 'nullable|boolean',
        ]);

        $residentIds = Resident::query()->pluck('id');
        $now = now();
        $baseDueDate = Carbon::parse($validated['due_date'])->startOfDay();

        if (empty($validated['billing_period_start'])) {
            $validated['billing_period_start'] = Due::defaultBillingPeriodStart($baseDueDate)->toDateString();
        }

        if (empty($validated['billing_period_end'])) {
            $validated['billing_period_end'] = Due::defaultBillingPeriodEnd($baseDueDate)->toDateString();
        }

        if ($residentIds->isEmpty()) {
            return back()->with('error', 'No residents found to assign dues to.');
        }

        // Batch Creation Logic
        if ($request->boolean('apply_all_months') && $validated['type'] === Due::TYPE_MONTHLY_HOA) {
            $year = $baseDueDate->year;
            $dayOfMonth = $baseDueDate->day;

            for ($month = 1; $month <= 12; $month++) {
                $monthStart = Carbon::create($year, $month, 1);
                $dueDate = $monthStart->copy()->day(min($dayOfMonth, $monthStart->daysInMonth));

                $billingStart = $monthStart->copy();
                $billingEnd = $monthStart->copy()->endOfMonth();
                $monthName = $billingEnd->format('F');
                $batchId = (string) Str::uuid();

                $this->createBatchRows(
            $residentIds,
            $batchId,
            $validated['title'] . ' - ' . $monthName . ' ' . $year,
            $validated['description'] ?? null,
            $validated['amount'],
            $validated['type'],
            $validated['frequency'],
            $monthName,
            $dueDate,
            $billingStart,
            $billingEnd,
            $now
        );
            }
        } else {
            $monthName = $baseDueDate->format('F');
            $batchId = (string) Str::uuid();
            $billingStart = $validated['billing_period_start']
                ? Carbon::parse($validated['billing_period_start'])
                : Due::defaultBillingPeriodStart($baseDueDate);
            $billingEnd = Carbon::parse($validated['billing_period_end']);

            $dueDate = $baseDueDate;

            $this->createBatchRows(
                $residentIds,
                $batchId,
                $validated['title'],
                $validated['description'] ?? null,
                $validated['amount'],
                $validated['type'],
                $validated['frequency'],
                $monthName,
                $dueDate,
                $billingStart,
                $billingEnd,
                $now
            );
        }

        return redirect()->route('admin.dues.index')->with('success', 'Dues created and assigned successfully.');
    }

    /**
     * Update an existing due record.
     */
    public function update(Request $request, $id)
    {
        $due = Due::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:' . implode(',', [
                Due::TYPE_MONTHLY_HOA,
                Due::TYPE_REGULAR_FEES,
                Due::TYPE_SPECIAL_ASSESSMENTS,
            ]),
            'frequency' => 'required|in:' . implode(',', [
                Due::FREQUENCY_MONTHLY,
                Due::FREQUENCY_ONE_TIME,
                Due::FREQUENCY_QUARTERLY,
            ]),
            'due_date' => 'required|date',
            'billing_period_start' => 'nullable|date',
            'billing_period_end' => 'nullable|date|after_or_equal:billing_period_start',
        ]);

        $batchId = $due->batch_id;
        $dueDate = Carbon::parse($validated['due_date']);

        $billingEnd = !empty($validated['billing_period_end']) 
            ? Carbon::parse($validated['billing_period_end']) 
            : Due::defaultBillingPeriodEnd($dueDate);
            
        $billingStart = !empty($validated['billing_period_start'])
            ? Carbon::parse($validated['billing_period_start'])
            : ($due->billing_period_start ?? null);

        // Update ALL dues in the same batch with the new details
        // Only update fields that are "batch-level" (title, amount, dates, etc.)
        // We do NOT update resident_id, status (unless we wanted to reset it, but usually not on edit), etc.
        Due::where('batch_id', $batchId)->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'amount' => $validated['amount'],
            'type' => $validated['type'],
            'frequency' => $validated['frequency'],
            'due_date' => $dueDate,
            'billing_period_start' => $billingStart,
            'billing_period_end' => $billingEnd,
            // 'updated_at' => now(), // Automatically handled by Eloquent if doing model save, but for batch update we might need it if we want to show updated time. However, bulk update doesn't touch timestamps automatically in some cases. Let's add it.
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.dues.index')->with('success', 'Due batch updated successfully.');
    }

    private function createBatchRows($residentIds, $batchId, $title, $desc, $amount, $type, $freq, $month, $dueDate, $billStart, $billEnd, $now)
    {
        $rows = [];
        foreach ($residentIds as $residentId) {
            $rows[] = [
                'batch_id' => $batchId,
                'resident_id' => $residentId,
                'title' => $title,
                'description' => $desc ?? 'No description provided', // Ensure description is set
                'amount' => $amount,
                'type' => $type,
                'frequency' => $freq,
                'month' => $month,
                'due_date' => $dueDate->toDateString(),
                'billing_period_start' => $billStart?->toDateString(),
                'billing_period_end' => $billEnd?->toDateString(),
                'status' => Due::STATUS_UNPAID,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($rows, 1000) as $chunk) {
            Due::insert($chunk);
        }
    }

    /**
     * Archive a batch of dues.
     */
    public function archive($batchId)
    {
        Due::where('batch_id', $batchId)->update(['archived_at' => now()]);
        return back()->with('success', 'Dues batch archived successfully.');
    }

    /**
     * Delete a batch of dues.
     */
    public function destroy($batchId)
    {
        Due::where('batch_id', $batchId)->delete();
        return back()->with('success', 'Dues batch deleted successfully.');
    }

    /**
     * Export dues to CSV.
     */
    public function export(Request $request)
    {
        $fileName = 'dues_export_' . now()->format('Y-m-d_His') . '.csv';
        $dues = Due::query()
            ->with('resident')
            ->orderBy('due_date')
            ->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($dues) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Resident Name', 'Title', 'Type', 'Amount', 'Due Date', 'Status', 'Payment Date']);

            foreach ($dues as $due) {
                // Determine payment date if paid (simplified logic, assuming one payment or last payment)
                $paymentDate = $due->status === 'paid' ? $due->updated_at->format('Y-m-d') : 'N/A'; // Or fetch relation

                fputcsv($file, [
                    $due->resident->name ?? 'N/A',
                    $due->title,
                    $due->type,
                    $due->amount,
                    $due->due_date->format('Y-m-d'),
                    ucfirst($due->status),
                    $paymentDate
                ]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
