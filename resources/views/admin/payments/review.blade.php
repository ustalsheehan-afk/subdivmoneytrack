@extends('layouts.admin')

@section('title', 'Review Payment')
@section('page-title', 'Review Payment')

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
                <button onclick="window.history.back()" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white border border-gray-100 text-gray-400 hover:text-emerald-600 hover:border-emerald-100 hover:shadow-sm transition-all shadow-sm">
                    <i class="bi bi-arrow-left text-xl"></i>
                </button>
                <div>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                        Review Payment
                    </h1>
                    <p class="mt-2 text-gray-600 text-lg max-w-xl">
                        Verify transaction details before recording to the resident's ledger.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                @if(isset($is_existing) && $is_existing)
                    @if($payment->status === 'pending')
                        <form action="{{ route('admin.payments.approve', $payment->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-premium">
                                <i class="bi bi-check2-circle"></i>
                                Approve
                            </button>
                        </form>
                        <form action="{{ route('admin.payments.reject', $payment->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="h-[52px] px-6 bg-white border border-red-100 text-red-600 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-sm hover:bg-red-50 transition-all active:scale-95 flex items-center gap-2">
                                <i class="bi bi-x-circle text-base"></i>
                                Reject
                            </button>
                        </form>
                    @elseif($payment->status === 'approved')
                        <a href="{{ route('admin.payments.receipt', $payment->id) }}" target="_blank" class="btn-premium">
                            <i class="bi bi-printer-fill"></i>
                            Print Receipt
                        </a>
                    @else
                        <div class="px-6 py-3 bg-gray-100 text-gray-500 rounded-xl font-bold text-sm border border-gray-200 flex items-center gap-2">
                            <i class="bi bi-info-circle"></i>
                            Payment {{ ucfirst($payment->status) }}
                        </div>
                    @endif
                @else
                    <form action="{{ route('admin.payments.confirm') }}" method="POST">
                        @csrf
                        <input type="hidden" name="resident_id" value="{{ $data['resident_id'] }}">
                        <input type="hidden" name="due_id" value="{{ $data['due_id'] }}">
                        <input type="hidden" name="amount" value="{{ $data['amount'] }}">
                        <input type="hidden" name="date_paid" value="{{ $data['date_paid'] }}">
                        <input type="hidden" name="payment_method" value="{{ $data['payment_method'] }}">
                        @if($proofPath)
                            <input type="hidden" name="temp_proof" value="{{ $proofPath }}">
                        @endif
                        <button type="submit" class="btn-premium">
                            <i class="bi bi-check2-circle"></i>
                            Confirm & Record
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- LEFT COLUMN: DETAILS --}}
        <div class="lg:col-span-2 space-y-8">
            
            {{-- TRANSACTION SUMMARY --}}
            <div class="glass-card p-10 relative overflow-hidden group">
                <div class="flex items-center gap-4 mb-10 relative z-10">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-xl border border-emerald-100 shadow-sm">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-black text-gray-900 tracking-tight">Transaction Summary</h4>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Core payment information</p>
                    </div>
                </div>

                <div class="space-y-6 relative z-10">
                    {{-- Resident --}}
                    <div class="flex items-center justify-between py-5 border-b border-gray-50">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Resident</span>
                        <div class="text-right">
                            <p class="text-base font-black text-gray-900 tracking-tight">{{ $resident->full_name }}</p>
                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Blk {{ $resident->block }} - Lot {{ $resident->lot }}</p>
                        </div>
                    </div>

                    {{-- Billing --}}
                    <div class="flex items-center justify-between py-5 border-b border-gray-50">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Billing Reference</span>
                        <span class="text-base font-black text-gray-900 tracking-tight">{{ $due->title }}</span>
                    </div>

                    {{-- Amount --}}
                    <div class="flex items-center justify-between py-5 border-b border-gray-50">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Amount Paid</span>
                        <span class="text-3xl font-black text-emerald-600 tracking-tighter">₱{{ number_format($data['amount'], 2) }}</span>
                    </div>

                    {{-- Method & Date --}}
                    <div class="grid grid-cols-2 gap-12 py-5">
                        <div class="space-y-2">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Payment Method</span>
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl">
                                <i class="bi bi-credit-card"></i>
                                {{ str_replace('_', ' ', $data['payment_method']) }}
                            </span>
                        </div>
                        <div class="space-y-2 text-right">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Transaction Date</span>
                            <p class="text-sm font-black text-gray-900">{{ \Carbon\Carbon::parse($data['date_paid'])->format('M d, Y') }}</p>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ \Carbon\Carbon::parse($data['date_paid'])->format('g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PROOF OF PAYMENT --}}
            @if($proofPath)
            <div class="glass-card p-10 relative overflow-hidden group">
                <div class="flex items-center gap-4 mb-8 relative z-10">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-xl border border-emerald-100 shadow-sm">
                        <i class="bi bi-image"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-black text-gray-900 tracking-tight">Evidence Attached</h4>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Proof of transaction</p>
                    </div>
                </div>

                <div class="relative z-10 p-6 rounded-3xl bg-gray-50 border border-gray-100 flex justify-center overflow-hidden">
                    <img src="{{ asset('storage/' . $proofPath) }}" class="max-h-[500px] rounded-2xl shadow-2xl hover:scale-[1.02] transition-transform duration-500 cursor-zoom-in" onclick="window.open(this.src)">
                </div>
            </div>
            @endif
        </div>

        {{-- RIGHT COLUMN: ACTIONS & INFO --}}
        <div class="space-y-8">
            {{-- STATUS CARD --}}
            <div class="glass-card bg-gray-900 p-8 relative overflow-hidden group border-none">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all duration-700"></div>
                
                <div class="relative z-10 space-y-6">
                    <div class="space-y-2">
                        <p class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Next Action</p>
                        <h4 class="text-xl font-black text-white tracking-tight leading-tight">Generate Official Receipt</h4>
                    </div>
                    
                    <p class="text-[11px] font-medium text-gray-400 leading-relaxed">
                        By confirming this transaction, the system will automatically:
                    </p>
                    
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3 text-[10px] font-black text-white uppercase tracking-widest">
                            <i class="bi bi-check2 text-emerald-500 text-lg"></i>
                            Update Resident Balance
                        </li>
                        <li class="flex items-center gap-3 text-[10px] font-black text-white uppercase tracking-widest">
                            <i class="bi bi-check2 text-emerald-500 text-lg"></i>
                            Record to Ledger
                        </li>
                        <li class="flex items-center gap-3 text-[10px] font-black text-white uppercase tracking-widest">
                            <i class="bi bi-check2 text-emerald-500 text-lg"></i>
                            Prepare Half-A4 Receipt
                        </li>
                    </ul>
                </div>
            </div>

            {{-- HELP CARD --}}
            <div class="glass-card p-8 text-center space-y-4">
                <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto border border-emerald-100 shadow-sm">
                    <i class="bi bi-info-circle text-2xl"></i>
                </div>
                <h5 class="text-sm font-black text-gray-900 uppercase tracking-widest">Verification Required</h5>
                <p class="text-[11px] font-medium text-gray-500 leading-relaxed">
                    Ensure the reference number and amount match the attached proof of payment before proceeding.
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
</style>
@endsection