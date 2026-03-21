@extends('resident.layouts.app')

@section('title', 'Subdivision Amenities')
@section('page-title', 'Subdivision Amenities')

@section('content')
@php
    $amenities = [
        ['title'=>'Clubhouse','category'=>'Community','desc'=>'Ideal for events, meetings, and community gatherings.','img'=>asset('images/clubhouse.jpg')],
        ['title'=>'Basketball Court','category'=>'Sports','desc'=>'Open for practice, friendly games, and tournaments.','img'=>asset('images/subdivision-clubhouse.jpg')],
        ['title'=>'Swimming Pool','category'=>'Recreation','desc'=>'A relaxing space for families and residents.','img'=>asset('images/subdivision-event.jpg')],
        ['title'=>'Children’s Playground','category'=>'Kids','desc'=>'Safe and fun outdoor play area for kids.','img'=>asset('images/subdivision-playground.jpg')],
        ['title'=>'Subdivision Houses','category'=>'Living','desc'=>'A view of our beautifully designed homes within the community.','img'=>asset('images/subdivision-hero1.jpg')],
        ['title'=>'Open Spaces','category'=>'Nature','desc'=>'Green areas for walking, relaxation, and community activities.','img'=>asset('images/open.jpg')],
    ];
@endphp

<div class="w-full bg-gray-50 py-16 md:py-24">
    <div class="max-w-7xl mx-auto px-6 md:px-12 space-y-12">
        <div class="max-w-3xl mx-auto text-center space-y-4">
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900">Subdivision Amenities</h2>
            <p class="text-lg font-medium text-slate-700">Built for Convenience. Maintained for Excellence.</p>
            <p class="text-base text-slate-500 leading-relaxed max-w-2xl mx-auto">
               Vistabellas provides top-tier infrastructure and well-maintained facilities for residents' convenience.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-10">
            @foreach ($amenities as $amenity)
                <div class="group relative h-[450px] bg-white border border-gray-200 shadow-lg overflow-hidden rounded-xl hover:shadow-2xl transition-all duration-500">
                    <!-- Image -->
                    <img 
                        src="{{ $amenity['img'] }}" 
                        alt="{{ $amenity['title'] }}" 
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                    >
                    <!-- Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent opacity-80 group-hover:opacity-90 transition-opacity duration-500"></div>

                    <!-- Content Panel -->
                    <div class="absolute bottom-0 left-0 right-0 p-6 translate-y-[20px] group-hover:translate-y-0 transition-transform duration-500 ease-out">
                        <div class="space-y-1">
                            <h3 class="text-2xl font-bold text-white tracking-wide">
                                {{ $amenity['title'] }}
                            </h3>
                            <p class="text-xs font-bold tracking-widest text-emerald-400 uppercase">
                                {{ $amenity['category'] }}
                            </p>
                        </div>
                        
                        <div class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100">
                            <p class="text-sm text-slate-200 leading-relaxed">
                                {{ $amenity['desc'] }}
                            </p>
                            
                            <div class="mt-4">
                                <span class="inline-flex items-center text-xs font-medium text-emerald-300">
                                    <i class="bi bi-check-circle-fill mr-1"></i> Available for use
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
