@extends('layouts.admin')

@section('title', 'Create Billing Statement')
@section('page-title', 'Create Billing')

@section('content')
<div class="max-w-6xl mx-auto space-y-8 pb-20">
    {{-- TOP BAR --}}
    <div class="flex items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dues.dashboard') }}" class="w-12 h-12 flex items-center justify-center rounded-2xl border border-gray-100 text-gray-400 hover:text-blue-600 hover:border-blue-100 hover:bg-blue-50 transition-all">
                <i class="bi bi-arrow-left text-lg"></i>
            </a>
            <div>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight">Create Billing Statement</h3>
                <p class="text-xs font-medium text-gray-500 flex items-center gap-2">
                    <i class="bi bi-info-circle text-blue-500"></i>
                    Fields marked with <span class="text-red-500 font-bold">*</span> are required
                </p>
            </div>
        </div>
    </div>

    <form id="billing-form" action="{{ route('admin.dues.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- FORM SIDE --}}
            <div class="lg:col-span-2 space-y-8">
                
                {{-- 1. Billing Details --}}
                <section class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm space-y-8 relative overflow-hidden">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg">1</div>
                        <h4 class="text-lg font-black text-gray-900">Billing Details</h4>
                    </div>

                    <div class="grid grid-cols-1 gap-8">
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-[0.15em]">Billing Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title" oninput="updatePreview()" 
                                class="w-full px-5 py-4 rounded-2xl border border-gray-100 bg-gray-50/50 text-sm font-medium focus:bg-white focus:border-blue-500 focus:ring-8 focus:ring-blue-500/5 transition-all outline-none" 
                                placeholder="e.g. March 2026 Monthly HOA" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-[11px] font-bold text-gray-400 uppercase tracking-[0.15em]">Billing Type <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <select name="type" id="type" onchange="updatePreview()" 
                                        class="w-full px-5 py-4 rounded-2xl border border-gray-100 bg-gray-50/50 text-sm font-medium appearance-none focus:bg-white focus:border-blue-500 transition-all outline-none" required>
                                        <option value="" disabled selected>Select Type</option>
                                        <option value="monthly_hoa">Monthly HOA Fees</option>
                                        <option value="special_assessment">Special Assessment</option>
                                        <option value="regular_fees">Regular Service Fees</option>
                                        <option value="amenity_dues">Amenity Dues</option>
                                    </select>
                                    <i class="bi bi-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[11px] font-bold text-gray-400 uppercase tracking-[0.15em]">Frequency <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <select name="frequency" id="frequency" 
                                        class="w-full px-5 py-4 rounded-2xl border border-gray-100 bg-gray-50/50 text-sm font-medium appearance-none focus:bg-white focus:border-blue-500 transition-all outline-none" required>
                                        <option value="one_time">One-time</option>
                                        <option value="monthly">Monthly</option>
                                        <option value="quarterly">Quarterly</option>
                                    </select>
                                    <i class="bi bi-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-[11px] font-bold text-gray-400 uppercase tracking-[0.15em]">Billing Start Date <span class="text-red-500">*</span></label>
                                <input type="date" name="billing_period_start" id="billing_period_start" onchange="updatePreview()" 
                                    class="w-full px-5 py-4 rounded-2xl border border-gray-100 bg-gray-50/50 text-sm font-medium focus:bg-white focus:border-blue-500 transition-all outline-none" required>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[11px] font-bold text-gray-400 uppercase tracking-[0.15em]">Due Date <span class="text-red-500">*</span></label>
                                <input type="date" name="due_date" id="due_date" onchange="updatePreview()" 
                                    class="w-full px-5 py-4 rounded-2xl border border-gray-100 bg-gray-50/50 text-sm font-medium focus:bg-white focus:border-blue-500 transition-all outline-none" required>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- 2. Billing Amount --}}
                <section class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm space-y-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">2</div>
                            <h4 class="text-lg font-black text-gray-900">Billing Amount <span class="text-red-500">*</span></h4>
                        </div>
                        <div class="flex bg-gray-50 p-1.5 rounded-2xl border border-gray-100">
                            <button type="button" onclick="toggleAmountType('standard')" id="btn-standard" class="px-6 py-2 rounded-xl text-xs font-bold transition-all bg-white shadow-sm text-blue-600">Standard Rates</button>
                            <button type="button" onclick="toggleAmountType('custom')" id="btn-custom" class="px-6 py-2 rounded-xl text-xs font-bold transition-all text-gray-400 hover:text-gray-600">Custom Amount</button>
                            <input type="hidden" name="amount_type" id="amount_type" value="standard">
                            <input type="hidden" name="apply_to" value="selected">
                        </div>
                    </div>

                    <div id="standard-rates-section" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="rate_choice" value="500" class="hidden peer" onchange="setAmount(500)">
                            <div class="p-5 rounded-2xl border-2 border-gray-50 bg-gray-50/50 peer-checked:border-blue-500 peer-checked:bg-blue-50/30 transition-all">
                                <div class="text-[10px] font-bold text-gray-400 uppercase mb-1">Regular</div>
                                <div class="text-xl font-black text-gray-900">₱500.00</div>
                            </div>
                        </label>
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="rate_choice" value="750" class="hidden peer" onchange="setAmount(750)">
                            <div class="p-5 rounded-2xl border-2 border-gray-50 bg-gray-50/50 peer-checked:border-blue-500 peer-checked:bg-blue-50/30 transition-all">
                                <div class="text-[10px] font-bold text-gray-400 uppercase mb-1">Premium</div>
                                <div class="text-xl font-black text-gray-900">₱750.00</div>
                            </div>
                        </label>
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="rate_choice" value="1000" class="hidden peer" onchange="setAmount(1000)">
                            <div class="p-5 rounded-2xl border-2 border-gray-50 bg-gray-50/50 peer-checked:border-blue-500 peer-checked:bg-blue-50/30 transition-all">
                                <div class="text-[10px] font-bold text-gray-400 uppercase mb-1">Commercial</div>
                                <div class="text-xl font-black text-gray-900">₱1,000.00</div>
                            </div>
                        </label>
                    </div>

                    <div id="custom-amount-section" class="hidden animate__animated animate__fadeIn">
                        <div class="relative">
                            <span class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 font-black text-lg">₱</span>
                            <input type="number" name="amount" id="amount_input" oninput="updatePreview()" 
                                class="w-full pl-12 pr-6 py-5 rounded-2xl border border-gray-100 bg-gray-50/50 text-xl font-black focus:bg-white focus:border-blue-500 transition-all outline-none" 
                                placeholder="0.00" step="0.01" min="0.01">
                        </div>
                    </div>
                </section>

                {{-- 3. Residents Selection --}}
                <section class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm space-y-8">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg">3</div>
                        <h4 class="text-lg font-black text-gray-900">Residents <span class="text-red-500">*</span></h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 h-[500px]">
                        {{-- Left: Available --}}
                        <div class="flex flex-col border border-gray-100 rounded-3xl overflow-hidden bg-gray-50/30">
                            <div class="p-4 bg-white border-b border-gray-100 space-y-3">
                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Available Residents</div>
                                <div class="relative">
                                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                    <input type="text" id="residentSearch" onkeyup="filterResidents()" 
                                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 text-xs focus:bg-white transition-all outline-none" 
                                        placeholder="Search by name or unit...">
                                </div>
                            </div>
                            <div class="flex-1 overflow-y-auto p-2 space-y-1 custom-scrollbar" id="available-list">
                                @foreach($residents as $resident)
                                <div class="resident-item p-3 rounded-2xl bg-white border border-gray-50 flex items-center justify-between group hover:border-blue-200 hover:shadow-sm transition-all" 
                                    data-id="{{ $resident->id }}" data-name="{{ strtolower($resident->first_name . ' ' . $resident->last_name) }}" data-unit="{{ strtolower('B'.$resident->block.'/L'.$resident->lot) }}">
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $resident->first_name }} {{ $resident->last_name }}</div>
                                        <div class="text-[10px] font-medium text-gray-400 uppercase">B{{ $resident->block }} / L{{ $resident->lot }}</div>
                                    </div>
                                    <button type="button" onclick="addResident({{ $resident->id }})" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center opacity-0 group-hover:opacity-100 hover:bg-blue-600 hover:text-white transition-all">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Right: Selected --}}
                        <div class="flex flex-col border border-gray-100 rounded-3xl overflow-hidden bg-gray-50/30">
                            {{-- Standard Header --}}
                            <div id="selected-header-default" class="p-4 bg-white border-b border-gray-100 flex items-center justify-between">
                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Selected Residents (<span id="selected-count">0</span>)</div>
                                <div class="flex items-center gap-3">
                                    <button type="button" onclick="addAllResidents()" class="text-[10px] font-bold text-blue-600 hover:underline">Add All</button>
                                    <span class="text-gray-200">|</span>
                                    <button type="button" onclick="removeAllResidents()" class="text-[10px] font-bold text-red-500 hover:underline">Remove All</button>
                                    <span class="text-gray-200">|</span>
                                    <button type="button" onclick="enterSelectionMode()" class="px-2 py-1 rounded-lg bg-gray-100 text-[10px] font-bold text-gray-600 hover:bg-gray-200 transition-all">Select</button>
                                </div>
                            </div>

                            {{-- Selection Mode Header --}}
                            <div id="selected-header-mode" class="hidden p-4 bg-blue-600 border-b border-blue-700 flex flex-col gap-3 animate__animated animate__fadeInDown animate__faster">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)" class="w-4 h-4 rounded border-blue-400 text-blue-600 focus:ring-blue-500 bg-blue-500/20">
                                        <span class="text-[10px] font-bold text-white uppercase tracking-widest">Select All</span>
                                    </div>
                                    <span class="text-[10px] font-bold text-blue-100 uppercase tracking-widest"><span id="batch-selected-count">0</span> selected</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" id="btn-remove-selected" onclick="removeBatchSelected()" disabled 
                                        class="flex-1 py-2 rounded-xl bg-white/10 border border-white/20 text-white text-[10px] font-bold hover:bg-white/20 disabled:opacity-30 disabled:cursor-not-allowed transition-all">
                                        Remove Selected
                                    </button>
                                    <button type="button" onclick="clearBatchSelection()" 
                                        class="px-4 py-2 rounded-xl bg-white/10 border border-white/20 text-white text-[10px] font-bold hover:bg-white/20 transition-all">
                                        Clear
                                    </button>
                                    <button type="button" onclick="exitSelectionMode()" 
                                        class="px-4 py-2 rounded-xl bg-white text-blue-600 text-[10px] font-bold hover:bg-blue-50 transition-all shadow-sm">
                                        Cancel
                                    </button>
                                </div>
                            </div>

                            <div class="flex-1 overflow-y-auto p-2 space-y-1 custom-scrollbar" id="selected-list">
                                <div id="empty-selected" class="h-full flex flex-col items-center justify-center text-center p-8 text-gray-400">
                                    <i class="bi bi-person-plus text-3xl mb-2 opacity-20"></i>
                                    <p class="text-[11px] font-medium">No residents selected yet</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Hidden select for form submission --}}
                    <select name="resident_ids[]" id="resident_ids_hidden" class="hidden" multiple></select>
                </section>
            </div>

            {{-- PREVIEW SIDE --}}
            <div class="lg:col-span-1">
                <div class="sticky top-8 space-y-6">
                    <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50/50 rounded-full -mr-16 -mt-16 blur-3xl"></div>
                        
                        <h5 class="text-sm font-black text-gray-900 mb-8 flex items-center gap-3">
                            <div class="w-2 h-6 bg-blue-500 rounded-full"></div>
                            Statement Preview
                        </h5>

                        <div id="preview-container" class="space-y-8 relative z-10">
                            <div class="space-y-2">
                                <div id="preview-type" class="text-[10px] font-black text-blue-500 uppercase tracking-[0.2em]">-</div>
                                <h4 id="preview-title" class="text-xl font-black text-gray-900 leading-tight">Billing Title</h4>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div class="space-y-1">
                                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Residents</div>
                                    <div id="preview-residents" class="text-sm font-black text-gray-900">0 Units</div>
                                </div>
                                <div class="space-y-1">
                                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Per Unit</div>
                                    <div id="preview-per-unit" class="text-sm font-black text-gray-900">₱0.00</div>
                                </div>
                            </div>

                            <div class="space-y-4 pt-6 border-t border-gray-50">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-gray-50 text-gray-400 flex items-center justify-center shrink-0">
                                        <i class="bi bi-calendar-check"></i>
                                    </div>
                                    <div>
                                        <div class="text-[10px] font-bold text-gray-400 uppercase">Billing Starts</div>
                                        <div id="preview-start" class="text-sm font-bold text-gray-700">-</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-gray-50 text-gray-400 flex items-center justify-center shrink-0">
                                        <i class="bi bi-calendar-x"></i>
                                    </div>
                                    <div>
                                        <div class="text-[10px] font-bold text-gray-400 uppercase">Due Date</div>
                                        <div id="preview-due" class="text-sm font-bold text-gray-700">-</div>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-8 border-t-2 border-dashed border-gray-100 flex flex-col items-center">
                                <div class="text-xs font-bold text-gray-400 uppercase mb-2">Total Statement Value</div>
                                <div id="preview-total" class="text-4xl font-black text-blue-600 tabular-nums">₱0.00</div>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="submit-btn" onclick="openConfirmModal()" disabled
                        class="w-full py-5 bg-blue-600 text-white font-black rounded-[1.5rem] hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-xl shadow-blue-100 flex items-center justify-center gap-3 group">
                        <span>Generate Statement</span>
                        <i class="bi bi-send-fill transition-transform group-hover:translate-x-1"></i>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- CONFIRMATION MODAL --}}
@push('modals')
<div id="confirmModal" class="hidden fixed inset-0 z-[999] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">
    <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden animate__animated animate__zoomIn animate__faster relative z-[1000]">
        <div class="p-10 text-center">
            <div class="w-20 h-20 rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-8 shadow-sm ring-1 ring-blue-100">
                <i class="bi bi-send-check-fill text-4xl"></i>
            </div>
            <h3 class="text-2xl font-black text-gray-900 mb-3 tracking-tight">Create Billing Statement?</h3>
            <p class="text-gray-500 text-sm mb-10 leading-relaxed px-4">
                You are about to generate dues for <span id="confirm-resident-count" class="font-black text-blue-600">0</span> residents. This will be recorded in their billing history.
            </p>
            <div class="grid grid-cols-2 gap-4">
                <button type="button" onclick="closeModal()" class="py-4 rounded-2xl border border-gray-100 text-gray-500 font-bold hover:bg-gray-50 transition-all active:scale-95">Cancel</button>
                <button type="button" onclick="submitForm()" class="py-4 rounded-2xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 active:scale-95">Create Dues</button>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    const allResidents = @json($residents);
    let selectedResidentIds = new Set();
    let isSelectionMode = false;
    let batchSelectedIds = new Set();
    let lastCheckedId = null;

    function enterSelectionMode() {
        if (selectedResidentIds.size === 0) return;
        isSelectionMode = true;
        document.getElementById('selected-header-default').classList.add('hidden');
        document.getElementById('selected-header-mode').classList.remove('hidden');
        renderSelected();
    }

    function exitSelectionMode() {
        isSelectionMode = false;
        batchSelectedIds.clear();
        document.getElementById('selected-header-default').classList.remove('hidden');
        document.getElementById('selected-header-mode').classList.add('hidden');
        renderSelected();
    }

    function toggleBatchItem(id, event) {
        if (event && event.shiftKey && lastCheckedId !== null) {
            const allSelectedArray = Array.from(selectedResidentIds);
            const start = allSelectedArray.indexOf(lastCheckedId);
            const end = allSelectedArray.indexOf(id);
            const range = allSelectedArray.slice(Math.min(start, end), Math.max(start, end) + 1);
            
            const shouldSelect = batchSelectedIds.has(lastCheckedId);
            range.forEach(rid => {
                if (shouldSelect) batchSelectedIds.add(rid);
                else batchSelectedIds.delete(rid);
            });
        } else {
            if (batchSelectedIds.has(id)) {
                batchSelectedIds.delete(id);
            } else {
                batchSelectedIds.add(id);
            }
        }
        lastCheckedId = id;
        updateBatchUI();
    }

    function toggleSelectAll(checkbox) {
        if (checkbox.checked) {
            selectedResidentIds.forEach(id => batchSelectedIds.add(id));
        } else {
            batchSelectedIds.clear();
        }
        updateBatchUI();
    }

    function clearBatchSelection() {
        batchSelectedIds.clear();
        document.getElementById('selectAllCheckbox').checked = false;
        updateBatchUI();
    }

    function updateBatchUI() {
        document.getElementById('batch-selected-count').textContent = batchSelectedIds.size;
        document.getElementById('btn-remove-selected').disabled = batchSelectedIds.size === 0;
        document.getElementById('selectAllCheckbox').checked = batchSelectedIds.size === selectedResidentIds.size && selectedResidentIds.size > 0;
        
        // Update row highlighting
        document.querySelectorAll('#selected-list .resident-selected-item').forEach(item => {
            const id = parseInt(item.dataset.id);
            const checkbox = item.querySelector('.batch-checkbox');
            if (checkbox) checkbox.checked = batchSelectedIds.has(id);
            
            if (batchSelectedIds.has(id)) {
                item.classList.add('bg-blue-50', 'border-blue-200');
                item.classList.remove('bg-white', 'border-gray-100');
            } else {
                item.classList.remove('bg-blue-50', 'border-blue-200');
                item.classList.add('bg-white', 'border-gray-100');
            }
        });
    }

    function removeBatchSelected() {
        if (confirm(`Remove ${batchSelectedIds.size} selected residents?`)) {
            batchSelectedIds.forEach(id => selectedResidentIds.delete(id));
            batchSelectedIds.clear();
            if (selectedResidentIds.size === 0) exitSelectionMode();
            renderSelected();
        }
    }

    function removeAllResidents() {
        if (selectedResidentIds.size === 0) return;
        if (confirm('Are you sure you want to remove all selected residents?')) {
            selectedResidentIds.clear();
            batchSelectedIds.clear();
            exitSelectionMode();
            renderSelected();
        }
    }

    function updatePreview() {
        const title = document.getElementById('title').value || 'Billing Title';
        const typeSelect = document.getElementById('type');
        const typeText = typeSelect.options[typeSelect.selectedIndex].text.toUpperCase();
        const amount = parseFloat(document.getElementById('amount_input').value) || 0;
        const startDate = document.getElementById('billing_period_start').value;
        const dueDate = document.getElementById('due_date').value;
        const residentCount = selectedResidentIds.size;

        document.getElementById('preview-title').textContent = title;
        document.getElementById('preview-type').textContent = typeSelect.value ? typeText : '-';
        document.getElementById('preview-residents').textContent = `${residentCount} Units`;
        document.getElementById('preview-per-unit').textContent = `₱${amount.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
        document.getElementById('preview-total').textContent = `₱${(amount * residentCount).toLocaleString(undefined, {minimumFractionDigits: 2})}`;

        const formatDate = (dateStr) => {
            if (!dateStr) return '-';
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateStr).toLocaleDateString('en-US', options);
        };

        document.getElementById('preview-start').textContent = formatDate(startDate);
        document.getElementById('preview-due').textContent = formatDate(dueDate);

        validateForm();
    }

    function toggleAmountType(type) {
        const btnStandard = document.getElementById('btn-standard');
        const btnCustom = document.getElementById('btn-custom');
        const standardSection = document.getElementById('standard-rates-section');
        const customSection = document.getElementById('custom-amount-section');
        const amountTypeInput = document.getElementById('amount_type');
        
        if (type === 'standard') {
            btnStandard.classList.add('bg-white', 'shadow-sm', 'text-blue-600');
            btnStandard.classList.remove('text-gray-400');
            btnCustom.classList.remove('bg-white', 'shadow-sm', 'text-blue-600');
            btnCustom.classList.add('text-gray-400');
            standardSection.classList.remove('hidden');
            customSection.classList.add('hidden');
            amountTypeInput.value = 'standard';
        } else {
            btnCustom.classList.add('bg-white', 'shadow-sm', 'text-blue-600');
            btnCustom.classList.remove('text-gray-400');
            btnStandard.classList.remove('bg-white', 'shadow-sm', 'text-blue-600');
            btnStandard.classList.add('text-gray-400');
            standardSection.classList.add('hidden');
            customSection.classList.remove('hidden');
            amountTypeInput.value = 'custom';
        }
        updatePreview();
    }

    function setAmount(val) {
        document.getElementById('amount_input').value = val;
        updatePreview();
    }

    function filterResidents() {
        const term = document.getElementById('residentSearch').value.toLowerCase();
        const items = document.querySelectorAll('#available-list .resident-item');
        items.forEach(item => {
            const name = item.dataset.name;
            const unit = item.dataset.unit;
            item.style.display = (name.includes(term) || unit.includes(term)) ? 'flex' : 'none';
        });
    }

    function addResident(id) {
        if (selectedResidentIds.has(id)) return;
        selectedResidentIds.add(id);
        renderSelected();
    }

    function addAllResidents() {
        allResidents.forEach(r => selectedResidentIds.add(r.id));
        renderSelected();
    }

    function removeResident(id) {
        selectedResidentIds.delete(id);
        renderSelected();
    }

    function renderSelected() {
        const list = document.getElementById('selected-list');
        const hiddenSelect = document.getElementById('resident_ids_hidden');
        const countSpan = document.getElementById('selected-count');
        const emptyState = document.getElementById('empty-selected');

        list.innerHTML = '';
        hiddenSelect.innerHTML = '';
        
        if (selectedResidentIds.size === 0) {
            list.appendChild(emptyState);
        } else {
            selectedResidentIds.forEach(id => {
                const resident = allResidents.find(r => r.id === id);
                if (resident) {
                    const isBatchChecked = batchSelectedIds.has(id);
                    const item = document.createElement('div');
                    item.dataset.id = id;
                    item.className = `resident-selected-item p-3 rounded-2xl border transition-all animate__animated animate__fadeIn flex items-center justify-between group ${isBatchChecked ? 'bg-blue-50 border-blue-200' : 'bg-white border-gray-100'}`;
                    
                    let leftContent = '';
                    if (isSelectionMode) {
                        leftContent = `
                            <div class="flex items-center gap-3">
                                <input type="checkbox" class="batch-checkbox w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" 
                                    ${isBatchChecked ? 'checked' : ''} onchange="toggleBatchItem(${id}, event)">
                                <div>
                                    <div class="text-sm font-bold text-gray-900">${resident.first_name} ${resident.last_name}</div>
                                    <div class="text-[10px] font-medium text-gray-400 uppercase">B${resident.block} / L${resident.lot}</div>
                                </div>
                            </div>
                        `;
                    } else {
                        leftContent = `
                            <div>
                                <div class="text-sm font-bold text-gray-900">${resident.first_name} ${resident.last_name}</div>
                                <div class="text-[10px] font-medium text-gray-400 uppercase">B${resident.block} / L${resident.lot}</div>
                            </div>
                        `;
                    }

                    item.innerHTML = `
                        ${leftContent}
                        ${!isSelectionMode ? `
                        <button type="button" onclick="removeResident(${resident.id})" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center opacity-0 group-hover:opacity-100 hover:bg-red-500 hover:text-white transition-all">
                            <i class="bi bi-trash"></i>
                        </button>
                        ` : ''}
                    `;
                    
                    if (isSelectionMode) {
                        item.onclick = (e) => {
                            if (e.target.type !== 'checkbox') {
                                toggleBatchItem(id, e);
                            }
                        };
                        item.classList.add('cursor-pointer');
                    }

                    list.appendChild(item);

                    const opt = document.createElement('option');
                    opt.value = id;
                    opt.selected = true;
                    hiddenSelect.appendChild(opt);
                }
            });
        }

        countSpan.textContent = selectedResidentIds.size;
        updateBatchUI(); // Refresh batch count and buttons if in selection mode
        
        // Toggle visibility in available list
        document.querySelectorAll('#available-list .resident-item').forEach(item => {
            const id = parseInt(item.dataset.id);
            if (selectedResidentIds.has(id)) {
                item.classList.add('opacity-50', 'grayscale-[0.5]', 'pointer-events-none');
            } else {
                item.classList.remove('opacity-50', 'grayscale-[0.5]', 'pointer-events-none');
            }
        });

        updatePreview();
    }

    function validateForm() {
        const title = document.getElementById('title').value;
        const type = document.getElementById('type').value;
        const amount = document.getElementById('amount_input').value;
        const startDate = document.getElementById('billing_period_start').value;
        const dueDate = document.getElementById('due_date').value;
        const residentsSelected = selectedResidentIds.size > 0;

        const isValid = title && type && amount && startDate && dueDate && residentsSelected;
        document.getElementById('submit-btn').disabled = !isValid;
    }

    function openConfirmModal() {
        document.getElementById('confirm-resident-count').textContent = selectedResidentIds.size;
        document.getElementById('confirmModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('confirmModal').classList.add('hidden');
    }

    function submitForm() {
        document.getElementById('billing-form').submit();
    }
</script>
@endpush
@endsection
