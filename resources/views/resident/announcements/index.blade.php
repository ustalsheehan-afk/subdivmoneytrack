@extends('resident.layouts.app')

@section('title', 'Announcements')
@section('page-title', 'Announcements')

@section('content')
<div class="space-y-8 pb-20 max-w-5xl mx-auto px-4">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm animate-fade-in">
        <div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight mb-2">Announcements</h2>
            <p class="text-sm font-medium text-gray-500 flex items-center gap-2">
                <i class="bi bi-megaphone text-blue-500"></i>
                Stay updated with the latest news and alerts from the administration.
            </p>
        </div>
        
        <div class="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0 custom-scrollbar">
            @php
                $currentCat = request('category');
                $categories = [
                    ['id' => '', 'label' => 'All', 'icon' => 'bi-grid-fill'],
                    ['id' => 'Emergency', 'label' => 'Emergency', 'icon' => 'bi-exclamation-octagon-fill'],
                    ['id' => 'Maintenance', 'label' => 'Maintenance', 'icon' => 'bi-tools'],
                    ['id' => 'Meeting', 'label' => 'Meeting', 'icon' => 'bi-people-fill'],
                    ['id' => 'Event', 'label' => 'Event', 'icon' => 'bi-calendar-event-fill'],
                ];
            @endphp
            @foreach($categories as $cat)
                <a href="{{ route('resident.announcements.index', ['category' => $cat['id']]) }}" 
                   class="px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest transition-all border shrink-0 flex items-center gap-2
                    {{ $currentCat == $cat['id'] ? 'bg-blue-600 text-white border-blue-600 shadow-lg shadow-blue-100' : 'bg-white text-gray-500 border-gray-100 hover:border-blue-200 hover:bg-blue-50/50' }}">
                    <i class="bi {{ $cat['icon'] }}"></i>
                    {{ $cat['label'] }}
                </a>
            @endforeach
        </div>
    </div>

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
<div class="space-y-6">
    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2">
        <i class="bi bi-pin-angle-fill text-orange-500"></i>
        Pinned Updates
    </h4>

    <div class="grid gap-4">
        @foreach($pinned as $announcement)
        @php
            $cat = $announcement->category ?? 'General';
            $accentColor = $categoryColors[$cat] ?? $defaultColor;
            $icon = $categoryIcons[$cat] ?? $defaultIcon;
            $prio = $announcement->priority ?? 'normal';
            $isUrgent = in_array($prio, ['high', 'urgent']);
            $prioClass = match($prio) {
                'urgent' => 'bg-red-100 text-red-800 border-red-200',
                'high'   => 'bg-orange-100 text-orange-800 border-orange-200',
                default  => null
            };
            $isRead = $announcement->is_read ?? false;
        @endphp

        <div onclick="window.location.href='{{ route('resident.announcements.show', $announcement) }}'" 
             class="relative block bg-white rounded-2xl border transition-all duration-300 overflow-hidden hover:shadow-lg group cursor-pointer {{ $isUrgent ? 'border-red-200 shadow-red-50 bg-red-50/5' : 'border-gray-200 shadow-sm hover:border-blue-300 hover:-translate-y-0.5' }}">

            <div class="absolute left-0 top-0 bottom-0 w-[5px] group-hover:w-[8px] transition-all duration-300" style="background-color: {{ $accentColor }};"></div>

            {{-- HEADER --}}
            <div class="pl-8 pr-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3 bg-gray-50/40 group-hover:bg-white transition-colors duration-300">
                <div class="flex items-center gap-4">
                    {{-- CATEGORY ICON --}}
                    <div class="w-11 h-11 rounded-2xl flex items-center justify-center shrink-0 shadow-sm transition-transform duration-300 group-hover:scale-110"
                         style="background-color: {{ $accentColor }}25; color: {{ $accentColor }};">
                        <i class="bi {{ $icon }} text-lg"></i>
                    </div>

                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <p class="text-[11px] font-black uppercase tracking-widest" style="color: {{ $accentColor }};">{{ $cat }}</p>
                            @if($prioClass)
                                <span class="px-2 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-wider border {{ $prioClass }} shadow-sm">
                                    {{ $prio }}
                                </span>
                            @endif
                        </div>
                        <p class="text-[11px] text-gray-400 font-bold flex items-center gap-2">
                            <span class="flex items-center gap-1"><i class="bi bi-calendar3"></i> {{ $announcement->created_at->format('M d, Y') }}</span>
                            <span class="text-gray-200">•</span>
                            <span class="flex items-center gap-1"><i class="bi bi-clock"></i> {{ $announcement->created_at->diffForHumans() }}</span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-full bg-orange-50 text-orange-700 text-[10px] font-black uppercase tracking-wider border border-orange-200 flex items-center gap-1.5 shadow-sm">
                        <i class="bi bi-pin-angle-fill"></i> Pinned
                    </span>
                    
                    @if(!$isRead)
                        <div id="mark-btn-{{ $announcement->id }}" 
                             class="px-2.5 py-1 rounded-full bg-blue-600 text-white text-[10px] font-black uppercase tracking-wider border border-blue-700 flex items-center gap-1.5 shadow-md animate-pulse">
                            <span class="w-1.5 h-1.5 rounded-full bg-white"></span> New
                        </div>
                    @else
                        <div class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase tracking-wider border border-emerald-200 flex items-center gap-1.5 opacity-80">
                            <i class="bi bi-check2-all"></i> Seen
                        </div>
                    @endif
                </div>
            </div>

            <div class="pl-8 pr-6 py-5">
                <div class="flex flex-col md:flex-row gap-8">
                    @if($announcement->image)
                        <div class="w-full md:w-56 h-36 rounded-2xl overflow-hidden shrink-0 border border-gray-100 shadow-sm relative group/img">
                            <img src="{{ Storage::url($announcement->image) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $announcement->title }}">
                            <div class="absolute inset-0 bg-black/5 group-hover/img:bg-transparent transition-colors"></div>
                        </div>
                    @endif
                    
                    <div class="flex-1 space-y-3">
                        <h3 class="text-gray-900 font-black text-xl leading-tight group-hover:text-blue-600 transition-colors flex items-center gap-2">
                            {{ $announcement->title }}
                            <i class="bi bi-arrow-right text-blue-500 opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300"></i>
                        </h3>
                        
                        <p class="text-sm text-gray-500 leading-relaxed font-medium line-clamp-3">
                             {!! nl2br(e($announcement->content)) !!}
                        </p>

                        @if(false) {{-- Placeholder for attachment indicator logic --}}
                        <div class="pt-2 flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-lg bg-gray-50 text-gray-500 text-[10px] font-bold border border-gray-100">
                                <i class="bi bi-paperclip"></i> 2 Attachments
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ================= RECENT ANNOUNCEMENTS ================= --}}
<div class="space-y-6">
@if($normal->count() > 0)

<h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2">
    <i class="bi bi-clock-history text-blue-500"></i>
    Recent Updates
</h4>

<div class="grid gap-4">
        @foreach($normal as $announcement)
        @php
            $cat = $announcement->category ?? 'General';
            $accentColor = $categoryColors[$cat] ?? $defaultColor;
            $icon = $categoryIcons[$cat] ?? $defaultIcon;
            $prio = $announcement->priority ?? 'normal';
            $isUrgent = in_array($prio, ['high', 'urgent']);
            $prioClass = match($prio) {
                'urgent' => 'bg-red-100 text-red-800 border-red-200',
                'high'   => 'bg-orange-100 text-orange-800 border-orange-200',
                default  => null
            };
            $isRead = $announcement->is_read ?? false;
        @endphp

        <div onclick="window.location.href='{{ route('resident.announcements.show', $announcement) }}'" class="relative block bg-white rounded-2xl border transition-all overflow-hidden hover:shadow-md group cursor-pointer {{ $isUrgent ? 'border-red-200 shadow-red-50' : 'border-gray-200 shadow-sm hover:border-blue-200' }}">

            <div class="absolute left-0 top-0 bottom-0 w-[4px]" style="background-color: {{ $accentColor }};"></div>

            {{-- HEADER --}}
            <div class="pl-7 pr-6 py-3 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3 bg-gray-50/30">
                <div class="flex items-center gap-3">
                    {{-- CATEGORY ICON --}}
                    <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0"
                         style="background-color: {{ $accentColor }}20; color: {{ $accentColor }};">
                        <i class="bi {{ $icon }} text-sm"></i>
                    </div>

                    <div class="space-y-0.5">
                        <div class="flex items-center gap-2">
                            <p class="text-xs font-semibold text-gray-700">{{ $cat }}</p>
                            @if($prioClass)
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold uppercase border {{ $prioClass }}">
                                    {{ $prio }}
                                </span>
                            @endif
                        </div>
                        <p class="text-[11px] text-gray-500 flex items-center gap-1.5">
                            <span>{{ $announcement->created_at->format('M d, Y • g:i A') }}</span>
                            <span class="text-gray-300">|</span>
                            <span>Posted by Administration</span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    @if(!$isRead)
                        <button id="mark-btn-{{ $announcement->id }}" 
                                onclick="event.stopPropagation(); markAsRead(this, {{ $announcement->id }})" 
                                class="px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 text-[10px] font-bold uppercase tracking-wide border border-blue-200 flex items-center gap-1 hover:bg-blue-100 transition cursor-pointer">
                            <i class="bi bi-circle-fill text-[6px]"></i> New
                        </button>
                    @endif
                </div>
            </div>

            <div class="pl-7 pr-6 py-4">
                <div class="flex flex-col md:flex-row gap-6">
                    @if($announcement->image)
                        <div class="w-full md:w-48 h-32 rounded-xl overflow-hidden shrink-0 border border-gray-100">
                            <img src="{{ Storage::url($announcement->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $announcement->title }}">
                        </div>
                    @endif
                    
                    <div class="flex-1">
                        <h3 class="text-gray-900 font-bold text-lg mb-2 group-hover:text-blue-600 transition-colors">
                            {{ $announcement->title }}
                        </h3>
                        
                        <div class="relative">
                            <div class="text-sm text-gray-600 leading-relaxed line-clamp-3">
                                 {!! nl2br(e($announcement->content)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

@else

<div class="text-center py-12 bg-white rounded-2xl border border-dashed border-gray-200">
    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="bi bi-inbox text-2xl text-gray-400"></i>
    </div>
    <h3 class="text-gray-900 font-medium">No announcements found</h3>
    <p class="text-gray-500 text-sm mt-1">Check back later for community updates.</p>
</div>

@endif
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
