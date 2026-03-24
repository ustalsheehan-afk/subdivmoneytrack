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
{{-- HEADER SECTION --}}
{{-- ======================================= --}}
<div class="glass-card p-8 mb-8 relative overflow-hidden group">
    {{-- Subtle gradient glow in background --}}
    <div class="absolute -right-20 -top-20 w-64 h-64 bg-brand-accent/5 rounded-full blur-3xl group-hover:bg-brand-accent/10 transition-all duration-700"></div>
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
        <div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                Announcements
            </h1>
            <p class="mt-2 text-gray-600 text-lg max-w-xl">
                Manage community updates and keep residents informed.
            </p>
        </div>

        <div class="flex items-center gap-3">
            {{-- Selection Toggle --}}
            <button @click="toggleSelectionMode()" 
                    :class="selectionMode ? 'bg-gray-100 text-gray-700' : 'bg-emerald-50 text-emerald-700'"
                    class="inline-flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-bold transition-all border border-gray-200 shadow-sm hover:shadow-md">
                <i :class="selectionMode ? 'bi-x-lg' : 'bi-check2-square'"></i>
                <span x-text="selectionMode ? 'Cancel Selection' : 'Select'"></span>
            </button>

            <a href="{{ route('admin.announcements.create') }}"
               class="btn-premium">
                <i class="bi bi-plus-lg text-sm"></i>
                New Announcement
            </a>
        </div>
    </div>
</div>

{{-- Bulk Actions (Visible only when items selected) --}}
<template x-if="selected.length > 0">
    <div class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 bg-gray-900 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-6 animate-fade-in border border-white/10">
        <span class="text-sm font-bold tracking-wide"><span x-text="selected.length"></span> Items Selected</span>
        <div class="h-6 w-px bg-white/10"></div>
        <div class="flex items-center gap-3">
            <form action="{{ route('admin.announcements.bulkArchive') }}" method="POST" onsubmit="return confirm('Archive selected?')">
                @csrf
                <template x-for="id in selected">
                    <input type="hidden" name="announcements[]" :value="id">
                </template>
                <button type="submit" class="text-xs font-black uppercase tracking-widest text-amber-400 hover:text-amber-300 transition-colors flex items-center gap-2">
                    <i class="bi bi-archive"></i> Archive
                </button>
            </form>
            <form action="{{ route('admin.announcements.bulkTrash') }}" method="POST" onsubmit="return confirm('Move selected to trash?')">
                @csrf
                @method('DELETE')
                <template x-for="id in selected">
                    <input type="hidden" name="announcements[]" :value="id">
                </template>
                <button type="submit" class="text-xs font-black uppercase tracking-widest text-red-400 hover:text-red-300 transition-colors flex items-center gap-2">
                    <i class="bi bi-trash"></i> Trash
                </button>
            </form>
        </div>
    </div>
</template>

{{-- ======================================= --}}
{{-- FILTER / TABS BAR --}}
{{-- ======================================= --}}
<div class="glass-card p-4 mb-8 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
    
    {{-- Tabs --}}
    <div class="flex items-center bg-gray-50 p-1.5 rounded-xl border border-gray-100 self-start">
        <a href="{{ route('admin.announcements.index') }}"
           class="px-6 py-2 rounded-lg text-xs font-black uppercase tracking-widest transition-all {{ request()->routeIs('admin.announcements.index') ? 'bg-white text-emerald-600 shadow-sm border border-gray-100' : 'text-gray-500 hover:text-gray-700' }}">
            Active
        </a>
        <a href="{{ route('admin.announcements.drafts') }}"
           class="relative px-6 py-2 rounded-lg text-xs font-black uppercase tracking-widest transition-all {{ request()->routeIs('admin.announcements.drafts') ? 'bg-white text-emerald-600 shadow-sm border border-gray-100' : 'text-gray-500 hover:text-gray-700' }}">
            Drafts
            @if(isset($draftsCount) && $draftsCount > 0)
                <span class="absolute -top-1 -right-1 w-4 h-4 bg-amber-500 text-white text-[8px] flex items-center justify-center rounded-full border border-white">
                    {{ $draftsCount }}
                </span>
            @endif
        </a>
        <a href="{{ route('admin.announcements.archive') }}"
           class="px-6 py-2 rounded-lg text-xs font-black uppercase tracking-widest transition-all {{ request()->routeIs('admin.announcements.archive') ? 'bg-white text-emerald-600 shadow-sm border border-gray-100' : 'text-gray-500 hover:text-gray-700' }}">
            Archive
        </a>
        <a href="{{ route('admin.announcements.trashed') }}"
           class="px-6 py-2 rounded-lg text-xs font-black uppercase tracking-widest transition-all {{ request()->routeIs('admin.announcements.trashed') ? 'bg-white text-emerald-600 shadow-sm border border-gray-100' : 'text-gray-500 hover:text-gray-700' }}">
            Trash
        </a>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap items-center gap-3">
        {{-- Select All Checkbox (Only in selection mode) --}}
        <template x-if="selectionMode">
            <button @click="toggleAll()" 
                    class="px-4 py-2 bg-emerald-50 text-emerald-700 rounded-lg text-[10px] font-black uppercase tracking-widest border border-emerald-100 hover:bg-emerald-100 transition-colors">
                <span x-text="selected.length === allIds.length && allIds.length > 0 ? 'Deselect All' : 'Select All'"></span>
            </button>
        </template>

        <form method="GET" class="flex flex-wrap items-center gap-3">
            <div class="relative group">
                <select name="month" onchange="this.form.submit()"
                        class="pl-4 pr-10 py-2.5 bg-white border border-gray-200 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-700 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all appearance-none cursor-pointer hover:border-gray-300">
                    <option value="">All Months</option>
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                        </option>
                    @endforeach
                </select>
                <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none group-hover:text-gray-600 transition-colors"></i>
            </div>

            <div class="relative group">
                <select name="year" onchange="this.form.submit()"
                        class="pl-4 pr-10 py-2.5 bg-white border border-gray-200 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-700 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all appearance-none cursor-pointer hover:border-gray-300">
                    <option value="">All Years</option>
                    @foreach(range(now()->year, now()->year - 5) as $y)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endforeach
                </select>
                <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none group-hover:text-gray-600 transition-colors"></i>
            </div>

            <div class="relative group">
                <i class="bi bi-funnel absolute left-4 top-1/2 -translate-y-1/2 text-emerald-600 text-xs"></i>
                <select name="category" onchange="this.form.submit()" 
                        class="pl-10 pr-10 py-2.5 bg-white border border-gray-200 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-700 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all appearance-none cursor-pointer hover:border-gray-300">
                    <option value="">All Categories</option>
                    @foreach(['Maintenance', 'Meeting', 'Security', 'Event', 'Finance', 'Emergency'] as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none group-hover:text-gray-600 transition-colors"></i>
            </div>
        </form>
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
<div class="mb-10">
    <h4 class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em] mb-4 flex items-center gap-2 px-1">
        <i class="bi bi-pin-fill"></i>
        Pinned Announcements
    </h4>

    <div class="grid grid-cols-1 gap-4">
        @foreach($pinned as $announcement)
            @include('admin.announcements.partials.card', [
                'announcement' => $announcement,
                'is_pinned_section' => true,
                'totalResidents' => $totalResidents ?? 0
            ])
        @endforeach
    </div>
</div>
@endif

{{-- ===================== --}}
{{-- NORMAL ANNOUNCEMENTS --}}
{{-- ===================== --}}
<div class="space-y-4">
    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 px-1">Recent Updates</h4>
    <div class="grid grid-cols-1 gap-4">
        @foreach($normal as $announcement)
            @include('admin.announcements.partials.card', [
                'announcement' => $announcement,
                'is_pinned_section' => false,
                'totalResidents' => $totalResidents ?? 0
            ])
        @endforeach
    </div>
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
@include('admin.announcements.partials.modal-script')
@endpush
@endsection
