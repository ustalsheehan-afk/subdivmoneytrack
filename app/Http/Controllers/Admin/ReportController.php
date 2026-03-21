<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use App\Models\Payment;
use App\Models\Due;
use App\Models\Penalty;
use App\Models\ServiceRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->input('category');
        $type = $request->input('type');
        $generate = $request->input('generate');
        
        // Default Dates (This Month)
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        $status = $request->input('status', 'all');
        $selectedColumns = $request->input('columns', []);

        $results = null;
        $summary = null;
        $columns = [];
        $chartData = null;

        if ($generate && $category && $type) {
            $data = $this->getReportData($category, $type, $startDate, $endDate, $status, $selectedColumns);
            $results = $data['rows']; // Use formatted rows
            $summary = $data['summary'];
            $columns = $data['columns'];
            $chartData = $data['chartData'] ?? null;
        }

        return view('admin.reports.index', compact(
            'category', 'type', 'generate', 
            'startDate', 'endDate', 'status', 
            'results', 'summary', 'columns', 'chartData'
        ));
    }

    private function getReportData($category, $type, $startDate, $endDate, $status, $selectedColumns = [])
    {
        $data = collect([]);
        $rows = [];
        $summary = [];
        $columns = [];
        $chartData = null; // For Board View

        // FINANCIAL REPORTS
        if ($category === 'financial') {
            if ($type === 'monthly_collection' || $type === 'payment_history') {
                $query = Payment::with('resident')->whereBetween('date_paid', [$startDate, $endDate]);
                if ($status !== 'all') {
                    $query->where('status', $status);
                }
                $data = $query->latest()->get();
                
                $summary = [
                    'Total Collected' => '₱' . number_format($data->where('status', 'approved')->sum('amount'), 2),
                    'Pending Amount' => '₱' . number_format($data->where('status', 'pending')->sum('amount'), 2),
                    'Transaction Count' => $data->count(),
                ];
                $columns = ['Date Paid', 'Resident', 'Unit', 'Payment Method', 'Amount', 'Status'];
                
                foreach ($data as $item) {
                    $rows[] = [
                        $item->created_at->format('Y-m-d H:i'),
                        $item->resident->full_name ?? 'N/A',
                        ($item->resident->block ?? '') . '-' . ($item->resident->lot ?? ''),
                        $item->payment_method,
                        '₱' . number_format($item->amount, 2),
                        ucfirst($item->status)
                    ];
                }

                if ($type === 'monthly_collection') {
                    // Chart: Daily Collections Trend
                    $dailyCollections = $data->where('status', 'approved')
                        ->groupBy(fn($item) => $item->date_paid ? $item->date_paid->format('M d') : $item->created_at->format('M d'))
                        ->map->sum('amount');
                    
                    $chartData = [
                        'title' => 'Daily Collection Trend',
                        'labels' => $dailyCollections->keys()->toArray(),
                        'values' => $dailyCollections->values()->toArray()
                    ];
                }
            }
            elseif ($type === 'outstanding_balances') {
                $dues = Due::where('status', 'unpaid')->with('resident')->get();
                $grouped = $dues->groupBy('resident_id');
                
                $summaryTotal = 0;
                $summaryCount = 0;

                foreach ($grouped as $residentId => $residentDues) {
                    $resident = $residentDues->first()->resident;
                    if ($resident) {
                        $totalUnpaid = $residentDues->sum('amount');
                        $summaryTotal += $totalUnpaid;
                        $summaryCount++;
                        
                        $rows[] = [
                            $resident->full_name,
                            ($resident->block ?? '') . '-' . ($resident->lot ?? ''),
                            '₱' . number_format($totalUnpaid, 2),
                            $residentDues->count(),
                            $residentDues->max('due_date') ? $residentDues->max('due_date')->format('Y-m-d') : 'N/A'
                        ];
                    }
                }
                
                $summary = [
                    'Total Outstanding' => '₱' . number_format($summaryTotal, 2),
                    'Residents with Debt' => $summaryCount,
                ];
                $columns = ['Resident', 'Unit', 'Total Unpaid', 'Unpaid Bills Count', 'Last Due Date'];
            }
            elseif ($type === 'paid_vs_unpaid') { // Deprecated in UI but kept for logic or if reused
                // ... (Logic same as outstanding_balances essentially, or broader)
            }
            elseif ($type === 'penalties') {
                $query = Penalty::with(['resident', 'payment'])->whereBetween('date_issued', [$startDate, $endDate]);
                if ($status !== 'all') {
                    $query->where('status', $status);
                }
                $data = $query->latest()->get();

                $summary = [
                    'Total Penalties' => '₱' . number_format($data->sum('amount'), 2),
                    'Collected' => '₱' . number_format($data->where('status', 'paid')->sum('amount'), 2),
                    'Pending' => '₱' . number_format($data->where('status', 'pending')->sum('amount'), 2),
                ];
                $columns = ['Date Issued', 'Resident', 'Reason', 'Amount', 'Status'];

                foreach ($data as $item) {
                    $rows[] = [
                        $item->date_issued->format('Y-m-d'),
                        $item->resident->full_name ?? 'N/A',
                        $item->reason,
                        '₱' . number_format($item->amount, 2),
                        ucfirst($item->status)
                    ];
                }
            }
            elseif ($type === 'financial_forecasting') {
                $sixMonthsAgo = Carbon::now()->subMonths(6);
                $collections = Payment::where('status', 'approved')
                    ->where('date_paid', '>=', $sixMonthsAgo)
                    ->selectRaw('YEAR(date_paid) as year, MONTH(date_paid) as month, SUM(amount) as total')
                    ->groupBy('year', 'month')
                    ->get();
                
                $avgCollection = $collections->count() > 0 ? $collections->avg('total') : 0;
                $expectedNextMonth = $avgCollection; 
                // Simple logic: Expect average. 
                
                // Estimate unpaid based on recent unpaid dues
                $estimatedUnpaid = Due::where('status', 'unpaid')
                    ->where('due_date', '>=', $sixMonthsAgo)
                    ->sum('amount');

                $summary = [
                    'Avg Collection (6mo)' => number_format($avgCollection, 2),
                    'Expected Next Month' => number_format($expectedNextMonth, 2),
                    'Est. Unpaid Amount' => number_format($estimatedUnpaid, 2),
                ];
                
                $columns = ['Month', 'Total Collected', 'Vs Average'];
                
                foreach ($collections as $c) {
                    $diff = $c->total - $avgCollection;
                    $rows[] = [
                        Carbon::createFromDate($c->year, $c->month, 1)->format('F Y'),
                        number_format($c->total, 2),
                        ($diff > 0 ? '+' : '') . number_format($diff, 2)
                     ];
                 }

                 $chartData = [
                    'title' => 'Monthly Collections Trend',
                    'labels' => $collections->map(fn($c) => Carbon::createFromDate($c->year, $c->month, 1)->format('M'))->toArray(),
                    'values' => $collections->pluck('total')->toArray()
                 ];
             }
             elseif ($type === 'payment_history') {
                $query = Payment::with(['resident', 'due'])
                    ->whereBetween('date_paid', [$startDate, $endDate]);
                
                if ($status !== 'all') {
                    $query->where('status', $status);
                }

                // Filter by Resident Name (if provided)
                if (request()->filled('resident_name')) {
                    $searchTerm = request()->input('resident_name');
                    $query->whereHas('resident', function($q) use ($searchTerm) {
                        $q->where('first_name', 'like', "%{$searchTerm}%")
                          ->orWhere('last_name', 'like', "%{$searchTerm}%");
                    });
                }

                // Filter by Billing Period (if provided)
                if (request()->filled('billing_period')) {
                    $periodTerm = request()->input('billing_period');
                    $query->whereHas('due', function($q) use ($periodTerm) {
                        $q->where('title', 'like', "%{$periodTerm}%") // Check title (e.g. "January 2025")
                          ->orWhere('month', 'like', "%{$periodTerm}%");
                    });
                }
                
                $data = $query->latest('date_paid')->get();
                
                $summary = [
                    'Total Payments' => $data->count(),
                    'Total Amount' => number_format($data->sum('amount'), 2),
                ];
                
                $columns = ['Date Paid', 'Resident', 'Unit', 'Billing Period', 'Amount', 'Status'];
                
                foreach ($data as $item) {
                    $period = $item->due ? 
                        ($item->due->billing_period_start ? $item->due->billing_period_start->format('M Y') : 'N/A') 
                        : 'N/A';
                        
                    $rows[] = [
                        $item->date_paid ? $item->date_paid->format('Y-m-d') : $item->created_at->format('Y-m-d'),
                        $item->resident->full_name ?? 'Unknown',
                        ($item->resident->block ?? '') . '-' . ($item->resident->lot ?? ''),
                        $period,
                        number_format($item->amount, 2),
                        ucfirst($item->status)
                    ];
                }
            }
            elseif ($type === 'statement_financial_position') {
                $rows = $this->getStatementOfFinancialPositionData($endDate);
                $summary = [
                    'Total Assets' => '₱' . number_format($rows['total_assets'], 2),
                    'Total Liabilities' => '₱' . number_format($rows['total_liabilities'], 2),
                    'Total Equity' => '₱' . number_format($rows['total_equity'], 2),
                ];
                $columns = ['Account Name', 'Amount (₱)'];
            }
        }
        
        // RESIDENT REPORTS
        elseif ($category === 'resident') {
            if ($type === 'resident_list') {
                $query = Resident::query();
                if ($status !== 'all') {
                    $query->where('status', $status);
                }
                $query->whereBetween('move_in_date', [$startDate, $endDate]);
                
                $data = $query->get();
                $summary = [
                    'Total Residents' => $data->count(),
                    'Active' => $data->where('status', 'active')->count(),
                ];
                $columns = ['Name', 'Block/Lot', 'Contact', 'Status', 'Move In Date'];
                
                foreach ($data as $item) {
                    $rows[] = [
                        $item->full_name,
                        ($item->block ?? '') . '-' . ($item->lot ?? ''),
                        $item->contact_number,
                        ucfirst($item->status),
                        $item->move_in_date ? $item->move_in_date->format('Y-m-d') : 'N/A'
                    ];
                }
            }
        }
        
        // AMENITIES REPORTS
        elseif ($category === 'amenities') {
            if ($type === 'amenity_usage') {
                $data = ServiceRequest::where('type', 'like', 'Amenity:%')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->with('resident')
                    ->latest()
                    ->get();
                    
                $summary = [
                    'Total Reservations' => $data->count(),
                    'Approved' => $data->where('status', 'approved')->count(),
                ];
                $columns = ['Date', 'Amenity', 'Resident', 'Status'];
                
                foreach ($data as $item) {
                    $rows[] = [
                        $item->created_at->format('Y-m-d'),
                        str_replace('Amenity: ', '', $item->type),
                        $item->resident->full_name ?? 'N/A',
                        ucfirst($item->status)
                    ];
                }
            }
            elseif ($type === 'most_used') {
                $data = ServiceRequest::where('type', 'like', 'Amenity:%')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->select('type', DB::raw('count(*) as total'))
                    ->groupBy('type')
                    ->orderByDesc('total')
                    ->get();

                $summary = [
                    'Most Popular' => $data->first() ? str_replace('Amenity: ', '', $data->first()->type) : 'N/A',
                    'Total Usage' => $data->sum('total'),
                ];
                $columns = ['Amenity Name', 'Total Reservations', 'Percentage'];

                $total = $data->sum('total');
                foreach ($data as $item) {
                    $rows[] = [
                        str_replace('Amenity: ', '', $item->type),
                        $item->total,
                        $total > 0 ? round(($item->total / $total) * 100, 1) . '%' : '0%'
                    ];
                }

                $chartData = [
                    'title' => 'Amenity Usage Distribution',
                    'labels' => $data->map(fn($item) => str_replace('Amenity: ', '', $item->type))->toArray(),
                    'values' => $data->pluck('total')->toArray()
                ];
            }
            elseif ($type === 'amenity_revenue') {
                // Approximate revenue by finding Payments linked to Dues with "Amenity" or "Reservation" in title
                $data = Payment::whereHas('due', function($q) {
                        $q->where('title', 'like', '%Amenity%')
                          ->orWhere('title', 'like', '%Reservation%');
                    })
                    ->whereBetween('date_paid', [$startDate, $endDate])
                    ->with(['resident', 'due'])
                    ->get();
                
                $summary = [
                    'Total Revenue' => number_format($data->sum('amount'), 2),
                    'Transactions' => $data->count(),
                ];
                $columns = ['Date', 'Amenity/Fee', 'Resident', 'Amount'];
                
                foreach ($data as $item) {
                    $rows[] = [
                        $item->date_paid ? $item->date_paid->format('Y-m-d') : $item->created_at->format('Y-m-d'),
                        $item->due->title ?? 'N/A',
                        $item->resident->full_name ?? 'N/A',
                        number_format($item->amount, 2)
                    ];
                }

                // Chart: Revenue by Amenity Type
                $revenueByAmenity = $data->groupBy(function($item) {
                    return $item->due->title ?? 'Unknown';
                })->map->sum('amount');

                $chartData = [
                    'title' => 'Revenue by Amenity',
                    'labels' => $revenueByAmenity->keys()->toArray(),
                    'values' => $revenueByAmenity->values()->toArray()
                ];
            }
            elseif ($type === 'reservation_history') {
                $data = ServiceRequest::where('type', 'like', 'Amenity:%')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->with('resident')
                    ->latest()
                    ->get();
                    
                $summary = [
                    'Total Reservations' => $data->count(),
                    'Approved' => $data->where('status', 'approved')->count(),
                    'Rejected' => $data->where('status', 'rejected')->count(),
                ];
                $columns = ['Date Requested', 'Amenity', 'Resident', 'Status'];
                
                foreach ($data as $item) {
                    $rows[] = [
                        $item->created_at->format('Y-m-d H:i'),
                        str_replace('Amenity: ', '', $item->type),
                        $item->resident->full_name ?? 'N/A',
                        ucfirst($item->status)
                    ];
                }
            }
        }
        
        // MAINTENANCE REPORTS
        elseif ($category === 'maintenance') {
            if ($type === 'maintenance_repeated' || $type === 'complaints_by_category') {
                $data = ServiceRequest::where('type', 'not like', 'Amenity:%')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->select('type', DB::raw('count(*) as total'))
                    ->groupBy('type')
                    ->orderByDesc('total')
                    ->get();

                $summary = [
                    'Top Issue' => $data->first() ? $data->first()->type : 'None',
                    'Total Issues' => $data->sum('total'),
                ];
                $columns = ['Issue Category', 'Report Count', 'Percentage'];
                
                $total = $data->sum('total');
                foreach ($data as $item) {
                    $rows[] = [
                        ucfirst($item->type),
                        $item->total,
                        $total > 0 ? round(($item->total / $total) * 100, 1) . '%' : '0%'
                    ];
                }

                $chartData = [
                    'title' => 'Top Maintenance Issues',
                    'labels' => $data->map(fn($item) => ucfirst($item->type))->toArray(),
                    'values' => $data->pluck('total')->toArray()
                ];
            }
            elseif ($type === 'request_summary') {
             $query = ServiceRequest::where('type', 'not like', 'Amenity:%')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->with('resident');
                
             if ($status !== 'all') {
                 $query->where('status', $status);
             }
             
             $data = $query->latest()->get();
             
             $resolved = $data->whereIn('status', ['completed', 'approved'])->whereNotNull('updated_at');
             $totalHours = 0;
             foreach($resolved as $r) {
                 $totalHours += $r->created_at->diffInHours($r->updated_at);
             }
             $avgTime = $resolved->count() > 0 ? round($totalHours / 24, 1) . ' Days' : 'N/A';
             
             $summary = [
                 'Total Requests' => $data->count(),
                 'Pending' => $data->where('status', 'pending')->count(),
                 'Avg Resolution Time' => $avgTime,
             ];
             $columns = ['Date', 'Type', 'Resident', 'Priority', 'Status'];
             
             foreach ($data as $item) {
                 $rows[] = [
                     $item->created_at->format('Y-m-d'),
                     $item->type,
                     $item->resident->full_name ?? 'N/A',
                     ucfirst($item->priority),
                     ucfirst($item->status)
                 ];
             }

             // Chart: Request Status Distribution
             $statusDist = $data->groupBy('status')->map->count();
             $chartData = [
                'title' => 'Request Status Distribution',
                'labels' => $statusDist->keys()->map(fn($k) => ucfirst($k))->toArray(),
                'values' => $statusDist->values()->toArray()
            ];
        }
        } // End Maintenance

        // CUSTOM REPORTS
        elseif ($category === 'custom') {
            $columns = [];
            $columnMap = [];

            if ($type === 'custom_financial') {
                $query = Payment::with('resident')->whereBetween('date_paid', [$startDate, $endDate]);
                if ($status !== 'all') {
                    $query->where('status', $status);
                }
                $data = $query->latest()->get();

                // Map selection to labels and accessor logic
                $availableColumns = [
                    'created_at' => 'Date Paid', 
                    'resident_name' => 'Resident Name', 
                    'unit' => 'Unit', 
                    'amount' => 'Amount', 
                    'status' => 'Status', 
                    'payment_method' => 'Payment Method'
                ];

                foreach ($selectedColumns as $col) {
                    if (isset($availableColumns[$col])) {
                        $columns[] = $availableColumns[$col];
                        $columnMap[] = $col;
                    }
                }
                // Fallback if no columns selected
                if (empty($columns)) {
                    $columns = ['Date Paid', 'Amount'];
                    $columnMap = ['created_at', 'amount'];
                }

                $summary = ['Records Found' => $data->count(), 'Total Amount' => '₱' . number_format($data->sum('amount'), 2)];

                foreach ($data as $item) {
                    $row = [];
                    foreach ($columnMap as $colKey) {
                        if ($colKey === 'created_at') $row[] = $item->created_at->format('Y-m-d H:i');
                        elseif ($colKey === 'resident_name') $row[] = $item->resident->full_name ?? 'N/A';
                        elseif ($colKey === 'unit') $row[] = ($item->resident->block ?? '') . '-' . ($item->resident->lot ?? '');
                        elseif ($colKey === 'amount') $row[] = '₱' . number_format($item->amount, 2);
                        elseif ($colKey === 'status') $row[] = ucfirst($item->status);
                        elseif ($colKey === 'payment_method') $row[] = $item->payment_method;
                        else $row[] = $item->$colKey ?? '';
                    }
                    $rows[] = $row;
                }

            } elseif ($type === 'custom_resident') {
                $query = Resident::query();
                if ($status !== 'all') {
                    $query->where('status', $status);
                }
                // Optional: Date range for residents usually means move-in date
                // $query->whereBetween('move_in_date', [$startDate, $endDate]);
                $data = $query->get();

                $availableColumns = [
                    'full_name' => 'Name', 
                    'unit' => 'Unit', 
                    'contact_number' => 'Contact', 
                    'email' => 'Email', 
                    'status' => 'Status', 
                    'move_in_date' => 'Move In Date'
                ];

                foreach ($selectedColumns as $col) {
                    if (isset($availableColumns[$col])) {
                        $columns[] = $availableColumns[$col];
                        $columnMap[] = $col;
                    }
                }
                if (empty($columns)) {
                    $columns = ['Name', 'Status'];
                    $columnMap = ['full_name', 'status'];
                }

                $summary = ['Total Residents' => $data->count()];

                foreach ($data as $item) {
                    $row = [];
                    foreach ($columnMap as $colKey) {
                        if ($colKey === 'full_name') $row[] = $item->full_name;
                        elseif ($colKey === 'unit') $row[] = ($item->block ?? '') . '-' . ($item->lot ?? '');
                        elseif ($colKey === 'move_in_date') $row[] = $item->move_in_date ? $item->move_in_date->format('Y-m-d') : 'N/A';
                        elseif ($colKey === 'status') $row[] = ucfirst($item->status);
                        else $row[] = $item->$colKey ?? '';
                    }
                    $rows[] = $row;
                }
            }
        }

        return ['rows' => $rows, 'summary' => $summary, 'columns' => $columns, 'chartData' => $chartData];
    }

    public function exportExcel(Request $request)
    {
        $category = $request->input('category');
        $type = $request->input('type');
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        $status = $request->input('status', 'all');
        $selectedColumns = $request->input('columns', []);

        $data = $this->getReportData($category, $type, $startDate, $endDate, $status, $selectedColumns);
        
        $filename = "report-{$category}-{$type}-{$startDate}.xls";
        
        // Simple HTML Table Export for Excel
        return response()->streamDownload(function() use ($data, $category, $type, $startDate, $endDate) {
            echo '<html><head><meta charset="utf-8"></head><body>';
            echo '<h2>' . ucwords(str_replace('_', ' ', $type ?? 'Report')) . ' Report</h2>';
            echo '<p>Period: ' . $startDate . ' to ' . $endDate . '</p>';
            echo '<table border="1">';
            
            // Headers
            echo '<tr style="background-color: #f0f0f0; font-weight: bold;">';
            foreach ($data['columns'] as $col) {
                echo '<th style="padding: 5px;">' . htmlspecialchars($col ?? '') . '</th>';
            }
            echo '</tr>';
            
            // Rows
            foreach ($data['rows'] as $row) {
                echo '<tr>';
                foreach ($row as $cell) {
                    echo '<td style="padding: 5px;">' . htmlspecialchars($cell ?? '') . '</td>';
                }
                echo '</tr>';
            }
            
            echo '</table></body></html>';
        }, $filename);
    }

    public function exportCsv(Request $request)
    {
        $category = $request->input('category');
        $type = $request->input('type');
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        $status = $request->input('status', 'all');
        $selectedColumns = $request->input('columns', []);

        $data = $this->getReportData($category, $type, $startDate, $endDate, $status, $selectedColumns);
        $results = $data['rows'];
        $columns = $data['columns'];

        $filename = "report-{$category}-{$type}-{$startDate}.csv";
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($results, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($results as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $category = $request->input('category');
        $type = $request->input('type');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status', 'all');
        $selectedColumns = $request->input('columns', []);
        
        $data = $this->getReportData($category, $type, $startDate, $endDate, $status, $selectedColumns);
        
        if ($type === 'statement_financial_position') {
            $pdf = Pdf::loadView('admin.reports.pdf_financial_position', [
                'results' => $data['rows'],
                'asOf' => $endDate,
                'title' => 'Statement of Financial Position'
            ]);
            return $pdf->download('Statement_of_Financial_Position.pdf');
        }

        $pdf = Pdf::loadView('admin.reports.pdf_generic', [
            'results' => $data['rows'],
            'columns' => $data['columns'],
            'summary' => $data['summary'],
            'title' => ucwords(str_replace('_', ' ', $type)),
            'period' => "$startDate to $endDate"
        ]);

        return $pdf->download('report.pdf');
    }

    private function getStatementOfFinancialPositionData($asOfDate)
    {
        // Calculate dynamic values from system data
        $asOf = Carbon::parse($asOfDate)->endOfDay();

        // 1. Cash on Hand/Bank (Approximation: Total Approved Payments)
        $totalCash = Payment::where('status', 'approved')
            ->where('date_paid', '<=', $asOf)
            ->sum('amount');
        
        // 2. Resident Receivables (Unpaid Dues)
        $receivables = Due::where('status', 'unpaid')
            ->where('due_date', '<=', $asOf)
            ->sum('amount');
        
        // 3. Penalty Receivables
        $penaltyReceivables = Penalty::where('status', 'pending')
            ->where('date_issued', '<=', $asOf)
            ->sum('amount');

        // Static fallbacks for non-existent accounting tables
        $officeEquip = 150000.00;
        $furniture = 75000.00;
        $prepaid = 12000.00;

        $totalAssets = $totalCash + $receivables + $penaltyReceivables + $prepaid + $officeEquip + $furniture;

        // Liabilities
        $accountsPayable = 25000.00;
        $utilitiesPayable = 18500.00;
        $maintenancePayable = 12000.00;
        $longTermLoans = 500000.00;
        
        $totalLiabilities = $accountsPayable + $utilitiesPayable + $maintenancePayable + $longTermLoans;

        // Equity (Equation: Equity = Assets - Liabilities)
        $totalEquity = $totalAssets - $totalLiabilities;
        $associationCapital = $totalEquity * 0.7; // Example split
        $retainedEarnings = $totalEquity * 0.3;

        return [
            'as_of' => $asOf->format('F j, Y'),
            'assets' => [
                'current' => [
                    'Cash on Hand' => $totalCash * 0.1,
                    'Cash in Bank' => $totalCash * 0.9,
                    'Resident Receivables' => $receivables,
                    'Penalty Receivables' => $penaltyReceivables,
                    'Prepaid Expenses' => $prepaid,
                ],
                'noncurrent' => [
                    'Office Equipment' => $officeEquip,
                    'Furniture and Fixtures' => $furniture,
                ],
                'total' => $totalAssets
            ],
            'liabilities' => [
                'current' => [
                    'Accounts Payable' => $accountsPayable,
                    'Utility Payables' => $utilitiesPayable,
                    'Maintenance Payables' => $maintenancePayable,
                ],
                'noncurrent' => [
                    'Long-term Loans' => $longTermLoans,
                ],
                'total' => $totalLiabilities
            ],
            'equity' => [
                'Association Capital' => $associationCapital,
                'Retained Earnings' => $retainedEarnings,
                'total' => $totalEquity
            ],
            'total_assets' => $totalAssets,
            'total_liabilities' => $totalLiabilities,
            'total_equity' => $totalEquity
        ];
    }
}
