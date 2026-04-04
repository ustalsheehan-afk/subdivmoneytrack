@extends('resident.layouts.app')

@section('title', 'Payments & Dues')
@section('page-title', 'Payments & Dues ')

@section('content')
<div class="space-y-8" x-data="{ activeTab: 'history', statusFilter: 'all' }">
    
    {{-- HERO SECTION --}}
    <x-resident-hero-header 
        label="Financial Center" 
        icon="bi-wallet2"
        title="Payments & Dues" 
        description="Manage your monthly dues, track payment history, and view any outstanding penalties."
    />

    {{-- TAB NAVIGATION --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex gap-1 border-b border-gray-200 pb-4">
            <button @click="activeTab = 'history'"
                    :class="activeTab === 'history' 
                        ? 'border-b-2 border-blue-600 text-blue-600' 
                        : 'text-gray-500 hover:text-gray-700'"
                    class="flex items-center gap-2 pb-2 px-4 font-semibold text-sm md:text-base transition-colors whitespace-nowrap">
                <i class="bi bi-receipt"></i>
                Payment History
            </button>
            <button @click="activeTab = 'penalties'"
                    :class="activeTab === 'penalties' 
                        ? 'border-b-2 border-blue-600 text-blue-600' 
                        : 'text-gray-500 hover:text-gray-700'"
                    class="flex items-center gap-2 pb-2 px-4 font-semibold text-sm md:text-base transition-colors whitespace-nowrap">
                <i class="bi bi-shield-exclamation"></i>
                Penalties
            </button>
        </div>
    </div>

    {{-- SUMMARY CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        {{-- OUTSTANDING DUES --}}
        <div @click="activeTab = 'history'; statusFilter = 'unpaid'"
            class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm hover:shadow-md transition-all cursor-pointer group relative overflow-hidden">
            
            <div class="flex justify-between items-start mb-2">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                    Outstanding Dues
                </p>
                <div class="w-7 h-7 rounded-full bg-orange-50 flex items-center justify-center text-orange-500 group-hover:scale-110 transition-transform">
                    <i class="bi bi-clock text-sm"></i>
                </div>
            </div>

            <h3 class="text-2xl font-black text-gray-900 tracking-tight mb-2">
                ₱{{ number_format($summary['outstanding_dues'] ?? 0, 2) }}
            </h3>

            <div class="flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                <span class="text-xs font-bold text-orange-600">Due Soon</span>
            </div>
        </div>

        {{-- TOTAL PAID --}}
        @php
            $currentYear = date('Y');
            $yearTotalPaid = $dues->filter(function($d) use ($currentYear) {
                return \Carbon\Carbon::parse($d->due_date)->year == $currentYear;
            })->sum('collected');
        @endphp

        <div @click="activeTab = 'history'; statusFilter = 'paid'"
            class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm hover:shadow-md transition-all cursor-pointer group relative overflow-hidden">
            
            <div class="flex justify-between items-start mb-2">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                    Total Paid ({{ $currentYear }})
                </p>
                <div class="w-7 h-7 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500 group-hover:scale-110 transition-transform">
                    <i class="bi bi-wallet2 text-sm"></i>
                </div>
            </div>

            <h3 class="text-2xl font-black text-gray-900 tracking-tight mb-2">
                ₱{{ number_format($yearTotalPaid, 2) }}
            </h3>

            <div class="flex items-center gap-1.5">
                <div class="w-4 h-4 rounded-full bg-emerald-100 flex items-center justify-center">
                    <i class="bi bi-check text-emerald-600 text-[10px]"></i>
                </div>
                <span class="text-xs font-bold text-emerald-600">Verified Payments</span>
            </div>
        </div>

        {{-- UNPAID PENALTIES --}}
        <div @click="activeTab = 'penalties'"
            class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm hover:shadow-md transition-all cursor-pointer group relative overflow-hidden">
            
            <div class="flex justify-between items-start mb-2">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                    Unpaid Penalties
                </p>
                <div class="w-7 h-7 rounded-full bg-red-50 flex items-center justify-center text-red-500 group-hover:scale-110 transition-transform">
                    <i class="bi bi-exclamation-triangle text-sm"></i>
                </div>
            </div>

            <h3 class="text-2xl font-black text-gray-900 tracking-tight mb-2">
                ₱{{ number_format($summary['total_penalties'] ?? 0, 2) }}
            </h3>

            <div class="flex items-center gap-1.5">
                <div class="w-4 h-4 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="bi bi-exclamation text-red-600 text-[10px] font-bold"></i>
                </div>
                <span class="text-xs font-bold text-red-600">Action Needed</span>
            </div>
        </div>

    </div>

    {{-- TAB CONTENT: PAYMENT HISTORY --}}
    <div x-show="activeTab === 'history'" x-transition.opacity>
        
        @forelse($duesGrouped as $month => $monthDues)
            @php
                $monthTotal = $monthDues->sum('amount');
                $monthPaid = $monthDues->sum('collected');
                $monthProgress = $monthTotal > 0 ? ($monthPaid / $monthTotal) * 100 : 0;
            @endphp

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm mb-8 overflow-hidden last:mb-0">
                {{-- MONTH SUMMARY HEADER --}}
                <div class="p-6 bg-white pb-2">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-900 tracking-tight">{{ $month }}</h2>
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                            TOTAL: <span class="text-gray-900">₱{{ number_format($monthTotal, 2) }}</span> 
                            <span class="mx-1">•</span>
                            PAID: <span class="text-emerald-600">₱{{ number_format($monthPaid, 2) }}</span>
                        </div>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden mb-6">
                        <div class="h-full bg-blue-600 rounded-full" style="width: {{ $monthProgress }}%"></div>
                    </div>
                </div>

                {{-- DESKTOP TABLE --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left border-collapse table-fixed">
                        <thead class="bg-gray-50/50 border-t border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider w-32">Due Date</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider w-48">Due</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider w-32">Type</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider w-32">Amount</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider w-32 text-center">Status</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider w-32 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($monthDues as $due)
                            @php
                                $displayStatus = strtolower($due->display_status ?? $due->status);
                                $isPaid = $displayStatus === 'paid';
                                $isPending = $displayStatus === 'pending';

                                $statusLabel = match($displayStatus) {
                                    'paid' => 'Paid',
                                    'pending' => 'Payment Pending',
                                    default => 'Unpaid',
                                };

                                $statusBadgeClass = match($displayStatus) {
                                    'paid' => 'bg-emerald-50 text-emerald-700',
                                    'pending' => 'bg-orange-50 text-orange-700',
                                    default => 'bg-red-50 text-red-700',
                                };

                                $statusDotClass = match($displayStatus) {
                                    'paid' => 'bg-emerald-500',
                                    'pending' => 'bg-orange-500',
                                    default => 'bg-red-500',
                                };
                                
                                $dueType = $due->type ? ucwords(str_replace('_', ' ', $due->type)) : '-';
                                $dueYear = \Carbon\Carbon::parse($due->due_date)->year;
                                $isOverdue = $displayStatus === 'unpaid' && \Carbon\Carbon::parse($due->due_date)->isPast();
                            @endphp
                            <tr class="group even:bg-gray-50 hover:bg-gray-100 transition-colors" 
                                data-status="{{ $displayStatus }}"
                                data-overdue="{{ $isOverdue ? 'true' : 'false' }}"
                                x-show="statusFilter === 'all' || statusFilter === $el.dataset.status">
                                
                                <td class="px-6 py-4 text-xs font-bold text-gray-900">
                                    {{ \Carbon\Carbon::parse($due->due_date)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $due->title }}</p>
                                </td>
                                <td class="px-6 py-4 text-xs font-medium text-gray-600">
                                    {{ $dueType }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-gray-900">₱{{ number_format($due->amount, 2) }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold {{ $statusBadgeClass }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $statusDotClass }}"></span>
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($displayStatus === 'unpaid')
                                        <a href="{{ route('resident.payments.pay', $due->id) }}" 
                                           class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">
                                            Pay Now
                                        </a>
                                    @elseif($isPending)
                                        <button disabled class="inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-400 text-xs font-bold rounded-lg cursor-not-allowed border border-gray-200">
                                            Processing
                                        </button>
                                    @else
                                        <div class="flex items-center justify-end gap-1 text-emerald-600">
                                            <i class="bi bi-check-lg text-lg"></i>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- MOBILE LIST --}}
                <div class="md:hidden flex flex-col divide-y divide-gray-100">
                    @foreach($monthDues as $due)
                    @php
                        $displayStatus = strtolower($due->display_status ?? $due->status);
                        $isPaid = $displayStatus === 'paid';
                        $isPending = $displayStatus === 'pending';

                        $statusLabel = match($displayStatus) {
                            'paid' => 'Paid',
                            'pending' => 'Payment Pending',
                            default => 'Unpaid',
                        };

                        $statusBadgeClass = match($displayStatus) {
                            'paid' => 'bg-emerald-50 text-emerald-700',
                            'pending' => 'bg-orange-50 text-orange-700',
                            default => 'bg-red-50 text-red-700',
                        };

                        $statusDotClass = match($displayStatus) {
                            'paid' => 'bg-emerald-500',
                            'pending' => 'bg-orange-500',
                            default => 'bg-red-500',
                        };
                        
                        $dueType = $due->type ? ucwords(str_replace('_', ' ', $due->type)) : '-';
                        $dueYear = \Carbon\Carbon::parse($due->due_date)->year;
                        $isOverdue = $displayStatus === 'unpaid' && \Carbon\Carbon::parse($due->due_date)->isPast();
                    @endphp
                    <div class="p-5 bg-white active:bg-gray-50 transition-colors"
                         data-status="{{ $displayStatus }}"
                         data-overdue="{{ $isOverdue ? 'true' : 'false' }}"
                         x-show="statusFilter === 'all' || statusFilter === $el.dataset.status">
                        
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 block">
                                    {{ \Carbon\Carbon::parse($due->due_date)->format('M d, Y') }}
                                </span>
                                <h4 class="text-sm font-bold text-gray-900 leading-tight mb-1">
                                    {{ $due->title }}
                                </h4>
                                <p class="text-xs text-gray-500">{{ $dueType }}</p>
                            </div>
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-[10px] font-bold {{ $statusBadgeClass }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $statusDotClass }}"></span>
                                {{ $statusLabel }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-50">
                            <p class="text-sm font-bold text-gray-900 font-mono">₱{{ number_format($due->amount, 2) }}</p>
                            <div>
                                @if($displayStatus === 'unpaid')
                                    <a href="{{ route('resident.payments.pay', $due->id) }}" 
                                       class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-bold rounded-lg transition-colors shadow-sm">
                                        Pay Now
                                    </a>
                                @elseif($isPending)
                                    <button disabled class="inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-400 text-[10px] font-bold rounded-lg cursor-not-allowed border border-gray-200">
                                        Processing
                                    </button>
                                @else
                                    <div class="flex items-center justify-end gap-1 text-emerald-600">
                                        <i class="bi bi-check-lg"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-12 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="bi bi-receipt text-3xl text-gray-300"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">No payments found</h3>
                <p class="text-sm text-gray-500">You don't have any dues or payment history yet.</p>
            </div>
        @endforelse
    </div>

    {{-- TAB CONTENT: PENALTIES --}}
    <div x-show="activeTab === 'penalties'" x-cloak>
        @if($penalties->isNotEmpty())
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                {{-- DESKTOP PENALTY TABLE --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left border-collapse table-fixed">
                        <thead class="bg-gray-50/50 border-t border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider w-32">Date Issued</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider w-32">Type</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider w-64">Reason</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider w-32">Amount</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider w-32 text-center">Status</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-wider w-32 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($penalties as $penalty)
                            @php
                                $pStatusConfig = match($penalty->status) {
                                    'paid' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'dot' => 'bg-emerald-500', 'label' => 'Paid'],
                                    default => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'dot' => 'bg-red-500', 'label' => 'Unpaid']
                                };
                            @endphp
                            <tr class="group even:bg-gray-50 hover:bg-gray-100 transition-colors">
                                <td class="px-6 py-4 text-xs font-bold text-gray-900">
                                    {{ \Carbon\Carbon::parse($penalty->date_issued)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-600">
                                    {{ ucfirst(str_replace('_', ' ', $penalty->type)) }}
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-700 truncate" title="{{ $penalty->reason }}">{{ $penalty->reason }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-gray-900">₱{{ number_format($penalty->amount, 2) }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold {{ $pStatusConfig['bg'] }} {{ $pStatusConfig['text'] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $pStatusConfig['dot'] }}"></span>
                                        {{ $pStatusConfig['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($penalty->status !== 'paid')
                                        <a href="{{ route('resident.penalties.show', $penalty->id) }}" 
                                           class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">
                                            Pay Now
                                        </a>
                                    @else
                                        <div class="flex items-center justify-end gap-1 text-emerald-600">
                                            <i class="bi bi-check-lg text-lg"></i>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- MOBILE PENALTY LIST --}}
                <div class="md:hidden flex flex-col divide-y divide-gray-100">
                    @foreach($penalties as $penalty)
                    <div class="p-5 bg-white active:bg-gray-50 transition-colors">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 block">
                                    {{ \Carbon\Carbon::parse($penalty->date_issued)->format('M d, Y') }}
                                </span>
                                <h4 class="text-sm font-bold text-gray-900 leading-tight mb-1">
                                    {{ ucfirst(str_replace('_', ' ', $penalty->type)) }}
                                </h4>
                                <p class="text-xs text-gray-500">{{ $penalty->reason }}</p>
                            </div>
                            @php
                                $pStatusConfig = match($penalty->status) {
                                    'paid' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'label' => 'Paid'],
                                    default => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'label' => 'Unpaid']
                                };
                            @endphp
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-bold {{ $pStatusConfig['bg'] }} {{ $pStatusConfig['text'] }}">
                                {{ $pStatusConfig['label'] }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-50">
                            <p class="text-sm font-bold text-gray-900 font-mono">₱{{ number_format($penalty->amount, 2) }}</p>
                            <div>
                                @if($penalty->status !== 'paid')
                                    <a href="{{ route('resident.penalties.show', $penalty->id) }}" 
                                       class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-bold rounded-lg transition-colors shadow-sm">
                                        Pay Now
                                    </a>
                                @else
                                    <div class="flex items-center justify-end gap-1 text-emerald-600">
                                        <i class="bi bi-check-lg"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-12 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="bi bi-check-circle text-3xl text-gray-300"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">No Penalties</h3>
                <p class="text-sm text-gray-500">You don't have any penalties. Great job staying on top of payments!</p>
            </div>
        @endif
    </div>

</div>
@endsection
