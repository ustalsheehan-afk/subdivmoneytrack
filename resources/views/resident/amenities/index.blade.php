@extends('resident.layouts.app')

@section('title', 'Amenities')
@section('page-title', 'Amenities & Facilities')

@section('content')
<div class="space-y-8">
        <x-resident-hero-header 
            label="Facilities" 
            icon="bi-building-fill"
            title="Amenities & Facilities" 
            description="Browse our community facilities and make a reservation for your events and activities."
        >
            <x-slot name="actions">
                <a href="{{ route('resident.my-reservations.index') }}" class="btn-premium">
                    <i class="bi bi-calendar-check"></i>
                    My Bookings
                </a>
            </x-slot>
        </x-resident-hero-header>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($amenities as $amenity)
                @php
                    $priceLabel = $amenity->price > 0 ? '₱' . number_format($amenity->price, 2) . '/hr' : 'Free';
                    $scheduleLabel = implode(', ', array_map(function($day) { return substr($day, 0, 3); }, $amenity->days_available ?? []));
                @endphp
                <div class="glass-card overflow-hidden group hover:-translate-y-1 transition-all duration-300 hover:shadow-xl hover:border-emerald-200">
                    <div class="relative h-36 bg-gray-50 overflow-hidden">
                        @if($amenity->image)
                            <img src="{{ Storage::url($amenity->image) }}" alt="{{ $amenity->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.03]">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-200">
                                <i class="bi bi-building text-4xl"></i>
                            </div>
                        @endif

                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>

                        <div class="absolute top-3 left-3">
                            @if($amenity->status === 'maintenance')
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50/95 text-amber-700 border border-amber-100 text-[9px] font-black uppercase tracking-widest">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Maintenance
                                </span>
                            @else
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50/95 text-emerald-700 border border-emerald-100 text-[9px] font-black uppercase tracking-widest">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Available
                                </span>
                            @endif
                        </div>

                        <div class="absolute left-0 right-0 bottom-0 p-4">
                            <div class="flex items-end justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="text-white font-black text-sm tracking-tight truncate">{{ $amenity->name }}</div>
                                    <div class="text-white/70 text-[9px] font-black uppercase tracking-widest">Amenity</div>
                                </div>
                                <div class="shrink-0 text-right">
                                    <div class="text-white font-black text-sm tabular-nums">{{ $priceLabel }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 flex flex-col gap-4">
                        <p class="text-[12px] text-gray-600 font-medium leading-relaxed line-clamp-2">
                            {{ $amenity->description }}
                        </p>

                        <div class="flex flex-wrap gap-2">
                            <span class="inline-flex items-center gap-2 px-3 py-2 rounded-full bg-gray-50 border border-gray-100 text-[10px] font-black uppercase tracking-widest text-gray-600">
                                <i class="bi bi-people-fill text-emerald-600"></i>
                                {{ $amenity->max_capacity }} Pax
                            </span>
                            <span class="inline-flex items-center gap-2 px-3 py-2 rounded-full bg-gray-50 border border-gray-100 text-[10px] font-black uppercase tracking-widest text-gray-600">
                                <i class="bi bi-calendar-check text-emerald-600"></i>
                                <span class="truncate max-w-[160px]">{{ $scheduleLabel ?: 'Schedule N/A' }}</span>
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <a href="{{ route('resident.amenities.show', $amenity) }}" class="btn-secondary justify-center w-full">
                                View Details
                                <i class="bi bi-arrow-right"></i>
                            </a>
                            @if($amenity->status === 'maintenance')
                                <button disabled class="w-full px-4 py-3 bg-gray-100 text-gray-400 text-[10px] font-black uppercase tracking-widest rounded-xl border border-gray-200">
                                    Book Now
                                </button>
                            @else
                                <a href="{{ route('resident.amenities.show', $amenity) }}" class="btn-premium justify-center w-full">
                                    Book Now
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 glass-card">
                    <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-gray-200">
                        <i class="bi bi-building-slash text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-black text-gray-900 tracking-tight">No amenities found</h3>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-2">Check back later for updates</p>
                </div>
            @endforelse
        </div>
</div>
@endsection
