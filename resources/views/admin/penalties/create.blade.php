@extends('layouts.admin')

@section('title', 'Add Penalty')
@section('page-title', 'Add New Penalty')

@section('content')
<div class="space-y-8 animate-fade-in pb-20" x-data="penaltyForm()">

    {{-- ===================== --}}
    {{-- HEADER SECTION --}}
    {{-- ===================== --}}
    <div class="glass-card p-8 relative overflow-hidden group">
        {{-- Subtle gradient glow in background --}}
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-brand-accent/5 rounded-full blur-3xl group-hover:bg-brand-accent/10 transition-all duration-700"></div>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div class="flex items-center gap-6">
                <a href="{{ route('admin.penalties.index') }}" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white border border-gray-100 text-gray-400 hover:text-emerald-600 hover:border-emerald-100 hover:shadow-sm transition-all shadow-sm">
                    <i class="bi bi-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                        Add New Penalty
                    </h1>
                    <p class="mt-2 text-gray-600 text-lg max-w-xl">
                        Create and assign a penalty to a resident for community violations.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" form="penalty-form" class="btn-premium">
                    <i class="bi bi-check2-circle"></i>
                    Record Penalty
                </button>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="glass-card border-red-100 bg-red-50/50 p-6 animate-fade-in">
            <div class="flex items-center gap-3 mb-4 text-red-700">
                <i class="bi bi-exclamation-circle-fill text-xl"></i>
                <span class="font-black text-sm uppercase tracking-widest">Validation Errors</span>
            </div>
            <ul class="space-y-2">
                @foreach ($errors->all() as $error)
                    <li class="text-sm font-bold text-red-600 flex items-center gap-2">
                        <span class="w-1 h-1 rounded-full bg-red-400"></span>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="penalty-form" action="{{ route('admin.penalties.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- LEFT COLUMN: FORM DETAILS --}}
            <div class="lg:col-span-2 space-y-8">
                
                {{-- 1. Penalty Details --}}
                <section class="glass-card p-8 space-y-8 relative overflow-hidden group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-xl border border-emerald-100 shadow-sm">1</div>
                        <div>
                            <h4 class="text-xl font-black text-gray-900 tracking-tight">Penalty Details</h4>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Core violation information</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-8">
                        {{-- Resident Selection --}}
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Select Resident <span class="text-red-500">*</span></label>
                            <div class="relative group/select">
                                <select name="resident_id" id="resident_id" x-model="residentId" @change="updateResidentPreview" 
                                    class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm font-medium appearance-none focus:bg-white focus:border-emerald-500 transition-all outline-none cursor-pointer" required>
                                    <option value="">Select a resident...</option>
                                    @foreach($residents as $resident)
                                        <option value="{{ $resident->id }}" 
                                            data-name="{{ $resident->full_name }}"
                                            data-property="Block {{ $resident->block }} / Lot {{ $resident->lot }}"
                                            data-initials="{{ substr($resident->first_name, 0, 1) }}{{ substr($resident->last_name, 0, 1) }}"
                                            {{ old('resident_id') == $resident->id ? 'selected' : '' }}>
                                            {{ $resident->full_name }} (B{{ $resident->block }} L{{ $resident->lot }})
                                        </option>
                                    @endforeach
                                </select>
                                <i class="bi bi-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none group-hover/select:text-emerald-600 transition-colors"></i>
                            </div>
                        </div>

                        {{-- Type & Date --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Penalty Type <span class="text-red-500">*</span></label>
                                <div class="relative group/select">
                                    <select name="penalty_type" id="penalty_type" 
                                        class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm font-medium appearance-none focus:bg-white focus:border-emerald-500 transition-all outline-none cursor-pointer" required>
                                        <option value="general" {{ old('penalty_type')=='general'?'selected':'' }}>General Violation</option>
                                        <option value="late_payment" {{ old('penalty_type')=='late_payment'?'selected':'' }}>Late Payment</option>
                                        <option value="overdue" {{ old('penalty_type')=='overdue'?'selected':'' }}>Overdue Account</option>
                                        <option value="violation" {{ old('penalty_type')=='violation'?'selected':'' }}>Rules Violation</option>
                                        <option value="damage" {{ old('penalty_type')=='damage'?'selected':'' }}>Property Damage</option>
                                    </select>
                                    <i class="bi bi-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none group-hover/select:text-emerald-600 transition-colors"></i>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Date Issued <span class="text-red-500">*</span></label>
                                <input type="date" name="date_issued" id="date_issued" value="{{ old('date_issued', date('Y-m-d')) }}" 
                                    class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm font-medium focus:bg-white focus:border-emerald-500 transition-all outline-none" required>
                            </div>
                        </div>

                        {{-- Reason --}}
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Reason / Description <span class="text-red-500">*</span></label>
                            <textarea name="reason" id="reason" rows="4" 
                                class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm font-medium focus:bg-white focus:border-emerald-500 transition-all outline-none resize-none" 
                                placeholder="Describe the violation in detail..." required>{{ old('reason') }}</textarea>
                        </div>
                    </div>
                </section>

                {{-- 2. Financials --}}
                <section class="glass-card p-8 space-y-8 relative overflow-hidden group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-xl border border-emerald-100 shadow-sm">2</div>
                        <div>
                            <h4 class="text-xl font-black text-gray-900 tracking-tight">Fine Amount</h4>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Financial assessment</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Penalty Amount <span class="text-red-500">*</span></label>
                        <div class="relative group/input max-w-md">
                            <span class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 font-black text-lg transition-colors group-focus-within/input:text-emerald-600">₱</span>
                            <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount') }}" 
                                class="w-full pl-12 pr-6 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-lg font-black focus:bg-white focus:border-emerald-500 transition-all outline-none" 
                                placeholder="0.00" min="0.01" required>
                        </div>
                    </div>
                </section>
            </div>

            {{-- RIGHT COLUMN: PREVIEW & STATUS --}}
            <div class="space-y-8">
                {{-- Resident Preview --}}
                <div class="glass-card p-8 space-y-6 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 rounded-full -mr-16 -mt-16 blur-3xl opacity-50"></div>
                    
                    <h5 class="text-[10px] font-black text-gray-400 uppercase tracking-widest flex items-center gap-3 relative z-10">
                        <i class="bi bi-person text-emerald-600 text-lg"></i>
                        Target Resident
                    </h5>

                    <div class="relative z-10">
                        <template x-if="residentId">
                            <div class="flex items-center gap-5 p-5 rounded-2xl bg-emerald-50/30 border border-emerald-100 shadow-sm animate-fade-in">
                                <div class="w-14 h-14 rounded-2xl bg-white text-emerald-600 flex items-center justify-center font-black text-lg shadow-sm" x-text="residentInitials"></div>
                                <div class="min-w-0">
                                    <p class="text-base font-black text-gray-900 tracking-tight truncate" x-text="residentName"></p>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1" x-text="residentProperty"></p>
                                </div>
                            </div>
                        </template>
                        <template x-if="!residentId">
                            <div class="p-10 text-center border-2 border-dashed border-gray-100 rounded-3xl">
                                <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest">No resident selected</p>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Enforcement Status --}}
                <div class="glass-card p-8 space-y-6 relative overflow-hidden group">
                    <h5 class="text-[10px] font-black text-gray-400 uppercase tracking-widest flex items-center gap-3">
                        <i class="bi bi-shield-check text-emerald-600 text-lg"></i>
                        Enforcement Status
                    </h5>

                    <div class="flex p-1.5 bg-gray-50 rounded-2xl border border-gray-100">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="status" value="unpaid" class="peer hidden" {{ old('status', 'unpaid') == 'unpaid' ? 'checked' : '' }}>
                            <div class="py-3 text-center text-[10px] font-black uppercase tracking-widest rounded-xl transition-all peer-checked:bg-white peer-checked:text-red-600 peer-checked:shadow-sm text-gray-400 hover:text-gray-600">
                                Unpaid
                            </div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="status" value="paid" class="peer hidden" {{ old('status') == 'paid' ? 'checked' : '' }}>
                            <div class="py-3 text-center text-[10px] font-black uppercase tracking-widest rounded-xl transition-all peer-checked:bg-emerald-50 peer-checked:text-emerald-700 peer-checked:shadow-sm text-gray-400 hover:text-gray-600">
                                Paid
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Action Card --}}
                <div class="glass-card bg-gray-900 p-8 relative overflow-hidden group border-none">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all duration-700"></div>
                    
                    <div class="relative z-10 space-y-6">
                        <div class="space-y-2">
                            <p class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Review & Submit</p>
                            <h4 class="text-xl font-black text-white tracking-tight leading-tight">Confirm Violation Recording</h4>
                        </div>
                        
                        <p class="text-[11px] font-medium text-gray-400 leading-relaxed">
                            Once recorded, this penalty will be added to the resident's statement and appear in their dashboard.
                        </p>
                        
                        <button type="submit" form="penalty-form" class="btn-premium w-full py-5 text-xs group/btn">
                            <span>Issue Penalty Record</span>
                            <i class="bi bi-arrow-right text-lg group-hover/btn:translate-x-1 transition-transform"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function penaltyForm() {
        return {
            residentId: '{{ old('resident_id') }}',
            residentName: '',
            residentProperty: '',
            residentInitials: '',
            init() {
                if (this.residentId) {
                    this.$nextTick(() => this.updateResidentPreview());
                }
            },
            updateResidentPreview() {
                const select = document.getElementById('resident_id');
                const selectedOption = select.options[select.selectedIndex];
                
                if (this.residentId && selectedOption.value) {
                    this.residentName = selectedOption.getAttribute('data-name');
                    this.residentProperty = selectedOption.getAttribute('data-property');
                    this.residentInitials = selectedOption.getAttribute('data-initials');
                } else {
                    this.residentName = '';
                    this.residentProperty = '';
                    this.residentInitials = '';
                }
            }
        }
    }
</script>
@endpush
