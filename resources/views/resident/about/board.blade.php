@extends('resident.layouts.app')

@section('title', 'Vistabellas Board')
@section('page-title', 'Vistabellas Board')

@section('content')

{{-- PAGE CONTENT WITH EXTENDED BACKGROUND --}}
<div class="relative overflow-hidden bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-[calc(100vh+400px)] pb-32">

    {{-- Decorative Blur Background --}}
    <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-blue-400/30 rounded-full blur-3xl animate-pulse-slow"></div>
    <div class="absolute top-1/4 -right-40 w-[500px] h-[500px] bg-indigo-400/30 rounded-full blur-3xl animate-pulse-slow"></div>

    {{-- CONTENT SECTION --}}
    <div class="relative z-10 max-w-7xl mx-auto px-6 md:px-12 py-20 space-y-16">

        {{-- Header --}}
        <div class="max-w-3xl mx-auto text-center space-y-4">
            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900">
                Meet Your Vistabellas Board
            </h1>
            <p class="text-lg font-medium text-slate-700">
                Dedicated to Making Vistabellas a Better Place to Call Home.
            </p>
            <p class="text-base text-slate-500 leading-relaxed max-w-2xl mx-auto">
                Our Board of Directors is composed of resident volunteers committed to maintaining the beauty,
                safety, and community spirit of our subdivision.
            </p>
        </div>

        {{-- Board Members Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-10">
            @foreach ($boardMembers as $member)
                <div class="group relative h-[360px] rounded-2xl overflow-hidden bg-white/80 backdrop-blur
                            border border-slate-200 shadow-lg hover:shadow-2xl transition-all duration-500">

                    {{-- Image --}}
                    <img
                        src="{{ $member->photo ? asset('storage/' . $member->photo) : asset('images/default-member.jpg') }}"
                        alt="{{ $member->name }}"
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                    >

                    {{-- Gradient Overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/30 to-transparent"></div>

                    {{-- Content Panel --}}
                    <div class="absolute bottom-0 left-0 right-0 px-5 pb-5
                                translate-y-[70px] group-hover:translate-y-[12px]
                                transition-transform duration-500 ease-out">

                        <div class="space-y-1">
                            <h3 class="text-xl md:text-2xl font-bold text-white truncate">
                                {{ $member->name }}
                            </h3>
                            <p class="text-xs font-bold tracking-widest text-blue-400 uppercase truncate">
                                {{ $member->position }}
                            </p>
                        </div>

                        <div class="mt-3 opacity-0 group-hover:opacity-100
                                    transform translate-y-3 group-hover:translate-y-0
                                    transition-all duration-500 delay-150">
                            <p class="text-sm text-slate-200 leading-relaxed line-clamp-3">
                                {{ $member->bio }}
                            </p>

                            {{-- Contact Icons --}}
                            <div class="mt-4 flex gap-3">
                                @if($member->email)
                                <a href="mailto:{{ $member->email }}"
                                   class="p-2 rounded-full bg-white/15 text-white backdrop-blur
                                          hover:bg-white/25 hover:text-blue-400 transition" title="Email">
                                    <i class="bi bi-envelope"></i>
                                </a>
                                @endif

                                @if($member->facebook)
                                <a href="{{ $member->facebook }}" target="_blank"
                                   class="p-2 rounded-full bg-white/15 text-white backdrop-blur
                                          hover:bg-white/25 hover:text-blue-400 transition" title="Facebook">
                                    <i class="bi bi-facebook"></i>
                                </a>
                                @endif

                                @if($member->phone)
                                <a href="tel:{{ $member->phone }}"
                                   class="p-2 rounded-full bg-white/15 text-white backdrop-blur
                                          hover:bg-white/25 hover:text-blue-400 transition" title="Phone">
                                    <i class="bi bi-telephone"></i>
                                </a>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>

{{-- FOOTER --}}
<footer class="border-t border-slate-200 bg-white py-6">
    <div class="max-w-7xl mx-auto px-6 md:px-12 text-center text-sm text-slate-500">
        © {{ date('Y') }} Vistabellas Subdivision. All rights reserved.
    </div>
</footer>

{{-- Extra Animations --}}
<style>
@keyframes pulse-slow {
  0%, 100% { transform: scale(1); opacity: 0.3; }
  50% { transform: scale(1.05); opacity: 0.4; }
}
.animate-pulse-slow {
  animation: pulse-slow 10s infinite ease-in-out;
}
</style>

@endsection