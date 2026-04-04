@extends('layouts.admin')

@section('title', 'Edit Billing Statement')
@section('page-title', 'Edit Billing')

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
                        Edit Billing Statement
                    </h1>
                    <p class="mt-2 text-gray-600 text-lg max-w-xl">
                        Update the details of the billing batch: <span class="font-bold text-gray-900">{{ $batch->title }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <form id="edit-billing-form" action="{{ route('admin.dues.update', $batch->id) }}" method="POST" x-data="billingForm()">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="mb-8 p-6 bg-red-50 border border-red-100 rounded-[2rem] animate-in fade-in slide-in-from-top-4 duration-300">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center text-red-600 shadow-sm">
                        <i class="bi bi-exclamation-triangle-fill text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-red-900 uppercase tracking-tight">Update Failed</h4>
                        <p class="text-[10px] font-bold text-red-500 uppercase tracking-widest">Please correct the following errors</p>
                    </div>
                </div>
                <ul class="space-y-2 ml-14">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm font-medium text-red-700 flex items-center gap-2">
                            <span class="w-1 h-1 rounded-full bg-red-400"></span>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

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
                            <input type="text" name="title" id="title" value="{{ old('title', $batch->title) }}" oninput="updatePreview()" 
                                class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm font-medium focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all outline-none" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Billing Type <span class="text-red-500">*</span></label>
                                <div class="relative group/select">
                                    <select name="type" id="type" x-model="billingType" 
                                        class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm font-medium appearance-none focus:bg-white focus:border-emerald-500 transition-all outline-none cursor-pointer" required>
                                        <option value="monthly_hoa" {{ old('type', $batch->type) == 'monthly_hoa' ? 'selected' : '' }}>Monthly HOA Fees</option>
                                        <option value="special_assessments" {{ old('type', $batch->type) == 'special_assessments' ? 'selected' : '' }}>Special Assessment</option>
                                        <option value="regular_fees" {{ old('type', $batch->type) == 'regular_fees' ? 'selected' : '' }}>Regular Service Fees</option>
                                        <option value="amenity_dues" {{ old('type', $batch->type) == 'amenity_dues' ? 'selected' : '' }}>Amenity Dues</option>
                                    </select>
                                    <i class="bi bi-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none group-hover/select:text-emerald-600 transition-colors"></i>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Frequency <span class="text-red-500">*</span></label>
                                <div class="relative group/select">
                                    <select name="frequency" id="frequency" 
                                        class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm font-medium appearance-none focus:bg-white focus:border-emerald-500 transition-all outline-none cursor-pointer" required>
                                        <option value="one_time" {{ old('frequency', $batch->frequency) == 'one_time' ? 'selected' : '' }}>One-time</option>
                                        <option value="monthly" {{ old('frequency', $batch->frequency) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="quarterly" {{ old('frequency', $batch->frequency) == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                    </select>
                                    <i class="bi bi-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none group-hover/select:text-emerald-600 transition-colors"></i>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Due Date <span class="text-red-500">*</span></label>
                                <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $batch->due_date ? $batch->due_date->format('Y-m-d') : '') }}" onchange="updatePreview()" 
                                    class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm font-medium focus:bg-white focus:border-emerald-500 transition-all outline-none" required>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- 2. Billing Amount --}}
                <section class="glass-card p-8 space-y-8 relative overflow-hidden group">
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
                                    <option value="monthly_hoa">Monthly HOA Fees</option>
                                    <option value="special_assessments">Special Assessment</option>
                                    <option value="amenity_dues">Amenity Dues</option>
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
            </div>

            {{-- PREVIEW SIDE --}}
            <div class="lg:col-span-1">
                <div class="sticky top-8 space-y-6">
                    <div class="glass-card p-8 relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 rounded-full -mr-16 -mt-16 blur-3xl opacity-50"></div>
                        
                        <h5 class="text-sm font-black text-gray-900 mb-8 flex items-center gap-3">
                            <div class="w-2 h-6 bg-emerald-500 rounded-full shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
                            Update Preview
                        </h5>

                        <div id="preview-container" class="space-y-8 relative z-10">
                            <div class="space-y-3">
                                <div id="preview-type" class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">-</div>
                                <h4 id="preview-title" class="text-2xl font-black text-gray-900 leading-tight tracking-tight">Billing Title</h4>
                            </div>

                            <div class="grid grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</div>
                                    <div class="text-lg font-black text-gray-900">Editing Batch</div>
                                </div>
                                <div class="space-y-2">
                                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Per Unit</div>
                                    <div id="preview-per-unit" class="text-lg font-black text-emerald-600">₱0.00</div>
                                </div>
                            </div>

                            <div class="space-y-5 pt-8 border-t border-gray-50">
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 rounded-2xl bg-gray-50 text-gray-400 flex items-center justify-center shrink-0 border border-gray-100">
                                        <i class="bi bi-calendar-x text-lg"></i>
                                    </div>
                                    <div>
                                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">New Due Date</div>
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
                                <p class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Action</p>
                                <div class="text-2xl font-black text-white">Save Changes</div>
                            </div>
                            
                            <div class="flex gap-3">
                                <a href="{{ route('admin.dues.index') }}" class="flex-1 py-4 rounded-2xl bg-white/10 border border-white/10 text-white text-[10px] font-black uppercase tracking-widest hover:bg-white/20 transition-all text-center">
                                    Cancel
                                </a>
                                <button type="submit" form="edit-billing-form" class="flex-[2] py-4 rounded-2xl bg-[#B6FF5C] text-[#081412] text-[10px] font-black uppercase tracking-widest hover:bg-[#8AC941] transition-all shadow-lg shadow-emerald-500/20 active:scale-95" id="submit-btn">
                                    Update Batch
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function billingForm() {
        return {
            billingType: '{{ old("type", $batch->type) }}',
            amountType: '{{ old("amount_type", $batch->amount_type ?? $batch->type) }}',
            amount: '{{ old("amount", $batch->amount) }}',
            init() {
                // Watch billingType to sync amountType
                this.$watch('billingType', (newVal) => {
                    if (newVal && this.amountType !== 'custom') {
                        this.amountType = newVal;
                    }
                    updatePreview();
                });

                // Watch amountType to set amounts
                this.$watch('amountType', (newVal) => {
                    if (newVal === 'monthly_hoa') {
                        this.amount = 500.00;
                    } else if (newVal === 'special_assessments') {
                        this.amount = 1500.00; 
                    } else if (newVal === 'amenity_dues') {
                        this.amount = 250.00; 
                    } else if (newVal === 'regular_fees') {
                        this.amount = 300.00;
                    } else if (newVal === 'custom') {
                        // Keep current amount if switching to custom
                    }
                    this.$nextTick(() => updatePreview());
                });

                this.$watch('amount', () => {
                    updatePreview();
                });

                // Initial preview
                this.$nextTick(() => updatePreview());
            }
        }
    }

    function updatePreview() {
        const title = document.getElementById('title').value || 'Billing Title';
        const typeSelect = document.getElementById('type');
        const typeText = typeSelect.value ? typeSelect.options[typeSelect.selectedIndex].text.toUpperCase() : '-';
        const amount = parseFloat(document.getElementById('amount_input').value) || 0;
        const dueDate = document.getElementById('due_date').value;

        document.getElementById('preview-title').textContent = title;
        document.getElementById('preview-type').textContent = typeText;
        document.getElementById('preview-per-unit').textContent = `₱${amount.toLocaleString(undefined, {minimumFractionDigits: 2})}`;

        const formatDate = (dateStr) => {
            if (!dateStr) return '-';
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateStr).toLocaleDateString('en-US', options);
        };

        document.getElementById('preview-due').textContent = formatDate(dueDate);
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
        updatePreview();
    });
</script>
@endpush

@endsection
