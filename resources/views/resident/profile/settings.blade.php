@extends('resident.layouts.app')

@section('title', 'Account Settings')
@section('page-title', 'Account Settings')

@section('content')
<div class="h-full bg-[#F8F9FB] overflow-y-auto custom-scrollbar">
    <div class="max-w-4xl mx-auto px-6 py-8 pb-24 animate-fade-in">

        {{-- ========================= --}}
        {{-- SETTINGS HEADER --}}
        {{-- ========================= --}}
        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-emerald-500/10 border border-emerald-500/20 mb-3">
                    <i class="bi bi-gear-fill text-emerald-500 text-xs"></i>
                    <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">System Preferences</span>
                </div>
                <h1 class="text-4xl font-black text-gray-900 tracking-tight leading-none">Account Settings</h1>
                <p class="text-sm font-black text-gray-400 mt-3 uppercase tracking-widest">Manage your account preferences and security</p>
            </div>
            <a href="{{ route('resident.profile.index') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-gray-200 rounded-2xl text-sm font-black text-gray-400 hover:text-gray-900 hover:border-gray-900 transition-all duration-300 shadow-sm">
                <i class="bi bi-arrow-left"></i>
                <span>BACK TO PROFILE</span>
            </a>
        </div>

        <div class="grid grid-cols-1 gap-8">
            
            {{-- PROFILE OVERVIEW --}}
            <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm p-10 relative overflow-hidden group">
                <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-emerald-500/5 rounded-full blur-3xl group-hover:bg-emerald-500/10 transition-all duration-700"></div>
                
                <div class="flex items-center gap-4 mb-10 relative z-10">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center border border-emerald-100 shadow-sm">
                        <i class="bi bi-person-circle text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-gray-900 text-xl tracking-tight">Profile Overview</h3>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Public & Private Identity</p>
                    </div>
                </div>

                <div class="space-y-6 relative z-10">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-6 rounded-[28px] bg-gray-50 border border-gray-100 hover:bg-white hover:shadow-xl transition-all duration-500 group/item">
                        <div class="mb-4 sm:mb-0">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Full Name</span>
                            <span class="text-sm font-black text-gray-900 tracking-tight">{{ $resident->first_name }} {{ $resident->last_name }}</span>
                        </div>
                        <a href="{{ route('resident.profile.edit') }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500/10 text-emerald-600 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-emerald-500 hover:text-black transition-all duration-300">
                            <i class="bi bi-pencil-fill"></i>
                            <span>Edit Name</span>
                        </a>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-6 rounded-[28px] bg-gray-50 border border-gray-100 hover:bg-white hover:shadow-xl transition-all duration-500 group/item">
                        <div>
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Email Address</span>
                            <span class="text-sm font-black text-gray-900 tracking-tight break-all">{{ $resident->email }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECURITY SECTION --}}
            <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm p-10 relative overflow-hidden group">
                <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-emerald-500/5 rounded-full blur-3xl group-hover:bg-emerald-500/10 transition-all duration-700"></div>

                <div class="flex items-center gap-4 mb-10 relative z-10">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center border border-emerald-100 shadow-sm">
                        <i class="bi bi-shield-lock-fill text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-gray-900 text-xl tracking-tight">Security</h3>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Access & Authentication</p>
                    </div>
                </div>

                <div class="p-8 rounded-[28px] bg-[#081412] text-white relative overflow-hidden group/security">
                    <div class="absolute -right-20 -top-20 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl"></div>
                    
                    <div class="relative z-10">
                        <h4 class="text-lg font-black tracking-tight mb-2">Password Management</h4>
                        <p class="text-xs font-black text-white/40 uppercase tracking-widest leading-relaxed mb-8">
                            For security purposes, password updates are managed through the profile edit module.
                        </p>
                        
                        <a href="{{ route('resident.profile.edit') }}" 
                           class="inline-flex items-center gap-3 px-8 py-4 bg-emerald-500 text-black text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-emerald-400 hover:shadow-[0_20px_40px_rgba(16,185,129,0.2)] transition-all duration-300">
                            <i class="bi bi-key-fill text-sm"></i>
                            <span>MANAGE PASSWORD</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- NOTIFICATIONS SECTION --}}
            <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm p-10 relative overflow-hidden group opacity-60">
                <div class="flex items-center gap-4 mb-10 relative z-10">
                    <div class="w-12 h-12 rounded-2xl bg-gray-50 text-gray-400 flex items-center justify-center border border-gray-100 shadow-sm">
                        <i class="bi bi-bell-fill text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-gray-900 text-xl tracking-tight">Notifications</h3>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Alerts & Updates</p>
                    </div>
                </div>

                <div class="p-8 rounded-[28px] bg-gray-50 border border-dashed border-gray-200 flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center mb-4 shadow-sm">
                        <i class="bi bi-stars text-2xl text-emerald-500/40"></i>
                    </div>
                    <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-2">Coming Soon</h4>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-relaxed max-w-xs">
                        Advanced notification preferences will be available in a future system update.
                    </p>
                </div>
            </div>
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
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
</style>
@endsection

