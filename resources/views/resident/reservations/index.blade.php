@extends('resident.layouts.app')

@section('title', 'My Reservations')

@section('content')
<div class="h-full bg-[#F8F9FB] overflow-y-auto custom-scrollbar" x-data="{ filter: 'all' }">
    <div class="max-w-7xl mx-auto px-6 py-8 flex flex-col gap-8 pb-24 animate-fade-in">
        
        <x-resident-hero-header 
            label="Reservation History" 
            icon="bi-calendar-check-fill"
            title="My Reservations" 
            description="Manage and track your community amenity bookings, schedules, and payment status."
            :tabs="[
                ['id' => 'all', 'label' => 'All', 'icon' => 'bi-grid-fill', 'click' => 'filter = \'all\'', 'active_condition' => 'filter === \'all\''],
                ['id' => 'upcoming', 'label' => 'Upcoming', 'icon' => 'bi-calendar-event', 'click' => 'filter = \'upcoming\'', 'active_condition' => 'filter === \'upcoming\''],
                ['id' => 'approved', 'label' => 'Approved', 'icon' => 'bi-shield-check', 'click' => 'filter = \'approved\'', 'active_condition' => 'filter === \'approved\''],
                ['id' => 'completed', 'label' => 'Completed', 'icon' => 'bi-check-circle-fill', 'click' => 'filter = \'completed\'', 'active_condition' => 'filter === \'completed\''],
            ]"
        >
            <x-slot name="actions">
                <a href="{{ route('resident.amenities.index') }}" class="btn-premium">
                    <i class="bi bi-plus-lg"></i>
                    New Booking
                </a>
            </x-slot>
        </x-resident-hero-header>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 p-5 rounded-[24px] shadow-sm flex items-center gap-4 animate-fade-in">
                <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <i class="bi bi-check-lg text-xl"></i>
                </div>
                <p class="text-sm font-black text-emerald-800 uppercase tracking-tight">{{ session('success') }}</p>
            </div>
        @endif

        {{-- ===================== --}}
        {{-- TABLE CONTAINER --}}
        {{-- ===================== --}}
        <div class="glass-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50/50 border-b border-gray-100">
                        <tr>
                            <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Amenity</th>
                            <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Schedule</th>
                            <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                            <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Payment</th>
                            <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($reservations as $reservation)
                            @php
                                $isUpcoming = $reservation->date->isFuture() || $reservation->date->isToday();
                                $isCompleted = $reservation->date->isPast() && !$reservation->date->isToday();
                            @endphp
                            <tr class="cursor-pointer hover:bg-emerald-50/30 transition-all duration-300 group border-l-4 border-transparent hover:border-emerald-500"
                                onclick="window.location.href='{{ route('resident.amenities.reservation.show', $reservation->id) }}'"
                                x-show="filter === 'all' || 
                                       (filter === 'upcoming' && {{ $isUpcoming ? 'true' : 'false' }}) || 
                                       (filter === 'approved' && '{{ $reservation->status }}' === 'approved') || 
                                       (filter === 'completed' && {{ $isCompleted ? 'true' : 'false' }})">
                                
                                <td class="p-6">
                                    <div class="flex items-center gap-5">
                                        <div class="relative shrink-0">
                                            @if($reservation->amenity->image)
                                                <img src="{{ Storage::url($reservation->amenity->image) }}" 
                                                     class="w-14 h-14 rounded-2xl object-cover border-2 border-white shadow-sm group-hover:scale-105 transition-transform duration-500">
                                            @else
                                                <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-300 border-2 border-white shadow-sm">
                                                    <i class="bi bi-building text-2xl"></i>
                                                </div>
                                            @endif
                                            <span class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-white 
                                                {{ $reservation->status === 'approved' ? 'bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]' : 
                                                   ($reservation->status === 'pending' ? 'bg-orange-400 shadow-[0_0_10px_rgba(251,146,60,0.5)]' : 'bg-red-500 shadow-[0_0_10px_rgba(239,68,68,0.5)]') }}"></span>
                                        </div>
                                        <div>
                                            <p class="font-black text-gray-900 group-hover:text-emerald-700 transition-colors tracking-tight text-lg">{{ $reservation->amenity->name }}</p>
                                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.2em] mt-0.5">ID #{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="p-6 text-center">
                                    <p class="text-sm font-black text-gray-900 tracking-tight tabular-nums">{{ $reservation->date->format('M d, Y') }}</p>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1 tabular-nums">{{ $reservation->time_slot }}</p>
                                </td>

                                <td class="p-6 text-center">
                                    @php
                                        $statusClass = $reservation->status === 'approved' 
                                            ? 'bg-emerald-50 text-emerald-600 border-emerald-100' 
                                            : ($reservation->status === 'rejected' ? 'bg-red-50 text-red-600 border-red-100' : 'bg-orange-50 text-orange-600 border-orange-100');
                                        $dotClass = $reservation->status === 'approved' ? 'bg-emerald-500' : ($reservation->status === 'rejected' ? 'bg-red-500' : 'bg-orange-500');
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $statusClass }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span>
                                        {{ $reservation->status }}
                                    </span>
                                </td>

                                <td class="p-6 text-right">
                                    <p class="text-lg font-black text-black tracking-tighter tabular-nums mb-2">₱{{ number_format($reservation->total_price, 2) }}</p>
                                    
                                    @php
                                        $pStatus = $reservation->payment_status;
                                        $pStyle = match($pStatus) {
                                            'paid'      => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'border' => 'border-emerald-100', 'dot' => 'bg-emerald-500', 'label' => 'Verified'],
                                            'submitted' => ['bg' => 'bg-blue-50',    'text' => 'text-blue-600',    'border' => 'border-blue-100',    'dot' => 'bg-blue-500',    'label' => 'Awaiting Verification'],
                                            'rejected'  => ['bg' => 'bg-red-50',     'text' => 'text-red-600',     'border' => 'border-red-100',     'dot' => 'bg-red-500',     'label' => 'Payment Rejected'],
                                            default     => ['bg' => 'bg-gray-50',    'text' => 'text-gray-600',    'border' => 'border-gray-100',    'dot' => 'bg-gray-400',    'label' => 'Pending Payment'],
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest border {{ $pStyle['bg'] }} {{ $pStyle['text'] }} {{ $pStyle['border'] }}">
                                        <span class="w-1 h-1 rounded-full {{ $pStyle['dot'] }}"></span>
                                        {{ $pStyle['label'] }}
                                    </span>
                                </td>

                                <td class="p-6 text-center" onclick="event.stopPropagation()">
                                    <a href="{{ route('resident.amenities.reservation.show', $reservation->id) }}" 
                                       class="w-11 h-11 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 border border-transparent hover:border-emerald-100 transition-all group-hover:scale-110 shadow-sm mx-auto">
                                        <i class="bi bi-arrow-right-short text-3xl"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-32 text-center">
                                    <div class="max-w-sm mx-auto space-y-8">
                                        <div class="w-24 h-24 bg-gray-50 rounded-[40px] flex items-center justify-center mx-auto text-gray-200 shadow-inner group-hover:scale-110 transition-transform duration-500">
                                            <i class="bi bi-calendar-x text-5xl"></i>
                                        </div>
                                        <div class="space-y-3">
                                            <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tight">No reservations yet</h3>
                                            <p class="text-[11px] font-black text-gray-400 uppercase tracking-[0.3em]">Start by exploring our community amenities</p>
                                        </div>
                                        <a href="{{ route('resident.amenities.index') }}" class="btn-premium inline-flex mx-auto">
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

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $reservations->links() }}
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
    .btn-premium {
        @apply inline-flex items-center gap-3 px-8 py-4 bg-[#081412] text-white text-[11px] font-black uppercase tracking-widest rounded-2xl hover:shadow-[0_0_25px_rgba(182,255,92,0.2)] transition-all active:scale-95 border border-white/5;
    }
    .badge-standard {
        @apply inline-flex items-center px-4 py-1.5 text-[10px] font-black rounded-xl uppercase tracking-widest shadow-sm;
    }
</style>
@endsection
