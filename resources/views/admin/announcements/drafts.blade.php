@extends('layouts.admin')

@section('title', 'Draft Announcements')
@section('page-title', 'Drafts')

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

{{-- HEADER --}}
<div class="glass-card p-8 mb-8 relative overflow-hidden group">
    <div class="absolute -right-20 -top-20 w-64 h-64 bg-amber-500/5 rounded-full blur-3xl group-hover:bg-amber-500/10 transition-all duration-700"></div>
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
        <div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                Drafts
            </h1>
            <p class="mt-2 text-gray-600 text-lg max-w-xl">
                Review and publish your saved drafts.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <button @click="toggleSelectionMode()" 
                    :class="selectionMode ? 'bg-gray-100 text-gray-700' : 'bg-amber-50 text-amber-700'"
                    class="inline-flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-bold transition-all border border-gray-200 shadow-sm hover:shadow-md">
                <i :class="selectionMode ? 'bi-x-lg' : 'bi-check2-square'"></i>
                <span x-text="selectionMode ? 'Cancel Selection' : 'Select'"></span>
            </button>
            <a href="{{ route('admin.announcements.index') }}" class="px-6 py-3 bg-white border border-gray-200 text-gray-600 rounded-xl text-sm font-bold hover:bg-gray-50 transition-all duration-200">
                Back to Active
            </a>
        </div>
    </div>
</div>

{{-- Bulk Actions --}}
<template x-if="selected.length > 0">
    <div class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 bg-gray-900 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-6 animate-fade-in border border-white/10">
        <span class="text-sm font-bold tracking-wide"><span x-text="selected.length"></span> Items Selected</span>
        <div class="h-6 w-px bg-white/10"></div>
        <div class="flex items-center gap-3">
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

{{-- TABS --}}
<div class="glass-card p-4 mb-8 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
    <div class="flex items-center bg-gray-50 p-1.5 rounded-xl border border-gray-100 self-start">
        <a href="{{ route('admin.announcements.index') }}" class="px-6 py-2 rounded-lg text-xs font-black uppercase tracking-widest transition-all text-gray-500 hover:text-gray-700">Active</a>
        <a href="{{ route('admin.announcements.drafts') }}" class="px-6 py-2 rounded-lg text-xs font-black uppercase tracking-widest transition-all bg-white text-emerald-600 shadow-sm border border-gray-100">Drafts</a>
        <a href="{{ route('admin.announcements.archive') }}" class="px-6 py-2 rounded-lg text-xs font-black uppercase tracking-widest transition-all text-gray-500 hover:text-gray-700">Archive</a>
        <a href="{{ route('admin.announcements.trashed') }}" class="px-6 py-2 rounded-lg text-xs font-black uppercase tracking-widest transition-all text-gray-500 hover:text-gray-700">Trash</a>
    </div>

    <div class="flex items-center gap-3">
        <template x-if="selectionMode">
            <button @click="toggleAll()" class="px-4 py-2 bg-emerald-50 text-emerald-700 rounded-lg text-[10px] font-black uppercase tracking-widest border border-emerald-100 hover:bg-emerald-100 transition-colors">
                <span x-text="selected.length === allIds.length && allIds.length > 0 ? 'Deselect All' : 'Select All'"></span>
            </button>
        </template>
        
        <form method="GET" class="flex items-center gap-3">
            <select name="month" onchange="this.form.submit()" class="pl-4 pr-10 py-2.5 bg-white border border-gray-200 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-700 focus:ring-2 focus:ring-emerald-500/20 appearance-none cursor-pointer">
                <option value="">All Months</option>
                @foreach(range(1,12) as $m)
                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                @endforeach
            </select>
            <select name="year" onchange="this.form.submit()" class="pl-4 pr-10 py-2.5 bg-white border border-gray-200 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-700 focus:ring-2 focus:ring-emerald-500/20 appearance-none cursor-pointer">
                <option value="">All Years</option>
                @foreach(range(now()->year, now()->year - 5) as $y)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </form>
    </div>
</div>

{{-- LIST --}}
<div class="space-y-4">
    @forelse($announcements as $announcement)
        @include('admin.announcements.partials.card', [
            'announcement' => $announcement,
            'totalResidents' => $totalResidents ?? 0
        ])
    @empty
        <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-gray-200">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="bi bi-journal-text text-2xl text-gray-400"></i>
            </div>
            <h3 class="text-gray-900 font-medium">No drafts found</h3>
            <p class="text-gray-500 text-sm mt-1">Your saved drafts will appear here.</p>
        </div>
    @endforelse
</div>

</div>

@include('admin.announcements.partials.modal-script')

@endsection
