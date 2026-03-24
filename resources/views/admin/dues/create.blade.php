@extends('layouts.admin')

@section('title', 'Create Billing Statement')
@section('page-title', 'Create Billing')

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
                <a href="{{ route('admin.dues.index') }}" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white border border-gray-100 text-gray-400 hover:text-emerald-600 hover:border-emerald-100 hover:shadow-sm transition-all shadow-sm">
                    <i class="bi bi-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                        Create Billing Statement
                    </h1>
                    <p class="mt-2 text-gray-600 text-lg max-w-xl">
                        Generate new monthly dues or special assessment batches for residents.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" onclick="document.getElementById('billing-form').submit()" class="btn-premium" id="header-submit-btn" disabled>
                    <i class="bi bi-check2-circle"></i>
                    Generate Batch
                </button>
            </div>
        </div>
    </div>

    <form id="billing-form" action="{{ route('admin.dues.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- FORM SIDE --}}
            <div class="lg:col-span-2 space-y-8">
                
                {{-- 1. Billing Details --}}
                <section class="glass-card p-8 space-y-8 relative overflow-hidden group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-xl border border-emerald-100 shadow-sm">1</div>
                        <div>
                            <h4 class="text-xl font-black text-gray-900 tracking-tight">Billing Details</h4>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Basic information and timing</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-8">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Billing Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title" oninput="updatePreview()" 
                                class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm font-medium focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all outline-none" 
                                placeholder="e.g. March 2026 Monthly HOA" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Billing Type <span class="text-red-500">*</span></label>
                                <div class="relative group/select">
                                    <select name="type" id="type" onchange="updatePreview()" 
                                        class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm font-medium appearance-none focus:bg-white focus:border-emerald-500 transition-all outline-none cursor-pointer" required>
                                        <option value="" disabled selected>Select Type</option>
                                        <option value="monthly_hoa">Monthly HOA Fees</option>
                                        <option value="special_assessment">Special Assessment</option>
                                        <option value="regular_fees">Regular Service Fees</option>
                                        <option value="amenity_dues">Amenity Dues</option>
                                    </select>
                                    <i class="bi bi-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none group-hover/select:text-emerald-600 transition-colors"></i>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Frequency <span class="text-red-500">*</span></label>
                                <div class="relative group/select">
                                    <select name="frequency" id="frequency" 
                                        class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm font-medium appearance-none focus:bg-white focus:border-emerald-500 transition-all outline-none cursor-pointer" required>
                                        <option value="one_time">One-time</option>
                                        <option value="monthly">Monthly</option>
                                        <option value="quarterly">Quarterly</option>
                                    </select>
                                    <i class="bi bi-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none group-hover/select:text-emerald-600 transition-colors"></i>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Billing Start Date <span class="text-red-500">*</span></label>
                                <input type="date" name="billing_period_start" id="billing_period_start" onchange="updatePreview()" 
                                    class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm font-medium focus:bg-white focus:border-emerald-500 transition-all outline-none" required>
                            </div>
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Due Date <span class="text-red-500">*</span></label>
                                <input type="date" name="due_date" id="due_date" onchange="updatePreview()" 
                                    class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm font-medium focus:bg-white focus:border-emerald-500 transition-all outline-none" required>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- 2. Billing Amount --}}
                <section class="glass-card p-8 space-y-8 relative overflow-hidden group" x-data="billingAmount()">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-xl border border-emerald-100 shadow-sm">2</div>
                        <div>
                            <h4 class="text-xl font-black text-gray-900 tracking-tight">Billing Amount</h4>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Financial configuration</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Amount Type <span class="text-red-500">*</span></label>
                            <div class="relative group/select">
                                <select x-model="amountType" name="amount_type" id="amount_type" class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm font-medium appearance-none focus:bg-white focus:border-emerald-500 transition-all outline-none cursor-pointer" required>
                                    <option value="standard">Standard HOA Fee</option>
                                    <option value="assessment">Special Assessment</option>
                                    <option value="amenity">Amenity Charge</option>
                                    <option value="custom">Custom Amount</option>
                                </select>
                                <i class="bi bi-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none group-hover/select:text-emerald-600 transition-colors"></i>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Amount <span class="text-red-500">*</span></label>
                            <div class="relative group/input">
                                <span class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 font-black text-lg transition-colors group-focus-within/input:text-emerald-600">₱</span>
                                <input type="number" name="amount" id="amount_input" x-model="amount" :readonly="amountType !== 'custom'" oninput="updatePreview()" 
                                    class="w-full pl-12 pr-6 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-lg font-black focus:bg-white focus:border-emerald-500 transition-all outline-none disabled:bg-gray-100/80 disabled:text-gray-400" 
                                    placeholder="0.00" step="0.01" min="0.01" required>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- 3. Residents Selection --}}
                <section class="glass-card p-8 space-y-8 relative overflow-hidden group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-xl border border-emerald-100 shadow-sm">3</div>
                        <div>
                            <h4 class="text-xl font-black text-gray-900 tracking-tight">Residents Selection</h4>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Target community members</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 h-[550px]">
                        {{-- Left: Available --}}
                        <div class="flex flex-col border border-gray-100 rounded-3xl overflow-hidden bg-gray-50/30">
                            <div class="p-5 bg-white border-b border-gray-100 space-y-4">
                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Available Residents</div>
                                <div class="relative group/search">
                                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs group-focus-within/search:text-emerald-600 transition-colors"></i>
                                    <input type="text" id="residentSearch" onkeyup="filterResidents()" 
                                        class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-xs font-medium focus:bg-white focus:border-emerald-500 transition-all outline-none" 
                                        placeholder="Search by name or unit...">
                                </div>
                            </div>
                            <div class="flex-1 overflow-y-auto p-3 space-y-2 custom-scrollbar" id="available-list">
                                @foreach($residents as $resident)
                                <div class="resident-item p-4 rounded-2xl bg-white border border-gray-50 flex items-center justify-between group hover:border-emerald-500/30 hover:bg-emerald-50/30 hover:shadow-sm transition-all duration-300" 
                                    data-id="{{ $resident->id }}" data-name="{{ strtolower($resident->first_name . ' ' . $resident->last_name) }}" data-unit="{{ strtolower('B'.$resident->block.'/L'.$resident->lot) }}">
                                    <div>
                                        <div class="text-sm font-bold text-gray-900 group-hover:text-emerald-700 transition-colors">{{ $resident->first_name }} {{ $resident->last_name }}</div>
                                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-0.5">B{{ $resident->block }} / L{{ $resident->lot }}</div>
                                    </div>
                                    <button type="button" onclick="addResident({{ $resident->id }})" class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center opacity-0 group-hover:opacity-100 hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Right: Selected --}}
                        <div class="flex flex-col border border-gray-100 rounded-3xl overflow-hidden bg-gray-50/30">
                            {{-- Standard Header --}}
                            <div id="selected-header-default" class="p-5 bg-white border-b border-gray-100 flex items-center justify-between">
                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Selected (<span id="selected-count">0</span>)</div>
                                <div class="flex items-center gap-3">
                                    <button type="button" onclick="addAllResidents()" class="text-[10px] font-black text-emerald-600 hover:text-emerald-700 uppercase tracking-widest">Add All</button>
                                    <span class="text-gray-200">|</span>
                                    <button type="button" onclick="removeAllResidents()" class="text-[10px] font-black text-red-500 hover:text-red-600 uppercase tracking-widest">Remove All</button>
                                    <span class="text-gray-200">|</span>
                                    <button type="button" onclick="enterSelectionMode()" class="px-3 py-1.5 rounded-lg bg-gray-100 text-[10px] font-black text-gray-600 hover:bg-gray-200 transition-all uppercase tracking-widest">Select</button>
                                </div>
                            </div>

                            {{-- Selection Mode Header --}}
                            <div id="selected-header-mode" class="hidden p-5 bg-emerald-600 border-b border-emerald-700 flex flex-col gap-4 animate__animated animate__fadeInDown animate__faster">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)" class="w-5 h-5 rounded-lg border-emerald-400 text-emerald-700 focus:ring-emerald-500 bg-emerald-500/20">
                                        <span class="text-[10px] font-black text-white uppercase tracking-widest">Select All</span>
                                    </div>
                                    <span class="text-[10px] font-black text-emerald-100 uppercase tracking-widest"><span id="batch-selected-count">0</span> selected</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" id="btn-remove-selected" onclick="removeBatchSelected()" disabled 
                                        class="flex-1 py-2.5 rounded-xl bg-white/10 border border-white/20 text-white text-[10px] font-black uppercase tracking-widest hover:bg-white/20 disabled:opacity-30 disabled:cursor-not-allowed transition-all">
                                        Remove Selected
                                    </button>
                                    <button type="button" onclick="clearBatchSelection()" 
                                        class="px-4 py-2.5 rounded-xl bg-white/10 border border-white/20 text-white text-[10px] font-black uppercase tracking-widest hover:bg-white/20 transition-all">
                                        Clear
                                    </button>
                                    <button type="button" onclick="exitSelectionMode()" 
                                        class="px-4 py-2.5 rounded-xl bg-white text-emerald-600 text-[10px] font-black uppercase tracking-widest hover:bg-emerald-50 transition-all shadow-sm">
                                        Cancel
                                    </button>
                                </div>
                            </div>

                            <div class="flex-1 overflow-y-auto p-3 space-y-2 custom-scrollbar" id="selected-list">
                                <div id="empty-selected" class="h-full flex flex-col items-center justify-center text-center p-12 text-gray-400">
                                    <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-4 text-gray-200">
                                        <i class="bi bi-person-plus text-3xl"></i>
                                    </div>
                                    <p class="text-[11px] font-black uppercase tracking-widest">No residents selected</p>
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
                    <div class="glass-card p-8 relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 rounded-full -mr-16 -mt-16 blur-3xl opacity-50"></div>
                        
                        <h5 class="text-sm font-black text-gray-900 mb-8 flex items-center gap-3">
                            <div class="w-2 h-6 bg-emerald-500 rounded-full shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
                            Statement Preview
                        </h5>

                        <div id="preview-container" class="space-y-8 relative z-10">
                            <div class="space-y-3">
                                <div id="preview-type" class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">-</div>
                                <h4 id="preview-title" class="text-2xl font-black text-gray-900 leading-tight tracking-tight">Billing Title</h4>
                            </div>

                            <div class="grid grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Residents</div>
                                    <div id="preview-residents" class="text-lg font-black text-gray-900">0 Units</div>
                                </div>
                                <div class="space-y-2">
                                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Per Unit</div>
                                    <div id="preview-per-unit" class="text-lg font-black text-emerald-600">₱0.00</div>
                                </div>
                            </div>

                            <div class="space-y-5 pt-8 border-t border-gray-50">
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 rounded-2xl bg-gray-50 text-gray-400 flex items-center justify-center shrink-0 border border-gray-100">
                                        <i class="bi bi-calendar-check text-lg"></i>
                                    </div>
                                    <div>
                                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Billing Starts</div>
                                        <div id="preview-start" class="text-sm font-bold text-gray-900">-</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 rounded-2xl bg-gray-50 text-gray-400 flex items-center justify-center shrink-0 border border-gray-100">
                                        <i class="bi bi-calendar-x text-lg"></i>
                                    </div>
                                    <div>
                                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Due Date</div>
                                        <div id="preview-due" class="text-sm font-bold text-gray-900">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="glass-card bg-gray-900 p-8 relative overflow-hidden group border-none">
                        <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all duration-700"></div>
                        
                        <div class="relative z-10 space-y-6">
                            <div class="space-y-2">
                                <p class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Total Projected Collection</p>
                                <div class="text-3xl font-black tabular-nums text-white">₱<span id="preview-total">0.00</span></div>
                            </div>
                            
                            <button type="submit" form="billing-form" class="btn-premium w-full py-5 text-xs group/btn" id="submit-btn" disabled>
                                <span>Generate Statement</span>
                                <i class="bi bi-arrow-right text-lg group-hover/btn:translate-x-1 transition-transform"></i>
                            </button>
                        </div>
                    </div>
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
            <div class="w-20 h-20 rounded-3xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-8 shadow-sm ring-1 ring-emerald-100">
                <i class="bi bi-send-check-fill text-4xl"></i>
            </div>
            <h3 class="text-2xl font-black text-gray-900 mb-3 tracking-tight">Create Billing Statement?</h3>
            <p class="text-gray-500 text-sm mb-10 leading-relaxed px-4">
                You are about to generate dues for <span id="confirm-resident-count" class="font-black text-emerald-600">0</span> residents. This will be recorded in their billing history.
            </p>
            <div class="grid grid-cols-2 gap-4">
                <button type="button" onclick="closeModal()" class="py-4 rounded-2xl border border-gray-100 text-gray-500 font-bold hover:bg-gray-50 transition-all active:scale-95">Cancel</button>
                <button type="button" onclick="submitForm()" class="py-4 rounded-2xl bg-gray-900 text-white font-bold hover:bg-emerald-600 transition-all shadow-lg active:scale-95">Create Dues</button>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    function billingAmount() {
        return {
            amountType: 'standard',
            amount: 500.00,
            init() {
                this.$watch('amountType', (newVal) => {
                    if (newVal === 'standard') {
                        this.amount = 500.00;
                    } else if (newVal === 'assessment') {
                        this.amount = 1500.00; 
                    } else if (newVal === 'amenity') {
                        this.amount = 250.00; 
                    } else {
                        this.amount = '';
                    }
                    this.$nextTick(() => updatePreview());
                });
                this.$watch('amount', () => {
                    updatePreview();
                });
            }
        }
    }

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
        
        document.querySelectorAll('#selected-list .resident-selected-item').forEach(item => {
            const id = parseInt(item.dataset.id);
            const checkbox = item.querySelector('.batch-checkbox');
            if (checkbox) checkbox.checked = batchSelectedIds.has(id);
            
            if (batchSelectedIds.has(id)) {
                item.classList.add('bg-emerald-50', 'border-emerald-200');
                item.classList.remove('bg-white', 'border-gray-100');
            } else {
                item.classList.remove('bg-emerald-50', 'border-emerald-200');
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
        const typeText = typeSelect.value ? typeSelect.options[typeSelect.selectedIndex].text.toUpperCase() : '-';
        const amount = parseFloat(document.getElementById('amount_input').value) || 0;
        const startDate = document.getElementById('billing_period_start').value;
        const dueDate = document.getElementById('due_date').value;
        const residentCount = selectedResidentIds.size;

        document.getElementById('preview-title').textContent = title;
        document.getElementById('preview-type').textContent = typeText;
        document.getElementById('preview-residents').textContent = `${residentCount} Units`;
        document.getElementById('preview-per-unit').textContent = `₱${amount.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
        document.getElementById('preview-total').textContent = `${(amount * residentCount).toLocaleString(undefined, {minimumFractionDigits: 2})}`;

        const formatDate = (dateStr) => {
            if (!dateStr) return '-';
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateStr).toLocaleDateString('en-US', options);
        };

        document.getElementById('preview-start').textContent = formatDate(startDate);
        document.getElementById('preview-due').textContent = formatDate(dueDate);

        validateForm();
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
        if (batchSelectedIds.has(id)) batchSelectedIds.delete(id);
        if (selectedResidentIds.size === 0) exitSelectionMode();
        renderSelected();
    }

    function renderSelected() {
        const list = document.getElementById('selected-list');
        const count = document.getElementById('selected-count');
        const hiddenSelect = document.getElementById('resident_ids_hidden');
        const emptyMsg = document.getElementById('empty-selected');
        
        count.textContent = selectedResidentIds.size;
        hiddenSelect.innerHTML = '';
        list.innerHTML = '';

        if (selectedResidentIds.size === 0) {
            list.appendChild(emptyMsg);
            emptyMsg.classList.remove('hidden');
        } else {
            emptyMsg.classList.add('hidden');
            allResidents.filter(r => selectedResidentIds.has(r.id)).forEach(r => {
                const opt = document.createElement('option');
                opt.value = r.id;
                opt.selected = true;
                hiddenSelect.appendChild(opt);

                const div = document.createElement('div');
                div.className = `resident-selected-item p-4 rounded-2xl border flex items-center justify-between transition-all duration-300 group ${batchSelectedIds.has(r.id) ? 'bg-emerald-50 border-emerald-200' : 'bg-white border-gray-100 hover:border-emerald-500/30 hover:bg-emerald-50/30'}`;
                div.dataset.id = r.id;
                
                if (isSelectionMode) {
                    div.onclick = (e) => toggleBatchItem(r.id, e);
                    div.classList.add('cursor-pointer');
                }

                div.innerHTML = `
                    <div class="flex items-center gap-4">
                        ${isSelectionMode ? `
                            <input type="checkbox" class="batch-checkbox w-5 h-5 rounded-lg border-emerald-300 text-emerald-600 focus:ring-emerald-500 pointer-events-none" ${batchSelectedIds.has(r.id) ? 'checked' : ''}>
                        ` : ''}
                        <div>
                            <div class="text-sm font-bold text-gray-900 group-hover:text-emerald-700 transition-colors">${r.first_name} ${r.last_name}</div>
                            <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-0.5">B${r.block} / L${r.lot}</div>
                        </div>
                    </div>
                    ${!isSelectionMode ? `
                        <button type="button" onclick="removeResident(${r.id})" class="w-9 h-9 rounded-xl text-gray-400 hover:bg-red-50 hover:text-red-500 transition-all flex items-center justify-center">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    ` : ''}
                `;
                list.appendChild(div);
            });
        }
        updatePreview();
        if (isSelectionMode) updateBatchUI();
    }

    function validateForm() {
        const title = document.getElementById('title').value;
        const type = document.getElementById('type').value;
        const amount = document.getElementById('amount_input').value;
        const startDate = document.getElementById('billing_period_start').value;
        const dueDate = document.getElementById('due_date').value;
        const hasResidents = selectedResidentIds.size > 0;

        const isValid = title && type && amount && startDate && dueDate && hasResidents;
        document.getElementById('submit-btn').disabled = !isValid;
        document.getElementById('header-submit-btn').disabled = !isValid;
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

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
        updatePreview();
    });
</script>
@endpush

@endsection
