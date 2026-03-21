@extends('resident.layouts.app')

@section('title', 'Amenities')
@section('page-title', 'Amenities & Facilities')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 p-4 sm:p-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Amenities & Facilities</h1>
            <p class="text-base text-gray-600">Browse our community facilities and make a reservation.</p>
        </div>
    </div>

    <!-- Amenities List -->
    <div class="space-y-5">
        @forelse($amenities as $amenity)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300 flex flex-col md:flex-row">
                <!-- Image -->
                <div class="md:w-1/3 h-44 md:h-auto relative bg-gray-100">
                    @if($amenity->image)
                        <img src="{{ Storage::url($amenity->image) }}" alt="{{ $amenity->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <i class="bi bi-image text-4xl"></i>
                        </div>
                    @endif

                    <!-- Status Badge -->
                    <div class="absolute top-3 left-3">
                        @if($amenity->status === 'maintenance')
                            <span class="px-3 py-1 bg-orange-100 text-orange-800 text-xs font-bold rounded-full shadow-sm flex items-center gap-2">
                                <i class="bi bi-tools"></i> Maintenance
                            </span>
                        @else
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full shadow-sm flex items-center gap-2">
                                <i class="bi bi-check-circle-fill"></i> Available
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6 md:w-2/3 flex flex-col justify-between">
                    <div>
                        <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-3 gap-2">
                            <h2 class="text-xl font-bold text-gray-900">{{ $amenity->name }}</h2>

                            <div class="flex items-center gap-2 md:flex-col md:items-end md:gap-0">
                                <span class="block text-xl font-bold text-blue-600">
                                    {{ $amenity->price > 0 ? '₱' . number_format($amenity->price, 2) : 'Free' }}
                                </span>
                                <span class="text-xs text-gray-500">per hour</span>
                            </div>
                        </div>

                        <p class="text-gray-600 text-base mb-4 leading-relaxed">
                            {{ $amenity->description }}
                        </p>

                        <!-- Key Details -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-5">
                            <div class="flex items-center gap-3 text-gray-700 bg-gray-50 p-3 rounded-lg">
                                <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                                    <i class="bi bi-people-fill text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Capacity</p>
                                    <p class="font-semibold text-base">{{ $amenity->max_capacity }} Persons</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 text-gray-700 bg-gray-50 p-3 rounded-lg">
                                <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                                    <i class="bi bi-calendar-check text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Availability</p>
                                    <p class="font-semibold text-base">
                                        {{ implode(', ', array_map(function($day) { return substr($day, 0, 3); }, $amenity->days_available ?? [])) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action -->
                    <div class="mt-2">
                        @if($amenity->status === 'maintenance')
                            <button disabled class="w-full md:w-auto px-6 py-2.5 bg-gray-100 text-gray-400 font-bold rounded-xl cursor-not-allowed flex items-center justify-center gap-2">
                                <i class="bi bi-slash-circle"></i>
                                Currently Unavailable
                            </button>
                        @else
                            <a href="{{ route('resident.amenities.show', $amenity) }}" class="inline-flex items-center justify-center w-full md:w-auto px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-sm hover:shadow-md transition-all text-base gap-2">
                                <span>Book This Amenity</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white rounded-2xl border border-gray-100">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-400 mb-4">
                    <i class="bi bi-building-slash text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900">No amenities found</h3>
                <p class="text-gray-500 mt-1 text-base">Check back later for updates.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
