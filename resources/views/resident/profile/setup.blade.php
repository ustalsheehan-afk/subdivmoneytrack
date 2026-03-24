@extends('resident.layouts.app')

@section('title', 'Setup Profile')
@section('page-title', 'Profile Setup')

@section('content')
<div class="h-full bg-[#F8F9FB] overflow-y-auto custom-scrollbar">
    <div class="max-w-2xl mx-auto px-6 py-12 pb-24 animate-fade-in">

        {{-- ========================= --}}
        {{-- SETUP HEADER --}}
        {{-- ========================= --}}
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-emerald-500/10 border border-emerald-500/20 mb-4">
                <i class="bi bi-person-plus-fill text-emerald-500 text-xs"></i>
                <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">Initial Onboarding</span>
            </div>
            <h1 class="text-4xl font-black text-gray-900 tracking-tight leading-none mb-4">Setup Your Profile</h1>
            <p class="text-sm font-black text-gray-400 uppercase tracking-widest">Please complete your resident information to continue</p>
        </div>

        @if(session('success'))
            <div class="mb-8 p-6 bg-emerald-500/10 border border-emerald-500/20 rounded-[28px] flex items-center gap-4 animate-bounce">
                <div class="w-10 h-10 rounded-xl bg-emerald-500 text-black flex items-center justify-center shadow-lg">
                    <i class="bi bi-check-lg text-xl"></i>
                </div>
                <p class="text-emerald-700 text-sm font-black uppercase tracking-widest">{{ session('success') }}</p>
            </div>
        @endif

        {{-- ========================= --}}
        {{-- SETUP FORM --}}
        {{-- ========================= --}}
        <div class="bg-white rounded-[40px] border border-gray-100 shadow-2xl p-10 relative overflow-hidden group">
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-emerald-500/5 rounded-full blur-3xl group-hover:bg-emerald-500/10 transition-all duration-1000"></div>

            <form method="POST" action="{{ route('resident.profile.update') }}" class="relative z-10 space-y-8">
                @csrf

                <div class="grid grid-cols-1 gap-6">
                    {{-- Full Name --}}
                    <div class="space-y-2">
                        <label for="name" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Full Name</label>
                        <div class="relative">
                            <i class="bi bi-person absolute left-6 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input id="name" name="name" 
                                   class="w-full bg-gray-50 border border-gray-100 rounded-2xl pl-14 pr-6 py-4 text-sm font-black text-gray-900 focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all outline-none"
                                   value="{{ old('name', $user->name ?? '') }}" placeholder="Enter your full name" required>
                        </div>
                    </div>

                    {{-- Address --}}
                    <div class="space-y-2">
                        <label for="address" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Home Address</label>
                        <div class="relative">
                            <i class="bi bi-geo-alt absolute left-6 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input id="address" name="address" 
                                   class="w-full bg-gray-50 border border-gray-100 rounded-2xl pl-14 pr-6 py-4 text-sm font-black text-gray-900 focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all outline-none"
                                   value="{{ old('address', $user->address ?? '') }}" placeholder="Your subdivision address" required>
                        </div>
                    </div>

                    {{-- Block & Lot --}}
                    <div class="space-y-2">
                        <label for="block_lot" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Block & Lot</label>
                        <div class="relative">
                            <i class="bi bi-house absolute left-6 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input id="block_lot" name="block_lot" 
                                   class="w-full bg-gray-50 border border-gray-100 rounded-2xl pl-14 pr-6 py-4 text-sm font-black text-gray-900 focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all outline-none"
                                   value="{{ old('block_lot', $user->block_lot ?? '') }}" placeholder="e.g. Block 1 Lot 2" required>
                        </div>
                    </div>

                    {{-- Contact Number --}}
                    <div class="space-y-2">
                        <label for="contact" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Contact Number</label>
                        <div class="relative">
                            <i class="bi bi-phone absolute left-6 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input id="contact" name="contact_number" 
                                   class="w-full bg-gray-50 border border-gray-100 rounded-2xl pl-14 pr-6 py-4 text-sm font-black text-gray-900 focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all outline-none"
                                   value="{{ old('contact_number', $user->resident?->contact_number ?? '') }}" placeholder="09XX XXX XXXX" required>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="space-y-2">
                        <label for="email" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Email Address</label>
                        <div class="relative">
                            <i class="bi bi-envelope absolute left-6 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input id="email" name="email" type="email" 
                                   class="w-full bg-gray-50 border border-gray-100 rounded-2xl pl-14 pr-6 py-4 text-sm font-black text-gray-900 focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all outline-none"
                                   value="{{ old('email', $user->email ?? '') }}" placeholder="your@email.com" required>
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" 
                            class="w-full py-5 bg-emerald-500 text-black text-sm font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-emerald-400 hover:shadow-[0_20px_40px_rgba(16,185,129,0.2)] transition-all duration-300 transform active:scale-95 flex items-center justify-center gap-3">
                        <span>COMPLETE SETUP</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center mt-8 text-[10px] font-black text-gray-400 uppercase tracking-widest">
            Protected by subdivision security protocols
        </p>
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
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
</style>
@endsection
