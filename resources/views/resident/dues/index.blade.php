@extends('resident.layouts.app')

@section('title', 'Payments & Dues')
@section('page-title', 'Payments & Dues')

@section('content')
<div class="space-y-8">

    {{-- ========================= --}}
    {{-- SUMMARY CARDS --}}
    {{-- ========================= --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @php
            $summaryCards = [
                [
                    'title' => 'Outstanding Dues',
                    'value' => $summary['outstanding_dues'] ?? 0,
                    'icon' => 'bi-clock-history',
                    'color' => 'orange',
                    'badge' => ($summary['outstanding_dues'] ?? 0) > 0 ? 'Due Soon' : null,
                ],
                [
                    'title' => 'Total Paid ('.now()->year.')',
                    'value' => $summary['total_paid'] ?? 0,
                    'icon' => 'bi-check-circle',
                    'color' => 'emerald',
                ],
                [
                    'title' => 'Current Penalties',
                    'value' => $summary['penalties'] ?? 0,
                    'icon' => 'bi-exclamation-triangle-fill',
                    'color' => 'red',
                ]
            ];
        @endphp

        @foreach($summaryCards as $card)
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <i class="bi {{ $card['icon'] }} text-6xl text-{{ $card['color'] }}-500"></i>
            </div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">{{ $card['title'] }}</p>
            <h3 class="text-2xl font-bold text-gray-900">₱{{ number_format($card['value'], 2) }}</h3>
            @if(isset($card['badge']))
                <div class="mt-4 flex items-center text-xs text-{{ $card['color'] }}-600 font-bold">
                    <span class="bg-{{ $card['color'] }}-50 px-2 py-1 rounded-md">{{ $card['badge'] }}</span>
                </div>
            @endif
        </div>
        @endforeach
    </div>

    {{-- ========================= --}}
    {{-- TAB INTERFACE --}}
    {{-- ========================= --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

        {{-- Tabs Header --}}
        <div class="flex border-b border-gray-100 bg-gray-50/50">
            <button class="px-8 py-4 text-sm font-bold text-gray-500 hover:text-gray-700 border-b-2 border-transparent focus:outline-none tab-btn active transition-all" data-tab="payments">
                <i class="bi bi-receipt mr-2"></i> Payment History
            </button>
            <button class="px-8 py-4 text-sm font-bold text-gray-500 hover:text-gray-700 border-b-2 border-transparent focus:outline-none tab-btn transition-all" data-tab="penalties">
                <i class="bi bi-exclamation-circle mr-2"></i> Penalties
            </button>
        </div>

        {{-- PAYMENTS TAB --}}
        <div class="tab-content" id="payments">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Status</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($dues as $due)
                        <tr class="hover:bg-gray-50 transition {{ $due->isOverdue ? 'bg-red-50/30' : '' }}">
                            <td class="px-6 py-4 text-sm text-gray-500 font-medium">{{ optional($due->due_date)->format('M d, Y') ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 font-bold">{{ $due->title }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                @php
                                    $typeLabel = match ($due->type) {
                                        \App\Models\Due::TYPE_SPECIAL_ASSESSMENTS => 'Special Assessment',
                                        \App\Models\Due::TYPE_REGULAR_FEES => 'Regular Fee',
                                        \App\Models\Due::TYPE_MONTHLY_HOA => 'Monthly HOA',
                                        default => $due->type ?? 'Dues',
                                    };
                                @endphp
                                {{ $typeLabel }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                ₱{{ number_format($due->amount, 2) }}
                                @if($due->totalCollected() > 0 && $due->totalCollected() < $due->amount)
                                    <div class="text-xs text-orange-600 font-bold mt-0.5">
                                        ({{ number_format($due->totalCollected(), 2) }} paid)
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $displayStatus = strtolower($due->display_status ?? $due->status);
                                    $statusClass = match($displayStatus) {
                                        'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                        'pending' => 'bg-orange-50 text-orange-700 border-orange-100',
                                        'unpaid', 'overdue' => 'bg-red-50 text-red-700 border-red-100',
                                        default => 'bg-gray-50 text-gray-700 border-gray-100'
                                    };
                                    $dotClass = match($displayStatus) {
                                        'paid' => 'bg-emerald-500',
                                        'pending' => 'bg-orange-500',
                                        'unpaid', 'overdue' => 'bg-red-500',
                                        default => 'bg-gray-500'
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold border capitalize tracking-wide {{ $statusClass }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span>
                                    {{ $displayStatus === 'pending' ? 'Payment Pending' : ucfirst($displayStatus) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(in_array(strtolower($due->display_status ?? $due->status), ['unpaid', 'overdue']))
                                    <a href="{{ route('resident.payments.pay', $due->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 transition shadow-sm">
                                        Pay Now <i class="bi bi-arrow-right"></i>
                                    </a>
                                @elseif(strtolower($due->display_status ?? $due->status) === 'pending')
                                    <button disabled class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-100 text-gray-400 text-xs font-bold rounded-lg cursor-not-allowed border border-gray-200">
                                        Processing <i class="bi bi-hourglass-split"></i>
                                    </button>
                                @else
                                    <span class="text-emerald-600 text-xs font-bold flex items-center justify-center gap-1">
                                        <i class="bi bi-check-circle-fill"></i> Completed
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-400">
                                <i class="bi bi-inbox text-2xl mb-2 block"></i>
                                No payment history found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Outstanding Balance Footer --}}
            @if(($summary['outstanding_dues'] ?? 0) > 0 && Route::has('resident.payments.index'))
            <div class="p-6 bg-gray-50 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                        <i class="bi bi-wallet2 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">Outstanding Balance</p>
                        <p class="text-sm text-gray-500">You have pending dues of <strong class="text-gray-900">₱{{ number_format($summary['outstanding_dues'], 2) }}</strong></p>
                    </div>
                </div>
                <div class="flex gap-3 w-full md:w-auto">
                    <a href="{{ route('resident.payments.index') }}" class="flex-1 md:flex-none text-center px-6 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 text-sm font-bold transition shadow-sm hover:shadow-md">
                        Manage Payments
                    </a>
                    @if(Route::has('resident.dues.download'))
                    <a href="{{ route('resident.dues.download') }}" class="flex-1 md:flex-none text-center px-6 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 text-sm font-bold transition shadow-sm hover:shadow-md">
                        View Invoice
                    </a>
                    @endif
                </div>
            </div>
            @endif
        </div>

        {{-- PENALTIES TAB --}}
        <div class="tab-content hidden" id="penalties">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($penalties as $penalty)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-500 font-medium">{{ optional($penalty->created_at)->format('M d, Y') ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 font-bold">{{ $penalty->reason ?? 'Penalty' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">₱{{ number_format($penalty->amount, 2) }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border capitalize tracking-wide {{ strtolower($penalty->status)=='paid' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-red-50 text-red-700 border-red-100' }}">
                                    {{ ucfirst($penalty->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-gray-400">
                                <i class="bi bi-check-circle text-2xl mb-2 block"></i>
                                No penalties found. Great job!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($penalties->where('status','Unpaid')->count() && Route::has('resident.payments.index'))
            <div class="p-6 bg-red-50 border-t border-red-100 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                        <i class="bi bi-exclamation-triangle-fill text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-red-900">Unpaid Penalties</p>
                        <p class="text-sm text-red-700">Total unpaid penalties: <strong>₱{{ number_format($penalties->where('status','Unpaid')->sum('amount'), 2) }}</strong></p>
                    </div>
                </div>
                <a href="{{ route('resident.payments.index') }}" class="w-full md:w-auto text-center px-6 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 text-sm font-bold transition shadow-sm hover:shadow-md">
                    Manage Payments
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .tab-btn.active {
        color: #2563EB; /* Blue 600 */
        border-bottom-color: #2563EB;
    }
</style>

<script>
    const tabs = document.querySelectorAll('.tab-btn');
    const contents = document.querySelectorAll('.tab-content');

    tabs.forEach(btn => {
        btn.addEventListener('click', () => {
            tabs.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            contents.forEach(c => c.classList.add('hidden'));
            document.getElementById(btn.dataset.tab).classList.remove('hidden');
        });
    });
</script>
@endsection
