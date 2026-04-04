@extends('layouts.admin')

@section('title', 'Resident Details')
@section('page-title', 'Resident Details')

@section('content')
<div class="space-y-8 animate-fade-in pb-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- ===================== --}}
    {{-- PROFILE SECTION --}}
    {{-- ===================== --}}
    <div class="bg-white rounded-[32px] shadow-sm border border-gray-100 overflow-hidden transition-all duration-500 hover:shadow-xl hover:shadow-emerald-500/5">
        <div class="p-6 space-y-6">
            {{-- Profile Header --}}
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-black text-gray-900 tracking-tight flex items-center gap-3">
                    Profile
                </h3>
                <div class="flex items-center gap-3">
                    @if($resident->hasAccount())
                        <div class="flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest rounded-full border border-emerald-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Registered
                        </div>
                    @else
                        <button onclick="generateInvite({{ $resident->id }}, '{{ $resident->email }}')"
                           class="px-4 py-2 bg-amber-50 text-amber-600 text-[10px] font-black uppercase tracking-widest rounded-full border border-amber-100 hover:bg-amber-100 transition-all flex items-center gap-2">
                           <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                           Pending Invite
                        </button>
                    @endif
                    <a href="{{ route('admin.residents.edit', $resident->id) }}" 
                       class="px-6 py-2.5 bg-[#0D1F1C] text-[#B6FF5C] rounded-xl font-black uppercase tracking-widest text-[10px] hover:bg-[#1a2e2a] transition-all active:scale-95 flex items-center gap-2 shadow-lg shadow-emerald-500/10">
                        <i class="bi bi-pencil-fill"></i>
                        Edit
                    </a>
                </div>
            </div>

            {{-- Profile Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                {{-- Left: Photo & Status --}}
                <div class="lg:col-span-2 flex flex-col items-center gap-4">
                    <div class="relative group">
                        <div class="absolute inset-0 bg-emerald-500/20 rounded-[32px] blur-2xl opacity-0 group-hover:opacity-100 transition-all duration-700"></div>
                        <img src="{{ $resident->photo ? asset('storage/' . $resident->photo) : asset('CDlogo.jpg') }}" 
                             onerror="this.onerror=null; this.src='{{ asset('CDlogo.jpg') }}';"
                             class="w-28 h-28 rounded-[32px] object-cover border-4 border-white shadow-2xl relative z-10 transition-transform duration-500 group-hover:scale-105">
                    </div>
                    <span class="px-4 py-1.5 rounded-full bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase tracking-widest border border-emerald-100 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        {{ strtoupper($resident->status) }}
                    </span>
                </div>

                {{-- Right: Detailed Info Grid --}}
                <div class="lg:col-span-10 grid grid-cols-1 md:grid-cols-3 gap-3">
                    {{-- Full Name --}}
                    <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-100 group hover:bg-white hover:border-emerald-200 transition-all">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-slate-500 group-hover:text-[#B6FF5C] group-hover:bg-[#0D1F1C] shadow-sm transition-colors">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-600 uppercase tracking-widest mb-0.5">Full Name</p>
                                <p class="text-sm font-black text-gray-900 tracking-tight">{{ $resident->full_name }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Contact --}}
                    <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-100 group hover:bg-white hover:border-emerald-200 transition-all">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-slate-500 group-hover:text-[#B6FF5C] group-hover:bg-[#0D1F1C] shadow-sm transition-colors">
                                <i class="bi bi-telephone-fill"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-600 uppercase tracking-widest mb-0.5">Contact</p>
                                <p class="text-sm font-black text-gray-900 tracking-tight">{{ $resident->contact_number ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-100 group hover:bg-white hover:border-emerald-200 transition-all">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-slate-500 group-hover:text-[#B6FF5C] group-hover:bg-[#0D1F1C] shadow-sm transition-colors">
                                <i class="bi bi-envelope-fill"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[9px] font-black text-slate-600 uppercase tracking-widest mb-0.5">Email</p>
                                <p class="text-sm font-black text-gray-900 tracking-tight truncate">{{ $resident->email }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Block / Lot --}}
                    <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-100 group hover:bg-white hover:border-emerald-200 transition-all">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-slate-500 group-hover:text-[#B6FF5C] group-hover:bg-[#0D1F1C] shadow-sm transition-colors">
                                <i class="bi bi-house-door-fill"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-600 uppercase tracking-widest mb-0.5">Block / Lot</p>
                                <p class="text-sm font-black text-gray-900 tracking-tight">{{ $resident->block }} / {{ $resident->lot }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Move-in Date --}}
                    <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-100 group hover:bg-white hover:border-emerald-200 transition-all">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-slate-500 group-hover:text-[#B6FF5C] group-hover:bg-[#0D1F1C] shadow-sm transition-colors">
                                <i class="bi bi-calendar-event-fill"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-600 uppercase tracking-widest mb-0.5">Move-in</p>
                                <p class="text-sm font-black text-gray-900 tracking-tight">{{ $resident->move_in_date ? $resident->move_in_date->format('M d, Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== --}}
    {{-- FINANCIAL OVERVIEW --}}
    {{-- ===================== --}}
    <div class="bg-white rounded-[32px] shadow-sm border border-gray-100 overflow-hidden transition-all duration-500 hover:shadow-xl hover:shadow-emerald-500/5">
        <div class="p-6">
            <h3 class="text-lg font-black text-gray-900 tracking-tight mb-6">Financial Overview</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Total Dues --}}
                <div class="text-center space-y-2 group">
                    <div class="w-10 h-10 rounded-2xl bg-gray-50 flex items-center justify-center text-slate-500 mx-auto group-hover:bg-[#0D1F1C] group-hover:text-[#B6FF5C] transition-all shadow-sm">
                        <i class="bi bi-journal-text text-lg"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-600 uppercase tracking-[0.2em] mb-0.5">Total Dues</p>
                        <h4 class="text-xl font-black text-gray-900">₱{{ number_format($financials['outstandingDues'] ?? 0, 2) }}</h4>
                    </div>
                </div>

                {{-- Payments --}}
                <div class="text-center space-y-2 group">
                    <div class="w-10 h-10 rounded-2xl bg-gray-50 flex items-center justify-center text-slate-500 mx-auto group-hover:bg-[#0D1F1C] group-hover:text-[#B6FF5C] transition-all shadow-sm">
                        <i class="bi bi-credit-card-fill text-lg"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-600 uppercase tracking-[0.2em] mb-0.5">Payments</p>
                        <h4 class="text-xl font-black text-gray-900">₱{{ number_format($financials['totalPayments'] ?? 0, 2) }}</h4>
                    </div>
                </div>

                {{-- Penalties --}}
                <div class="text-center space-y-2 group">
                    <div class="w-10 h-10 rounded-2xl bg-gray-50 flex items-center justify-center text-slate-500 mx-auto group-hover:bg-[#0D1F1C] group-hover:text-[#B6FF5C] transition-all shadow-sm">
                        <i class="bi bi-exclamation-triangle-fill text-lg"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-600 uppercase tracking-[0.2em] mb-0.5">Penalties</p>
                        <h4 class="text-xl font-black text-gray-900">₱{{ number_format($financials['totalPenalties'] ?? 0, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== --}}
    {{-- TABS & DATA TABLE --}}
    {{-- ===================== --}}
    <div class="bg-white rounded-[32px] shadow-sm border border-gray-100 overflow-hidden transition-all duration-500 hover:shadow-xl hover:shadow-emerald-500/5 min-h-[500px] flex flex-col">
        {{-- Custom Tabs --}}
        <div class="flex items-center px-8 border-b border-gray-100 bg-gray-50/30">
            @foreach(['dues' => 'Dues', 'payments' => 'Payments', 'penalties' => 'Penalties', 'notes' => 'Notes'] as $key => $label)
                <button onclick="showTab('{{ $key }}')" 
                        class="tab-btn px-8 py-6 text-[11px] font-black uppercase tracking-widest relative transition-all group {{ $loop->first ? 'active text-gray-900' : 'text-gray-400 hover:text-gray-600' }}"
                        data-tab="{{ $key }}">
                    {{ $label }}
                    <div class="absolute bottom-0 left-8 right-8 h-1 bg-[#B6FF5C] rounded-t-full transition-all duration-300 {{ $loop->first ? 'scale-x-100' : 'scale-x-0' }} active-indicator"></div>
                </button>
            @endforeach
        </div>

        {{-- Tab Content --}}
        <div class="p-8 flex-1">
            <div id="tab-dues" class="tab-content animate-fade-in">
                @include('admin.residents.partials.dues-table', ['dues' => $resident->dues ?? []])
            </div>

            <div id="tab-payments" class="tab-content hidden animate-fade-in">
                @include('admin.residents.partials.payments-table', ['payments' => $resident->payments ?? []])
            </div>

            <div id="tab-penalties" class="tab-content hidden animate-fade-in">
                @include('admin.residents.partials.penalties-table', ['penalties' => $resident->penalties ?? []])
            </div>

            <div id="tab-notes" class="tab-content hidden animate-fade-in">
                <div class="max-w-3xl space-y-6">
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-3 block">Administrative Notes</label>
                        <textarea
                            id="resident-notes"
                            class="w-full border border-gray-100 bg-gray-50 rounded-[24px] p-8 text-sm font-medium focus:bg-white focus:ring-8 focus:ring-[#B6FF5C]/10 focus:border-[#B6FF5C] transition-all outline-none resize-none shadow-inner min-h-[200px]"
                            placeholder="Admin remarks about this resident...">{{ $resident->notes ?? '' }}</textarea>
                    </div>
                    <div class="flex justify-end">
                        <button onclick="saveNotes({{ $resident->id }})" class="px-8 py-4 bg-[#0D1F1C] text-[#B6FF5C] rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-[#1a2e2a] transition-all active:scale-95 flex items-center gap-2 shadow-lg shadow-emerald-500/10">
                            <i class="bi bi-check2-circle"></i>
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPTS --}}
<script>
function showTab(tabId) {
    // Hide all contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Reset all buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active', 'text-gray-900');
        btn.classList.add('text-gray-400');
        btn.querySelector('.active-indicator').classList.remove('scale-x-100');
        btn.querySelector('.active-indicator').classList.add('scale-x-0');
    });

    // Show active content
    document.getElementById('tab-' + tabId).classList.remove('hidden');
    
    // Highlight active button
    const activeBtn = document.querySelector(`[data-tab="${tabId}"]`);
    activeBtn.classList.add('active', 'text-gray-900');
    activeBtn.classList.remove('text-gray-400');
    activeBtn.querySelector('.active-indicator').classList.add('scale-x-100');
    activeBtn.querySelector('.active-indicator').classList.remove('scale-x-0');
}

function saveNotes(id) {
    const notes = document.getElementById('resident-notes').value;
    
    // Using fetch to save notes via AJAX
    fetch(`/admin/residents/${id}/update-notes`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ notes: notes })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Notes saved successfully!');
        } else {
            alert('Error saving notes.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An unexpected error occurred.');
    });
}
</script>

<style>
.animate-fade-in {
    animation: fadeIn 0.5s ease-out forwards;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endsection

