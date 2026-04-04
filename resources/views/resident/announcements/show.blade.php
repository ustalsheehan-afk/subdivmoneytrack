@extends('resident.layouts.app')

@section('title', $announcement->title)
@section('page-title', 'Announcement Details')

@section('content')
<!-- Modal Overlay (Fixed) -->
<div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm transition-opacity duration-300"
     role="dialog" aria-modal="true">
    
    <!-- Modal Panel -->
    <div class="relative w-full max-w-2xl bg-white rounded-[32px] shadow-2xl ring-1 ring-gray-100 transform transition-all scale-100 opacity-100 flex flex-col max-h-[85vh] overflow-hidden group">
        
        <!-- Background Glow -->
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl group-hover:bg-emerald-500/10 transition-all duration-700"></div>

        <!-- Close Button -->
        <a href="{{ route('resident.announcements.index') }}" 
           class="absolute top-6 right-6 p-2.5 text-gray-400 hover:text-gray-900 bg-gray-50 hover:bg-gray-100 rounded-full transition z-30 shadow-sm border border-gray-100">
            <i class="bi bi-x-lg text-lg"></i>
        </a>

        <!-- Accent Bar & Logic -->
        @php
            $categoryColors = [
                'Maintenance' => '#E6B566',
                'Meeting'     => '#7DA2D6',
                'Event'       => '#7FB69A',
                'Security'    => '#8B8F9C',
                'Finance'     => '#8FAE9E',
                'Emergency'   => '#C97A7A',
            ];
            $cat = $announcement->category ?? 'General';
            $accentColor = $categoryColors[$cat] ?? '#94a3b8';
            
            $icon = match($cat) {
                'Maintenance' => 'bi-tools',
                'Meeting' => 'bi-people-fill',
                'Event' => 'bi-calendar-event-fill',
                'Security' => 'bi-shield-lock-fill',
                'Finance' => 'bi-cash-stack',
                'Emergency' => 'bi-exclamation-octagon-fill',
                default => 'bi-megaphone-fill',
            };

            $isRead = (bool) ($announcement->is_read ?? false);
        @endphp
        <div class="absolute left-0 top-0 bottom-0 w-[8px] z-20" style="background-color: {{ $accentColor }}"></div>

        <!-- Scrollable Content Area -->
        <div class="overflow-y-auto custom-scrollbar p-8 md:p-10 pl-10 md:pl-12 rounded-[32px] relative z-10">
            
            <!-- Header -->
            <div class="flex items-start gap-6 mb-8 pr-12">
                <!-- Icon Box -->
                <div class="w-16 h-16 rounded-[20px] flex items-center justify-center shrink-0 shadow-lg" 
                     style="background-color: {{ $accentColor }}20; color: {{ $accentColor }}; border: 1px solid {{ $accentColor }}30;">
                    <i class="bi {{ $icon }} text-2xl"></i>
                </div>
                
                <div class="space-y-2">
                    <div class="flex flex-wrap items-center gap-3 mb-1">
                        <span class="text-[10px] font-black uppercase tracking-[0.2em]" style="color: {{ $accentColor }}">{{ $cat }}</span>
                        <span class="text-gray-200">•</span>
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">{{ $announcement->created_at->format('M d, Y') }}</span>
                        
                        {{-- Priority Badge --}}
                        @php
                            $priority = strtolower($announcement->priority ?? 'fyi'); 
                            $priorityMap = [
                                'urgent'   => ['label' => 'Urgent',   'class' => 'bg-red-50 text-red-600 border-red-100'],
                                'upcoming' => ['label' => 'Upcoming', 'class' => 'bg-amber-50 text-amber-600 border-amber-100'],
                                'fyi'      => ['label' => 'FYI',      'class' => 'hidden'], 
                            ];
                            $p = $priorityMap[$priority] ?? $priorityMap['fyi'];
                        @endphp
                        @if($priority !== 'fyi')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border {{ $p['class'] }}">
                                {{ $p['label'] }}
                            </span>
                        @endif

                        {{-- Read Badge --}}
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $isRead ? 'bg-gray-50 text-gray-400 border-gray-100' : 'bg-emerald-50 text-emerald-600 border-emerald-100 shadow-[0_0_15px_rgba(16,185,129,0.05)]' }}">
                            <i class="bi {{ $isRead ? 'bi-check2-all' : 'bi-circle-fill text-[5px]' }}"></i>
                            {{ $isRead ? 'Read' : 'Unread' }}
                        </span>
                    </div>
                    <h2 class="text-2xl md:text-3xl font-black text-gray-900 leading-tight tracking-tight break-words">
                        {{ $announcement->title }}
                    </h2>
                </div>
            </div>

            <!-- Content -->
            <div class="space-y-8">
                @if($announcement->image)
                    <div class="w-full rounded-[24px] overflow-hidden border border-gray-100 shadow-xl relative group/img bg-gray-50">
                        <img src="{{ Storage::url($announcement->image) }}" 
                             class="w-full h-auto object-cover max-h-[400px] cursor-pointer hover:scale-[1.02] transition-all duration-700" 
                             alt="{{ $announcement->title }}"
                             onclick="window.open(this.src,'_blank')">
                    </div>
                @endif

                <div class="prose prose-emerald max-w-none">
                    <p class="text-[16px] text-gray-600 leading-relaxed font-medium whitespace-pre-line">
                        {!! nl2br(e($announcement->content)) !!}
                    </p>
                </div>
            </div>
            
            <!-- Actions / Footer -->
            <div class="mt-10 pt-8 border-t border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-700 border border-emerald-100">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Authority</p>
                        <p class="text-xs font-black text-gray-900 uppercase tracking-widest">Administration</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 w-full sm:w-auto">
                    @if(!$isRead)
                        <form method="POST" action="{{ route('resident.announcements.read', $announcement->id) }}" class="flex-1 sm:flex-none">
                            @csrf
                            <button type="submit"
                                    class="btn-premium w-full sm:w-auto justify-center">
                                <i class="bi bi-check2-circle"></i>
                                Mark as read
                            </button>
                        </form>
                    @endif
                    
                    <a href="{{ route('resident.announcements.index') }}" 
                       class="btn-secondary w-full sm:w-auto justify-center text-center">
                        Close
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lightbox Modal -->
<div id="lightboxModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/95 backdrop-blur-md" onclick="closeLightbox()">
    <button class="absolute top-6 right-6 w-12 h-12 bg-white/10 hover:bg-white/20 text-white rounded-full flex items-center justify-center transition-all">
        <i class="bi bi-x-lg text-xl"></i>
    </button>
    <img id="lightboxImg" src="#" class="max-w-[90vw] max-h-[85vh] rounded-xl shadow-2xl animate-zoom-in object-contain">
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-4">
        <a id="lightboxDownload" href="#" download class="px-8 py-3 bg-white/10 hover:bg-white/20 text-white text-[11px] font-bold rounded-full flex items-center gap-2 transition-all uppercase tracking-widest backdrop-blur-sm border border-white/10">
            <i class="bi bi-download"></i>
            <span>Download Image</span>
        </a>
    </div>
</div>

<script>
    document.body.style.overflow = 'hidden';
    
    function openLightbox(src) {
        const modal = document.getElementById('lightboxModal');
        const img = document.getElementById('lightboxImg');
        const download = document.getElementById('lightboxDownload');
        img.src = src;
        download.href = src;
        modal.classList.remove('hidden');
    }

    function closeLightbox() {
        document.getElementById('lightboxModal').classList.add('hidden');
    }

    // Cleanup on exit
    window.addEventListener('beforeunload', () => {
        document.body.style.overflow = '';
    });

    // Escape to close
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (!document.getElementById('lightboxModal').classList.contains('hidden')) {
                closeLightbox();
            } else {
                window.location.href = "{{ route('resident.announcements.index') }}";
            }
        }
    });
</script>
<style>
    @keyframes zoomIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-zoom-in {
        animation: zoomIn 0.2s ease-out forwards;
    }
</style>
@endsection
