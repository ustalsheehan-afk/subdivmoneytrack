@extends('layouts.admin')

@section('title', 'Batch Details')
@section('page-title', 'Billing Statement Details')

@section('content')
<div class="space-y-6">
    {{-- HEADER LABEL --}}
    <div class="flex items-center gap-2 text-xs font-bold text-gray-400 uppercase tracking-widest">
        <i class="bi bi-shield-check text-blue-500"></i>
        <span>Financial Management / Billing Statement</span>
    </div>

    {{-- TOP BAR --}}
    <div class="flex items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dues.index') }}" class="w-10 h-10 flex items-center justify-center rounded-xl border border-gray-200 text-gray-400 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all shadow-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h3 class="text-xl font-bold text-gray-900">{{ $batch->title ?? 'Untitled Statement' }}</h3>
                <p class="text-sm text-gray-500 uppercase font-bold tracking-wider">
                    {{ str_replace('_', ' ', $batch->type ?? 'N/A') }} • 
                    Due {{ $batch->due_date ? $batch->due_date->format('M d, Y') : 'N/A' }}
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button class="px-4 py-2 bg-gray-50 text-gray-600 text-xs font-bold rounded-xl border border-gray-200 hover:bg-white hover:shadow-sm transition-all flex items-center gap-2">
                <i class="bi bi-download"></i>
                <span>Export CSV</span>
            </button>
            <button class="px-4 py-2 bg-blue-600 text-white text-xs font-bold rounded-xl hover:bg-blue-700 transition-all shadow-md shadow-blue-100 flex items-center gap-2">
                <i class="bi bi-bell"></i>
                <span>Send Bulk Reminders</span>
            </button>
        </div>
    </div>

    {{-- BATCH SUMMARY STATS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
            <div class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Collection Progress</div>
            <div class="flex items-end justify-between">
                <div class="text-3xl font-black text-gray-900">{{ number_format($batch->progress ?? 0, 1) }}%</div>
                <div class="text-xs font-bold text-gray-400">₱{{ number_format($batch->collected_amount ?? 0, 0) }} / ₱{{ number_format($batch->total_expected ?? 0, 0) }}</div>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                <div class="bg-blue-500 h-full rounded-full transition-all duration-1000" style="width: {{ $batch->progress ?? 0 }}%"></div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
            <div class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Resident Status</div>
            <div class="flex items-center justify-between">
                <div class="text-center px-4">
                    <div class="text-xl font-black text-green-600">{{ $batch->residentDues->where('status', 'paid')->count() }}</div>
                    <div class="text-[10px] font-bold text-gray-400 uppercase">Paid</div>
                </div>
                <div class="text-center px-4 border-x border-gray-100">
                    <div class="text-xl font-black text-orange-500">{{ $batch->residentDues->where('status', 'partial')->count() }}</div>
                    <div class="text-[10px] font-bold text-gray-400 uppercase">Partial</div>
                </div>
                <div class="text-center px-4">
                    <div class="text-xl font-black text-red-500">{{ $batch->residentDues->where('status', 'unpaid')->count() }}</div>
                    <div class="text-[10px] font-bold text-gray-400 uppercase">Unpaid</div>
                </div>
            </div>
        </div>

        <div class="bg-blue-600 p-6 rounded-2xl shadow-xl shadow-blue-100 space-y-4 text-white">
            <div class="text-[11px] font-bold text-blue-200 uppercase tracking-widest">Pending Collection</div>
            <div class="text-3xl font-black">₱{{ number_format(($batch->total_expected ?? 0) - ($batch->collected_amount ?? 0), 2) }}</div>
            <div class="text-xs font-medium text-blue-100">Targeting {{ $batch->residentDues->count() }} residents</div>
        </div>
    </div>

    {{-- RESIDENT DUES TABLE --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-50 flex items-center justify-between gap-4">
            <div class="relative max-w-xs w-full group">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-hover:text-blue-500 transition-colors"></i>
                <input type="text" id="residentSearch" placeholder="Search resident..." class="w-full pl-10 pr-4 py-2 rounded-xl bg-gray-50 border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/5 text-sm transition-all outline-none">
            </div>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <select id="statusFilter" class="appearance-none pl-4 pr-10 py-2 rounded-xl bg-gray-50 border-transparent text-sm font-bold text-gray-500 focus:bg-white focus:border-blue-500 transition-all outline-none cursor-pointer">
                        <option value="">All Statuses</option>
                        <option value="paid">Paid</option>
                        <option value="partial">Partial</option>
                        <option value="unpaid">Unpaid</option>
                    </select>
                    <i class="bi bi-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Resident</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Property</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Amount Due</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total Paid</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Balance</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider text-center">Status</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50" id="residentTableBody">
                    @forelse($batch->residentDues as $due)
                    @php
                        $statusInfo = $due->status_info;
                    @endphp
                    <tr class="group hover:bg-blue-50/30 transition-all duration-200" data-status="{{ $due->dynamic_status }}" data-name="{{ strtolower(($due->resident->first_name ?? '') . ' ' . ($due->resident->last_name ?? '')) }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center font-bold text-xs group-hover:bg-blue-100 group-hover:text-blue-600 transition-colors">
                                    {{ substr($due->resident->first_name ?? 'R', 0, 1) }}{{ substr($due->resident->last_name ?? 'S', 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900">{{ ($due->resident->first_name ?? 'Unknown') . ' ' . ($due->resident->last_name ?? 'Resident') }}</div>
                                    <div class="text-[10px] text-gray-400 font-bold uppercase">{{ $due->resident->email ?? 'No Email' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-700">Block {{ $due->resident->block ?? '-' }} / Lot {{ $due->resident->lot ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-900 tabular-nums">₱{{ number_format($due->amount, 2) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-emerald-600 tabular-nums">₱{{ number_format($due->total_paid, 2) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-red-600 tabular-nums">₱{{ number_format($due->balance, 2) }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-{{ $statusInfo['color'] }}-50 text-{{ $statusInfo['color'] }}-700 text-[10px] font-bold uppercase border border-{{ $statusInfo['color'] }}-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-{{ $statusInfo['color'] }}-500"></span>
                                {{ $statusInfo['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($due->balance > 0)
                                <button onclick="openPaymentModal({{ $due->id }}, '{{ ($due->resident->first_name ?? '') . ' ' . ($due->resident->last_name ?? '') }}', {{ $due->balance }})" class="px-4 py-1.5 bg-blue-600 text-white text-[10px] font-bold uppercase rounded-lg hover:bg-blue-700 transition-all shadow-sm">
                                    Record Payment
                                </button>
                            @else
                                <div class="w-8 h-8 rounded-lg bg-green-50 text-green-600 flex items-center justify-center ml-auto border border-green-100 shadow-sm" title="Fully Paid">
                                    <i class="bi bi-check2-all text-lg"></i>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center">
                            <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-4">
                                <i class="bi bi-people text-2xl text-gray-300"></i>
                            </div>
                            <p class="text-gray-400 text-sm">No residents found for this billing statement.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- PAYMENT MODAL --}}
<div id="paymentModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm animate__animated animate__fadeIn animate__faster">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden animate__animated animate__zoomIn animate__faster">
        <form id="paymentForm" method="POST">
            @csrf
            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-black text-gray-900">Record Payment</h3>
                    <button type="button" onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600 transition-colors"><i class="bi bi-x-lg"></i></button>
                </div>

                <div class="bg-blue-50 p-4 rounded-2xl mb-6">
                    <div class="text-[10px] font-bold text-blue-400 uppercase mb-1">Resident</div>
                    <div id="modalResidentName" class="text-sm font-bold text-blue-900">-</div>
                </div>

                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Amount to Pay</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">₱</span>
                            <input type="number" name="amount" id="paymentAmount" step="0.01" class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm focus:bg-white focus:border-blue-500 outline-none transition-all" required>
                        </div>
                        <p class="text-[10px] text-blue-500 font-medium">Balance: ₱<span id="modalBalance">0.00</span></p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Payment Method</label>
                        <select name="method" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm focus:bg-white focus:border-blue-500 outline-none transition-all" required>
                            <option value="cash">Cash</option>
                            <option value="gcash">GCash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="check">Check</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-8">
                    <button type="button" onclick="closePaymentModal()" class="py-3.5 rounded-2xl border border-gray-100 text-gray-500 font-bold hover:bg-gray-50 transition-all">Cancel</button>
                    <button type="submit" class="py-3.5 rounded-2xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">Confirm Payment</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function openPaymentModal(dueId, name, balance) {
        const form = document.getElementById('paymentForm');
        // Use a placeholder for the ID and replace it in JS to avoid hardcoding the base path
        let url = "{{ route('admin.dues.markAsPaid', ':id') }}";
        form.action = url.replace(':id', dueId);
        
        document.getElementById('modalResidentName').textContent = name;
        document.getElementById('paymentAmount').value = balance.toFixed(2);
        document.getElementById('modalBalance').textContent = balance.toLocaleString(undefined, {minimumFractionDigits: 2});
        document.getElementById('paymentModal').classList.remove('hidden');
    }

    function closePaymentModal() {
        document.getElementById('paymentModal').classList.add('hidden');
    }

    // Client-side filtering
    document.getElementById('residentSearch').addEventListener('input', filterTable);
    document.getElementById('statusFilter').addEventListener('change', filterTable);

    function filterTable() {
        const searchTerm = document.getElementById('residentSearch').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const rows = document.querySelectorAll('#residentTableBody tr[data-status]');

        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            const status = row.getAttribute('data-status');
            
            const matchesSearch = name.includes(searchTerm);
            const matchesStatus = statusFilter === '' || status === statusFilter;

            if (matchesSearch && matchesStatus) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        });
    }
</script>
@endsection
