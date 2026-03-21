@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-8">

    <!-- ===================== -->
    <!-- HEADER -->
    <!-- ===================== -->
    <div class="flex flex-col gap-4 rounded-3xl border border-blue-100 bg-gradient-to-r from-blue-50/60 to-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between sm:p-6">
    
    <div class="min-w-0">
        <h1 class="mt-1 text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 flex items-center gap-2">
            Good Day, Admin
        </h1>

        <p class="mt-2 text-sm sm:text-base text-gray-600">
            Here's what's happening in your subdivision today.
        </p>
    </div>

    <div class="self-start sm:self-auto">
        <div class="inline-flex items-center gap-2 rounded-2xl bg-blue-50 px-4 py-2.5 border border-blue-100">
            <i class="bi bi-calendar3 text-sm text-blue-500"></i>
            <p class="text-sm sm:text-base font-semibold text-gray-700">
                {{ now()->format('l, F j, Y') }}
            </p>
        </div>
    </div>

</div>
    <!-- ===================== -->
    <!-- STATS OVERVIEW -->
    <!-- ===================== -->
 <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    @php
        $stats = [
            ['label' => 'Total Residents', 'value' => $summaryData['totalResidents'], 'icon' => 'bi-people', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50', 'link' => route('admin.residents.index')],
            ['label' => 'Dues Collected', 'value' => '₱ ' . number_format($summaryData['totalDuesCollected'], 2), 'icon' => 'bi-wallet2', 'color' => 'text-emerald-600', 'bg' => 'bg-emerald-50', 'link' => route('admin.dues.index')],
            ['label' => 'Pending Payments', 'value' => $summaryData['pendingPayments'], 'icon' => 'bi-hourglass-split', 'color' => 'text-amber-600', 'bg' => 'bg-amber-50', 'link' => route('admin.payments.index')],
            ['label' => 'Total Penalties', 'value' => '₱ ' . number_format($summaryData['totalPenalties'], 2), 'icon' => 'bi-exclamation-triangle', 'color' => 'text-red-600', 'bg' => 'bg-red-50', 'link' => route('admin.penalties.index')],
        ];
    @endphp

    @foreach($stats as $stat)
    <a href="{{ $stat['link'] }}"
       class="group relative bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden">

        <!-- subtle accent line -->
<span class="absolute top-0 left-0 w-full h-[3px] {{ $stat['bg'] }}"></span>

<div class="flex justify-between items-start">
    <div>
        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">
            {{ $stat['label'] }}
        </p>

        <h3 class="text-2xl font-bold text-gray-900 mt-2">
            {{ $stat['value'] }}
        </h3>
    </div>

    <div class="w-11 h-11 rounded-xl {{ $stat['bg'] }} {{ $stat['color'] }} flex items-center justify-center text-lg shadow-sm group-hover:scale-105 transition">
        <i class="bi {{ $stat['icon'] }}"></i>
    </div>
</div>

</a>
@endforeach
</div>

    <!-- ===================== -->
    <!-- MAIN CONTENT GRID -->
    <!-- ===================== -->
    <div class="grid grid-cols-1 gap-8">

<!-- ROW 2: Financials & Schedule -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">

 
<!-- Financial Performance -->
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-semibold text-gray-900">Financial Performance</h3>

        <div class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-200 flex items-center justify-center text-gray-500">
            <i class="bi bi-pie-chart"></i>
        </div>
    </div>

    @php
        $collected = $summaryData['totalDuesCollected'] ?? 0;
        $unpaid = $summaryData['unpaidDuesAmount'] ?? 0;
        $totalExpected = $collected + $unpaid;
        $unpaidResidentsCount = $summaryData['unpaidResidentsCount'] ?? 0;
    @endphp

    <!-- Compact Body -->
    <div class="px-6 py-5 flex items-center justify-between gap-6">

        <!-- Summary -->
        <div class="min-w-0 flex-1">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                Collected Amount
            </p>

            <div class="mt-2 flex items-baseline gap-2 flex-wrap">
                <span class="text-2xl font-bold text-gray-900">
                    ₱{{ number_format($collected) }}
                </span>
                <span class="text-sm text-gray-500">
                    out of ₱{{ number_format($totalExpected) }} expected
                </span>
            </div>

            <div class="mt-3">
                @if($unpaidResidentsCount > 0)
                    <span class="inline-flex items-center text-xs font-semibold text-red-600 bg-red-50 border border-red-100 px-2.5 py-1 rounded-full">
                        {{ $unpaidResidentsCount }} unpaid
                    </span>
                @else
                    <span class="inline-flex items-center text-xs font-semibold text-emerald-600 bg-emerald-50 border border-emerald-100 px-2.5 py-1 rounded-full">
                        All paid
                    </span>
                @endif
            </div>
        </div>

        <!-- Chart + Legend -->
        <div class="shrink-0 flex items-center gap-5">
            <!-- Legend -->
            <div class="space-y-2 text-sm text-gray-600">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <span>Collected</span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                    <span>Unpaid</span>
                </div>
            </div>

            <!-- Chart -->
            <div class="w-32 h-32 flex items-center justify-center">
                @if($totalExpected > 0)
                    <canvas id="financialChart"></canvas>
                @else
                    <i class="bi bi-pie-chart text-3xl text-gray-300"></i>
                @endif
            </div>
        </div>

    </div>
</div>
<!-- Upcoming Due Dates -->
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm flex flex-col h-full overflow-hidden">
    
    <!-- Header -->
    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-200">
        <div>
            <h3 class="font-bold text-gray-900 text-lg">Upcoming Due Dates</h3>
            <p class="text-sm text-gray-600 mt-1">Scheduled dues for this month</p>
        </div>

        <span class="text-xs font-semibold text-gray-700 bg-gray-100 px-3 py-1.5 rounded-full border border-gray-200">
            {{ now()->format('M Y') }}
        </span>
    </div>

    <!-- Content -->
    <div class="p-6 flex-1 overflow-y-auto custom-scrollbar">
        <div class="space-y-4">

            @forelse($upcomingDues as $due)
                @php
                    $dueDate = \Carbon\Carbon::parse($due->due_date);
                    $isToday = $dueDate->isToday();
                    $isUrgent = $dueDate->diffInDays(now()) <= 3 && $dueDate->isFuture();
                    $hasPending = $due->count > 0;
                @endphp

                <a href="{{ route('admin.dues.index') }}" class="group rounded-xl border border-gray-200 bg-gray-50 p-4 hover:bg-white hover:shadow-sm transition block">

                    <div class="flex items-center gap-4">

                        <!-- Date Block -->
                        <div class="shrink-0 w-16 rounded-lg border text-center py-3
                            {{ ($isUrgent || $isToday) 
                                ? 'bg-red-100 border-red-200 text-red-700' 
                                : 'bg-white border-gray-200 text-gray-800' }}">

                            <p class="text-[11px] font-semibold uppercase tracking-wide">
                                {{ $dueDate->format('M') }}
                            </p>

                            <p class="text-lg font-bold leading-none mt-1">
                                {{ $dueDate->format('d') }}
                            </p>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 flex items-center justify-between gap-4 min-w-0">

                            <div class="min-w-0">
                                <h4 class="font-semibold text-gray-900 text-sm truncate">
                                    {{ $due->title }}
                                </h4>

                                <p class="text-sm text-gray-700 mt-1">
                                    ₱{{ number_format($due->amount ?? 0, 2) }}
                                    <span class="text-gray-500 text-xs">/ resident</span>
                                </p>
                            </div>

                            <div class="shrink-0">
                                @if($hasPending)
                                    <span class="text-xs font-semibold text-amber-700 bg-amber-100 border border-amber-200 px-2.5 py-1 rounded-full">
                                        {{ $due->count }} Pending
                                    </span>
                                @else
                                    <span class="text-xs font-semibold text-emerald-700 bg-emerald-100 border border-emerald-200 px-2.5 py-1 rounded-full">
                                        All Paid
                                    </span>
                                @endif
                            </div>

                        </div>

                    </div>

                </a>

            @empty
                <div class="flex flex-col items-center justify-center h-48 text-gray-400">
                    <div class="w-14 h-14 rounded-xl bg-gray-100 border border-gray-200 flex items-center justify-center mb-3">
                        <i class="bi bi-calendar-check text-2xl text-gray-500"></i>
                    </div>

                    <p class="text-sm font-semibold text-gray-600">No upcoming due dates</p>
                    <p class="text-xs text-gray-500 mt-1">You're all caught up this month.</p>
                </div>
            @endforelse

        </div>
    </div>

</div>
</div>

       <!-- ROW 3: Announcements, Requests, Payments -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

<!-- 1. Latest Announcements -->
<div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all h-full">
    <div class="flex justify-between items-center mb-6">
        <h3 class="font-bold text-gray-900 text-sm">Announcements</h3>
        <a href="{{ route('admin.announcements.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">View All</a>
    </div>

    <div class="space-y-6">
        @forelse($recentAnnouncements->take(4) as $announcement)
        <a href="{{ route('admin.announcements.edit', $announcement->id) }}" class="flex items-start gap-4 group">

            <!-- Icon -->
            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0
                {{ $announcement->category == 'Event'
                    ? 'bg-purple-50 text-purple-600'
                    : ($announcement->category == 'Meeting'
                        ? 'bg-amber-50 text-amber-600'
                        : 'bg-blue-50 text-blue-600') }}">
                <i class="bi {{ $announcement->category == 'Event'
                        ? 'bi-calendar-event'
                        : ($announcement->category == 'Meeting'
                            ? 'bi-people'
                            : 'bi-megaphone') }}"></i>
            </div>

            <!-- Content -->
            <div class="min-w-0 flex-1">
                <div class="flex justify-between items-start mb-1">
                    <h4 class="text-sm font-bold text-gray-900 truncate pr-2 group-hover:text-blue-600 transition">
                        {{ $announcement->title }}
                    </h4>

                    <span class="text-[10px] font-bold text-gray-600 bg-gray-50 px-2 py-0.5 rounded border border-gray-200">
                        {{ $announcement->created_at->format('M d') }}
                    </span>
                </div>

                <p class="text-xs text-gray-600 line-clamp-2 leading-relaxed">
                    {{ Str::limit($announcement->content, 80) }}
                </p>
            </div>
        </a>

        @empty
        <div class="py-8 text-center text-gray-500 text-xs">
            No active announcements
        </div>
        @endforelse
    </div>
</div>


<!-- 2. Recent Requests -->
<div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all h-full">
    <div class="flex justify-between items-center mb-6">
        <h3 class="font-bold text-gray-900 text-sm">Recent Requests</h3>
        <a href="{{ route('admin.requests.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">View All</a>
    </div>

    <div class="space-y-2">
        @forelse($recentRequests->take(5) as $request)

        <a href="{{ route('admin.requests.show', $request->id) }}" class="flex items-center gap-4 p-2 rounded-xl hover:bg-gray-50 transition cursor-pointer group">

            <!-- Avatar -->
            @if(isset($request->resident->profile_picture))
                <img src="{{ asset('storage/' . $request->resident->profile_picture) }}"
                     class="w-10 h-10 rounded-full object-cover border border-gray-100 shadow-sm shrink-0">
            @else
                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 shrink-0 text-sm font-bold border border-gray-100">
                    {{ substr($request->resident->first_name ?? 'U', 0, 1) }}
                </div>
            @endif

            <!-- Content -->
            <div class="min-w-0 flex-1">

                <div class="flex justify-between items-center mb-0.5">
                    <p class="text-sm font-medium text-gray-900 truncate">
                        {{ $request->resident->full_name ?? 'Unknown' }}
                    </p>

                    <span class="text-xs px-2.5 py-1 rounded-full font-bold
                        {{ $request->status=='pending'
                            ? 'bg-amber-50 text-amber-700'
                            : ($request->status=='approved'
                                ? 'bg-emerald-50 text-emerald-700'
                                : 'bg-red-50 text-red-700') }}">
                        {{ ucfirst($request->status) }}
                    </span>
                </div>

                <p class="text-xs text-gray-600 truncate group-hover:text-blue-600 transition">
                    {{ $request->title }}
                    <span class="text-gray-500 text-[10px]">
                        • {{ $request->created_at->diffForHumans() }}
                    </span>
                </p>

            </div>
        </a>

        @empty
        <div class="py-8 text-center text-gray-500 text-xs">
            No recent requests
        </div>
        @endforelse
    </div>
</div>


<!-- 3. Latest Payments -->
<div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all h-full">
    <div class="flex justify-between items-center mb-6">
        <h3 class="font-bold text-gray-900 text-sm">Latest Payments</h3>
        <a href="{{ route('admin.payments.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">View All</a>
    </div>

    <div class="space-y-2">
        @forelse($latestPayments->take(5) as $payment)

        <a href="{{ route('admin.payments.index', ['active_id' => $payment->id]) }}" class="flex items-center gap-4 p-2 rounded-xl hover:bg-gray-50 transition cursor-pointer group">

            <!-- Icon -->
            <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <i class="bi bi-wallet2"></i>
            </div>

            <!-- Content -->
            <div class="min-w-0 flex-1">

                <div class="flex justify-between items-center">
                    <p class="text-sm font-medium text-gray-900 truncate group-hover:text-emerald-700 transition">
                        {{ $payment->resident->full_name ?? 'Unknown' }}
                    </p>

                    <div class="flex items-center gap-1 text-emerald-600">
                        <span class="text-sm font-bold">
                            +₱{{ number_format($payment->amount, 0) }}
                        </span>
                        <i class="bi bi-check-circle-fill text-[10px]"></i>
                    </div>
                </div>

                <p class="text-[10px] text-gray-500 mt-0.5">
                    {{ $payment->created_at->diffForHumans() }}
                </p>

            </div>
        </a>

        @empty
        <div class="py-8 text-center text-gray-500 text-xs">
            No recent payments
        </div>
        @endforelse
    </div>
</div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Alpine.js for Tabs -->
<script src="//unpkg.com/alpinejs" defer></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const chartCanvas = document.getElementById('financialChart');
    
    if (chartCanvas) {
        const ctx = chartCanvas.getContext('2d');
        
        const collected = {{ $summaryData['totalDuesCollected'] ?? 0 }};
        const unpaid = {{ $summaryData['unpaidDuesAmount'] ?? 0 }};
        
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Collected', 'Unpaid'],
                datasets: [{
                    data: [collected, unpaid],
                    backgroundColor: [
                        '#10B981',
                        '#EF4444'
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {

                    // REMOVE DEFAULT LEGEND
                    legend: {
                        display: false
                    },

                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        titleFont: { size: 13 },
                        bodyFont: { size: 13 },
                        cornerRadius: 8,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.chart._metasets[context.datasetIndex].total;
                                const percentage = total > 0 ? Math.round((value / total) * 100) + '%' : '0%';
                                return `${label}: ₱${value.toLocaleString()} (${percentage})`;
                            }
                        }
                    }

                }
            }
        });
    }
});
</script>
@endpush

