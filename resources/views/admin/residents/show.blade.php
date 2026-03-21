@extends('layouts.admin')

@section('title', 'Resident Details')
@section('page-title', 'Resident Details')

@section('content')
<div class="max-w-7xl mx-auto space-y-5">

    {{-- ========================= --}}
    {{-- PROFILE SECTION --}}
    {{-- ========================= --}}
    <div class="bg-white rounded-3xl border border-gray-200 shadow-lg">

        {{-- Header --}}
        <div class="px-5 py-3 border-b flex justify-between items-center">
            <h2 class="text-base font-bold text-gray-900">Profile</h2>
            
            <div class="flex gap-2">
                {{-- INVITE BUTTON --}}
                @if(!$resident->hasAccount())
                    <button onclick="generateInvite({{ $resident->id }}, '{{ $resident->email }}')"
                       class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-1.5 rounded-xl shadow-sm transition flex items-center gap-2">
                       <i class="bi bi-envelope-plus-fill"></i> Invite Resident
                    </button>
                @else
                    <span class="bg-emerald-50 text-emerald-700 border border-emerald-100 text-xs px-3 py-1.5 rounded-xl font-bold flex items-center gap-2">
                        <i class="bi bi-check-circle-fill"></i> Account Active
                    </span>
                @endif

                {{-- EDIT BUTTON --}}
                <a href="{{ route('admin.residents.edit', $resident->id) }}"
                   class="bg-[#800020] hover:bg-[#9a002e] text-white text-sm px-4 py-1.5 rounded-xl shadow-sm transition">
                   <i class="bi bi-pencil-fill mr-1"></i> Edit
                </a>
            </div>
        </div>

        {{-- Body --}}
        <div class="p-5 flex flex-col md:flex-row gap-6 items-center">

            {{-- PHOTO & STATUS --}}
            <div class="flex flex-col items-center flex-shrink-0">
                <img
                    src="{{ $resident->photo ? asset('storage/' . $resident->photo) : asset('CDlogo.jpg') }}"
                    onerror="this.onerror=null; this.src='{{ asset('CDlogo.jpg') }}';"
                    class="w-32 h-32 rounded-2xl object-cover border border-gray-300 shadow-sm"
                >

                <span class="mt-2 inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold border capitalize tracking-wide
                    {{ $resident->status === 'active' 
                        ? 'bg-emerald-50 text-emerald-700 border-emerald-100' 
                        : 'bg-red-50 text-red-700 border-red-100' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $resident->status === 'active' ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                    {{ $resident->status }}
                </span>
            </div>

            {{-- INFO CARDS --}}
            <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @php
                    $infoCards = [
                        ['icon' => 'bi-person-fill', 'label' => 'Full Name', 'value' => $resident->first_name . ' ' . $resident->last_name],
                      ['icon' => 'bi-telephone-fill', 'label' => 'Contact', 'value' => $resident->contact_number],
                        ['icon' => 'bi-envelope-fill', 'label' => 'Email', 'value' => $resident->email],
                        ['icon' => 'bi-house-fill', 'label' => 'Block / Lot', 'value' => ($resident->block ?? '-') . ' / ' . ($resident->lot ?? '-')],
                        ['icon' => 'bi-calendar-check-fill', 'label' => 'Move-in', 'value' => $resident->move_in_date ? $resident->move_in_date->format('M d, Y') : '-'],
                    ];
                @endphp

                @foreach($infoCards as $card)
                <div
                    class="bg-white/70 border border-gray-200/70 rounded-2xl p-4 flex items-center gap-3
                           shadow-[0_1px_2px_rgba(0,0,0,0.04)]
                           hover:shadow-[0_3px_8px_rgba(0,0,0,0.06)]
                           transition-all">

                    <i class="bi {{ $card['icon'] }} text-2xl text-[#777777]"></i>

                    <div class="space-y-0.5">
                        {{-- LABEL --}}
                        <p class="text-[12px] font-medium text-[#800020]/80">
                            {{ $card['label'] }}
                        </p>

                        {{-- VALUE --}}
                        <p class="text-[14px] font-bold text-[#1C2833]">
                            {{ $card['value'] }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>

    {{-- ========================= --}}
    {{-- FINANCIAL OVERVIEW --}}
    {{-- ========================= --}}
    <div class="bg-white rounded-3xl border border-gray-200 shadow-lg">

        {{-- Header --}}
        <div class="px-5 py-3 border-b">
            <h2 class="text-base font-bold text-gray-900">Financial Overview</h2>
        </div>

        {{-- Body --}}
        <div class="p-4 flex flex-col sm:flex-row justify-around items-center gap-4">

            {{-- TOTAL DUES --}}
            <div class="flex flex-col items-center px-4 py-2">
                <i class="bi bi-cash-stack text-xl text-[#777777] mb-1"></i>
                <p class="text-[12px] font-medium text-[#800020]/80">Total Dues</p>
                <p class="text-[14px] font-bold text-[#1C2833]">
                    ₱ {{ number_format($financials['outstandingDues'] ?? 0, 2) }}
                </p>
            </div>

            {{-- PAYMENTS --}}
            <div class="flex flex-col items-center px-4 py-2">
                <i class="bi bi-credit-card-fill text-xl text-[#777777] mb-1"></i>
                <p class="text-[12px] font-medium text-[#800020]/80">Payments</p>
                <p class="text-[14px] font-bold text-[#1C2833]">
                    ₱ {{ number_format($financials['totalPayments'] ?? 0, 2) }}
                </p>
            </div>

            {{-- PENALTIES --}}
            <div class="flex flex-col items-center px-4 py-2">
                <i class="bi bi-exclamation-circle-fill text-xl text-[#777777] mb-1"></i>
                <p class="text-[12px] font-medium text-[#800020]/80">Penalties</p>
                <p class="text-[14px] font-bold text-[#1C2833]">
                    ₱ {{ number_format($financials['totalPenalties'] ?? 0, 2) }}
                </p>
            </div>

        </div>
    </div>

    {{-- ========================= --}}
    {{-- TABS --}}
    {{-- ========================= --}}
    <div class="bg-white rounded-3xl border shadow-lg">

        <div class="border-b flex">
            @foreach(['dues','payments','penalties','notes'] as $tab)
                <button
                    onclick="showTab('{{ $tab }}')"
                    class="tab-btn px-5 py-3 capitalize {{ $loop->first ? 'active' : '' }}"
                    data-tab="{{ $tab }}">
                    {{ ucfirst($tab) }}
                </button>
            @endforeach
        </div>

        <div class="p-5">
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
                <textarea
                    class="w-full border border-gray-300 rounded-2xl p-4 text-sm focus:ring-2 focus:ring-gray-500 resize-none"
                    rows="4"
                    placeholder="Admin remarks about this resident...">{{ $resident->notes ?? '' }}</textarea>
            </div>
        </div>
    </div>

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
