@extends('layouts.admin')

@section('title', 'Trashed Announcements')
@section('page-title', 'Trashed Announcements')

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
{{-- ADMIN HEADER --}}
{{-- ======================================= --}}
<div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

    {{-- TOP ROW: Title + Primary CTA --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shadow-sm">
                <i class="bi bi-trash-fill text-lg"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">
                Trashed Announcements
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
                    <form action="{{ route('admin.announcements.bulkRestore') }}" method="POST" onsubmit="return confirm('Restore selected announcements?')">
                        @csrf
                        @method('PATCH')
                        <template x-for="id in selected">
                            <input type="hidden" name="announcements[]" :value="id">
                        </template>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 hover:shadow-lg transition-all shadow-md">
                            <i class="bi bi-arrow-clockwise"></i>
                            Restore Selected
                        </button>
                    </form>

                    <form action="{{ route('admin.announcements.bulkForceDelete') }}" method="POST" onsubmit="return confirm('PERMANENTLY DELETE selected announcements? This cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <template x-for="id in selected">
                            <input type="hidden" name="announcements[]" :value="id">
                        </template>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-red-600 text-white text-sm font-semibold hover:bg-red-700 hover:shadow-lg transition-all shadow-md">
                            <i class="bi bi-trash-fill"></i>
                            Delete Permanently
                        </button>
                    </form>
                </div>
            </template>
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

            <form method="GET" class="flex flex-wrap items-center gap-3">
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
            </form>
        </div>
    </div>
</div>

{{-- Announcements List --}}
<div class="grid grid-cols-1 gap-6">
    @forelse($announcements as $announcement)
        @include('admin.announcements.partials.card', [
            'announcement' => $announcement,
            'isTrashedView' => true
        ])
    @empty
        <div class="col-span-full text-center py-12 bg-white rounded-2xl border border-dashed border-gray-200">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="bi bi-trash text-2xl text-gray-400"></i>
            </div>
            <h3 class="text-gray-900 font-medium">No trashed announcements found</h3>
            <p class="text-gray-500 text-sm mt-1">Deleted items will appear here.</p>
        </div>
    @endforelse
</div>

</div>

@endsection
