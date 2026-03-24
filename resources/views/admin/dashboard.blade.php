@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-8 animate-fade-in">

    <!-- ===================== -->
    <!-- GREETING CARD -->
    <!-- ===================== -->
    <div class="glass-card p-8 relative overflow-hidden group">
        <!-- Subtle gradient glow in background -->
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-brand-accent/5 rounded-full blur-3xl group-hover:bg-brand-accent/10 transition-all duration-700"></div>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                    Good Day, <span class="text-brand text-emerald-600">Admin</span>
                </h1>
                <p class="mt-2 text-gray-600 text-lg max-w-xl">
                    Here's a comprehensive overview of your community's activity and financial health today.
                </p>
            </div>

            <div class="flex items-center gap-3 bg-gray-50 border border-gray-200 px-5 py-3 rounded-2xl shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <i class="bi bi-calendar3"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Current Date</p>
                    <p class="text-gray-900 font-bold">{{ now()->format('l, F j, Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== -->
    <!-- QUICK ACTIONS -->
    <!-- ===================== -->
    <div class="space-y-4">
        <h2 class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] px-1">Quick Actions</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @php
                $quickActions = [
                    ['label' => 'Add Resident', 'icon' => 'bi-person-plus-fill', 'route' => route('admin.residents.index')],
                    ['label' => 'Announcement', 'icon' => 'bi-megaphone-fill', 'route' => route('admin.announcements.index')],
                    ['label' => 'Record Payment', 'icon' => 'bi-credit-card-fill', 'route' => route('admin.payments.index')],
                    ['label' => 'Create Dues', 'icon' => 'bi-receipt', 'route' => route('admin.dues.index')],
                    ['label' => 'Review Requests', 'icon' => 'bi-clipboard-check-fill', 'route' => route('admin.requests.index')],
                    ['label' => 'Reserve Amenity', 'icon' => 'bi-calendar-check-fill', 'route' => route('admin.amenity-reservations.index')],
                ];
            @endphp

            @foreach($quickActions as $action)
                <a href="{{ $action['route'] }}" class="glass-card p-5 flex flex-col items-center justify-center text-center gap-3 group hover:border-emerald-500/30 transition-all duration-300 relative">
                    <div class="w-12 h-12 rounded-[16px] bg-gray-50 flex items-center justify-center shrink-0 border border-gray-100 group-hover:bg-emerald-600 group-hover:text-white group-hover:border-emerald-500 transition-all duration-300 shadow-sm relative z-10">
                        <i class="bi {{ $action['icon'] }} text-xl"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-[10px] font-black text-gray-900 uppercase tracking-widest">{{ $action['label'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- ===================== -->
    <!-- SUMMARY CARDS -->
    <!-- ===================== -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            $stats = [
                ['label' => 'Total Residents', 'value' => $summaryData['totalResidents'], 'icon' => 'bi-people-fill', 'link' => route('admin.residents.index'), 'trend' => 'Community size', 'accent' => 'border-t-emerald-500'],
                ['label' => 'Dues Collected', 'value' => '₱' . number_format($summaryData['totalDuesCollected'], 2), 'icon' => 'bi-wallet2', 'link' => route('admin.dues.index'), 'trend' => 'Total revenue', 'accent' => 'border-t-emerald-600'],
                ['label' => 'Pending Payments', 'value' => $summaryData['pendingPayments'], 'icon' => 'bi-hourglass-split', 'link' => route('admin.payments.index'), 'trend' => 'Action required', 'accent' => 'border-t-amber-400'],
                ['label' => 'Total Penalties', 'value' => '₱' . number_format($summaryData['totalPenalties'], 2), 'icon' => 'bi-exclamation-triangle-fill', 'link' => route('admin.penalties.index'), 'trend' => 'Outstanding fees', 'accent' => 'border-t-red-400'],
            ];
        @endphp

        @foreach($stats as $stat)
            <a href="{{ $stat['link'] }}" class="glass-card p-6 group relative overflow-hidden {{ $stat['accent'] }} border-t-2">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">{{ $stat['label'] }}</p>
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight">{{ $stat['value'] }}</h3>
                        <p class="text-[10px] text-gray-600 mt-2 font-medium">{{ $stat['trend'] }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-700 text-lg shadow-inner group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                        <i class="bi {{ $stat['icon'] }}"></i>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <!-- ===================== -->
    <!-- MAIN CONTENT GRID -->
    <!-- ===================== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- LEFT: Financial Performance -->
        <div class="lg:col-span-7 glass-card overflow-hidden flex flex-col border border-gray-100 shadow-sm">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-graph-up-arrow text-emerald-600"></i>
                        Financial Performance
                    </h3>
                    <p class="text-xs text-gray-600 mt-1 tracking-wide">Collection vs. Expected Revenue</p>
                </div>
                <div class="px-3 py-1 bg-emerald-50 border border-emerald-100 rounded-lg text-[10px] font-bold text-emerald-600 uppercase tracking-widest">
                    Real-time
                </div>
            </div>

            @php
                $collected = $summaryData['totalDuesCollected'] ?? 0;
                $unpaid = $summaryData['unpaidDuesAmount'] ?? 0;
                $totalExpected = $collected + $unpaid;
                $unpaidResidentsCount = $summaryData['unpaidResidentsCount'] ?? 0;
            @endphp

            <div class="p-8 flex flex-col md:flex-row items-center gap-12 flex-1">
                <div class="flex-1 space-y-6">
                    <div>
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Total Collected</p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-4xl font-black text-gray-900 tracking-tighter">₱{{ number_format($collected) }}</span>
                            <span class="text-sm text-gray-600 font-medium">/ ₱{{ number_format($totalExpected) }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 border border-gray-100 p-4 rounded-2xl">
                            <p class="text-[10px] font-bold text-emerald-700 uppercase tracking-widest mb-1">Success Rate</p>
                            <p class="text-xl font-bold text-gray-900">{{ $totalExpected > 0 ? round(($collected / $totalExpected) * 100) : 0 }}%</p>
                        </div>
                        <div class="bg-gray-50 border border-gray-100 p-4 rounded-2xl">
                            <p class="text-[10px] font-bold text-red-700 uppercase tracking-widest mb-1">Unpaid Dues</p>
                            <p class="text-xl font-bold text-gray-900">{{ $unpaidResidentsCount }} <span class="text-xs font-normal text-gray-600">Residents</span></p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex justify-between text-xs font-bold uppercase tracking-widest">
                            <span class="text-gray-600">Collection Progress</span>
                            <span class="text-emerald-600">{{ $totalExpected > 0 ? round(($collected / $totalExpected) * 100) : 0 }}%</span>
                        </div>
                        <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden border border-gray-200 p-[1px]">
                            <div class="h-full bg-emerald-500 rounded-full transition-all duration-1000" style="width: {{ $totalExpected > 0 ? ($collected / $totalExpected) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="shrink-0 relative">
                    <div class="w-48 h-48 relative z-10">
                        @if($totalExpected > 0)
                            <canvas id="financialChart"></canvas>
                        @else
                            <div class="w-full h-full rounded-full border-4 border-dashed border-gray-200 flex items-center justify-center">
                                <i class="bi bi-pie-chart text-4xl text-gray-300"></i>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: Upcoming Due Dates -->
        <div class="lg:col-span-5 glass-card flex flex-col border border-gray-100 shadow-sm">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-calendar-event text-emerald-600"></i>
                        Upcoming Due Dates
                    </h3>
                    <p class="text-xs text-gray-600 mt-1 tracking-wide">Monthly schedule overview</p>
                </div>
                <div class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-gray-500">
                    <i class="bi bi-filter"></i>
                </div>
            </div>

            <div class="p-6 flex-1 overflow-y-auto custom-scrollbar max-h-[400px]">
                <div class="space-y-4">
                    @forelse($upcomingDues as $due)
                        @php
                            $dueDate = \Carbon\Carbon::parse($due->due_date);
                            $isToday = $dueDate->isToday();
                            $isUrgent = $dueDate->diffInDays(now()) <= 3 && $dueDate->isFuture();
                            $hasPending = $due->count > 0;
                        @endphp

                        <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 hover:border-emerald-500/20 group transition-all duration-300">
                            <div class="flex items-center gap-5">
                                <div class="shrink-0 w-16 h-16 rounded-2xl flex flex-col items-center justify-center border {{ ($isUrgent || $isToday) ? 'bg-red-50 border-red-100 text-red-600' : 'bg-white border-gray-200 text-gray-900' }} shadow-sm">
                                    <span class="text-[10px] font-black uppercase tracking-widest opacity-60">{{ $dueDate->format('M') }}</span>
                                    <span class="text-2xl font-black leading-none mt-1">{{ $dueDate->format('d') }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start mb-1">
                                        <h4 class="text-sm font-bold text-gray-900 truncate pr-2 group-hover:text-emerald-600 transition-colors">{{ $due->title }}</h4>
                                        <span class="shrink-0 text-[10px] font-black uppercase tracking-widest {{ $hasPending ? 'text-amber-700 bg-amber-50 border border-amber-200' : 'text-emerald-700 bg-emerald-50 border border-emerald-200' }} px-2 py-0.5 rounded-md">
                                            {{ $hasPending ? $due->count . ' Pending' : 'Cleared' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-3 mt-2">
                                        <div class="flex items-center gap-1.5">
                                            <i class="bi bi-cash-stack text-emerald-600 text-xs"></i>
                                            <span class="text-xs font-bold text-gray-900">₱{{ number_format($due->amount ?? 0, 2) }}</span>
                                        </div>
                                        <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                        <span class="text-[10px] text-gray-600 font-bold uppercase tracking-wider">Per Resident</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-12 text-center">
                            <div class="w-16 h-16 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center mb-4">
                                <i class="bi bi-calendar-check text-2xl text-gray-300"></i>
                            </div>
                            <p class="text-sm font-bold text-gray-900">No upcoming dues</p>
                            <p class="text-xs text-gray-600 mt-1">Everything is up to date.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            
            <a href="{{ route('admin.dues.index') }}" class="p-4 text-center border-t border-gray-100 text-[10px] font-black text-emerald-600 uppercase tracking-widest hover:bg-gray-50 transition-colors">
                View Billing Calendar
            </a>
        </div>
    </div>

    <!-- ===================== -->
    <!-- LOWER SECTION -->
    <!-- ===================== -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Announcements -->
        <div class="glass-card flex flex-col h-full overflow-hidden border border-gray-100 shadow-sm">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-[11px] font-black text-gray-900 uppercase tracking-[0.2em]">Announcements</h3>
                <a href="{{ route('admin.announcements.index') }}" class="text-[10px] font-black text-emerald-600 uppercase tracking-widest hover:underline">View All</a>
            </div>
            <div class="p-2 space-y-1 flex-1 overflow-y-auto custom-scrollbar">
                @forelse($recentAnnouncements->take(4) as $announcement)
                    <a href="{{ route('admin.announcements.edit', $announcement->id) }}" class="flex items-start gap-4 p-4 rounded-xl hover:bg-gray-50 transition-all group">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 border border-gray-100 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300
                            {{ $announcement->category == 'Event' ? 'bg-purple-50 text-purple-600' : ($announcement->category == 'Meeting' ? 'bg-amber-50 text-amber-600' : 'bg-blue-50 text-blue-600') }}">
                            <i class="bi {{ $announcement->category == 'Event' ? 'bi-calendar-event' : ($announcement->category == 'Meeting' ? 'bi-people' : 'bi-megaphone') }}"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex justify-between items-start mb-1">
                                <h4 class="text-xs font-bold text-gray-900 truncate pr-2 group-hover:text-emerald-600 transition-colors">{{ $announcement->title }}</h4>
                                <span class="shrink-0 text-[9px] font-black text-gray-500 bg-white px-2 py-0.5 rounded-md border border-gray-200">{{ $announcement->created_at->format('M d') }}</span>
                            </div>
                            <p class="text-[11px] text-gray-600 line-clamp-1 opacity-80 group-hover:opacity-100 transition-opacity">{{ Str::limit($announcement->content, 60) }}</p>
                        </div>
                    </a>
                @empty
                    <div class="py-12 text-center">
                        <i class="bi bi-chat-dots text-3xl text-gray-200 mb-3 block"></i>
                        <p class="text-xs text-gray-500">No active announcements</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Requests -->
        <div class="glass-card flex flex-col h-full overflow-hidden border border-gray-100 shadow-sm">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-[11px] font-black text-gray-900 uppercase tracking-[0.2em]">Recent Requests</h3>
                <a href="{{ route('admin.requests.index') }}" class="text-[10px] font-black text-emerald-600 uppercase tracking-widest hover:underline">View All</a>
            </div>
            <div class="p-2 space-y-1 flex-1 overflow-y-auto custom-scrollbar">
                @forelse($recentRequests->take(5) as $request)
                    <a href="{{ route('admin.requests.show', $request->id) }}" class="flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 transition-all group">
                        @if(isset($request->resident?->profile_picture))
                            <img src="{{ asset('storage/' . $request->resident->profile_picture) }}" class="w-10 h-10 rounded-full object-cover border-2 border-gray-100 shadow-sm group-hover:border-emerald-500/50 transition-all">
                        @else
                            <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-700 text-[10px] font-black border-2 border-gray-100 group-hover:border-emerald-500/50 transition-all">
                                {{ substr($request->resident?->first_name ?? 'U', 0, 1) }}
                            </div>
                        @endif
                        <div class="min-w-0 flex-1">
                            <div class="flex justify-between items-center mb-1">
                                <p class="text-xs font-bold text-gray-900 truncate">{{ $request->resident?->full_name ?? 'Unknown' }}</p>
                                <span class="badge-standard 
                                    {{ $request->status=='pending' ? 'bg-amber-50 text-amber-600 border border-amber-100' : ($request->status=='approved' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-red-50 text-red-600 border border-red-100') }}">
                                    {{ $request->status }}
                                </span>
                            </div>
                            <p class="text-[10px] text-gray-600 truncate group-hover:text-emerald-600 transition-colors">{{ $request->title }} <span class="opacity-60 ml-1">• {{ $request->created_at->diffForHumans() }}</span></p>
                        </div>
                    </a>
                @empty
                    <div class="py-12 text-center">
                        <i class="bi bi-inbox text-3xl text-gray-200 mb-3 block"></i>
                        <p class="text-xs text-gray-500">No recent requests</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Latest Payments -->
        <div class="glass-card flex flex-col h-full overflow-hidden border border-gray-100 shadow-sm">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-[11px] font-black text-gray-900 uppercase tracking-[0.2em]">Latest Payments</h3>
                <a href="{{ route('admin.payments.index') }}" class="text-[10px] font-black text-emerald-600 uppercase tracking-widest hover:underline">View All</a>
            </div>
            <div class="p-2 space-y-1 flex-1 overflow-y-auto custom-scrollbar">
                @forelse($latestPayments->take(5) as $payment)
                    <a href="{{ route('admin.payments.index', ['active_id' => $payment->id]) }}" class="flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 transition-all group">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex justify-between items-center">
                                <p class="text-xs font-bold text-gray-900 truncate group-hover:text-emerald-600 transition-colors">{{ $payment->resident?->full_name ?? 'Unknown' }}</p>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-sm font-black text-emerald-600">+₱{{ number_format($payment->amount, 0) }}</span>
                                    <i class="bi bi-check-circle-fill text-[10px] text-emerald-600"></i>
                                </div>
                            </div>
                            <p class="text-[9px] text-gray-500 font-bold uppercase tracking-widest mt-0.5 opacity-60">{{ $payment->created_at->diffForHumans() }}</p>
                        </div>
                    </a>
                @empty
                    <div class="py-12 text-center">
                        <i class="bi bi-cash-stack text-3xl text-gray-200 mb-3 block"></i>
                        <p class="text-xs text-gray-500">No recent payments</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartCanvas = document.getElementById('financialChart');
    if (chartCanvas) {
        const ctx = chartCanvas.getContext('2d');
        const collected = {{ $summaryData['totalDuesCollected'] ?? 0 }};
        const unpaid = {{ $summaryData['unpaidDuesAmount'] ?? 0 }};
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Collected', 'Unpaid'],
                datasets: [{
                    data: [collected, unpaid],
                    backgroundColor: [
                        '#10B981', // Emerald 500
                        '#F3F4F6'  // Gray 100
                    ],
                    borderColor: '#FFFFFF',
                    borderWidth: 4,
                    hoverOffset: 4,
                    borderRadius: 10,
                    spacing: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#111827',
                        padding: 12,
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 12 },
                        cornerRadius: 12,
                        displayColors: true,
                        borderColor: 'rgba(182, 255, 92, 0.2)',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.chart._metasets[context.datasetIndex].total;
                                const percentage = total > 0 ? Math.round((value / total) * 100) + '%' : '0%';
                                return ` ${label}: ₱${value.toLocaleString()} (${percentage})`;
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
