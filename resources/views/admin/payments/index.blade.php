@extends('layouts.admin')

@section('title', 'Payments')
@section('page-title', 'Payments History')

@section('content')
<div class="h-full bg-[#F8F9FB] overflow-y-auto">
    <div class="max-w-7xl mx-auto px-6 py-8 flex flex-col gap-8">

        {{-- STATS SECTION --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Total Collected (Year) --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="bi bi-wallet2 text-6xl text-emerald-600"></i>
                </div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Collected ({{ date('Y') }})</p>
                <h3 class="text-2xl font-bold text-gray-900">₱{{ number_format($totalCollectedYear, 2) }}</h3>
                <div class="mt-4 flex items-center text-xs text-emerald-700 font-bold">
                    <p class="text-sm font-bold text-emerald-600">Approved Payments</p>
                </div>
            </div>

            {{-- Pending Approvals --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="bi bi-hourglass-split text-6xl text-orange-500"></i>
                </div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Pending Approval</p>
                <h3 class="text-2xl font-bold text-gray-900">₱{{ number_format($pendingAmount, 2) }}</h3>
                <div class="mt-4 flex items-center text-xs text-orange-600 font-medium">
                    <p class="text-sm font-bold text-orange-600">{{ $pendingCount }} transactions waiting</p>
                </div>
            </div>

            {{-- This Month --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="bi bi-calendar-check text-6xl text-blue-500"></i>
                </div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Collected This Month</p>
                <h3 class="text-2xl font-bold text-gray-900">₱{{ number_format($thisMonth, 2) }}</h3>
                <div class="mt-4 flex items-center text-xs text-blue-600 font-medium">
                    <p class="text-sm font-bold text-blue-600">{{ date('F') }} Collection</p>
                </div>
            </div>

            {{-- Growth --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="bi bi-graph-up-arrow text-6xl text-purple-500"></i>
                </div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">MoM Growth</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-2xl font-bold text-gray-900">{{ number_format(abs($growth), 1) }}%</h3>
                    <span class="mb-1 text-sm font-bold {{ $direction === 'up' ? 'text-emerald-500' : ($direction === 'down' ? 'text-red-500' : 'text-gray-500') }}">
                        @if($direction !== 'neutral')
                            <i class="bi bi-arrow-{{ $direction }}"></i>
                        @else
                            -
                        @endif
                    </span>
                </div>
                <div class="mt-4 flex items-center text-xs text-purple-700 font-bold">
                    <p class="text-sm font-bold text-purple-600">vs Last Month</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden relative">

    {{-- ========================================= --}}
    {{-- TOOLBAR (Sticky, Filters, Actions)        --}}
    {{-- ========================================= --}}
    <div class="px-6 py-5 border-b border-gray-100 flex flex-wrap items-center justify-between gap-4 bg-white z-30 relative shadow-[0_4px_20px_-10px_rgba(0,0,0,0.05)]">
        
        {{-- LEFT: Bulk Actions & Search --}}
        <div class="flex items-center gap-4 flex-1">
            
            {{-- Bulk Actions --}}
            <div class="flex items-center gap-2">
                <div class="relative">
                    <select id="bulkActionSelect" onchange="handleBulkActionChange(this)" 
                        class="appearance-none pl-4 pr-10 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all cursor-pointer hover:bg-white hover:shadow-sm">
                        <option value="">Select Action</option>
                        <option value="approve">Approve Selected</option>
                        <option value="reject">Reject Selected</option>
                        <option value="export">Export Selected</option>
                    </select>
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                        <i class="bi bi-chevron-down text-xs"></i>
                    </div>
                </div>

                {{-- Apply Button (Hidden by default) --}}
                <button id="applyBulkActionBtn" onclick="submitBulkAction()" class="hidden px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-black transition-all shadow-sm flex items-center gap-2">
                    <span>Apply</span>
                    <i class="bi bi-arrow-right text-xs"></i>
                </button>
            </div>

            {{-- Hidden Form for Bulk Action --}}
            <form id="bulkActionForm" action="{{ route('admin.payments.bulkAction') }}" method="POST" class="hidden">
                @csrf
                <input type="hidden" name="action" id="bulkActionInput">
                <div id="bulkActionIds"></div>
            </form>

            {{-- Search --}}
            <form method="GET" action="{{ route('admin.payments.index') }}" class="relative w-full max-w-xs group">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-hover:text-blue-500 transition-colors"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Search payments..." 
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all placeholder-gray-400">
                
                {{-- Preserve other filters --}}
                @foreach(request()->except(['search', 'page']) as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
            </form>

        </div>

        {{-- RIGHT: Icon Filters & View Toggle --}}
        <div class="flex items-center gap-2">

            {{-- Filter Group --}}
            <div class="flex items-center gap-2 mr-4 border-r border-gray-100 pr-4">
                
                {{-- Status Filter --}}
                <div class="relative group">
                    <button onclick="toggleDropdown('statusDropdown')" class="w-10 h-10 flex items-center justify-center rounded-xl border border-gray-200 text-gray-600 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all relative">
                        <i class="bi bi-funnel-fill"></i>
                        @if(request('status'))
                            <span class="absolute top-2 right-2 w-2 h-2 bg-blue-500 rounded-full border border-white"></span>
                        @endif
                    </button>
                    
                    <div class="absolute top-full left-1/2 -translate-x-1/2 mt-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-50 shadow-lg">
                        Filter by Status
                    </div>

                    <div id="statusDropdown" class="hidden absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-50 py-2 transform origin-top-right transition-all">
                        <div class="px-4 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Status</div>
                        <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">All Statuses</a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">Pending</a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'approved']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">Approved</a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'rejected']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">Rejected</a>
                    </div>
                </div>

                {{-- Method Filter --}}
                <div class="relative group">
                    <button onclick="toggleDropdown('methodDropdown')" class="w-10 h-10 flex items-center justify-center rounded-xl border border-gray-200 text-gray-600 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all relative">
                        <i class="bi bi-credit-card"></i>
                        @if(request('method'))
                            <span class="absolute top-2 right-2 w-2 h-2 bg-blue-500 rounded-full border border-white"></span>
                        @endif
                    </button>
                    
                    <div class="absolute top-full left-1/2 -translate-x-1/2 mt-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-50 shadow-lg">
                        Filter by Method
                    </div>

                    <div id="methodDropdown" class="hidden absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-50 py-2 transform origin-top-right transition-all">
                        <div class="px-4 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Method</div>
                        <a href="{{ request()->fullUrlWithQuery(['method' => null]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">All Methods</a>
                        @foreach($paymentMethods as $method)
                            <a href="{{ request()->fullUrlWithQuery(['method' => $method]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 capitalize">{{ $method }}</a>
                        @endforeach
                    </div>
                </div>

                {{-- Date Filter --}}
                <div class="relative group">
                    <button onclick="toggleDropdown('dateDropdown')" class="w-10 h-10 flex items-center justify-center rounded-xl border border-gray-200 text-gray-600 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all relative">
                        <i class="bi bi-calendar3"></i>
                        @if(request('date_filter'))
                            <span class="absolute top-2 right-2 w-2 h-2 bg-blue-500 rounded-full border border-white"></span>
                        @endif
                    </button>
                    
                    <div class="absolute top-full left-1/2 -translate-x-1/2 mt-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-50 shadow-lg">
                        Filter by Date
                    </div>

                    <div id="dateDropdown" class="hidden absolute right-0 top-full mt-2 w-64 bg-white rounded-xl shadow-xl border border-gray-100 z-50 py-2 transform origin-top-right transition-all">
                        <div class="px-4 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Date Period</div>
                        <a href="{{ request()->fullUrlWithQuery(['date_filter' => null]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">All Time</a>
                        <a href="{{ request()->fullUrlWithQuery(['date_filter' => 'today']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">Today</a>
                        <a href="{{ request()->fullUrlWithQuery(['date_filter' => 'week']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">This Week</a>
                        <a href="{{ request()->fullUrlWithQuery(['date_filter' => 'month']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">This Month</a>
                        
                        <div class="border-t border-gray-100 mt-2 pt-2 px-4 pb-2">
                            <form action="{{ route('admin.payments.index') }}" method="GET" class="space-y-2">
                                <input type="hidden" name="date_filter" value="custom">
                                @foreach(request()->except(['date_filter', 'start_date', 'end_date', 'page']) as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach
                                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-2 py-1 text-xs border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500">
                                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-2 py-1 text-xs border border-gray-200 rounded-lg focus:ring-1 focus:ring-blue-500">
                                <button type="submit" class="w-full py-1 bg-blue-50 text-blue-600 text-xs font-bold rounded-lg hover:bg-blue-100 transition">Apply</button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Sort --}}
                <div class="relative group">
                    <button onclick="toggleDropdown('sortDropdown')" class="w-10 h-10 flex items-center justify-center rounded-xl border border-gray-200 text-gray-600 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all">
                        <i class="bi bi-sort-down"></i>
                    </button>
                    
                    <div class="absolute top-full left-1/2 -translate-x-1/2 mt-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-50 shadow-lg">
                        Sort List
                    </div>

                    <div id="sortDropdown" class="hidden absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-50 py-2">
                        <div class="px-4 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Sort By</div>
                        <a href="{{ request()->fullUrlWithQuery(['sort_option' => 'date_desc']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">Date (Newest)</a>
                        <a href="{{ request()->fullUrlWithQuery(['sort_option' => 'date_asc']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">Date (Oldest)</a>
                        <a href="{{ request()->fullUrlWithQuery(['sort_option' => 'amount_desc']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">Amount (High-Low)</a>
                        <a href="{{ request()->fullUrlWithQuery(['sort_option' => 'amount_asc']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">Amount (Low-High)</a>
                    </div>
                </div>

                {{-- Clear Filters (Icon) --}}
                @if(request()->anyFilled(['search', 'status', 'method', 'date_filter', 'sort_option']))
                    <a href="{{ route('admin.payments.index') }}" class="w-10 h-10 flex items-center justify-center rounded-xl border border-red-100 text-red-500 hover:bg-red-50 hover:border-red-200 transition-all group relative">
                        <i class="bi bi-x-lg"></i>
                        <div class="absolute top-full left-1/2 -translate-x-1/2 mt-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-50 shadow-lg">
                            Clear Filters
                        </div>
                    </a>
                @endif

            </div>

            {{-- VIEW TOGGLE --}}
            <div class="flex items-center gap-1 bg-gray-100 p-1 rounded-xl">
                <button onclick="toggleView('list')" id="listViewBtn" class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-500 hover:bg-white hover:shadow-sm transition-all">
                    <i class="bi bi-list-ul text-lg"></i>
                </button>
                <button onclick="toggleView('grid')" id="gridViewBtn" class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-500 hover:bg-white hover:shadow-sm transition-all">
                    <i class="bi bi-grid-fill text-lg"></i>
                </button>
            </div>

            {{-- Add Button --}}
            <a href="{{ route('admin.payments.create') }}" class="ml-2 flex items-center justify-center w-10 h-10 bg-gray-900 text-white rounded-xl hover:bg-black transition shadow-lg hover:-translate-y-0.5 transform">
                <i class="bi bi-plus-lg"></i>
            </a>
        </div>
    </div>

    {{-- ========================================= --}}
    {{-- CONTENT AREA (List & Grid)        --}}
    {{-- ========================================= --}}
    <div class="flex-1 overflow-y-auto bg-white custom-scrollbar relative">
        
        @if($payments->count() > 0)
            
            {{-- LIST VIEW --}}
            <div id="listView" class="block w-full pb-20">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50/90 backdrop-blur-sm sticky top-0 z-20 border-b border-gray-100">
                        <tr>
                            <th class="p-4 w-12 text-center bulk-checkbox hidden">
                                <input type="checkbox" onchange="toggleAllCheckboxes(this)" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Resident</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Reference No</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Amount</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Method</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Paid Date</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($payments as $payment)
                        <tr onclick="selectPayment({{ $payment->id }})" 
                            data-id="{{ $payment->id }}"
                            class="payment-row cursor-pointer hover:bg-gray-50 transition-all duration-200 group border-l-4 border-transparent">
                            
                            {{-- Checkbox --}}
                            <td onclick="event.stopPropagation()" class="p-4 text-center bulk-checkbox hidden">
                                <input type="checkbox" name="selected_payments[]" value="{{ $payment->id }}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 payment-checkbox">
                            </td>

                            {{-- Resident --}}
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <img 
                                        src="{{ $payment->resident->photo ? asset('storage/' . $payment->resident->photo) : asset('CDlogo.jpg') }}"
                                        onerror="this.onerror=null; this.src='{{ asset('CDlogo.jpg') }}';"
                                        class="w-8 h-8 rounded-full object-cover ring-2 ring-gray-100 group-hover:ring-blue-200 transition-all duration-300"
                                        alt="{{ $payment->resident->first_name ?? 'Resident' }}">
                                    <div>
                                        <p class="font-bold text-gray-900 group-hover:text-blue-700 transition">{{ $payment->resident->first_name ?? 'Unknown' }} {{ $payment->resident->last_name ?? 'Resident' }}</p>
                                        <p class="text-xs text-gray-500">Blk {{ $payment->resident->block ?? '-' }} - Lot {{ $payment->resident->lot ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Reference No --}}
                            <td class="p-4 text-sm text-gray-600 font-medium align-middle">#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>

                            {{-- Amount --}}
                            <td class="p-4 text-right text-sm text-gray-600 font-medium align-middle">
                                ₱{{ number_format($payment->amount, 2) }}
                            </td>

                            {{-- Method --}}
                            <td class="p-4 text-center text-sm text-gray-600 font-medium capitalize align-middle">
                                {{ $payment->payment_method }}
                            </td>

                            {{-- Paid Date --}}
                            <td class="p-4 text-right align-middle">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-gray-900">{{ \Carbon\Carbon::parse($payment->date_paid)->format('M d, Y') }}</span>
                                    <span class="text-[10px] text-gray-500 font-medium mt-0.5">{{ \Carbon\Carbon::parse($payment->date_paid)->format('g:i A') }}</span>
                                </div>
                            </td>

                            {{-- Status --}}
                            <td class="p-4 text-center align-middle">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold border capitalize tracking-wide
                                    {{ $payment->status === 'approved' 
                                        ? 'bg-emerald-50 text-emerald-700 border-emerald-100' 
                                        : ($payment->status === 'rejected' ? 'bg-red-50 text-red-700 border-red-100' : 'bg-yellow-50 text-yellow-700 border-yellow-100') }}">
                                    <span class="w-1.5 h-1.5 rounded-full 
                                        {{ $payment->status === 'approved' ? 'bg-emerald-500' : ($payment->status === 'rejected' ? 'bg-red-500' : 'bg-yellow-500') }}"></span>
                                    {{ $payment->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- GRID VIEW --}}
            <div id="gridView" class="hidden p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 pb-20">
                @foreach($payments as $payment)
                <div onclick="selectPayment({{ $payment->id }})"
                    data-id="{{ $payment->id }}"
                    class="payment-card bg-white rounded-xl p-5 border border-gray-200 hover:shadow-md transition-all duration-200 cursor-pointer relative group">
                    
                    {{-- Checkbox --}}
                    <div onclick="event.stopPropagation()" class="absolute top-4 left-4 z-10 bulk-checkbox hidden">
                        <input type="checkbox" name="selected_payments[]" value="{{ $payment->id }}" class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 payment-checkbox">
                    </div>

                    {{-- Status Pill (Upper Right) --}}
                    <span class="absolute top-4 right-4 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider border
                        {{ $payment->status === 'approved' 
                            ? 'bg-emerald-50 text-emerald-600 border-emerald-100' 
                            : ($payment->status === 'rejected' ? 'bg-red-50 text-red-600 border-red-100' : 'bg-yellow-50 text-yellow-600 border-yellow-100') }}">
                        {{ $payment->status }}
                    </span>

                    <div class="flex flex-col items-center text-center mt-2">
                        <img 
                            src="{{ $payment->resident->photo ? asset('storage/' . $payment->resident->photo) : asset('CDlogo.jpg') }}"
                            onerror="this.onerror=null; this.src='{{ asset('CDlogo.jpg') }}';"
                            class="w-16 h-16 rounded-full object-cover mb-3 ring-2 ring-gray-100 group-hover:ring-blue-50 transition-all duration-300"
                            alt="{{ $payment->resident->first_name ?? 'Resident' }}">
                        
                        <h3 class="text-base font-bold text-gray-900 group-hover:text-blue-700 transition leading-tight mb-1">
                            {{ $payment->resident->first_name ?? 'Unknown' }} {{ $payment->resident->last_name ?? 'Resident' }}
                        </h3>
                        <p class="text-lg font-bold text-gray-900 mb-4">₱{{ number_format($payment->amount, 2) }}</p>

                        {{-- Footer: Details --}}
                        <div class="w-full border-t border-gray-50 pt-3 flex flex-col gap-1 text-xs text-gray-600">
                            <div class="flex justify-between w-full">
                                <span class="text-gray-400">Method</span>
                                <span class="font-medium capitalize">{{ $payment->payment_method }}</span>
                            </div>
                            <div class="flex justify-between w-full">
                                <span class="text-gray-400">Date</span>
                                <span class="font-medium text-right">
                                    {{ \Carbon\Carbon::parse($payment->date_paid)->format('M d, Y') }}<br>
                                    <span class="text-[10px] text-gray-400 font-normal">{{ \Carbon\Carbon::parse($payment->date_paid)->format('g:i A') }}</span>
                                </span>
                            </div>
                            <div class="flex justify-between w-full">
                                <span class="text-gray-400">Ref No.</span>
                                <span class="font-medium text-gray-500">#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        @else
            <div class="flex flex-col items-center justify-center h-full text-center pb-20">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <i class="bi bi-receipt text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">No Payments Found</h3>
                <p class="text-gray-500 max-w-xs mx-auto mt-2">Try adjusting your filters to find what you're looking for.</p>
                <a href="{{ route('admin.payments.index') }}" class="mt-6 px-6 py-2 bg-gray-900 text-white rounded-lg hover:bg-black transition text-sm font-medium">Clear Filters</a>
            </div>
        @endif
    </div>

</div>

{{-- ========================================= --}}
{{-- JAVASCRIPT LOGIC                          --}}
{{-- ========================================= --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize View State
        const savedView = localStorage.getItem('payments_view_mode') || 'list';
        toggleView(savedView);

        // Check for active ID in URL
        const urlParams = new URLSearchParams(window.location.search);
        const activeId = urlParams.get('active_id');
        if (activeId) selectPayment(activeId, false);

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.group')) {
                closeAllDropdowns();
            }
        });
    });

    // ---------------------------------------------------------
    // 1. FILTER DROPDOWNS
    // ---------------------------------------------------------
    function toggleDropdown(id) {
        const dropdown = document.getElementById(id);
        const isHidden = dropdown.classList.contains('hidden');
        
        closeAllDropdowns();

        if (isHidden) {
            dropdown.classList.remove('hidden');
            // Focus search input if it exists
            const searchInput = dropdown.querySelector('input[type="text"]');
            if (searchInput) {
                setTimeout(() => searchInput.focus(), 100);
            }
        }
    }

    function closeAllDropdowns() {
        document.querySelectorAll('[id$="Dropdown"]').forEach(el => el.classList.add('hidden'));
    }

    function filterResidents() {
        const input = document.getElementById('residentSearch');
        const filter = input.value.toLowerCase();
        const list = document.getElementById('residentList');
        const items = list.getElementsByClassName('resident-item');

        for (let i = 0; i < items.length; i++) {
            const name = items[i].getAttribute('data-name');
            if (name.includes(filter)) {
                items[i].style.display = "";
            } else {
                items[i].style.display = "none";
            }
        }
    }

    // ---------------------------------------------------------
    // 2. BULK ACTIONS
    // ---------------------------------------------------------
    function handleBulkActionChange(select) {
        const checkboxes = document.querySelectorAll('.bulk-checkbox');
        const applyBtn = document.getElementById('applyBulkActionBtn');
        
        if (select.value !== "") {
            // Show checkboxes & Apply button
            checkboxes.forEach(el => el.classList.remove('hidden'));
            applyBtn.classList.remove('hidden');
        } else {
            // Hide checkboxes & Apply button
            checkboxes.forEach(el => el.classList.add('hidden'));
            applyBtn.classList.add('hidden');
            // Uncheck all
            document.querySelectorAll('.payment-checkbox').forEach(cb => cb.checked = false);
        }
    }

    function toggleAllCheckboxes(source) {
        document.querySelectorAll('.payment-checkbox').forEach(cb => cb.checked = source.checked);
    }

    function submitBulkAction() {
        const select = document.getElementById('bulkActionSelect');
        const action = select.value;
        const selectedCheckboxes = Array.from(document.querySelectorAll('.payment-checkbox:checked'));
        const selectedIds = selectedCheckboxes.map(cb => cb.value);

        if (selectedIds.length === 0) {
            alert('Please select at least one payment.');
            return;
        }

        let confirmMsg = '';
        if (action === 'approve') confirmMsg = `Are you sure you want to APPROVE ${selectedIds.length} payments?`;
        else if (action === 'reject') confirmMsg = `Are you sure you want to REJECT ${selectedIds.length} payments?`;
        else if (action === 'export') confirmMsg = `Export ${selectedIds.length} selected payments?`;
        else {
            alert('Please select a valid action.');
            return;
        }

        if (confirm(confirmMsg)) {
            const form = document.getElementById('bulkActionForm');
            const idsContainer = document.getElementById('bulkActionIds');
            const actionInput = document.getElementById('bulkActionInput');
            
            idsContainer.innerHTML = '';
            actionInput.value = action;
            
            selectedIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                idsContainer.appendChild(input);
            });
            
            form.submit();
        }
    }

    // ---------------------------------------------------------
    // 3. STATE MANAGEMENT
    // ---------------------------------------------------------
    let activePaymentId = null;

    window.toggleView = function(viewMode) {
        const listBtn = document.getElementById('listViewBtn');
        const gridBtn = document.getElementById('gridViewBtn');
        const listView = document.getElementById('listView');
        const gridView = document.getElementById('gridView');

        if (viewMode === 'grid') {
            listView.classList.add('hidden');
            gridView.classList.remove('hidden');
            listBtn.classList.remove('bg-white', 'shadow-sm', 'text-blue-600');
            gridBtn.classList.add('bg-white', 'shadow-sm', 'text-blue-600');
        } else {
            gridView.classList.add('hidden');
            listView.classList.remove('hidden');
            gridBtn.classList.remove('bg-white', 'shadow-sm', 'text-blue-600');
            listBtn.classList.add('bg-white', 'shadow-sm', 'text-blue-600');
        }
        localStorage.setItem('payments_view_mode', viewMode);
    }

    window.selectPayment = function(id, pushState = true) {
        // Prevent selection if clicking checkbox
        if (event.target.closest('.bulk-checkbox') || event.target.tagName === 'INPUT') return;

        activePaymentId = id;
        highlightActiveRow();

        if (pushState) {
            const url = new URL(window.location);
            url.searchParams.set('active_id', id);
            window.history.pushState({}, '', url);
        }
        loadPaymentDetails(id);
    }

    window.highlightActiveRow = function() {
        // Clear all highlights
        document.querySelectorAll('.payment-row, .payment-card').forEach(el => {
            if (el.classList.contains('payment-row')) {
                el.classList.remove('bg-blue-50', 'border-blue-600');
                el.classList.add('border-transparent');
            }
            if (el.classList.contains('payment-card')) {
                el.classList.remove('ring-2', 'ring-blue-600', 'bg-blue-50');
            }
        });

        if (!activePaymentId) return;

        // Apply Highlight
        document.querySelectorAll(`[data-id="${activePaymentId}"]`).forEach(el => {
            if (el.classList.contains('payment-row')) {
                el.classList.add('bg-blue-50', 'border-blue-600');
                el.classList.remove('border-transparent');
            }
            if (el.classList.contains('payment-card')) {
                el.classList.add('ring-2', 'ring-blue-600', 'bg-blue-50');
            }
        });
    }

    // ---------------------------------------------------------
    // 4. DRAWER LOGIC
    // ---------------------------------------------------------
    window.loadPaymentDetails = function(id) {
        const url = `{{ route('admin.payments.data', ':id') }}`.replace(':id', id);
        UniversalDrawer.open('payment', url);
    }
    
    // Alias for the close button inside the partial
    // Removed to align with Resident module pattern

</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 3px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
</style>
@endsection
