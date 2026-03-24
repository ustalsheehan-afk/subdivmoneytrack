<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Resident;
use App\Models\Due;
use App\Models\Payment;
use App\Models\Penalty;
use App\Models\Announcement;
use App\Models\ServiceRequest;
use App\Models\Notification;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Mark all notifications as read for the authenticated admin.
     */
    public function markAllNotificationsAsRead()
    {
        Notification::where('admin_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'All notifications marked as read.');
    }

    // =====================
    // MAIN DASHBOARD PAGE
    // =====================
    public function index()
    {
        // Default to This Month to ensure data visibility
        $start = now()->startOfMonth();
        $end = now()->endOfMonth();

        $summaryData = $this->getSummaryData($start, $end);
        [$monthlyCollectionLabels, $monthlyCollectionData] = $this->getMonthlyCollectionData($start, $end);
        $batchStatusDistribution = $this->getBatchStatusDistribution($start, $end);

        return view('admin.dashboard', [
            'summaryData' => $summaryData,
            'monthlyCollectionLabels' => $monthlyCollectionLabels,
            'monthlyCollectionData' => $monthlyCollectionData,
            'batchStatusDistribution' => $batchStatusDistribution,
            'recentAnnouncements' => Announcement::latest()->take(6)->get(),
            'pendingRequestsCount' => ServiceRequest::where('status', 'pending')->count(),
            'recentRequests' => ServiceRequest::with(['resident.user'])->latest()->take(5)->get(),
            'latestPayments' => Payment::with(['resident.user'])->where('status', 'approved')->latest()->take(5)->get(),
            'upcomingDues' => Due::with(['resident.user'])
                ->selectRaw('title, MIN(due_date) as due_date, MAX(amount) as amount, COUNT(*) as count')
                ->where('status', 'unpaid')
                ->where('due_date', '>=', now())
                ->groupBy('title')
                ->orderBy('due_date')
                ->take(5)
                ->get(),
        ]);
    }

    // =====================
    // AJAX DASHBOARD FILTER
    // =====================
    public function getDashboardData(Request $request)
    {
        $request->validate([
            'preset' => 'nullable|in:today,this_month,last_month,custom',
            'date_range' => 'nullable|string'
        ]);

        [$start, $end] = $this->resolveDateRange($request);

        if ($request->preset === 'custom' && (!$start || !$end || $start->gt($end))) {
            return response()->json(['error' => 'Invalid date range selected.'], 422);
        }

        $summaryData = $this->getSummaryData($start, $end);
        [$monthlyCollectionLabels, $monthlyCollectionData] = $this->getMonthlyCollectionData($start, $end);
        $batchStatusDistribution = $this->getBatchStatusDistribution($start, $end);

        $isEmpty =
            $summaryData['totalDuesCollected'] == 0 &&
            $summaryData['pendingPayments'] == 0 &&
            $summaryData['totalPenalties'] == 0;

        return response()->json([
            'isEmpty' => $isEmpty,
            'summaryData' => $summaryData,
            'monthlyCollectionLabels' => $monthlyCollectionLabels,
            'monthlyCollectionData' => $monthlyCollectionData,
            'batchStatusDistribution' => $batchStatusDistribution,
        ]);
    }

    // =====================
    // DATE RANGE
    // =====================
    private function resolveDateRange(Request $request): array
    {
        return match ($request->get('preset')) {
            'today' => [now()->startOfDay(), now()->endOfDay()],
            'this_month' => [now()->startOfMonth(), now()->endOfMonth()],
            'last_month' => [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()],
            'custom' => $this->parseCustomRange($request->get('date_range')),
            default => [null, null],
        };
    }

    private function parseCustomRange(?string $range): array
    {
        if (!$range) return [null, null];

        [$start, $end] = array_pad(explode(' to ', $range), 2, null);

        try {
            return [
                Carbon::parse($start)->startOfDay(),
                Carbon::parse($end ?? $start)->endOfDay(),
            ];
        } catch (\Exception $e) {
            return [null, null];
        }
    }

    // =====================
    // SUMMARY DATA
    // =====================
    private function getSummaryData(?Carbon $start = null, ?Carbon $end = null): array
    {
        $filter = fn ($q, $col) => $start && $end ? $q->whereBetween($col, [$start, $end]) : $q;

        return [
            'totalResidents' => Resident::count(),

            'totalDuesCollected' => Payment::where('status', 'approved')
                ->tap(fn ($q) => $filter($q, 'date_paid'))
                ->sum('amount'),

            'pendingPayments' => Payment::where('status', 'pending')
                ->tap(fn ($q) => $filter($q, 'created_at'))
                ->count(),

            'totalPenalties' => Penalty::tap(fn ($q) => $filter($q, 'created_at'))
                ->sum('amount'),

            'paidDues' => Due::where('status', 'paid')
                ->tap(fn ($q) => $filter($q, 'due_date'))
                ->count(),

            // Pending = Unpaid but NOT YET Overdue (Due Date >= Now/End)
            'pendingDues' => Due::where('status', 'unpaid')
                ->tap(fn ($q) => $filter($q, 'due_date'))
                ->where('due_date', '>=', $end ?? now())
                ->count(),

            // Overdue = Unpaid AND Past Due Date
            'overdueDues' => Due::where('status', 'unpaid')
                ->where('due_date', '<', $end ?? now())
                ->count(),

            // Amounts for Donut Chart
            'paidDuesAmount' => (float) Due::where('status', 'paid')
                ->tap(fn ($q) => $filter($q, 'due_date'))
                ->sum('amount'),

            'unpaidDuesAmount' => (float) Due::where('status', 'unpaid')
                ->tap(fn ($q) => $filter($q, 'due_date'))
                ->sum('amount'),

            'unpaidResidentsCount' => Due::where('status', 'unpaid')
                ->tap(fn ($q) => $filter($q, 'due_date'))
                ->distinct('resident_id')
                ->count('resident_id'),
        ];
    }

    // =====================
    // MONTHLY COLLECTION (PHP Aggregation)
    // =====================
    private function getMonthlyCollectionData(?Carbon $start = null, ?Carbon $end = null): array
    {
        $start = $start ?? now()->startOfYear();
        $end = $end ?? now()->endOfYear();

        // Fetch raw data to avoid SQL strict mode issues with groupBy aliases
        $payments = Payment::select('date_paid', 'amount')
            ->where('status', 'approved')
            ->whereBetween('date_paid', [$start, $end])
            ->get();

        // Group by month in PHP
        $monthlyMap = $payments->groupBy(function($payment) {
            return Carbon::parse($payment->date_paid)->format('M Y');
        })->map(function($group) {
            return $group->sum('amount');
        });

        $labels = [];
        $data = [];

        // Generate all months in the range
        $period = \Carbon\CarbonPeriod::create($start->copy()->startOfMonth(), '1 month', $end->copy()->endOfMonth());

        foreach ($period as $date) {
            $label = $date->format('M Y');
            $labels[] = $label;
            $data[] = $monthlyMap[$label] ?? 0;
        }

        return [$labels, $data];
    }

    // =====================
    // BATCH STATUS DISTRIBUTION (Pie Chart)
    // =====================
    private function getBatchStatusDistribution(?Carbon $start = null, ?Carbon $end = null): array
    {
        $start = $start ?? now()->startOfYear();
        $end = $end ?? now()->endOfYear();

        // Query grouped by batch_id
        $batches = Due::select(
                'batch_id',
                \Illuminate\Support\Facades\DB::raw('sum(amount) as total_expected'),
                \Illuminate\Support\Facades\DB::raw('sum(case when status = "paid" then amount else 0 end) as total_collected')
            )
            ->whereBetween('due_date', [$start, $end])
            ->groupBy('batch_id')
            ->get();

        $counts = ['fully_paid' => 0, 'partial' => 0, 'unpaid' => 0];

        foreach ($batches as $batch) {
            if ($batch->total_expected <= 0) {
                // Treat as unpaid or skip? If expected is 0, percentage is undefined.
                // Assuming valid dues have amount > 0.
                continue;
            }

            $percentage = ($batch->total_collected / $batch->total_expected) * 100;

            if ($percentage >= 99.9) {
                $counts['fully_paid']++;
            } elseif ($percentage > 0.1) {
                $counts['partial']++;
            } else {
                $counts['unpaid']++;
            }
        }

        return $counts;
    }
}
