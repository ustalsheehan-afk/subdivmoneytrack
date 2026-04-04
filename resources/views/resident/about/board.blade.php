@extends('resident.layouts.app')

@section('title', 'Vistabellas Board')
@section('page-title', 'Vistabellas Board')

@section('content')
<div class="space-y-8">
    <x-resident-hero-header
        label="Leadership Team"
        icon="bi-people-fill"
        title="Meet Your Vistabellas Board"
        description="Dedicated to making Vistabellas a better place to call home. Our board of directors is composed of resident volunteers committed to maintaining the beauty, safety, and community spirit."
    />

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($boardMembers as $member)
            @php
                $photoUrl = $member->photo ? asset('storage/' . $member->photo) : asset('images/default-member.jpg');
                $email = trim((string) ($member->email ?? ''));
                $phoneRaw = trim((string) ($member->phone ?? ''));
                $phoneTel = preg_replace('/[^\d\+]/', '', $phoneRaw);
                $facebookRaw = trim((string) ($member->facebook ?? ''));
                $facebookUrl = $facebookRaw && !preg_match('/^https?:\/\//i', $facebookRaw) ? 'https://' . $facebookRaw : $facebookRaw;
            @endphp

            <div class="glass-card overflow-hidden p-6 sm:p-7 flex flex-col gap-6">
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 text-[9px] font-black uppercase tracking-widest">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Active
                    </span>
                </div>

                <div class="flex flex-col items-center text-center gap-3">
                    <div class="w-24 h-24 rounded-[28px] overflow-hidden border border-gray-100 bg-gray-50 shadow-lg shadow-gray-200/50">
                        <img src="{{ $photoUrl }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <div class="text-xl font-black text-gray-900 tracking-tight">{{ $member->name }}</div>
                        <div class="mt-1 text-[9px] font-black text-emerald-700 uppercase tracking-[0.35em]">
                            {{ $member->position }}
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl bg-gray-50 border border-gray-100 p-5 text-center">
                    <p class="text-[12px] text-gray-600 font-medium italic leading-relaxed">
                        "{{ $member->bio }}"
                    </p>
                </div>

                <div class="pt-2 border-t border-gray-100 space-y-3">
                    @if($email)
                        <a href="mailto:{{ $email }}" class="flex items-center gap-3 p-3 rounded-2xl bg-white border border-gray-100 hover:bg-gray-50 transition">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-500">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Email</div>
                                <div class="text-[12px] font-bold text-gray-700 truncate">{{ $email }}</div>
                            </div>
                        </a>
                    @endif

                    @if($phoneTel)
                        <a href="tel:{{ $phoneTel }}" class="flex items-center gap-3 p-3 rounded-2xl bg-white border border-gray-100 hover:bg-gray-50 transition">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-500">
                                <i class="bi bi-telephone"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Phone</div>
                                <div class="text-[12px] font-bold text-gray-700 truncate">{{ $phoneRaw }}</div>
                            </div>
                        </a>
                    @endif

                    @if($facebookUrl)
                        <a href="{{ $facebookUrl }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 p-3 rounded-2xl bg-white border border-gray-100 hover:bg-gray-50 transition">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-500">
                                <i class="bi bi-facebook"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Facebook</div>
                                <div class="text-[12px] font-bold text-gray-700 truncate">{{ $facebookRaw }}</div>
                            </div>
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
