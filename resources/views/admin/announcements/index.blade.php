@extends('layouts.admin')

@section('title', 'Announcements')
@section('page-title', 'Announcements')

@section('content')

<div class="max-w-6xl mx-auto space-y-6" x-data="{ 
    selectionMode: false, 
    selected: [],
    allIds: [{{ $announcements->pluck('id')->implode(',') }}],
    toggleSelectionMode() {
        this.selectionMode = !this.selectionMode;
        if (!this.selectionMode) this.selected = [];
    },
    toggleAll() {
        if (this.selected.length === this.allIds.length) {
            this.selected = [];
        } else {
            this.selected = [...this.allIds];
        }
    }
}">

{{-- ======================================= --}}
{{-- ADMIN HEADER (MATCHES RESIDENT STYLE) --}}
{{-- ======================================= --}}
<div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

    {{-- TOP ROW: Title + Primary CTA --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-[#800020]/10 text-[#800020] flex items-center justify-center shadow-sm">
                <i class="bi bi-megaphone-fill text-lg"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">
                Manage Announcements
            </h2>
        </div>

        <div class="flex items-center gap-3">
            {{-- Selection Toggle --}}
            <button @click="toggleSelectionMode()" 
                    :class="selectionMode ? 'bg-gray-100 text-gray-700' : 'bg-blue-50 text-blue-700'"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all border border-transparent">
                <i :class="selectionMode ? 'bi-x-lg' : 'bi-check2-square'"></i>
                <span x-text="selectionMode ? 'Cancel Selection' : 'Select'"></span>
            </button>

            {{-- Bulk Actions (Visible only when items selected) --}}
            <template x-if="selected.length > 0">
                <div class="flex items-center gap-2">
                    <form action="{{ route('admin.announcements.bulkArchive') }}" method="POST" onsubmit="return confirm('Archive selected?')">
                        @csrf
                        <template x-for="id in selected">
                            <input type="hidden" name="announcements[]" :value="id">
                        </template>
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-600 text-white text-sm font-semibold hover:bg-amber-700 transition shadow-sm">
                            <i class="bi bi-archive"></i>
                            Archive
                        </button>
                    </form>

                    <form action="{{ route('admin.announcements.bulkTrash') }}" method="POST" onsubmit="return confirm('Move selected to trash?')">
                        @csrf
                        @method('DELETE')
                        <template x-for="id in selected">
                            <input type="hidden" name="announcements[]" :value="id">
                        </template>
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-red-600 text-white text-sm font-semibold hover:bg-red-700 transition shadow-sm">
                            <i class="bi bi-trash"></i>
                            Trash
                        </button>
                    </form>
                </div>
            </template>

            <a href="{{ route('admin.announcements.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#800020] text-white text-sm font-semibold hover:bg-[#600018] hover:shadow-lg transition-all shadow-md">
                <i class="bi bi-plus-lg text-sm"></i>
                New Announcement
            </a>
        </div>
    </div>

    {{-- BOTTOM ROW: Tabs + Filter --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-4 border-t border-gray-100">

        {{-- LEFT: Tabs --}}
        <div class="flex items-center gap-6 text-sm font-medium text-gray-500">
            <a href="{{ route('admin.announcements.index') }}"
               class="pb-1 transition-colors {{ request()->routeIs('admin.announcements.index') ? 'text-[#800020] border-b-2 border-[#800020] font-bold' : 'hover:text-gray-800' }}">
                Active
            </a>
            <a href="{{ route('admin.announcements.archive') }}"
               class="pb-1 transition-colors {{ request()->routeIs('admin.announcements.archive') ? 'text-[#800020] border-b-2 border-[#800020] font-bold' : 'hover:text-gray-800' }}">
                Archive
            </a>
            <a href="{{ route('admin.announcements.trashed') }}"
               class="pb-1 transition-colors {{ request()->routeIs('admin.announcements.trashed') ? 'text-[#800020] border-b-2 border-[#800020] font-bold' : 'hover:text-gray-800' }}">
                Trash
            </a>
        </div>

        {{-- RIGHT: Filter --}}
        <div class="flex items-center gap-4">
            {{-- Select All Checkbox (Only in selection mode) --}}
            <template x-if="selectionMode">
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="checkbox" 
                           @click="toggleAll()"
                           :checked="selected.length === allIds.length && allIds.length > 0"
                           class="w-4 h-4 rounded border-gray-300 text-[#800020] focus:ring-[#800020]/20 transition-all">
                    <span class="text-sm font-bold text-gray-500 group-hover:text-[#800020] transition-colors uppercase tracking-wider">Select All</span>
                </label>
            </template>

            <form method="GET" class="flex items-center gap-3">
                <div class="relative min-w-[140px]">
                    <select name="month" onchange="this.form.submit()"
                            class="w-full pl-3 pr-8 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 focus:ring-2 focus:ring-[#800020]/20 focus:border-[#800020] transition appearance-none cursor-pointer">
                        <option value="">All Months</option>
                        @foreach(range(1,12) as $m)
                            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                    <i class="bi bi-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                </div>

                <div class="relative min-w-[100px]">
                    <select name="year" onchange="this.form.submit()"
                            class="w-full pl-3 pr-8 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 focus:ring-2 focus:ring-[#800020]/20 focus:border-[#800020] transition appearance-none cursor-pointer">
                        <option value="">All Years</option>
                        @foreach(range(now()->year, now()->year - 5) as $y)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                    <i class="bi bi-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                </div>

                <div class="relative min-w-[200px]">
                    <i class="bi bi-funnel-fill absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <select name="category" onchange="this.form.submit()" 
                            class="w-full pl-9 pr-8 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 focus:ring-2 focus:ring-[#800020]/20 focus:border-[#800020] transition appearance-none cursor-pointer">
                        <option value="">All Categories</option>
                        @foreach(['Maintenance', 'Meeting', 'Security', 'Event', 'Finance', 'Emergency'] as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                    <i class="bi bi-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===================== --}}
{{-- DATA PREP --}}
{{-- ===================== --}}
@php
    $pinned = $announcements->filter(fn($a) => $a->is_pinned);
    $normal = $announcements->filter(fn($a) => !$a->is_pinned);
@endphp

{{-- ===================== --}}
{{-- PINNED ANNOUNCEMENTS --}}
{{-- ===================== --}}
@if($pinned->count())
<div class="mb-6">
    <h4 class="text-sm font-bold text-[#800020] uppercase mb-3 flex items-center gap-2">
        <i class="bi bi-pin-fill"></i>
        Pinned Announcements
    </h4>

    <div class="grid grid-cols-1 gap-6">
        @foreach($pinned as $announcement)
            @include('admin.announcements.partials.card', [
                'announcement' => $announcement,
                'pinned' => true,
                'totalResidents' => $totalResidents ?? 0
            ])
        @endforeach
    </div>
</div>
@endif

{{-- ===================== --}}
{{-- NORMAL ANNOUNCEMENTS --}}
{{-- ===================== --}}
<div class="grid grid-cols-1 gap-6">
    @foreach($normal as $announcement)
        @include('admin.announcements.partials.card', [
            'announcement' => $announcement,
            'totalResidents' => $totalResidents ?? 0
        ])
    @endforeach
</div>

@if($announcements->isEmpty())
    <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-gray-200">
        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="bi bi-inbox text-2xl text-gray-400"></i>
        </div>
        <h3 class="text-gray-900 font-medium">No announcements found</h3>
        <p class="text-gray-500 text-sm mt-1">Create your first announcement to get started.</p>
    </div>
@endif

</div>

@push('modals')
{{-- MODAL --}}
<div id="announcement-modal" class="fixed inset-0 z-[60] invisible opacity-0 transition-all duration-300 ease-in-out bg-gray-900/60 backdrop-blur-sm overflow-y-auto overflow-x-hidden flex items-center justify-center p-4 md:p-6" role="dialog" aria-modal="true">
    <div id="modal-panel" class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl ring-1 ring-gray-200 transform scale-95 opacity-0 transition-all duration-300 ease-out flex flex-col max-h-[90vh]">
        
        {{-- Close Button --}}
        <button onclick="closeModal()" class="absolute top-4 right-4 p-2 text-gray-400 hover:text-gray-600 bg-white hover:bg-gray-100 rounded-full transition z-50 border border-gray-100 shadow-sm">
            <i class="bi bi-x-lg text-lg"></i>
        </button>

        {{-- Accent Bar --}}
        <div id="modal-bar" class="absolute left-0 top-0 bottom-0 w-[6px] rounded-l-2xl bg-gray-300 z-20"></div>

        <div class="overflow-y-auto custom-scrollbar p-8 md:p-10 pl-10 md:pl-12 rounded-2xl">
            {{-- Header --}}
            <div class="flex items-start gap-5 mb-8">
                <div id="modal-icon-box" class="w-16 h-16 rounded-2xl flex items-center justify-center shrink-0 bg-gray-100 text-gray-500 shadow-sm border border-gray-100/50">
                    <i id="modal-icon" class="bi bi-megaphone-fill text-2xl"></i>
                </div>
                
                <div class="space-y-1.5">
                    <div class="flex items-center gap-2.5 mb-1.5">
                        <span id="modal-category" class="text-xs font-bold uppercase tracking-wider text-gray-700">General</span>
                        <span class="text-gray-400">•</span>
                        <span id="modal-date" class="text-xs font-bold text-gray-500">Date</span>
                    </div>
                    <h2 id="modal-title" class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight tracking-tight break-words">
                        Announcement Title
                    </h2>
                </div>
            </div>

            {{-- Content --}}
            <div id="modal-content-container" class="space-y-6">
                <div id="modal-content" class="prose prose-blue prose-2xl max-w-none text-gray-900 font-medium leading-relaxed antialiased break-words">
                    Content goes here...
                </div>

                {{-- Image Container --}}
                <div id="modal-image-container" class="hidden pt-4">
                    <img id="modal-image" src="" alt="Announcement Image" class="w-full h-auto rounded-2xl border border-gray-100 shadow-sm object-cover max-h-[500px]">
                </div>
            </div>
            
            {{-- Footer --}}
            <div class="mt-10 pt-8 border-t border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2.5 text-base text-gray-700">
                    <i class="bi bi-person-circle text-gray-500"></i>
                    <span class="font-medium">Posted by Administration</span>
                </div>
                <button onclick="closeModal()" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold rounded-xl transition-all shadow-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    function openModal(id, title, content, date, category, accentColor, iconClass, imagePath = null) {
        const modalTitle = document.getElementById('modal-title');
        const modalContent = document.getElementById('modal-content');
        const modalDate = document.getElementById('modal-date');
        const modalCategory = document.getElementById('modal-category');
        const iconBox = document.getElementById('modal-icon-box');
        const modalIcon = document.getElementById('modal-icon');
        const modalBar = document.getElementById('modal-bar');
        const modalImageContainer = document.getElementById('modal-image-container');
        const modalImage = document.getElementById('modal-image');

        if(modalTitle) modalTitle.innerText = title;
        if(modalContent) modalContent.innerHTML = content;
        if(modalDate) modalDate.innerText = date;
        if(modalCategory) modalCategory.innerText = category;
        
        // Update category styles
        if(iconBox) {
            iconBox.style.backgroundColor = accentColor + '20'; // 20% opacity
            iconBox.style.color = accentColor;
        }
        if(modalIcon) modalIcon.className = 'bi ' + iconClass + ' text-xl';
        if(modalBar) modalBar.style.backgroundColor = accentColor;

        // Handle Image
        if (imagePath && modalImage && modalImageContainer) {
            modalImage.src = '{{ asset("storage") }}/' + imagePath;
            modalImageContainer.classList.remove('hidden');
        } else if (modalImageContainer) {
            modalImageContainer.classList.add('hidden');
        }

        const modal = document.getElementById('announcement-modal');
        const panel = document.getElementById('modal-panel');
        
        if(modal && panel) {
            modal.classList.remove('invisible', 'opacity-0');
            panel.classList.remove('scale-95', 'opacity-0');
            panel.classList.add('scale-100', 'opacity-100');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModal() {
        const modal = document.getElementById('announcement-modal');
        const panel = document.getElementById('modal-panel');
        
        if(modal && panel) {
            modal.classList.add('invisible', 'opacity-0');
            panel.classList.remove('scale-100', 'opacity-100');
            panel.classList.add('scale-95', 'opacity-0');
        }
        
        setTimeout(() => {
            document.body.style.overflow = 'auto';
        }, 300);
    }

    // Close on click outside
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('announcement-modal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal();
                }
            });
        }
    });
</script>
@endpush
@endsection
