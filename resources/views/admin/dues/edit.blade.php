@extends('layouts.admin')

@section('title', 'Edit Billing Statement')
@section('page-title', 'Edit Billing Statement')

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

            <div class="flex items-center gap-3">
                <button type="submit" form="edit-billing-form" class="btn-premium">
                    <i class="bi bi-check2-circle"></i>
                    Update Statement
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <form id="edit-billing-form" action="{{ route('admin.dues.update', $batch->id) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="glass-card p-8 space-y-8 relative overflow-hidden group" x-data="billingAmount()">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-xl border border-emerald-100 shadow-sm">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-black text-gray-900 tracking-tight">Update Details</h4>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Modify core billing information</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-8">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Billing Title</label>
                        <input type="text" name="title" value="{{ old('title', $batch->title) }}" 
                            class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm font-medium focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all outline-none" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Amount Type</label>
                            <div class="relative group/select">
                                <select x-model="amountType" name="amount_type" 
                                    class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm font-medium appearance-none focus:bg-white focus:border-emerald-500 transition-all outline-none cursor-pointer" required>
                                    <option value="standard" {{ old('amount_type', $batch->amount_type) == 'standard' ? 'selected' : '' }}>Standard HOA Fee</option>
                                    <option value="assessment" {{ old('amount_type', $batch->amount_type) == 'assessment' ? 'selected' : '' }}>Special Assessment</option>
                                    <option value="amenity" {{ old('amount_type', $batch->amount_type) == 'amenity' ? 'selected' : '' }}>Amenity Charge</option>
                                    <option value="custom" {{ old('amount_type', $batch->amount_type) == 'custom' ? 'selected' : '' }}>Custom Amount</option>
                                </select>
                                <i class="bi bi-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none group-hover/select:text-emerald-600 transition-colors"></i>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Amount</label>
                            <div class="relative group/input">
                                <span class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 font-black text-lg transition-colors group-focus-within/input:text-emerald-600">₱</span>
                                <input type="number" name="amount" x-model="amount" :readonly="amountType !== 'custom'" 
                                    value="{{ old('amount', $batch->amount) }}" 
                                    class="w-full pl-12 pr-6 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-lg font-black focus:bg-white focus:border-emerald-500 transition-all outline-none disabled:bg-gray-100/80 disabled:text-gray-400" 
                                    placeholder="0.00" step="0.01" min="0.01" required>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Due Date</label>
                        <input type="date" name="due_date" value="{{ old('due_date', $batch->due_date ? $batch->due_date->format('Y-m-d') : '') }}" 
                            class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm font-medium focus:bg-white focus:border-emerald-500 transition-all outline-none" required>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('admin.dues.index') }}" class="btn-secondary px-10 py-4">
                    Cancel
                </a>
                <button type="submit" class="btn-premium px-10 py-4">
                    Update Statement
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function billingAmount() {
        return {
            amountType: '{{ old("amount_type", $batch->amount_type) }}',
            amount: {{ old("amount", $batch->amount) }},
            init() {
                this.$watch('amountType', (newVal) => {
                    if (newVal === 'standard') {
                        this.amount = 500.00;
                    } else if (newVal === 'assessment') {
                        this.amount = 1500.00;
                    } else if (newVal === 'amenity') {
                        this.amount = 250.00;
                    } else if (this.amountType !== 'custom') {
                        this.amount = '';
                    }
                });
            }
        }
    }
</script>
@endpush
