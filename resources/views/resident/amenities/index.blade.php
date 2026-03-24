@extends('resident.layouts.app')

@section('title', 'Amenities')
@section('page-title', 'Amenities & Facilities')

@section('content')
<div class="h-full bg-[#F8F9FB] overflow-y-auto custom-scrollbar">
    <div class="max-w-5xl mx-auto px-6 py-8 flex flex-col gap-10 pb-24">
        
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

        <!-- Amenities List -->
        <div class="grid gap-8">
            @forelse($amenities as $amenity)
                <div class="relative bg-white rounded-[32px] border border-gray-100 shadow-sm overflow-hidden hover:shadow-2xl transition-all duration-500 group flex flex-col md:flex-row hover:-translate-y-1">
                    
                    <!-- Image Section -->
                    <div class="md:w-2/5 h-64 md:h-auto relative overflow-hidden bg-gray-50">
                        @if($amenity->image)
                            <img src="{{ Storage::url($amenity->image) }}" alt="{{ $amenity->name }}" 
                                 class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-200">
                                <i class="bi bi-building text-6xl"></i>
                            </div>
                        @endif

                        <!-- Status Badge -->
                        <div class="absolute top-6 left-6 z-20">
                            @if($amenity->status === 'maintenance')
                                <span class="px-4 py-2 bg-orange-500 text-white text-[10px] font-black rounded-xl shadow-lg shadow-orange-500/20 uppercase tracking-widest flex items-center gap-2 border border-orange-400">
                                    <i class="bi bi-tools"></i> Maintenance
                                </span>
                            @else
                                <span class="px-4 py-2 bg-emerald-500 text-white text-[10px] font-black rounded-xl shadow-lg shadow-emerald-500/20 uppercase tracking-widest flex items-center gap-2 border border-emerald-400">
                                    <i class="bi bi-check-circle-fill"></i> Available
                                </span>
                            @endif
                        </div>

                        {{-- Gradient Overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </div>

                    <!-- Content Section -->
                    <div class="p-10 md:w-3/5 flex flex-col justify-between relative bg-white">
                        <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-emerald-500/5 rounded-full blur-3xl group-hover:bg-emerald-500/10 transition-all duration-700"></div>

                        <div class="relative z-10">
                            <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-6 gap-4">
                                <div class="space-y-1">
                                    <h2 class="text-3xl font-black text-gray-900 tracking-tight group-hover:text-emerald-600 transition-colors">{{ $amenity->name }}</h2>
                                    <p class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em]">Community Resource</p>
                                </div>

                                <div class="flex flex-col md:items-end">
                                    <span class="text-2xl font-black text-gray-900 tabular-nums">
                                        {{ $amenity->price > 0 ? '₱' . number_format($amenity->price, 2) : 'Free' }}
                                    </span>
                                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">per hour</span>
                                </div>
                            </div>

                            <p class="text-gray-500 text-[15px] leading-relaxed font-medium mb-8 line-clamp-3">
                                {{ $amenity->description }}
                            </p>

                            <!-- Key Details Grid -->
                            <div class="grid grid-cols-2 gap-4 mb-10">
                                <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-2xl border border-gray-100 group/item hover:bg-white hover:shadow-md transition-all">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-500/20 border border-emerald-400 group-hover/item:scale-110 transition-transform">
                                        <i class="bi bi-people-fill"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] text-gray-400 uppercase font-black tracking-widest">Capacity</p>
                                        <p class="font-black text-gray-900 text-sm tracking-tight">{{ $amenity->max_capacity }} PAX</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-2xl border border-gray-100 group/item hover:bg-white hover:shadow-md transition-all">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-500/20 border border-emerald-400 group-hover/item:scale-110 transition-transform">
                                        <i class="bi bi-calendar-check"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[9px] text-gray-400 uppercase font-black tracking-widest">Schedule</p>
                                        <p class="font-black text-gray-900 text-[11px] tracking-tight truncate">
                                            {{ implode(', ', array_map(function($day) { return substr($day, 0, 3); }, $amenity->days_available ?? [])) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="relative z-10">
                            @if($amenity->status === 'maintenance')
                                <button disabled class="w-full px-8 py-4 bg-gray-100 text-gray-400 text-[11px] font-black uppercase tracking-widest rounded-2xl cursor-not-allowed flex items-center justify-center gap-3 border border-gray-200">
                                    <i class="bi bi-slash-circle"></i>
                                    Currently Unavailable
                                </button>
                            @else
                                <a href="{{ route('resident.amenities.show', $amenity) }}" class="w-full px-8 py-4 bg-[#081412] text-white text-[11px] font-black uppercase tracking-widest rounded-2xl hover:shadow-[0_0_25px_rgba(16,185,129,0.2)] transition-all flex items-center justify-center gap-3 group/btn border border-white/5">
                                    <span>Book Amenity Now</span>
                                    <i class="bi bi-arrow-right text-emerald-400 group-hover/btn:translate-x-1 transition-transform"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-24 bg-white rounded-[40px] border border-dashed border-gray-200 animate-fade-in">
                    <div class="w-24 h-24 bg-gray-50 rounded-[32px] flex items-center justify-center mx-auto mb-8 text-gray-200 shadow-inner">
                        <i class="bi bi-building-slash text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tight">No amenities found</h3>
                    <p class="text-[11px] font-black text-gray-400 uppercase tracking-[0.3em] mt-4">Check back later for updates</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #CBD5E0; }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }
</style>
@endsection
