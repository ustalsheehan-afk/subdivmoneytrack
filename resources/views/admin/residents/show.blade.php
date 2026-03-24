@extends('layouts.admin')

@section('title', 'Resident Details')
@section('page-title', 'Resident Details')

@section('content')
<div class="space-y-8 animate-fade-in pb-20">

    {{-- ===================== --}}
    {{-- HEADER SECTION --}}
    {{-- ===================== --}}
    <div class="glass-card p-8 relative overflow-hidden group">
        {{-- Subtle gradient glow in background --}}
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-brand-accent/5 rounded-full blur-3xl group-hover:bg-brand-accent/10 transition-all duration-700"></div>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div class="flex items-center gap-6">
                <a href="{{ route('admin.residents.index') }}" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white border border-gray-100 text-gray-400 hover:text-emerald-600 hover:border-emerald-100 hover:shadow-sm transition-all shadow-sm">
                    <i class="bi bi-arrow-left text-xl"></i>
                </a>
                <div class="flex items-center gap-5">
                    <div class="relative shrink-0">
                        <img src="{{ $resident->photo ? asset('storage/' . $resident->photo) : asset('CDlogo.jpg') }}" 
                             onerror="this.onerror=null; this.src='{{ asset('CDlogo.jpg') }}';"
                             class="w-20 h-20 rounded-[24px] object-cover border-4 border-white shadow-2xl group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full border-2 border-white flex items-center justify-center shadow-lg
                            {{ $resident->status === 'active' ? 'bg-emerald-500' : 'bg-red-500' }}">
                            <i class="bi {{ $resident->status === 'active' ? 'bi-check-lg' : 'bi-x-lg' }} text-[10px] text-white"></i>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                            {{ $resident->full_name }}
                        </h1>
                        <p class="mt-2 text-gray-600 text-lg flex items-center gap-2">
                            <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase tracking-widest border border-emerald-100">
                                Resident
                            </span>
                            <span class="text-gray-400">•</span>
                            <span class="font-bold text-gray-500 uppercase tracking-widest text-xs">Blk {{ $resident->block }} / Lot {{ $resident->lot }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                @if(!$resident->hasAccount())
                    <button onclick="generateInvite({{ $resident->id }}, '{{ $resident->email }}')"
                       class="btn-premium">
                       <i class="bi bi-envelope-plus-fill"></i> Invite Resident
                    </button>
                @else
                    <div class="flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest rounded-xl border border-emerald-100">
                        <i class="bi bi-shield-check"></i> Account Active
                    </div>
                @endif

                <a href="{{ route('admin.residents.edit', $resident->id) }}" class="btn-secondary">
                    <i class="bi bi-pencil"></i> Edit Profile
                </a>
            </div>
        </div>
    </div>

    {{-- ===================== --}}
    {{-- STATS & INFO CARDS --}}
    {{-- ===================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        {{-- PROFILE DETAILS (Left Column) --}}
        <div class="lg:col-span-1 space-y-8">
            <div class="glass-card p-8 space-y-8">
                <div class="flex items-center gap-3 pb-4 border-b border-gray-50">
                    <i class="bi bi-person-badge text-emerald-500"></i>
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Profile Details</h3>
                </div>

                <div class="space-y-6">
                    {{-- Contact --}}
                    <div class="space-y-1.5">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Contact Number</p>
                        <p class="text-sm font-bold text-gray-900">{{ $resident->contact_number ?? 'Not provided' }}</p>
                    </div>

                    {{-- Email --}}
                    <div class="space-y-1.5">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Email Address</p>
                        <p class="text-sm font-bold text-gray-900">{{ $resident->email ?? 'Not provided' }}</p>
                    </div>

                    {{-- Move In --}}
                    <div class="space-y-1.5">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Move-in Date</p>
                        <p class="text-sm font-bold text-gray-900">{{ $resident->move_in_date ? $resident->move_in_date->format('M d, Y') : '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- ACCOUNT STATUS --}}
            <div class="glass-card bg-gray-900 p-8 relative overflow-hidden group border-none">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl"></div>
                <div class="relative z-10 space-y-4">
                    <p class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Account Health</p>
                    <div class="flex items-center justify-between">
                        <span class="text-white text-lg font-black tracking-tight uppercase">{{ $resident->status }}</span>
                        <div class="w-3 h-3 rounded-full {{ $resident->status === 'active' ? 'bg-emerald-500' : 'bg-red-500' }} animate-pulse shadow-[0_0_15px_rgba(16,185,129,0.5)]"></div>
                    </div>
                    <p class="text-[10px] font-medium text-gray-400 leading-relaxed">
                        Resident is currently in good standing with the subdivision association.
                    </p>
                </div>
            </div>
        </div>

        {{-- FINANCIAL OVERVIEW (Right Column) --}}
        <div class="lg:col-span-3 space-y-8">
            {{-- FINANCIAL STATS ROW --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Outstanding Dues --}}
                <div class="glass-card p-8 flex items-center gap-6 group hover:shadow-xl transition-all duration-300">
                    <div class="w-16 h-16 rounded-[24px] bg-red-50 flex items-center justify-center text-red-500 group-hover:scale-110 transition-all duration-500 border border-red-100/50 shadow-sm">
                        <i class="bi bi-cash-stack text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Outstanding Dues</p>
                        <h3 class="text-3xl font-black text-gray-900 tracking-tight tabular-nums">₱{{ number_format($financials['outstandingDues'] ?? 0, 0) }}</h3>
                    </div>
                </div>
                
                {{-- Total Payments --}}
                <div class="glass-card p-8 flex items-center gap-6 group hover:shadow-xl transition-all duration-300">
                    <div class="w-16 h-16 rounded-[24px] bg-emerald-50 flex items-center justify-center text-emerald-500 group-hover:scale-110 transition-all duration-500 border border-emerald-100/50 shadow-sm">
                        <i class="bi bi-credit-card-fill text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.2em] mb-1">Total Paid</p>
                        <h3 class="text-3xl font-black text-gray-900 tracking-tight tabular-nums">₱{{ number_format($financials['totalPayments'] ?? 0, 0) }}</h3>
                    </div>
                </div>

                {{-- Penalties --}}
                <div class="glass-card p-8 flex items-center gap-6 group hover:shadow-xl transition-all duration-300">
                    <div class="w-16 h-16 rounded-[24px] bg-amber-50 flex items-center justify-center text-amber-500 group-hover:scale-110 transition-all duration-500 border border-amber-100/50 shadow-sm">
                        <i class="bi bi-exclamation-circle-fill text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Total Penalties</p>
                        <h3 class="text-3xl font-black text-gray-900 tracking-tight tabular-nums">₱{{ number_format($financials['totalPenalties'] ?? 0, 0) }}</h3>
                    </div>
                </div>
            </div>

            {{-- TABS SECTION --}}
            <div class="glass-card overflow-hidden flex flex-col min-h-[500px]">
                <div class="flex border-b border-gray-100 bg-gray-50/30">
                    @foreach(['dues','payments','penalties','notes'] as $tab)
                        <button
                            onclick="showTab('{{ $tab }}')"
                            class="tab-btn px-10 py-5 text-[10px] font-black uppercase tracking-[0.2em] transition-all relative group {{ $loop->first ? 'active' : 'text-gray-400 hover:text-gray-600' }}"
                            data-tab="{{ $tab }}">
                            {{ $tab }}
                            <div class="absolute bottom-0 left-0 right-0 h-1 bg-emerald-500 scale-x-0 group-hover:scale-x-50 transition-transform duration-300 {{ $loop->first ? 'active-indicator' : '' }}"></div>
                        </button>
                    @endforeach
                </div>

                <div class="p-8 flex-1">
                    <div id="tab-dues" class="tab-content">
                        @include('admin.residents.partials.dues-table', ['dues' => $resident->dues ?? []])
                    </div>

                    <div id="tab-payments" class="tab-content hidden">
                        @include('admin.residents.partials.payments-table', ['payments' => $resident->payments ?? []])
                    </div>

                    <div id="tab-penalties" class="tab-content hidden">
                        @include('admin.residents.partials.penalties-table', ['penalties' => $resident->penalties ?? []])
                    </div>

                    <div id="tab-notes" class="tab-content hidden">
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Administrative Notes</label>
                            <textarea
                                class="w-full border border-gray-200 bg-gray-50 rounded-[32px] p-8 text-sm font-medium focus:bg-white focus:ring-8 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all outline-none resize-none shadow-inner"
                                rows="6"
                                placeholder="Admin remarks about this resident...">{{ $resident->notes ?? '' }}</textarea>
                            <div class="flex justify-end">
                                <button class="btn-premium">
                                    <i class="bi bi-check2-circle"></i>
                                    Save Notes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- TAB SCRIPT --}}
<script>
function showTab(tab) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(el => {
        el.classList.remove('active', 'text-gray-900');
        el.classList.add('text-gray-400');
        const indicator = el.querySelector('.active-indicator');
        if(indicator) indicator.classList.remove('active-indicator');
    });
    
    document.getElementById('tab-' + tab).classList.remove('hidden');
    const activeBtn = document.querySelector(`[data-tab="${tab}"]`);
    activeBtn.classList.add('active', 'text-gray-900');
    activeBtn.classList.remove('text-gray-400');
}
</script>

<style>
.tab-btn.active { color: #111827; }
.tab-btn.active .active-indicator { transform: scaleX(1); }
.active-indicator { transform: scaleX(1); background-color: #10B981; }
</style>

</div>

{{-- TAB SCRIPT --}}
<script>
function showTab(tab) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.remove('hidden');
    document.querySelector(`[data-tab="${tab}"]`).classList.add('active');
}
</script>

{{-- TAB STYLES --}}
<style>
.tab-btn {
    color: #1C2833;
    border-bottom: 2px solid transparent;
    transition: all 0.25s ease;
}
.tab-btn.active {
    border-bottom-color: #800020;
    font-weight: 600;
}
</style>
@endsection
