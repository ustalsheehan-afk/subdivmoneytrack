@extends('resident.layouts.app')

@section('title', 'My Reservations')

@section('content')
<div class="min-h-screen bg-[#F8F9FB] pb-20">
    {{-- Header --}}
    <div class="bg-white border-b border-gray-100 px-8 py-6 sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-black text-gray-900 tracking-tight">My Reservations</h2>
                <p class="text-sm font-medium text-gray-400 mt-1">Manage and track your amenity bookings.</p>
            </div>
            <a href="{{ route('resident.amenities.index') }}" class="px-6 py-3 bg-gray-900 hover:bg-black text-white text-sm font-black rounded-2xl shadow-xl transition-all active:scale-95 flex items-center gap-2 tracking-widest uppercase">
                <i class="bi bi-plus-lg"></i>
                New Booking
            </a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-10">
        @if(session('success'))
            <div class="bg-[#E6F6EC] border border-[#D1FAE5] p-4 rounded-2xl shadow-sm mb-8 flex items-center gap-3">
                <i class="bi bi-check-circle-fill text-[#059669]"></i>
                <p class="text-sm font-bold text-[#065F46]">{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-[32px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100/50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Amenity</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Schedule</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Status</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-right">Payment</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($reservations as $reservation)
                            <tr class="hover:bg-gray-50/80 transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl overflow-hidden shadow-sm border border-gray-100">
                                            @if($reservation->amenity->image)
                                                <img class="w-full h-full object-cover" src="{{ Storage::url($reservation->amenity->image) }}" alt="">
                                            @else
                                                <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                                    <i class="bi bi-building text-gray-400"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-gray-900 tracking-tight">{{ $reservation->amenity->name }}</p>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">ID #{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="text-sm font-bold text-gray-900 tracking-tight">{{ $reservation->date->format('M d, Y') }}</p>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">{{ $reservation->time_slot }}</p>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col gap-1.5">
                                        {{-- Reservation Status --}}
                                        <div>
                                            @if($reservation->status === 'approved')
                                                <span class="inline-flex items-center px-3 py-1 bg-[#E6F6EC] text-[#059669] text-[10px] font-black rounded-full uppercase tracking-widest">
                                                    <i class="bi bi-check-circle-fill mr-1.5"></i> Approved
                                                </span>
                                            @elseif($reservation->status === 'pending')
                                                <span class="inline-flex items-center px-3 py-1 bg-[#FEF3C7] text-[#D97706] text-[10px] font-black rounded-full uppercase tracking-widest">
                                                    <i class="bi bi-clock-fill mr-1.5"></i> Pending
                                                </span>
                                            @elseif($reservation->status === 'rejected')
                                                <span class="inline-flex items-center px-3 py-1 bg-[#FEE2E2] text-[#DC2626] text-[10px] font-black rounded-full uppercase tracking-widest">
                                                    <i class="bi bi-x-circle-fill mr-1.5"></i> Rejected
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <p class="text-sm font-black text-[#2563EB] tracking-tight">₱{{ number_format($reservation->total_price, 2) }}</p>
                                    
                                    @if($reservation->payment_status === 'paid')
                                        <span class="text-[9px] font-black text-[#059669] uppercase tracking-widest">Verified</span>
                                    @elseif($reservation->payment_status === 'submitted')
                                        <span class="text-[9px] font-black text-blue-600 uppercase tracking-widest">Awaiting Verification</span>
                                    @elseif($reservation->payment_status === 'rejected')
                                        <span class="text-[9px] font-black text-red-600 uppercase tracking-widest">Payment Rejected</span>
                                    @else
                                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Pending Payment</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <a href="{{ route('resident.amenities.reservation.show', $reservation->id) }}" 
                                       class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gray-50 text-gray-400 hover:bg-gray-900 hover:text-white transition-all shadow-sm group-hover:scale-110">
                                        <i class="bi bi-arrow-right-short text-2xl"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center">
                                    <div class="max-w-xs mx-auto">
                                        <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-gray-300">
                                            <i class="bi bi-calendar-x text-3xl"></i>
                                        </div>
                                        <h3 class="text-gray-900 font-black tracking-tight">No reservations yet</h3>
                                        <p class="text-sm text-gray-400 font-medium mt-1 mb-6">Start by exploring our community amenities and book your first slot.</p>
                                        <a href="{{ route('resident.amenities.index') }}" class="inline-flex items-center gap-2 text-blue-600 font-black text-xs uppercase tracking-widest hover:text-blue-700 transition-colors">
                                            Explore Amenities <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
