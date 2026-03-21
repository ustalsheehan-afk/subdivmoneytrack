@extends('resident.layouts.app')

@section('title', 'Reservation Details')

@section('content')
<div class="min-h-screen bg-[#F8F9FB] pb-20">
    {{-- Top Navigation Bar --}}
    <div class="bg-white border-b border-gray-100 px-8 py-4 flex items-center justify-between sticky top-0 z-30">
        <div class="flex items-center gap-4">
            <a href="{{ route('resident.my-reservations.index') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 text-gray-400 hover:bg-gray-900 hover:text-white transition-all group">
                <i class="bi bi-arrow-left text-lg group-hover:-translate-x-0.5 transition-transform"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-gray-900 tracking-tight">Reservation Details</h1>
                <p class="text-xs font-medium text-gray-400">Manage your amenity booking</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 border border-gray-200">
                <i class="bi bi-person-circle"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700">{{ Auth::user()->first_name }}</span>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            {{-- LEFT PANEL: Reservation Details --}}
            <div class="lg:col-span-7 space-y-8">
                
                {{-- Rejection Note (Conditional) --}}
                @if($reservation->status === 'rejected' || $reservation->rejection_reason)
                <div class="bg-red-50/50 border border-red-100/50 rounded-3xl p-6 backdrop-blur-sm shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-2xl bg-red-100 flex items-center justify-center text-red-600 shrink-0 shadow-sm">
                            <i class="bi bi-info-circle-fill text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-red-900 text-[10px] uppercase tracking-[0.2em] mb-1.5">Reservation Update</h3>
                            <p class="text-red-700 font-medium leading-relaxed text-sm">{{ $reservation->rejection_reason }}</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Main Details Card --}}
                <div class="bg-white rounded-[32px] border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] overflow-hidden">
                    <div class="p-8 md:p-10">
                        <div class="flex items-center justify-between mb-12">
                            <div>
                                <h2 class="text-3xl font-black text-gray-900 tracking-tight leading-none">{{ $reservation->amenity->name }}</h2>
                                <p class="text-gray-400 font-medium mt-3 text-sm">Booking ID: #{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Booking Status</p>
                                @if($reservation->status === 'approved')
                                    <span class="px-4 py-1.5 bg-[#E6F6EC] text-[#059669] rounded-full font-bold text-[10px] uppercase tracking-widest border border-[#D1FAE5]">Approved</span>
                                @elseif($reservation->status === 'pending')
                                    <span class="px-4 py-1.5 bg-[#FEF3C7] text-[#D97706] rounded-full font-bold text-[10px] uppercase tracking-widest border border-[#FDE68A]">Pending</span>
                                @elseif($reservation->status === 'rejected')
                                    <span class="px-4 py-1.5 bg-[#FEE2E2] text-[#DC2626] rounded-full font-bold text-[10px] uppercase tracking-widest border border-[#FECACA]">Rejected</span>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                            {{-- Date --}}
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Date</p>
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 border border-gray-100">
                                        <i class="bi bi-calendar-event text-xl"></i>
                                    </div>
                                    <p class="text-xl font-bold text-gray-900 tracking-tight">{{ $reservation->date->format('F j, Y') }}</p>
                                </div>
                            </div>

                            {{-- Time Slot --}}
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Time Slot</p>
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 border border-gray-100">
                                        <i class="bi bi-clock text-xl"></i>
                                    </div>
                                    <p class="text-xl font-bold text-gray-900 tracking-tight">{{ $reservation->time_slot }}</p>
                                </div>
                            </div>

                            {{-- Guests --}}
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Guests</p>
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 border border-gray-100">
                                        <i class="bi bi-people text-xl"></i>
                                    </div>
                                    <p class="text-xl font-bold text-gray-900 tracking-tight">{{ $reservation->guest_count }} People</p>
                                </div>
                            </div>

                            {{-- Total Price --}}
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Total Price</p>
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100 shadow-sm shadow-blue-100/50">
                                        <i class="bi bi-cash-stack text-xl"></i>
                                    </div>
                                    <p class="text-3xl font-black text-blue-600 tracking-tighter">₱{{ number_format($reservation->total_price, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Equipment Add-ons (Conditional) --}}
                        @if(!empty($reservation->equipment_addons))
                        <div class="mt-14 pt-10 border-t border-gray-50">
                            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6">Equipment Add-ons</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($reservation->equipment_addons as $item)
                                <div class="flex justify-between items-center p-5 rounded-[20px] bg-gray-50/50 border border-gray-100/50 hover:bg-white hover:shadow-md hover:border-blue-100 transition-all duration-300 group">
                                    <span class="text-gray-700 font-bold text-sm flex items-center gap-3">
                                        <div class="w-1.5 h-1.5 rounded-full bg-blue-500/40 group-hover:bg-blue-500 transition-colors"></div>
                                        {{ $item['name'] }}
                                    </span>
                                    <span class="font-black text-gray-900 tracking-tight">₱{{ number_format($item['price'], 2) }}</span>
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
                <div class="bg-white border border-gray-100 rounded-[32px] p-8 shadow-[0_8px_30px_rgb(0,0,0,0.02)] h-full flex flex-col">
                    <h3 class="text-gray-900 font-black text-xl mb-10 tracking-tight">Payment Status</h3>

                    {{-- Dynamic Status Indicator --}}
                    <div class="mb-10">
                        @if($reservation->payment_status === 'paid')
                            <div class="flex flex-col items-center text-center p-12 bg-[#E6F6EC] rounded-[28px] border border-[#D1FAE5] relative overflow-hidden group">
                                <div class="w-24 h-24 bg-white text-[#059669] rounded-[24px] shadow-sm flex items-center justify-center mb-6 transform rotate-3 hover:rotate-0 transition-transform duration-500 relative z-10">
                                    <i class="bi bi-patch-check-fill text-5xl"></i>
                                </div>
                                <span class="font-black text-[#065F46] text-2xl block tracking-tight relative z-10">Payment Verified</span>
                                <p class="text-[#059669] text-[10px] font-black mt-3 uppercase tracking-[0.2em] opacity-80 relative z-10">
                                    {{ $reservation->verified_at ? \Carbon\Carbon::parse($reservation->verified_at)->format('M d, Y') : 'Transaction Completed' }}
                                </p>
                            </div>
                        @elseif($reservation->payment_status === 'submitted')
                            <div class="flex flex-col items-center text-center p-12 bg-blue-50 rounded-[28px] border border-blue-100 relative overflow-hidden">
                                <div class="w-24 h-24 bg-white text-blue-600 rounded-[24px] shadow-sm flex items-center justify-center mb-6 animate-pulse">
                                    <i class="bi bi-shield-check text-5xl"></i>
                                </div>
                                <span class="font-black text-blue-900 text-2xl block tracking-tight">Verification in Progress</span>
                                <p class="text-blue-600 text-[10px] font-black mt-3 uppercase tracking-[0.2em]">Waiting for Admin</p>
                            </div>
                        @elseif($reservation->payment_method === 'cash')
                            <div class="flex flex-col items-center text-center p-12 bg-[#FFFBEB] rounded-[28px] border border-[#FEF3C7] relative overflow-hidden">
                                <div class="w-24 h-24 bg-white text-[#D97706] rounded-[24px] shadow-sm flex items-center justify-center mb-6 transform -rotate-3 hover:rotate-0 transition-transform duration-500">
                                    <i class="bi bi-cash-stack text-5xl"></i>
                                </div>
                                <span class="font-black text-[#92400E] text-2xl block tracking-tight uppercase">Pending Cash</span>
                                <p class="text-[#D97706] text-[10px] font-black mt-3 uppercase tracking-[0.2em] opacity-70">Please pay at the admin office</p>
                            </div>
                        @else 
                            {{-- Pending GCash --}}
                            <div class="flex flex-col items-center text-center p-12 bg-orange-50 rounded-[28px] border border-orange-100 relative overflow-hidden">
                                <div class="w-24 h-24 bg-white text-orange-600 rounded-[24px] shadow-sm flex items-center justify-center mb-6">
                                    <i class="bi bi-wallet2 text-5xl"></i>
                                </div>
                                <span class="font-black text-orange-900 text-2xl block tracking-tight uppercase">Payment Required</span>
                                <p class="text-orange-600 text-[10px] font-black mt-3 uppercase tracking-[0.2em] opacity-70">GCash Proof Required</p>
                            </div>
                        @endif
                    </div>

                    {{-- GCash Upload Form --}}
                    @if(in_array($reservation->payment_status, ['pending', 'rejected']) && $reservation->payment_method === 'gcash')
                        <form action="{{ route('resident.amenities.reservation.payment', $reservation->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8 flex-1 flex flex-col justify-end" x-data="{ fileName: '' }">
                            @csrf
                            
                            <div class="bg-gradient-to-br from-blue-600 to-blue-700 text-white p-8 rounded-[28px] shadow-2xl shadow-blue-200/50 relative overflow-hidden group">
                                <div class="absolute -right-8 -bottom-8 opacity-10 group-hover:scale-110 transition-transform duration-700">
                                    <i class="bi bi-phone text-[140px]"></i>
                                </div>
                                <p class="text-[10px] uppercase font-black tracking-[0.4em] opacity-70 mb-4">Send Payment To</p>
                                <p class="font-mono text-3xl font-black tracking-widest mb-2">0905 530 3469</p>
                                <p class="text-sm font-bold opacity-90 tracking-wide">Mussah Ustal (GCash)</p>
                            </div>

                            <div class="space-y-5">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Reference No.</label>
                                    <input type="text" name="payment_reference_no" required 
                                        class="w-full p-4 border border-gray-100 bg-gray-50 rounded-[20px] text-base font-mono focus:ring-4 focus:ring-blue-500/5 focus:border-blue-500 transition-all outline-none"
                                        placeholder="Enter 13-digit GCash Ref">
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Payment Receipt</label>
                                    <div class="relative group">
                                        <input type="file" name="payment_proof" required accept="image/*" 
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                            @change="fileName = $event.target.files[0].name">
                                        <div class="border-2 border-dashed border-gray-200 rounded-[24px] p-10 text-center group-hover:bg-gray-50 group-hover:border-blue-400 transition-all duration-300">
                                            <i class="bi bi-cloud-arrow-up text-4xl text-gray-300 group-hover:text-blue-500 transition-colors"></i>
                                            <p class="text-xs font-black text-gray-400 mt-4" x-text="fileName || 'Upload Screenshot'"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-5 bg-gray-900 hover:bg-black text-white font-black rounded-[24px] text-sm transition-all shadow-2xl active:scale-[0.98] tracking-[0.2em] uppercase mt-4">
                                Submit Verification
                            </button>
                        </form>
                    @endif

                    {{-- Submitted Proof Preview --}}
                    @if($reservation->payment_proof)
                        <div class="mt-auto pt-10 border-t border-gray-50">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6">Verification Proof</p>
                            <div class="relative rounded-[28px] overflow-hidden border border-gray-100 group shadow-sm hover:shadow-xl transition-all duration-500">
                                <img src="{{ Storage::url($reservation->payment_proof) }}" class="w-full h-56 object-cover transform group-hover:scale-105 transition-transform duration-700">
                                <a href="{{ Storage::url($reservation->payment_proof) }}" target="_blank" 
                                    class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center backdrop-blur-[2px]">
                                    <div class="bg-white/20 p-5 rounded-full backdrop-blur-md border border-white/30 transform scale-75 group-hover:scale-100 transition-transform duration-500">
                                        <i class="bi bi-eye-fill text-white text-3xl"></i>
                                    </div>
                                </a>
                            </div>
                            <div class="mt-6 flex items-center justify-between p-5 bg-gray-50 rounded-[24px] border border-gray-100/50">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Reference</span>
                                <span class="text-sm font-mono font-black text-gray-800 tracking-tight">{{ $reservation->payment_reference_no }}</span>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
