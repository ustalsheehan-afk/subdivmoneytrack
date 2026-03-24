@extends('resident.layouts.app')

@section('title', 'Vistabellas Board')
@section('page-title', 'Vistabellas Board')

@section('content')

<div class="h-full bg-[#F8F9FB] overflow-y-auto custom-scrollbar">
    <div class="max-w-7xl mx-auto px-6 py-10 flex flex-col gap-12 pb-24">

        <x-resident-hero-header 
            label="Leadership Team" 
            icon="bi-people-fill"
            title="Meet Your Vistabellas Board" 
            description="Dedicated to making Vistabellas a better place to call home. Our board of directors is composed of resident volunteers committed to maintaining the beauty, safety, and community spirit."
        />

        {{-- Board Members Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($boardMembers as $member)
                <div class="group relative h-[420px] rounded-[32px] overflow-hidden bg-[#081412] border border-white/5 shadow-2xl transition-all duration-500 hover:-translate-y-2 hover:shadow-[0_20px_50px_rgba(0,0,0,0.3)]">

                    {{-- Image --}}
                    <div class="absolute inset-0 w-full h-full bg-white/5">
                        <img
                            src="{{ $member->photo ? asset('storage/' . $member->photo) : asset('images/default-member.jpg') }}"
                            alt="{{ $member->name }}"
                            class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 opacity-80 group-hover:opacity-100"
                        >
                    </div>

                    {{-- Dark Overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-[#081412] via-[#081412]/60 to-transparent transition-opacity duration-500 opacity-90 group-hover:opacity-95"></div>

                    {{-- Accent Blur --}}
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-brand-accent/5 rounded-full blur-3xl group-hover:bg-brand-accent/10 transition-all duration-700"></div>

                    {{-- Content Panel --}}
                    <div class="absolute bottom-0 left-0 right-0 p-8 space-y-4 translate-y-6 group-hover:translate-y-0 transition-all duration-500 ease-out">

                        <div class="space-y-1.5">
                            <h3 class="text-2xl font-black text-white tracking-tight">
                                {{ $member->name }}
                            </h3>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-400">
                                    {{ $member->position }}
                                </span>
                                <span class="w-1 h-1 rounded-full bg-white/20"></span>
                                <span class="text-[9px] font-black text-white/30 uppercase tracking-widest">Active Member</span>
                            </div>
                        </div>

                        <div class="space-y-6 opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-500 delay-100">
                            <p class="text-sm text-white/60 leading-relaxed line-clamp-3 font-medium">
                                {{ $member->bio }}
                            </p>

                            {{-- Contact Icons --}}
                            <div class="flex gap-3">
                                @if($member->email)
                                <a href="mailto:{{ $member->email }}"
                                   class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 text-white/40 border border-white/5 hover:bg-emerald-500 hover:text-black hover:border-emerald-400 transition-all duration-300 shadow-lg" 
                                   title="Email">
                                    <i class="bi bi-envelope text-sm"></i>
                                </a>
                                @endif

                                @if($member->facebook)
                                <a href="{{ $member->facebook }}" target="_blank"
                                   class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 text-white/40 border border-white/5 hover:bg-emerald-500 hover:text-black hover:border-emerald-400 transition-all duration-300 shadow-lg" 
                                   title="Facebook">
                                    <i class="bi bi-facebook text-sm"></i>
                                </a>
                                @endif

                                @if($member->phone)
                                <a href="tel:{{ $member->phone }}"
                                   class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 text-white/40 border border-white/5 hover:bg-emerald-500 hover:text-black hover:border-emerald-400 transition-all duration-300 shadow-lg" 
                                   title="Phone">
                                    <i class="bi bi-telephone text-sm"></i>
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
