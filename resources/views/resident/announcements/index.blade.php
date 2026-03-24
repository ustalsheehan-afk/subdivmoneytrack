@extends('resident.layouts.app')

@section('title', 'Announcements')
@section('page-title', 'Announcements')

@section('content')
<div class="h-full bg-[#F8F9FB] overflow-y-auto custom-scrollbar">
    <div class="max-w-5xl mx-auto px-6 py-8 flex flex-col gap-10 pb-24">
        <x-resident-hero-header 
            label="Community Notice" 
            icon="bi-megaphone-fill"
            title="Announcements" 
            description="Stay updated with the latest news and alerts from the administration."
            :tabs="[
                ['id' => '', 'label' => 'All', 'icon' => 'bi-grid-fill', 'href' => route('resident.announcements.index'), 'active' => !request('category')],
                ['id' => 'Emergency', 'label' => 'Emergency', 'icon' => 'bi-exclamation-octagon-fill', 'href' => route('resident.announcements.index', ['category' => 'Emergency']), 'active' => request('category') == 'Emergency'],
                ['id' => 'Maintenance', 'label' => 'Maintenance', 'icon' => 'bi-tools', 'href' => route('resident.announcements.index', ['category' => 'Maintenance']), 'active' => request('category') == 'Maintenance'],
                ['id' => 'Meeting', 'label' => 'Meeting', 'icon' => 'bi-people-fill', 'href' => route('resident.announcements.index', ['category' => 'Meeting']), 'active' => request('category') == 'Meeting'],
                ['id' => 'Event', 'label' => 'Event', 'icon' => 'bi-calendar-event-fill', 'href' => route('resident.announcements.index', ['category' => 'Event']), 'active' => request('category') == 'Event'],
            ]"
        />

@php
$pinned = $announcements->filter(fn($a) => $a->is_pinned);
$normal = $announcements->filter(fn($a) => !$a->is_pinned);

$categoryColors = [
    'Maintenance' => '#E6B566',
    'Meeting'     => '#7DA2D6',
    'Event'       => '#7FB69A',
    'Security'    => '#8B8F9C',
    'Finance'     => '#8FAE9E',
    'Emergency'   => '#C97A7A',
];

$categoryIcons = [
    'Maintenance' => 'bi-tools',
    'Meeting'     => 'bi-people-fill',
    'Event'       => 'bi-calendar-event-fill',
    'Security'    => 'bi-shield-lock-fill',
    'Finance'     => 'bi-cash-stack',
    'Emergency'   => 'bi-exclamation-octagon-fill',
];

$defaultColor = '#94a3b8';
$defaultIcon  = 'bi-megaphone-fill';
@endphp

{{-- ================= PINNED ANNOUNCEMENTS ================= --}}
@if($pinned->count())
<div class="space-y-8 relative z-10">
    <div class="flex items-center gap-4">
        <h4 class="text-[11px] font-black text-gray-400 uppercase tracking-[0.3em] flex items-center gap-3">
            <span class="w-8 h-px bg-gray-200"></span>
            Pinned Updates
        </h4>
        <div class="px-3 py-1 rounded-full bg-orange-500/10 border border-orange-500/20 text-orange-500 text-[9px] font-black uppercase tracking-widest animate-pulse">
            Priority
        </div>
    </div>

    <div class="grid gap-8">
        @foreach($pinned as $announcement)
        @php
            $cat = $announcement->category ?? 'General';
            $accentColor = $categoryColors[$cat] ?? $defaultColor;
            $icon = $categoryIcons[$cat] ?? $defaultIcon;
            $prio = $announcement->priority ?? 'normal';
            $isUrgent = in_array($prio, ['high', 'urgent']);
            $isRead = $announcement->is_read ?? false;
        @endphp

        <div onclick="window.location.href='{{ route('resident.announcements.show', $announcement) }}'" 
             class="relative block bg-white rounded-[24px] border border-gray-100 transition-all duration-500 overflow-hidden hover:shadow-[0_20px_50px_rgba(0,0,0,0.05)] group cursor-pointer hover:-translate-y-1">

            {{-- Sidebar Accent --}}
            <div class="absolute left-0 top-0 bottom-0 w-[6px] transition-all duration-500 group-hover:w-[10px]" style="background-color: {{ $accentColor }};"></div>

            <div class="p-8">
                <div class="flex flex-col md:flex-row gap-8">
                    {{-- CATEGORY ICON --}}
                    <div class="w-16 h-16 rounded-[20px] bg-gray-50 flex items-center justify-center shrink-0 transition-all duration-500 group-hover:scale-110"
                         style="color: {{ $accentColor }};">
                        <i class="bi {{ $icon }} text-2xl"></i>
                    </div>

                    <div class="flex-1 space-y-4">
                        {{-- Meta Row --}}
                        <div class="flex flex-wrap items-center gap-3">
                            <p class="text-[10px] font-black uppercase tracking-[0.2em]" style="color: {{ $accentColor }};">{{ $cat }}</p>
                            <span class="text-gray-300">•</span>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">{{ $announcement->created_at->format('M d, Y') }}</p>
                            
                            <div class="flex items-center gap-2 ml-auto">
                                <span class="px-3 py-1 rounded-lg bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase tracking-widest border border-emerald-100 flex items-center gap-1.5">
                                    <i class="bi bi-pin-angle-fill"></i> Pinned
                                </span>
                                @if($isUrgent)
                                    <span class="px-3 py-1 rounded-lg bg-red-50 text-red-600 text-[9px] font-black uppercase tracking-widest border border-red-100">
                                        Urgent
                                    </span>
                                @endif
                                @if(!$isRead)
                                    <div class="px-3 py-1 rounded-lg bg-blue-50 text-blue-600 text-[9px] font-black uppercase tracking-widest border border-blue-100 flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> New
                                    </div>
                                @else
                                    <div class="px-3 py-1 rounded-lg bg-gray-50 text-gray-400 text-[9px] font-black uppercase tracking-widest border border-gray-100 flex items-center gap-1.5">
                                        <i class="bi bi-check2-all"></i> Seen
                                    </div>
                                @endif
                            </div>
                        </div>

                        <h3 class="text-gray-900 font-black text-2xl tracking-tight leading-tight group-hover:text-emerald-600 transition-colors duration-300">
                            {{ $announcement->title }}
                        </h3>
                        
                        <p class="text-[14px] text-gray-500 leading-relaxed font-medium line-clamp-2">
                             {!! nl2br(e($announcement->content)) !!}
                        </p>

                        {{-- Footer Meta --}}
                        <div class="pt-2 flex items-center gap-4 text-[10px] font-black uppercase tracking-widest text-gray-400">
                            <span class="flex items-center gap-1.5">
                                <i class="bi bi-eye"></i> 1 / 59 SEEN
                            </span>
                            <span class="flex items-center gap-1.5">
                                <i class="bi bi-clock"></i> {{ $announcement->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ================= RECENT ANNOUNCEMENTS ================= --}}
<div class="space-y-8 relative z-10">
@if($normal->count() > 0)

<h4 class="text-[11px] font-black text-gray-400 uppercase tracking-[0.3em] flex items-center gap-3">
    <span class="w-8 h-px bg-gray-200"></span>
    Recent Updates
</h4>

<div class="grid gap-8">
        @foreach($normal as $announcement)
        @php
            $cat = $announcement->category ?? 'General';
            $accentColor = $categoryColors[$cat] ?? $defaultColor;
            $icon = $categoryIcons[$cat] ?? $defaultIcon;
            $prio = $announcement->priority ?? 'normal';
            $isUrgent = in_array($prio, ['high', 'urgent']);
            $isRead = $announcement->is_read ?? false;
        @endphp

        <div onclick="window.location.href='{{ route('resident.announcements.show', $announcement) }}'" 
             class="relative block bg-white rounded-[24px] border border-gray-100 transition-all duration-500 overflow-hidden hover:shadow-[0_20px_50px_rgba(0,0,0,0.05)] group cursor-pointer hover:-translate-y-1">

            {{-- Sidebar Accent --}}
            <div class="absolute left-0 top-0 bottom-0 w-[6px] transition-all duration-500 group-hover:w-[10px]" style="background-color: {{ $accentColor }};"></div>

            <div class="p-8">
                <div class="flex flex-col md:flex-row gap-8">
                    {{-- CATEGORY ICON --}}
                    <div class="w-16 h-16 rounded-[20px] bg-gray-50 flex items-center justify-center shrink-0 transition-all duration-500 group-hover:scale-110"
                         style="color: {{ $accentColor }};">
                        <i class="bi {{ $icon }} text-2xl"></i>
                    </div>

                    <div class="flex-1 space-y-4">
                        {{-- Meta Row --}}
                        <div class="flex flex-wrap items-center gap-3">
                            <p class="text-[10px] font-black uppercase tracking-[0.2em]" style="color: {{ $accentColor }};">{{ $cat }}</p>
                            <span class="text-gray-300">•</span>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">{{ $announcement->created_at->format('M d, Y') }}</p>
                            
                            <div class="flex items-center gap-2 ml-auto">
                                @if($isUrgent)
                                    <span class="px-3 py-1 rounded-lg bg-red-50 text-red-600 text-[9px] font-black uppercase tracking-widest border border-red-100">
                                        Urgent
                                    </span>
                                @endif
                                @if(!$isRead)
                                    <div class="px-3 py-1 rounded-lg bg-blue-50 text-blue-600 text-[9px] font-black uppercase tracking-widest border border-blue-100 flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> New
                                    </div>
                                @else
                                    <div class="px-3 py-1 rounded-lg bg-gray-50 text-gray-400 text-[9px] font-black uppercase tracking-widest border border-gray-100 flex items-center gap-1.5">
                                        <i class="bi bi-check2-all"></i> Seen
                                    </div>
                                @endif
                            </div>
                        </div>

                        <h3 class="text-gray-900 font-black text-2xl tracking-tight leading-tight group-hover:text-emerald-600 transition-colors duration-300">
                            {{ $announcement->title }}
                        </h3>
                        
                        <p class="text-[14px] text-gray-500 leading-relaxed font-medium line-clamp-2">
                             {!! nl2br(e($announcement->content)) !!}
                        </p>

                        {{-- Footer Meta --}}
                        <div class="pt-2 flex items-center gap-4 text-[10px] font-black uppercase tracking-widest text-gray-400">
                            <span class="flex items-center gap-1.5">
                                <i class="bi bi-eye"></i> 1 / 59 SEEN
                            </span>
                            <span class="flex items-center gap-1.5">
                                <i class="bi bi-clock"></i> {{ $announcement->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

@else

<div class="text-center py-24 bg-white rounded-[40px] border border-gray-100 relative overflow-hidden group">
    <div class="absolute inset-0 bg-gradient-to-b from-gray-50/50 to-transparent"></div>
    <div class="relative z-10">
        <div class="w-24 h-24 bg-gray-50 rounded-[32px] flex items-center justify-center mx-auto mb-8 text-gray-200 shadow-inner border border-gray-100 group-hover:scale-110 transition-transform duration-500">
            <i class="bi bi-inbox text-5xl"></i>
        </div>
        <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tight">No announcements found</h3>
        <p class="text-[11px] font-black text-gray-400 uppercase tracking-[0.3em] mt-4">Check back later for community updates</p>
    </div>
</div>

@endif

{{-- Pagination --}}
<div class="mt-8">
    {{ $announcements->links() }}
</div>

</div>

</div>

@push('scripts')
<script>
    async function markAsRead(btn, id) {
        // Optimistic UI update
        if (btn) btn.remove();

        try {
            await fetch(`{{ url('resident/announcements') }}/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
        } catch (error) {
            console.error('Failed to mark as read', error);
        }
    }
</script>
@endpush
@endsection
