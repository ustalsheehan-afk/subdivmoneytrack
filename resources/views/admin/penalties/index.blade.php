@extends('layouts.admin')

@section('title', 'Penalties')
@section('page-title', 'Penalties Management')

@section('content')
<div class="space-y-8 animate-fade-in" x-data="penaltiesBulkApproval()" x-cloak>

    {{-- ===================== --}}
    {{-- HEADER SECTION --}}
    {{-- ===================== --}}
    <div class="glass-card p-8 relative overflow-hidden group">
        {{-- Subtle gradient glow in background --}}
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-brand-accent/5 rounded-full blur-3xl group-hover:bg-brand-accent/10 transition-all duration-700"></div>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                    Penalties
                </h1>
                <p class="mt-2 text-gray-600 text-lg max-w-xl">
                    Monitor community violations, track fine collections, and manage resident enforcement.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.smsTemplates.index') }}" class="btn-secondary">
                    <i class="bi bi-chat-left-text"></i>
                    SMS Templates
                </a>
                <form method="POST" action="{{ route('admin.penalties.sendSmsNotices') }}">
                    @csrf
                    <button type="submit" class="btn-secondary" onclick="return confirm('Send penalty SMS notices to residents with pending/unpaid penalties?')">
                        <i class="bi bi-send"></i>
                        Send Penalty SMS
                    </button>
                </form>
                <a href="{{ route('admin.penalties.create') }}" class="btn-premium">
                    <i class="bi bi-plus-lg"></i>
                    Record Penalty
                </a>
            </div>
        </div>
    </div>

    {{-- ===================== --}}
    {{-- STATS SECTION --}}
    {{-- ===================== --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Total Penalties --}}
        <div class="glass-card p-6 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative z-10 space-y-2">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Penalties</p>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight">{{ $totalCount }}</h3>
                <div class="flex items-center gap-2 pt-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                    <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest">Violations</p>
                </div>
            </div>
        </div>

        {{-- Collected --}}
        <div class="glass-card p-6 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative z-10 space-y-2">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Collected</p>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight">₱{{ number_format($totalPaid, 2) }}</h3>
                <div class="flex items-center gap-2 pt-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Paid Fines</p>
                </div>
            </div>
        </div>

        {{-- Pending --}}
        <div class="glass-card p-6 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-orange-50 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative z-10 space-y-2">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Pending Payment</p>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight">₱{{ number_format($totalPending, 2) }}</h3>
                <div class="flex items-center gap-2 pt-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                    <p class="text-[10px] font-black text-orange-600 uppercase tracking-widest">Awaiting Action</p>
                </div>
            </div>
        </div>

        {{-- Unpaid --}}
        <div class="glass-card p-6 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-red-50 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative z-10 space-y-2">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Unpaid / Overdue</p>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight">₱{{ number_format($totalUnpaid, 2) }}</h3>
                <div class="flex items-center gap-2 pt-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                    <p class="text-[10px] font-black text-red-600 uppercase tracking-widest">Past Due</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== --}}
    {{-- TOOLBAR SECTION --}}
    {{-- ===================== --}}
    <div class="glass-card p-4 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        
        {{-- Search Bar --}}
        <div class="flex-1 max-w-md">
            <form method="GET" action="{{ route('admin.penalties.index') }}" class="relative group">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-600 transition-colors"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Search name, reason, or description..." 
                    class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all placeholder-gray-400">
                
                @foreach(request()->except(['search', 'page']) as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
            </form>
        </div>

        {{-- Filters & Toggles --}}
        <div class="flex flex-wrap items-center gap-3">
            <button type="button"
                    x-show="hasPendingRows && !penaltySelectionMode"
                    x-transition.opacity.duration.150ms
                    @click="enableSelectionMode()"
                    class="h-11 px-4 inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white text-[10px] font-black uppercase tracking-widest text-gray-600 transition-all hover:border-emerald-500/30 hover:bg-gray-50">
                <i class="bi bi-check2-square text-emerald-600"></i>
                Select
            </button>

            <div x-show="penaltySelectionMode"
                 x-transition.opacity.duration.150ms
                 class="flex items-center gap-2">
                <button type="button"
                        @click="clearSelection()"
                        class="h-11 px-4 inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white text-[10px] font-black uppercase tracking-widest text-gray-600 transition-all hover:border-gray-300 hover:bg-gray-50">
                    <i class="bi bi-x-lg"></i>
                    Cancel Selection
                </button>

                <button type="button"
                        x-show="hasPendingSelection"
                        x-transition.opacity.duration.150ms
                        @click="submitApproval()"
                        class="h-11 px-4 inline-flex items-center gap-2 rounded-xl bg-gray-900 text-[10px] font-black uppercase tracking-widest text-white transition-all hover:bg-emerald-600 disabled:cursor-not-allowed disabled:opacity-50"
                        :disabled="!hasPendingSelection">
                    <i class="bi bi-check2-all"></i>
                    Approve Selected
                </button>
            </div>
            
            {{-- Status Filter --}}
            <div class="relative group/filter">
                <button onclick="toggleDropdown('statusDropdown')" 
                    class="h-11 px-4 flex items-center gap-2 rounded-xl border border-gray-200 bg-white text-[10px] font-black uppercase tracking-widest text-gray-600 hover:border-emerald-500/30 hover:bg-gray-50 transition-all relative">
                    <i class="bi bi-funnel text-emerald-600"></i>
                    Status
                    <i class="bi bi-chevron-down text-[8px] opacity-50"></i>
                    @if(request('status'))
                        <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-emerald-500 rounded-full border-2 border-white"></span>
                    @endif
                </button>
                <div id="statusDropdown" class="hidden absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-50 py-2">
                    <div class="px-4 py-2 text-[10px] font-black text-gray-400 uppercase tracking-wider border-b border-gray-50 mb-1">Filter Status</div>
                    <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium transition-colors">All Statuses</a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium transition-colors">Pending</a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'paid']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium transition-colors">Paid</a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'unpaid']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium transition-colors">Unpaid</a>
                </div>
            </div>

            {{-- Type Filter --}}
            <div class="relative group/filter">
                <button onclick="toggleDropdown('typeDropdown')" 
                    class="h-11 px-4 flex items-center gap-2 rounded-xl border border-gray-200 bg-white text-[10px] font-black uppercase tracking-widest text-gray-600 hover:border-emerald-500/30 hover:bg-gray-50 transition-all relative">
                    <i class="bi bi-tag text-emerald-600"></i>
                    Type
                    <i class="bi bi-chevron-down text-[8px] opacity-50"></i>
                    @if(request('type'))
                        <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-emerald-500 rounded-full border-2 border-white"></span>
                    @endif
                </button>
                <div id="typeDropdown" class="hidden absolute right-0 top-full mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 z-50 py-2">
                    <div class="px-4 py-2 text-[10px] font-black text-gray-400 uppercase tracking-wider border-b border-gray-50 mb-1">Filter Violation Type</div>
                    <a href="{{ request()->fullUrlWithQuery(['type' => null]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium transition-colors">All Types</a>
                    <a href="{{ request()->fullUrlWithQuery(['type' => 'late_payment']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium transition-colors">Late Payment</a>
                    <a href="{{ request()->fullUrlWithQuery(['type' => 'overdue']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium transition-colors">Overdue</a>
                    <a href="{{ request()->fullUrlWithQuery(['type' => 'violation']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium transition-colors">Violation</a>
                    <a href="{{ request()->fullUrlWithQuery(['type' => 'damage']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium transition-colors">Damage</a>
                </div>
            </div>

            {{-- Clear Button --}}
            @if(request()->anyFilled(['search', 'status', 'type', 'block', 'lot']))
                <a href="{{ route('admin.penalties.index') }}" class="h-11 w-11 flex items-center justify-center rounded-xl border border-red-100 text-red-500 hover:bg-red-50 transition-all" title="Clear All Filters">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
        </div>
    </div>

    <div x-show="penaltySelectionMode && hasPendingSelection"
         x-transition.opacity.duration.200ms
         class="fixed bottom-6 left-1/2 z-40 w-[calc(100%-2rem)] max-w-xl -translate-x-1/2">
        <div class="rounded-2xl border border-emerald-100 bg-white/95 px-5 py-4 shadow-2xl shadow-emerald-500/10 backdrop-blur">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm font-black tracking-tight text-gray-900">
                    <span x-text="selectedCount"></span> selected
                </p>
                <div class="flex items-center gap-2">
                    <button type="button"
                            @click="clearSelection()"
                            class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-[10px] font-black uppercase tracking-widest text-gray-600 transition-all hover:border-gray-300 hover:bg-gray-50">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== --}}
    {{-- TABLE CONTAINER --}}
    {{-- ===================== --}}
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50/50 border-b border-gray-100">
                    <tr>
                        <th class="p-5 w-14 text-center">
                            <input type="checkbox"
                                   x-show="penaltySelectionMode && hasPendingRows"
                                   x-model="selectAll"
                                   @change="toggleSelectAll($event.target.checked)"
                                   class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500/20">
                        </th>
                        <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Resident</th>
                        <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Violation Type</th>
                        <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Reason</th>
                        <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Issued Date</th>
                        <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Amount</th>
                        <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                        <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($penalties as $penalty)
                    <tr class="hover:bg-emerald-50/30 transition-all duration-300 group border-l-4 border-transparent hover:border-emerald-500">
                        <td class="p-5 text-center">
                            @if($penalty->status === 'pending')
                                <input type="checkbox"
                                       x-show="penaltySelectionMode"
                                       value="{{ $penalty->id }}"
                                       x-model="selectedPenalties"
                                       @change="syncSelectAll()"
                                       class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500/20">
                            @endif
                        </td>
                        <td class="p-5">
                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-xs border border-emerald-100 shadow-sm">
                                    {{ strtoupper(substr($penalty->resident?->first_name ?? 'R', 0, 1)) }}{{ strtoupper(substr($penalty->resident?->last_name ?? 'S', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 group-hover:text-emerald-700 transition-colors">{{ $penalty->resident?->full_name ?? 'Unknown Resident' }}</p>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-0.5">B{{ $penalty->resident?->block ?? '-' }} / L{{ $penalty->resident?->lot ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-5">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-gray-100 bg-gray-50 text-gray-600 capitalize">
                                {{ str_replace('_', ' ', $penalty->type) }}
                            </span>
                        </td>
                        <td class="p-5">
                            <span class="text-sm font-medium text-gray-600 truncate max-w-[200px] block" title="{{ $penalty->reason }}">
                                {{ $penalty->reason }}
                            </span>
                        </td>
                        <td class="p-5 text-center">
                            <span class="text-sm font-bold text-gray-500">{{ $penalty->created_at->format('M d, Y') }}</span>
                        </td>
                        <td class="p-5 text-right">
                            <span class="text-base font-black text-gray-900">₱{{ number_format($penalty->amount, 2) }}</span>
                        </td>
                        <td class="p-5 text-center">
                            @php
                                $statusColors = [
                                    'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                    'pending' => 'bg-amber-50 text-amber-700 border-amber-100',
                                    'unpaid' => 'bg-red-50 text-red-700 border-red-100'
                                ];
                                $statusDots = [
                                    'paid' => 'bg-emerald-500',
                                    'pending' => 'bg-amber-500',
                                    'unpaid' => 'bg-red-500'
                                ];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $statusColors[$penalty->status] ?? 'bg-gray-50 text-gray-600 border-gray-100' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $statusDots[$penalty->status] ?? 'bg-gray-400' }}"></span>
                                {{ $penalty->status }}
                            </span>
                        </td>
                        <td class="p-5 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.penalties.edit', $penalty->id) }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-900 text-white hover:bg-emerald-600 transition-all shadow-sm" title="Edit Penalty">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.penalties.destroy', $penalty->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this penalty record?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-xl border border-gray-200 text-gray-400 hover:text-red-600 hover:border-red-600 transition-all bg-white" title="Delete Record">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-20 text-center">
                            <div class="w-20 h-20 rounded-3xl bg-gray-50 flex items-center justify-center mx-auto mb-6 text-gray-200">
                                <i class="bi bi-exclamation-octagon text-4xl"></i>
                            </div>
                            <p class="text-gray-400 text-sm font-medium">No penalty records found matching your criteria.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($penalties->hasPages())
    <div class="mt-8">
        {{ $penalties->links() }}
    </div>
    @endif

</div>

<form id="bulkPenaltyApproveForm" action="{{ route('admin.penalties.bulkApprove') }}" method="POST" class="hidden">
    @csrf
    <div id="bulkPenaltyIds"></div>
</form>

<script>
    function penaltiesBulkApproval() {
        return {
            penaltySelectionMode: false,
            selectedPenalties: [],
            pendingIds: @json($penalties->getCollection()->filter(fn ($penalty) => $penalty->status === 'pending')->pluck('id')->values()),
            selectAll: false,
            get hasPendingRows() {
                return this.pendingIds.length > 0;
            },
            get selectedCount() {
                return this.selectedPenalties.length;
            },
            get hasPendingSelection() {
                return this.selectedCount > 0;
            },
            enableSelectionMode() {
                this.penaltySelectionMode = true;
            },
            toggleSelectAll(checked) {
                this.selectedPenalties = checked ? [...this.pendingIds] : [];
            },
            syncSelectAll() {
                this.selectAll = this.pendingIds.length > 0 && this.selectedPenalties.length === this.pendingIds.length;
            },
            clearSelection() {
                this.selectedPenalties = [];
                this.selectAll = false;
                this.penaltySelectionMode = false;
            },
            submitApproval() {
                if (!this.hasPendingSelection) return;

                const confirmed = confirm(`Approve ${this.selectedCount} selected penalt${this.selectedCount > 1 ? 'ies' : 'y'}?`);
                if (!confirmed) return;

                const idsContainer = document.getElementById('bulkPenaltyIds');
                idsContainer.innerHTML = '';

                this.selectedPenalties.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = id;
                    idsContainer.appendChild(input);
                });

                document.getElementById('bulkPenaltyApproveForm').submit();
            }
        }
    }

    function toggleDropdown(id) {
        const dropdown = document.getElementById(id);
        const allDropdowns = ['statusDropdown', 'typeDropdown', 'blockLotDropdown'];
        allDropdowns.forEach(d => {
            if (d !== id) document.getElementById(d)?.classList.add('hidden');
        });
        dropdown.classList.toggle('hidden');
    }

    // Close dropdowns on click outside
    window.onclick = function(event) {
        if (!event.target.closest('.group/filter')) {
            document.getElementById('statusDropdown')?.classList.add('hidden');
            document.getElementById('typeDropdown')?.classList.add('hidden');
            document.getElementById('blockLotDropdown')?.classList.add('hidden');
        }
    }
</script>
@endsection
