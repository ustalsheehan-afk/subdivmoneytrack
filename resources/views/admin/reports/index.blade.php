@extends('layouts.admin')

@section('title', 'Reports Management')
@section('page-title', 'Reports Management')

@section('content')
<div class="h-full bg-[#F8F9FB] overflow-y-auto" x-data="{ showSummary: false }">
    <div class="max-w-7xl mx-auto px-6 py-8">

        {{-- BREADCRUMBS / BACK NAVIGATION --}}
        @if($category)
        <div class="mb-6 flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('admin.reports.index') }}" class="hover:text-blue-600 flex items-center gap-1">
                <i class="bi bi-grid-fill"></i> Reports
            </a>
            <i class="bi bi-chevron-right text-xs"></i>
            <a href="{{ route('admin.reports.index', ['category' => $category]) }}" class="hover:text-blue-600 capitalize {{ $type ? '' : 'font-bold text-gray-900' }}">
                {{ $category }} Reports
            </a>
            @if($type)
                <i class="bi bi-chevron-right text-xs"></i>
                <span class="font-bold text-gray-900 capitalize">{{ str_replace('_', ' ', $type) }}</span>
            @endif
        </div>
        @endif

        {{-- STEP 1: LANDING PAGE (CATEGORIES) --}}
        @if(!$category)
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Reports Center</h1>
            <p class="text-gray-500 mt-2">Select a category to generate detailed reports.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Financial --}}
            <a href="{{ route('admin.reports.index', ['category' => 'financial']) }}" class="group bg-white p-8 rounded-2xl shadow-sm border border-gray-200 hover:border-blue-500 hover:shadow-md transition-all">
                <div class="flex items-start justify-between mb-4">
                    <div class="p-4 bg-blue-50 rounded-xl group-hover:bg-blue-600 transition-colors">
                        <i class="bi bi-wallet2 text-2xl text-blue-600 group-hover:text-white transition-colors"></i>
                    </div>
                    <i class="bi bi-arrow-right text-gray-300 group-hover:text-blue-500 text-xl transition-colors"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">Financial Reports</h3>
                <p class="text-gray-500 mt-2 text-sm">Collections, unpaid dues, penalties, and forecasting.</p>
            </a>

            {{-- Resident --}}
            <a href="{{ route('admin.reports.index', ['category' => 'resident']) }}" class="group bg-white p-8 rounded-2xl shadow-sm border border-gray-200 hover:border-emerald-500 hover:shadow-md transition-all">
                <div class="flex items-start justify-between mb-4">
                    <div class="p-4 bg-emerald-50 rounded-xl group-hover:bg-emerald-600 transition-colors">
                        <i class="bi bi-people text-2xl text-emerald-600 group-hover:text-white transition-colors"></i>
                    </div>
                    <i class="bi bi-arrow-right text-gray-300 group-hover:text-emerald-500 text-xl transition-colors"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 group-hover:text-emerald-600 transition-colors">Resident Reports</h3>
                <p class="text-gray-500 mt-2 text-sm">Resident lists, active status, and move-in history.</p>
            </a>

            {{-- Amenities --}}
            <a href="{{ route('admin.reports.index', ['category' => 'amenities']) }}" class="group bg-white p-8 rounded-2xl shadow-sm border border-gray-200 hover:border-purple-500 hover:shadow-md transition-all">
                <div class="flex items-start justify-between mb-4">
                    <div class="p-4 bg-purple-50 rounded-xl group-hover:bg-purple-600 transition-colors">
                        <i class="bi bi-tree text-2xl text-purple-600 group-hover:text-white transition-colors"></i>
                    </div>
                    <i class="bi bi-arrow-right text-gray-300 group-hover:text-purple-500 text-xl transition-colors"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 group-hover:text-purple-600 transition-colors">Amenities Reports</h3>
                <p class="text-gray-500 mt-2 text-sm">Usage statistics, reservations, and popularity.</p>
            </a>

            {{-- Requests --}}
            <a href="{{ route('admin.reports.index', ['category' => 'maintenance']) }}" class="group bg-white p-8 rounded-2xl shadow-sm border border-gray-200 hover:border-orange-500 hover:shadow-md transition-all">
                <div class="flex items-start justify-between mb-4">
                    <div class="p-4 bg-orange-50 rounded-xl group-hover:bg-orange-600 transition-colors">
                        <i class="bi bi-tools text-2xl text-orange-600 group-hover:text-white transition-colors"></i>
                    </div>
                    <i class="bi bi-arrow-right text-gray-300 group-hover:text-orange-500 text-xl transition-colors"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 group-hover:text-orange-600 transition-colors">Requests & Maintenance</h3>
                <p class="text-gray-500 mt-2 text-sm">Complaints, maintenance tracking, and resolution times.</p>
            </a>

            {{-- Custom Builder --}}
            <a href="{{ route('admin.reports.index', ['category' => 'custom']) }}" class="group bg-white p-8 rounded-2xl shadow-sm border border-gray-200 hover:border-gray-800 hover:shadow-md transition-all md:col-span-2">
                <div class="flex items-start justify-between mb-4">
                    <div class="p-4 bg-gray-100 rounded-xl group-hover:bg-gray-800 transition-colors">
                        <i class="bi bi-sliders text-2xl text-gray-800 group-hover:text-white transition-colors"></i>
                    </div>
                    <i class="bi bi-arrow-right text-gray-300 group-hover:text-gray-800 text-xl transition-colors"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 group-hover:text-gray-800 transition-colors">Custom Report Builder</h3>
                <p class="text-gray-500 mt-2 text-sm">Select specific columns, apply filters, and build your own report.</p>
            </a>
        </div>
        @endif

        {{-- STEP 2: SELECT REPORT TYPE --}}
        @if($category && !$type)
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight capitalize">{{ $category }} Reports</h1>
            <p class="text-gray-500 mt-2">Choose a specific report to generate.</p>
        </div>

        <div class="grid grid-cols-1 gap-4">
            @if($category == 'financial')
                @foreach([
                    'monthly_collection' => 'Monthly Dues Collection',
                    'outstanding_balances' => 'Outstanding Balances',
                    'payment_history' => 'Payment History',
                    'penalties' => 'Penalties & Late Fees',
                    'financial_forecasting' => 'Financial Forecasting',
                    'statement_financial_position' => 'Statement of Financial Position'
                ] as $key => $label)
                    <a href="{{ route('admin.reports.index', ['category' => $category, 'type' => $key]) }}" class="block p-5 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-blue-500 transition-all flex justify-between items-center group">
                        <span class="font-bold text-gray-800 group-hover:text-blue-700">{{ $label }}</span>
                        <i class="bi bi-chevron-right text-gray-400 group-hover:text-blue-500"></i>
                    </a>
                @endforeach
            @elseif($category == 'resident')
                 <a href="{{ route('admin.reports.index', ['category' => $category, 'type' => 'resident_list']) }}" class="block p-5 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-emerald-500 transition-all flex justify-between items-center group">
                    <span class="font-bold text-gray-800 group-hover:text-emerald-700">Resident List & Status</span>
                    <i class="bi bi-chevron-right text-gray-400 group-hover:text-emerald-500"></i>
                </a>
            @elseif($category == 'amenities')
                @foreach([
                    'amenity_usage' => 'Amenity Usage Summary',
                    'reservation_history' => 'Reservation History',
                    'most_used' => 'Most/Least Used Amenities',
                    'amenity_revenue' => 'Amenity Revenue Report'
                ] as $key => $label)
                    <a href="{{ route('admin.reports.index', ['category' => $category, 'type' => $key]) }}" class="block p-5 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-purple-500 transition-all flex justify-between items-center group">
                        <span class="font-bold text-gray-800 group-hover:text-purple-700">{{ $label }}</span>
                        <i class="bi bi-chevron-right text-gray-400 group-hover:text-purple-500"></i>
                    </a>
                @endforeach
            @elseif($category == 'maintenance')
                @foreach([
                    'request_summary' => 'Maintenance Requests Summary',
                    'complaints_by_category' => 'Total Complaints by Category',
                    'maintenance_repeated' => 'Repeated Issues per Area'
                ] as $key => $label)
                 <a href="{{ route('admin.reports.index', ['category' => $category, 'type' => $key]) }}" class="block p-5 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-orange-500 transition-all flex justify-between items-center group">
                    <span class="font-bold text-gray-800 group-hover:text-orange-700">{{ $label }}</span>
                    <i class="bi bi-chevron-right text-gray-400 group-hover:text-orange-500"></i>
                </a>
                @endforeach
            @elseif($category == 'custom')
                 <a href="{{ route('admin.reports.index', ['category' => $category, 'type' => 'custom_financial']) }}" class="block p-5 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-gray-800 transition-all flex justify-between items-center group">
                    <span class="font-bold text-gray-800 group-hover:text-gray-700">Financial Data (Payments/Dues)</span>
                    <i class="bi bi-chevron-right text-gray-400 group-hover:text-gray-500"></i>
                </a>
                <a href="{{ route('admin.reports.index', ['category' => $category, 'type' => 'custom_resident']) }}" class="block p-5 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-gray-800 transition-all flex justify-between items-center group">
                    <span class="font-bold text-gray-800 group-hover:text-gray-700">Resident Data</span>
                    <i class="bi bi-chevron-right text-gray-400 group-hover:text-gray-500"></i>
                </a>
            @endif
        </div>
        @endif

        {{-- STEP 3: CONFIGURE FILTERS --}}
        @if($type && !$generate)
        <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <div class="mb-6 text-center">
                <h1 class="text-2xl font-bold text-gray-900 capitalize">{{ str_replace('_', ' ', $type) }}</h1>
                <p class="text-gray-500 mt-1">Set your filters to generate the report.</p>
            </div>

            <form action="{{ route('admin.reports.index') }}" method="GET">
                <input type="hidden" name="category" value="{{ $category }}">
                <input type="hidden" name="type" value="{{ $type }}">
                <input type="hidden" name="generate" value="true">

                <div class="space-y-6">
                    {{-- Date Range --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                            <input type="date" name="start_date" value="{{ $startDate }}" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                            <input type="date" name="end_date" value="{{ $endDate }}" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    {{-- Custom Columns Selection --}}
                    @if($category == 'custom')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Columns</label>
                        <div class="grid grid-cols-2 gap-2 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            @if($type == 'custom_financial')
                                @foreach(['created_at' => 'Date Paid', 'resident_name' => 'Resident Name', 'unit' => 'Unit (Block/Lot)', 'amount' => 'Amount', 'status' => 'Status', 'payment_method' => 'Payment Method'] as $col => $label)
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" name="columns[]" value="{{ $col }}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" checked>
                                        <span class="text-sm text-gray-700">{{ $label }}</span>
                                    </label>
                                @endforeach
                            @elseif($type == 'custom_resident')
                                @foreach(['full_name' => 'Name', 'unit' => 'Unit (Block/Lot)', 'contact_number' => 'Contact', 'email' => 'Email', 'status' => 'Status', 'move_in_date' => 'Move In Date'] as $col => $label)
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" name="columns[]" value="{{ $col }}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" checked>
                                        <span class="text-sm text-gray-700">{{ $label }}</span>
                                    </label>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Status Filter (Conditional) --}}
                    @if($type !== 'outstanding_balances' && $type !== 'paid_vs_unpaid' && $type !== 'financial_forecasting' && $type !== 'statement_financial_position')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All Statuses</option>
                            @if($category == 'financial')
                                <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Paid / Approved</option>
                                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            @elseif($category == 'resident')
                                <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            @else
                                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved / In Progress</option>
                                <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                            @endif
                        </select>
                    </div>
                    @endif

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition-colors shadow-sm">
                            Generate Report
                        </button>
                    </div>
                </div>
            </form>
        </div>
        @endif

        {{-- STEP 4: REPORT OUTPUT --}}
        @if($generate)
        <div class="space-y-6">
            {{-- Header & Actions --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 capitalize">{{ str_replace('_', ' ', $type) }}</h1>
                    <div class="flex items-center gap-2 text-sm text-gray-500 mt-1">
                        <span class="bg-gray-100 px-2 py-0.5 rounded border border-gray-200">
                            {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
                        </span>
                        @if($status !== 'all')
                        <span class="bg-gray-100 px-2 py-0.5 rounded border border-gray-200 capitalize">
                            Status: {{ $status }}
                        </span>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    {{-- Summary Toggle --}}
                    <div class="bg-white p-1 rounded-lg border border-gray-200 flex items-center shadow-sm">
                        <button @click="showSummary = false" 
                            :class="!showSummary ? 'bg-gray-100 text-gray-900 font-bold shadow-sm' : 'text-gray-500 hover:bg-gray-50'"
                            class="px-3 py-1.5 rounded-md text-sm transition-all">
                            <i class="bi bi-table mr-1"></i> Detail View
                        </button>
                        <button @click="showSummary = true" 
                            :class="showSummary ? 'bg-gray-100 text-gray-900 font-bold shadow-sm' : 'text-gray-500 hover:bg-gray-50'"
                            class="px-3 py-1.5 rounded-md text-sm transition-all">
                            <i class="bi bi-pie-chart mr-1"></i> Board View
                        </button>
                    </div>

                    {{-- Export Buttons --}}
                    <div class="h-8 w-px bg-gray-300 mx-1"></div>
                    
                    <a href="{{ route('admin.reports.exportPdf', request()->all()) }}" class="bg-red-50 text-red-700 hover:bg-red-100 border border-red-200 px-4 py-2 rounded-lg text-sm font-bold transition-colors">
                        <i class="bi bi-file-pdf"></i> PDF
                    </a>
                    <a href="{{ route('admin.reports.exportExcel', request()->all()) }}" class="bg-green-50 text-green-700 hover:bg-green-100 border border-green-200 px-4 py-2 rounded-lg text-sm font-bold transition-colors">
                        <i class="bi bi-file-earmark-excel"></i> Excel
                    </a>
                    <a href="{{ route('admin.reports.exportCsv', request()->all()) }}" class="bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 px-4 py-2 rounded-lg text-sm font-bold transition-colors">
                        <i class="bi bi-file-text"></i> CSV
                    </a>
                </div>
            </div>

            {{-- VIEW: BOARD VIEW (SUMMARY + CHARTS) --}}
            <div x-show="showSummary" style="display: none;" class="space-y-6">
                {{-- Summary Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($summary as $key => $value)
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                        <p class="text-sm font-bold text-gray-500 uppercase tracking-wide">{{ $key }}</p>
                        <p class="text-3xl font-extrabold text-gray-900 mt-2">{{ $value }}</p>
                    </div>
                    @endforeach
                </div>

                {{-- Simple CSS Chart --}}
                @if(isset($chartData) && $chartData)
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-8">{{ $chartData['title'] }}</h3>
                    
                    <div class="relative h-64 flex items-end justify-between space-x-2 md:space-x-6 px-4">
                        @php 
                            $maxValue = 1;
                            if (!empty($chartData['values'])) {
                                $localMax = max($chartData['values']);
                                $maxValue = $localMax > 0 ? $localMax : 1;
                            }
                        @endphp
                        @foreach($chartData['values'] as $index => $value)
                            @php 
                                $height = ($value / $maxValue) * 100; 
                                $label = $chartData['labels'][$index] ?? '';
                                $colors = ['bg-blue-500', 'bg-emerald-500', 'bg-purple-500', 'bg-orange-500', 'bg-pink-500'];
                                $color = $colors[$index % count($colors)];
                            @endphp
                            <div class="flex-1 flex flex-col items-center group relative">
                                <div class="w-full {{ $color }} rounded-t-lg hover:opacity-90 transition-all relative" style="height: {{ $height }}%; min-height: 4px;">
                                    <div class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs font-bold py-1 px-2 rounded shadow-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10">
                                        {{ number_format($value) }}
                                    </div>
                                </div>
                                <div class="mt-3 text-xs font-medium text-gray-500 text-center w-full truncate" title="{{ $label }}">
                                    {{ $label }}
                                </div>
                            </div>
                        @endforeach
                        
                        {{-- Background Lines --}}
                        <div class="absolute inset-0 pointer-events-none flex flex-col justify-between" style="z-index: 0;">
                            <div class="border-t border-gray-100 w-full h-0"></div>
                            <div class="border-t border-gray-100 w-full h-0"></div>
                            <div class="border-t border-gray-100 w-full h-0"></div>
                            <div class="border-t border-gray-100 w-full h-0"></div>
                            <div class="border-t border-gray-200 w-full h-0"></div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- VIEW: TABLE --}}
            <div x-show="!showSummary" class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                @if($type === 'statement_financial_position')
                    @include('admin.reports.partials.statement_financial_position', ['results' => $results])
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                @foreach($columns as $col)
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">{{ $col }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($results as $row)
                            <tr class="hover:bg-gray-50 transition-colors">
                                @foreach($row as $cell)
                                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                        {{ $cell }}
                                    </td>
                                @endforeach
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ count($columns) }}" class="px-6 py-8 text-center text-gray-500 italic">
                                    No records found for the selected criteria.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        {{-- Totals Footer --}}
                        <tfoot class="bg-gray-50 border-t border-gray-200">
                            <tr>
                                <td colspan="{{ count($columns) }}" class="px-6 py-4">
                                    <div class="flex gap-6 text-sm">
                                        @foreach($summary as $key => $value)
                                            <div>
                                                <span class="font-bold text-gray-500">{{ $key }}:</span>
                                                <span class="font-bold text-gray-900 ml-1">{{ $value }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @endif
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
