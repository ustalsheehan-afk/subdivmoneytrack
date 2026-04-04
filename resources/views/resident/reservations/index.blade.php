@extends('resident.layouts.app')

@section('title', 'My Reservations')

@section('content')
<div class="space-y-8">

    <!-- HERO -->
   <x-resident-hero-header 
    label="Reservation History" 
    icon="bi-calendar-check-fill"
    title="My Reservations" 
    description="Manage and track your community amenity bookings, schedules, and payment status."
>
    <x-slot name="actions">
        <a href="{{ route('resident.amenities.index') }}" class="btn-premium whitespace-nowrap flex items-center gap-2">
            <i class="bi bi-plus-lg"></i>
            <span>New Booking</span>
        </a>
    </x-slot>
</x-resident-hero-header>

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 p-5 rounded-[24px] shadow-sm flex items-center gap-4 animate-fade-in">
            <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/20">
                <i class="bi bi-check-lg text-xl"></i>
            </div>
            <p class="text-sm font-black text-emerald-800 uppercase tracking-tight">
                {{ session('success') }}
            </p>
        </div>
    @endif

    <!-- TABLE -->
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">

                <!-- HEADER -->
                <thead class="bg-gray-50/50 border-b border-gray-100">
                    <tr>
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Amenity</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Schedule</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Payment</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Actions</th>
                    </tr>
                </thead>

                <!-- BODY -->
                <tbody class="divide-y divide-gray-50">
                    @forelse($reservations as $reservation)

                        <tr class="cursor-pointer hover:bg-emerald-50/30 transition-all duration-300 group border-l-4 border-transparent hover:border-emerald-500"
                            onclick="window.location.href='{{ route('resident.amenities.reservation.show', $reservation->id) }}'">

                            <!-- AMENITY -->
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

                                        <!-- STATUS DOT -->
                                        <span class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-white 
                                            {{ match($reservation->status) {
                                                'approved' => 'bg-emerald-500',
                                                'rejected' => 'bg-red-500',
                                                'cancelled' => 'bg-gray-400',
                                                default => 'bg-orange-400'
                                            } }}">
                                        </span>
                                    </div>

                                    <div>
                                        <p class="font-black text-gray-900 group-hover:text-emerald-700 transition-colors tracking-tight text-lg">
                                            {{ $reservation->amenity->name }}
                                        </p>
                                        <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.2em] mt-0.5">
                                            ID #{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }}
                                        </p>
                                    </div>

                                </div>
                            </td>

                            <!-- DATE -->
                            <td class="p-6 text-center">
                                <p class="text-sm font-black text-gray-900 tracking-tight tabular-nums">
                                    {{ $reservation->date->format('M d, Y') }}
                                </p>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1 tabular-nums">
                                    {{ $reservation->time_slot }}
                                </p>
                            </td>

                            <!-- STATUS -->
                            <td class="p-6 text-center">
                                @php
                                    $statusClass = match($reservation->status) {
                                        'approved'  => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                        'rejected'  => 'bg-red-50 text-red-600 border-red-100',
                                        'cancelled' => 'bg-gray-50 text-gray-600 border-gray-100',
                                        default     => 'bg-orange-50 text-orange-600 border-orange-100'
                                    };

                                    $dotClass = match($reservation->status) {
                                        'approved'  => 'bg-emerald-500',
                                        'rejected'  => 'bg-red-500',
                                        'cancelled' => 'bg-gray-400',
                                        default     => 'bg-orange-500'
                                    };
                                @endphp

                                <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $statusClass }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span>
                                    {{ $reservation->status }}
                                </span>
                            </td>

                            <!-- PAYMENT -->
                            <td class="p-6 text-right">
                                <p class="text-lg font-black text-black tracking-tighter tabular-nums mb-2">
                                    ₱{{ number_format($reservation->total_price, 2) }}
                                </p>

                                @php
                                    $pStatus = $reservation->payment_status;
                                    $pStyle = match($pStatus) {
                                        'paid'      => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'border' => 'border-emerald-100', 'dot' => 'bg-emerald-500', 'label' => 'Verified'],
                                        'submitted' => ['bg' => 'bg-blue-50',    'text' => 'text-blue-600',    'border' => 'border-blue-100',    'dot' => 'bg-blue-500',    'label' => 'Awaiting'],
                                        'rejected'  => ['bg' => 'bg-red-50',     'text' => 'text-red-600',     'border' => 'border-red-100',     'dot' => 'bg-red-500',     'label' => 'Rejected'],
                                        default     => ['bg' => 'bg-gray-50',    'text' => 'text-gray-600',    'border' => 'border-gray-100',    'dot' => 'bg-gray-400',    'label' => 'Pending'],
                                    };
                                @endphp

                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest border {{ $pStyle['bg'] }} {{ $pStyle['text'] }} {{ $pStyle['border'] }}">
                                    <span class="w-1 h-1 rounded-full {{ $pStyle['dot'] }}"></span>
                                    {{ $pStyle['label'] }}
                                </span>
                            </td>

                            <!-- ACTION -->
                            <td class="p-6 text-center" onclick="event.stopPropagation()">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('resident.amenities.reservation.show', $reservation->id) }}" 
                                       class="w-11 h-11 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 border border-transparent hover:border-emerald-100 transition-all group-hover:scale-110 shadow-sm"
                                       title="View Details">
                                        <i class="bi bi-arrow-right-short text-3xl"></i>
                                    </a>
                                    @if($reservation->payment_status === 'paid')
                                        <a href="{{ route('resident.amenities.reservation.receipt', $reservation->id) }}" 
                                           target="_blank"
                                           class="w-11 h-11 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-500 hover:text-white hover:bg-emerald-600 border border-emerald-100 hover:border-emerald-600 transition-all group-hover:scale-110 shadow-sm"
                                           title="View Receipt">
                                            <i class="bi bi-receipt text-lg"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="5" class="py-32 text-center">
                                <div class="max-w-sm mx-auto space-y-8">
                                    <div class="w-24 h-24 bg-gray-50 rounded-[40px] flex items-center justify-center mx-auto text-gray-200 shadow-inner">
                                        <i class="bi bi-calendar-x text-5xl"></i>
                                    </div>

                                    <div class="space-y-3">
                                        <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tight">
                                            No reservations yet
                                        </h3>
                                        <p class="text-[11px] font-black text-gray-400 uppercase tracking-[0.3em]">
                                            Start by exploring amenities
                                        </p>
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

    <!-- PAGINATION -->
    <div class="mt-6">
        {{ $reservations->links() }}
    </div>

</div>
@endsection
