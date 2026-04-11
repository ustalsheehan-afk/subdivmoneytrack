@extends('resident.layouts.app')

@section('title', 'Reservation Details')

@section('content')
<div class="min-h-[calc(100vh-4rem)] bg-[#F6F7F9] overflow-y-auto custom-scrollbar">
    {{-- Top Navigation Bar --}}
    <div class="bg-white/90 backdrop-blur-sm border-b border-gray-100 px-6 sm:px-8 py-4 flex items-center justify-between sticky top-0 z-30 shadow-[0_1px_0_rgba(15,23,42,0.04)]">
        <div class="flex items-center gap-4">
            <a href="{{ route('resident.my-reservations.index') }}" class="w-10 h-10 flex items-center justify-center rounded-lg bg-gray-50 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 border border-gray-100 transition-all group">
                <i class="bi bi-arrow-left text-lg group-hover:-translate-x-0.5 transition-transform"></i>
            </a>
            <div>
                <h1 class="text-lg sm:text-xl font-black text-gray-900 tracking-tight">Reservation Details</h1>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.28em]">Manage your amenity booking</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-emerald-500 text-white flex items-center justify-center shadow-sm border border-emerald-400/60">
                <i class="bi bi-person-fill"></i>
            </div>
            <span class="text-xs font-black text-gray-700 uppercase tracking-[0.22em]">{{ Auth::user()->first_name }}</span>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-10 animate-fade-in">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8">
            
            {{-- LEFT PANEL: Reservation Details --}}
            <div class="lg:col-span-7 space-y-6 sm:space-y-8">
                
                {{-- Rejection Note (Conditional) --}}
                @if($reservation->status === 'rejected' || $reservation->rejection_reason)
                <div class="bg-red-50 border border-red-100 rounded-2xl p-6 sm:p-8 shadow-sm">
                    <div class="flex items-start gap-4 sm:gap-6">
                        <div class="w-12 h-12 rounded-xl bg-red-500 flex items-center justify-center text-white shrink-0 shadow-sm">
                            <i class="bi bi-exclamation-triangle-fill text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-red-900 text-[10px] uppercase tracking-[0.2em] mb-2">Reservation Update</h3>
                            <p class="text-red-700 font-medium leading-relaxed text-sm">{{ $reservation->rejection_reason }}</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Cancellation Note (Conditional) --}}
                @if($reservation->status === 'cancelled')
                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-6 sm:p-8 shadow-sm">
                    <div class="flex items-start gap-4 sm:gap-6">
                        <div class="w-12 h-12 rounded-xl bg-gray-500 flex items-center justify-center text-white shrink-0 shadow-sm">
                            <i class="bi bi-x-circle-fill text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-gray-900 text-[10px] uppercase tracking-[0.2em] mb-2">Reservation Cancelled</h3>
                            <p class="text-gray-700 font-medium leading-relaxed text-sm mb-4">{{ $reservation->cancellation_reason }}</p>
                            @if($reservation->cancelled_at)
                            <p class="text-gray-500 text-xs">Cancelled on {{ \Carbon\Carbon::parse($reservation->cancelled_at)->format('M d, Y \a\t g:i A') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                {{-- Main Details Card --}}
                <div class="glass-card overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                    <div class="p-6 sm:p-8 lg:p-10">
                        <div class="flex items-start justify-between gap-6 mb-8 sm:mb-10">
                            <div class="space-y-3">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-emerald-500/10 border border-emerald-500/15">
                                    <i class="bi bi-building text-emerald-600 text-[10px]"></i>
                                    <span class="text-[9px] font-black text-emerald-600 uppercase tracking-[0.24em]">Amenity Booking</span>
                                </div>
                                <h2 class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight leading-none">{{ $reservation->amenity->name }}</h2>
                                <p class="text-gray-400 font-black text-[10px] uppercase tracking-widest">Booking ID: #{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Booking Status</p>
                                @if($reservation->status === 'approved')
                                    <span class="badge-standard bg-emerald-50 text-emerald-600 border border-emerald-100">
                                        <i class="bi bi-check-circle-fill mr-1.5"></i> Approved
                                    </span>
                                @elseif($reservation->status === 'pending')
                                    <span class="badge-standard bg-orange-50 text-orange-600 border border-orange-100">
                                        <i class="bi bi-clock-fill mr-1.5"></i> Pending
                                    </span>
                                @elseif($reservation->status === 'rejected')
                                    <span class="badge-standard bg-red-50 text-red-600 border border-red-100">
                                        <i class="bi bi-x-circle-fill mr-1.5"></i> Rejected
                                    </span>
                                @elseif($reservation->status === 'cancelled')
                                    <span class="badge-standard bg-gray-50 text-gray-600 border border-gray-100">
                                        <i class="bi bi-x-circle-fill mr-1.5"></i> Cancelled
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            {{-- Date --}}
                            <div class="bg-gray-50 p-5 sm:p-6 rounded-2xl border border-gray-100 group hover:bg-white hover:shadow-sm transition-all duration-300">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Reservation Date</p>
                                <div class="flex items-center gap-5">
                                    <div class="w-12 h-12 rounded-xl bg-emerald-500 flex items-center justify-center text-white shadow-sm border border-emerald-400/60 group-hover:scale-105 transition-transform">
                                        <i class="bi bi-calendar-event text-xl"></i>
                                    </div>
                                    <p class="text-xl font-black text-gray-900 tracking-tight tabular-nums">{{ $reservation->date->format('F j, Y') }}</p>
                                </div>
                            </div>

                            {{-- Time Slot --}}
                            <div class="bg-gray-50 p-5 sm:p-6 rounded-2xl border border-gray-100 group hover:bg-white hover:shadow-sm transition-all duration-300">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Time Slot</p>
                                <div class="flex items-center gap-5">
                                    <div class="w-12 h-12 rounded-xl bg-emerald-500 flex items-center justify-center text-white shadow-sm border border-emerald-400/60 group-hover:scale-105 transition-transform">
                                        <i class="bi bi-clock text-xl"></i>
                                    </div>
                                    <p class="text-xl font-black text-gray-900 tracking-tight tabular-nums">{{ $reservation->time_slot }}</p>
                                </div>
                            </div>

                            {{-- Guests --}}
                            <div class="bg-gray-50 p-5 sm:p-6 rounded-2xl border border-gray-100 group hover:bg-white hover:shadow-sm transition-all duration-300">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Guest Count</p>
                                <div class="flex items-center gap-5">
                                    <div class="w-12 h-12 rounded-xl bg-emerald-500 flex items-center justify-center text-white shadow-sm border border-emerald-400/60 group-hover:scale-105 transition-transform">
                                        <i class="bi bi-people text-xl"></i>
                                    </div>
                                    <p class="text-xl font-black text-gray-900 tracking-tight tabular-nums">{{ $reservation->guest_count }} People</p>
                                </div>
                            </div>

                            {{-- Total Price --}}
                            <div class="bg-gray-50 p-6 rounded-[24px] border border-gray-100 group hover:bg-white hover:shadow-xl transition-all duration-500">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Total Amount</p>
                                <div class="flex items-center gap-5">
                                    <div class="w-12 h-12 rounded-2xl bg-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-500/20 border border-emerald-400 group-hover:scale-110 transition-transform">
                                        <i class="bi bi-cash-stack text-xl"></i>
                                    </div>
                                    <p class="text-3xl font-black text-black tracking-tighter tabular-nums">₱{{ number_format($reservation->total_price, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Equipment Add-ons (Conditional) --}}
                        @if(!empty($reservation->equipment_addons))
                        <div class="mt-14 pt-10 border-t border-gray-100">
                            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mb-8 flex items-center gap-3">
                                <span class="w-8 h-px bg-gray-200"></span>
                                Equipment Add-ons
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($reservation->equipment_addons as $item)
                                <div class="flex justify-between items-center p-5 rounded-2xl bg-gray-50 border border-gray-100 hover:bg-white hover:shadow-sm transition-all duration-300 group">
                                    <span class="text-gray-900 font-black text-sm flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center border border-gray-100 text-emerald-500 group-hover:scale-105 transition-transform">
                                            <i class="bi bi-plus-lg"></i>
                                        </div>
                                        {{ $item['name'] }}
                                    </span>
                                    <span class="font-black text-gray-900 tracking-tight tabular-nums">₱{{ number_format($item['price'], 2) }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- RIGHT PANEL: Payment Status --}}
            <div class="lg:col-span-5">
                <div class="bg-white border border-gray-100 rounded-2xl p-6 sm:p-8 shadow-sm h-full flex flex-col relative overflow-hidden group/card">
                    <div class="absolute -right-20 -top-20 w-64 h-64 bg-brand-accent/5 rounded-full blur-3xl group-hover/card:bg-brand-accent/8 transition-all duration-700"></div>
                    
                    <h3 class="text-gray-900 font-black text-xl mb-8 tracking-tight relative z-10">Payment Overview</h3>

                    {{-- Dynamic Status Indicator --}}
                    <div class="mb-10 relative z-10">
                        @if($reservation->payment_status === 'paid')
                            <div class="flex flex-col items-center text-center p-10 bg-emerald-500 rounded-2xl border border-emerald-400 relative overflow-hidden group/status shadow-sm">
                                <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                                <div class="w-20 h-20 bg-white text-emerald-500 rounded-xl shadow-sm flex items-center justify-center mb-5 transform rotate-1 hover:rotate-0 transition-transform duration-500 relative z-10 border border-emerald-100">
                                    <i class="bi bi-patch-check-fill text-5xl"></i>
                                </div>
                                <span class="font-black text-black text-2xl block tracking-tight relative z-10 uppercase">Verified</span>
                                <p class="text-black/80 text-[10px] font-black mt-3 uppercase tracking-[0.2em] relative z-10">
                                    {{ $reservation->verified_at ? \Carbon\Carbon::parse($reservation->verified_at)->format('M d, Y') : 'Transaction Completed' }}
                                </p>
                            </div>
                        @elseif($reservation->payment_status === 'submitted')
                            <div class="flex flex-col items-center text-center p-10 bg-[#081412] rounded-2xl border border-white/5 relative overflow-hidden group/status shadow-sm">
                                <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-500/10 rounded-full blur-3xl"></div>
                                <div class="w-20 h-20 bg-white/5 text-blue-400 rounded-xl shadow-sm flex items-center justify-center mb-5 animate-pulse border border-white/10">
                                    <i class="bi bi-shield-check text-5xl"></i>
                                </div>
                                <span class="font-black text-white text-2xl block tracking-tight uppercase">In Review</span>
                                <p class="text-white/40 text-[10px] font-black mt-3 uppercase tracking-[0.2em]">Waiting for Admin</p>
                            </div>
                        @elseif($reservation->payment_method === 'cash')
                            <div class="flex flex-col items-center text-center p-10 bg-orange-50 rounded-2xl border border-orange-100 relative overflow-hidden shadow-sm">
                                <div class="w-20 h-20 bg-white text-orange-500 rounded-xl shadow-sm flex items-center justify-center mb-5 transform -rotate-1 hover:rotate-0 transition-transform duration-500 border border-orange-50">
                                    <i class="bi bi-cash-stack text-5xl"></i>
                                </div>
                                <span class="font-black text-orange-900 text-2xl block tracking-tight uppercase">Pending Cash</span>
                                <p class="text-orange-600 text-[10px] font-black mt-3 uppercase tracking-[0.2em]">Pay at Administration Office</p>
                            </div>
                        @else 
                            {{-- Pending GCash --}}
                            <div class="flex flex-col items-center text-center p-10 bg-orange-50 rounded-2xl border border-orange-100 relative overflow-hidden shadow-sm">
                                <div class="w-20 h-20 bg-white text-orange-500 rounded-xl shadow-sm flex items-center justify-center mb-5 border border-orange-50">
                                    <i class="bi bi-wallet2 text-5xl"></i>
                                </div>
                                <span class="font-black text-orange-900 text-2xl block tracking-tight uppercase">Proof Required</span>
                                <p class="text-orange-600 text-[10px] font-black mt-3 uppercase tracking-[0.2em]">GCash Transfer Proof</p>
                            </div>
                        @endif
                    </div>

                    {{-- GCash Upload Form --}}
                    @if(in_array($reservation->payment_status, ['pending', 'rejected']) && $reservation->payment_method === 'gcash')
                        <form action="{{ route('resident.amenities.reservation.payment', $reservation->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8 flex-1 flex flex-col justify-end relative z-10" x-data="{ fileName: '' }">
                            @csrf
                            
                            <div class="bg-[#081412] p-10 rounded-[32px] shadow-2xl relative overflow-hidden group/gcash text-center">
                                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-blue-500/10 rounded-full blur-3xl"></div>
                                <p class="text-[9px] uppercase font-black tracking-[0.4em] text-white/30 mb-6">Digital Payment Gateway</p>
                                <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-2">GCash Account</p>
                                <p class="font-mono text-4xl font-black text-white tracking-[0.1em] tabular-nums mb-4">0905 530 3469</p>
                                <p class="text-xs font-black text-white uppercase tracking-widest opacity-60">Mussah Ustal</p>
                            </div>

                            <div class="space-y-6">
                                <div class="space-y-3">
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] pl-2">Reference No.</label>
                                    <input type="text" name="payment_reference_no" required 
                                        class="w-full p-6 border-2 border-gray-50 bg-gray-50 rounded-[24px] text-lg font-black tracking-widest font-mono focus:ring-0 focus:border-emerald-500 focus:bg-white transition-all outline-none tabular-nums"
                                        placeholder="13-DIGIT CODE">
                                </div>

                                <div class="space-y-3">
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] pl-2">Payment Receipt</label>
                                    <div class="relative group/upload h-24">
                                        <input type="file" name="payment_proof" required accept="image/*" 
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20"
                                            @change="fileName = $event.target.files[0].name">
                                        <div class="absolute inset-0 bg-gray-50 border-2 border-dashed border-gray-200 rounded-[24px] flex items-center justify-between px-8 transition-all group-hover/upload:bg-white group-hover/upload:border-emerald-500/30"
                                             :class="fileName ? 'border-emerald-500 bg-emerald-50/50' : ''">
                                            <div class="flex items-center gap-5">
                                                <i class="bi text-2xl" :class="fileName ? 'bi-file-earmark-check-fill text-emerald-500' : 'bi-cloud-upload text-gray-300 group-hover/upload:text-emerald-500'"></i>
                                                <span class="text-xs font-black text-gray-500 uppercase tracking-widest truncate max-w-[180px]" x-text="fileName || 'Upload Screenshot'"></span>
                                            </div>
                                            <span class="text-[9px] font-black text-emerald-600 uppercase" x-show="fileName">Change</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn-premium w-full py-6 flex justify-center mt-4">
                                Submit Verification <i class="bi bi-shield-check text-lg"></i>
                            </button>
                        </form>
                    @endif

                    {{-- Submitted Proof Preview --}}
                    @if($reservation->payment_proof)
                        <div class="mt-auto pt-10 border-t border-gray-100 relative z-10">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                                <span class="w-8 h-px bg-gray-200"></span>
                                Verification Proof
                            </p>
                            <div class="relative rounded-[32px] overflow-hidden border border-gray-100 group/proof shadow-xl hover:shadow-2xl transition-all duration-700">
                                <img src="{{ Storage::disk('public')->url($reservation->payment_proof) }}" class="w-full h-64 object-cover transform group-hover/proof:scale-110 transition-transform duration-1000">
                                <a href="{{ Storage::disk('public')->url($reservation->payment_proof) }}" target="_blank" 
                                    class="absolute inset-0 bg-[#081412]/80 opacity-0 group-hover/proof:opacity-100 transition-all duration-500 flex flex-col items-center justify-center backdrop-blur-sm">
                                    <div class="bg-white/10 p-6 rounded-full backdrop-blur-md border border-white/20 transform scale-50 group-hover/proof:scale-100 transition-transform duration-500 mb-4 shadow-2xl">
                                        <i class="bi bi-arrows-fullscreen text-white text-3xl"></i>
                                    </div>
                                    <span class="text-[10px] font-black text-white uppercase tracking-[0.3em]">View Receipt</span>
                                </a>
                            </div>
                            <div class="mt-8 flex items-center justify-between p-6 bg-gray-50 rounded-[24px] border border-gray-100 group/ref hover:bg-white hover:shadow-xl transition-all duration-500">
                                <div>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">GCash Reference</p>
                                    <p class="text-lg font-black text-black tracking-widest font-mono tabular-nums">{{ $reservation->payment_reference_no }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-emerald-500 shadow-sm border border-gray-100">
                                    <i class="bi bi-upc-scan text-xl"></i>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Cancel Reservation Button --}}
                    @if(in_array($reservation->status, ['pending', 'approved']) && !$reservation->cancelled_at)
                        <div class="mt-10 pt-10 border-t border-gray-100 relative z-10">
                            <button type="button" 
                                class="btn-cancel w-full py-6 flex justify-center"
                                onclick="openCancelModal()">
                                Cancel Reservation <i class="bi bi-x-circle text-lg"></i>
                            </button>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

{{-- Cancellation Modal --}}
<div id="cancelModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-[32px] shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-black text-gray-900 tracking-tight">Cancel Reservation</h3>
                <button onclick="closeCancelModal()" class="w-8 h-8 rounded-xl bg-gray-50 text-gray-400 hover:text-gray-600 hover:bg-gray-100 flex items-center justify-center transition-all">
                    <i class="bi bi-x text-lg"></i>
                </button>
            </div>

            <form id="cancelForm" action="{{ route('resident.amenities.reservation.cancel', $reservation->id) }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Reason for Cancellation</label>
                        <select name="cancellation_reason_id" required class="w-full p-4 border-2 border-gray-50 bg-gray-50 rounded-[16px] text-sm font-medium focus:ring-0 focus:border-red-500 focus:bg-white transition-all outline-none">
                            <option value="">Select a reason</option>
                            @foreach($cancellationReasons as $reason)
                            <option value="{{ $reason->id }}">{{ $reason->label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Additional Notes (Optional)</label>
                        <textarea name="notes" rows="3" class="w-full p-4 border-2 border-gray-50 bg-gray-50 rounded-[16px] text-sm font-medium focus:ring-0 focus:border-red-500 focus:bg-white transition-all outline-none resize-none" placeholder="Any additional details..."></textarea>
                    </div>

                    <div class="bg-red-50 border border-red-100 rounded-[16px] p-4">
                        <div class="flex items-start gap-3">
                            <i class="bi bi-exclamation-triangle-fill text-red-500 text-sm mt-0.5"></i>
                            <p class="text-red-700 text-xs font-medium leading-relaxed">This action cannot be undone. Your reservation will be permanently cancelled.</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 mt-8">
                    <button type="button" onclick="closeCancelModal()" class="flex-1 py-4 bg-gray-50 text-gray-700 text-sm font-black uppercase tracking-widest rounded-[16px] hover:bg-gray-100 transition-all">
                        Back
                    </button>
                    <button type="submit" id="confirmCancelBtn" class="flex-1 py-4 bg-red-500 text-white text-sm font-black uppercase tracking-widest rounded-[16px] hover:bg-red-600 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        Confirm Cancellation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .btn-cancel {
        @apply inline-flex items-center gap-3 px-8 py-4 bg-red-500 text-white text-[11px] font-black uppercase tracking-widest rounded-lg hover:bg-red-600 transition-all active:scale-95 border border-red-400/50;
    }
</style>

<script>
function openCancelModal() {
    document.getElementById('cancelModal').classList.remove('hidden');
    document.getElementById('cancelModal').classList.add('flex');
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
    document.getElementById('cancelModal').classList.remove('flex');
}

// Handle form submission
document.getElementById('cancelForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('confirmCancelBtn');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = 'Processing...';
    
    fetch(this.action, {
        method: 'POST',
        body: new FormData(this),
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload the page to show updated status
            window.location.reload();
        } else {
            alert(data.message || 'An error occurred');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    })
    .catch(error => {
        alert('An error occurred. Please try again.');
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
});
</script>

@endsection
