@extends('resident.layouts.app')

@section('title', $announcement->title)
@section('page-title', 'Announcement Details')

@push('modals')
<!-- Modal Overlay (Fixed) -->
<div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm transition-opacity duration-300"
     role="dialog" aria-modal="true">
    
    <!-- Modal Panel -->
    <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl ring-1 ring-gray-200 transform transition-all scale-100 opacity-100 flex flex-col max-h-[85vh]">
        
        <!-- Close Button -->
        <a href="{{ route('resident.announcements.index') }}" 
           class="absolute top-4 right-4 p-2 text-gray-400 hover:text-gray-600 bg-white hover:bg-gray-100 rounded-full transition z-10 shadow-sm border border-gray-100">
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
        <div class="absolute left-0 top-0 bottom-0 w-[6px] rounded-l-2xl z-20" style="background-color: {{ $accentColor }}"></div>

        <!-- Scrollable Content Area -->
        <div class="overflow-y-auto custom-scrollbar p-6 md:p-8 pl-8 md:pl-10 rounded-2xl">
            
            <!-- Header -->
            <div class="flex items-start gap-4 mb-6 pr-8">
                <!-- Icon Box -->
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0" 
                     style="background-color: {{ $accentColor }}20; color: {{ $accentColor }}">
                    <i class="bi {{ $icon }} text-xl"></i>
                </div>
                
                <div class="space-y-1">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <span class="text-xs font-bold uppercase tracking-wider text-gray-500">{{ $cat }}</span>
                        <span class="text-gray-300">•</span>
                        <span class="text-xs font-medium text-gray-400">{{ $announcement->created_at->format('M d, Y') }}</span>
                        
                        {{-- Priority Badge --}}
                        @php
                            $priority = strtolower($announcement->priority ?? 'fyi'); 
                            $priorityMap = [
                                'urgent'   => ['label' => 'Urgent',   'class' => 'bg-red-50 text-red-700 border-red-200'],
                                'upcoming' => ['label' => 'Upcoming', 'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
                                'fyi'      => ['label' => 'FYI',      'class' => 'hidden'], // Hide FYI to keep it clean
                            ];
                            $p = $priorityMap[$priority] ?? $priorityMap['fyi'];
                        @endphp
                        @if($priority !== 'fyi')
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase border {{ $p['class'] }}">
                                {{ $p['label'] }}
                            </span>
                        @endif

                        {{-- Read Badge --}}
                        <span class="ml-2 inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide border {{ $isRead ? 'bg-gray-100 text-gray-600 border-gray-200' : 'bg-blue-50 text-blue-600 border-blue-100' }}">
                            <i class="bi {{ $isRead ? 'bi-check-circle-fill' : 'bi-circle-fill text-[6px]' }}"></i>
                            {{ $isRead ? 'Read' : 'Unread' }}
                        </span>
                    </div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight break-words">
                        {{ $announcement->title }}
                    </h2>
                </div>
            </div>

            <!-- Content -->
            <div class="space-y-6">
                @if($announcement->image)
                    <div class="w-full rounded-2xl overflow-hidden border border-gray-100 shadow-sm relative group/img">
                        <img src="{{ Storage::url($announcement->image) }}" 
                             class="w-full h-auto object-cover max-h-[400px] cursor-pointer hover:brightness-95 transition-all" 
                             alt="{{ $announcement->title }}"
                             onclick="openLightbox(this.src)">
                        <div class="absolute inset-0 flex items-center justify-center bg-black/0 group-hover/img:bg-black/10 transition-all pointer-events-none">
                            <i class="bi bi-zoom-in text-white text-3xl opacity-0 group-hover/img:opacity-100 transition-opacity"></i>
                        </div>
                    </div>
                @endif

                <div class="prose prose-blue prose-lg max-w-none text-gray-600 leading-relaxed">
                    {!! nl2br(e($announcement->content)) !!}
                </div>
            </div>
            
            <!-- Actions / Footer -->
            <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <i class="bi bi-person-circle text-gray-400"></i>
                    <span>Posted by Administration</span>
                </div>

                <div class="flex items-center gap-3 w-full sm:w-auto">
                    @if(!$isRead)
                        <form method="POST" action="{{ route('resident.announcements.read', $announcement->id) }}">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium border border-gray-200 hover:bg-gray-50 transition w-full sm:w-auto justify-center">
                                <i class="bi bi-check2-circle text-gray-400"></i>
                                Mark as read
                            </button>
                        </form>
                    @endif
                    
                    <a href="{{ route('resident.announcements.index') }}" 
                       class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition w-full sm:w-auto text-center">
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

<!-- Lock Scroll Script -->
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

    // Allow closing by clicking backdrop
    document.querySelector('.fixed.inset-0:not(#lightboxModal)').addEventListener('click', function(e) {
        if (e.target === this) {
            window.location.href = "{{ route('resident.announcements.index') }}";
        }
    });

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
@endpush
