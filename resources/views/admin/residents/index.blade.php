@extends('layouts.admin')

@section('title', 'Residents')
@section('page-title', 'Residents List')

@section('content')
<div class="flex flex-col h-[calc(100vh-10rem)] bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden relative">

        {{-- ========================================= --}}
        {{-- TOOLBAR (Sticky, Filters, Actions)        --}}
        {{-- ========================================= --}}
        <div class="px-6 py-5 border-b border-gray-100 flex flex-wrap items-center justify-between gap-4 bg-white z-30 relative shadow-[0_4px_20px_-10px_rgba(0,0,0,0.05)]">
            
            {{-- LEFT: Bulk Actions & Search --}}
            <div class="flex items-center gap-4 flex-1">
                
                {{-- Bulk Actions --}}
                <div class="flex items-center gap-2">
                    <div class="relative">
                        <select id="bulkActionSelect" onchange="handleBulkActionChange(this)" 
                            class="appearance-none pl-4 pr-10 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all cursor-pointer hover:bg-white hover:shadow-sm">
                            <option value="">Select Action</option>
                            <option value="delete">Delete Selected</option>
                            <option value="email">Send Email</option>
                            <option value="export">Export Selected</option>
                        </select>
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                            <i class="bi bi-chevron-down text-xs"></i>
                        </div>
                    </div>

                    {{-- Apply Button (Hidden by default) --}}
                    <button id="applyBulkActionBtn" onclick="submitBulkAction()" class="hidden px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-xl hover:bg-black transition-all shadow-sm flex items-center gap-2">
                        <span>Apply</span>
                        <i class="bi bi-arrow-right text-xs"></i>
                    </button>
                </div>

                {{-- Hidden Form for Bulk Delete --}}
                <form id="bulkDeleteForm" action="{{ route('admin.residents.bulkDestroy') }}" method="POST" class="hidden">
                    @csrf
                    <div id="bulkDeleteInputs"></div>
                </form>
                
                {{-- Hidden Form for Bulk Export --}}
                <form id="bulkExportForm" action="{{ route('admin.residents.export') }}" method="GET" class="hidden">
                    <div id="bulkExportInputs"></div>
                </form>

                {{-- Search --}}
                <form method="GET" action="{{ route('admin.residents.index') }}" class="relative w-full max-w-xs group">
                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-hover:text-blue-500 transition-colors"></i>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Search residents..." 
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all placeholder-gray-400">
                    
                    {{-- Preserve other filters --}}
                    @foreach(request()->except(['search', 'page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                </form>

            </div>

            {{-- RIGHT: Icon Filters & View Toggle --}}
            <div class="flex items-center gap-2">

                {{-- Filter Group --}}
                <div class="flex items-center gap-2 mr-4 border-r border-gray-100 pr-4">
                    
                    {{-- Status Filter --}}
                    <div class="relative group">
                        <button onclick="toggleDropdown('statusDropdown')" class="w-10 h-10 flex items-center justify-center rounded-xl border border-gray-200 text-gray-600 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all relative">
                            <i class="bi bi-funnel-fill"></i>
                            @if(request('status'))
                                <span class="absolute top-2 right-2 w-2 h-2 bg-blue-500 rounded-full border border-white"></span>
                            @endif
                        </button>
                        
                        {{-- Tooltip (Moved to bottom) --}}
                        <div class="absolute top-full left-1/2 -translate-x-1/2 mt-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-50 shadow-lg">
                            Filter by Status
                        </div>

                        {{-- Dropdown --}}
                        <div id="statusDropdown" class="hidden absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-50 py-2 transform origin-top-right transition-all">
                            <div class="px-4 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Status</div>
                            <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">All Statuses</a>
                            <a href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">Active</a>
                            <a href="{{ request()->fullUrlWithQuery(['status' => 'inactive']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">Inactive</a>
                        </div>
                    </div>

                    {{-- Combined Block & Lot Filter --}}
                    @php
                        // View-level query to get Block/Lot structure without touching controller logic
                        $blockLots = \App\Models\Resident::select('block', 'lot')
                            ->whereNotNull('block')
                            ->whereNotNull('lot')
                            ->distinct()
                            ->orderBy('block')
                            ->orderBy('lot')
                            ->get()
                            ->groupBy('block');
                    @endphp
                    <div class="relative group">
                        <button onclick="toggleDropdown('blockLotDropdown')" class="w-10 h-10 flex items-center justify-center rounded-xl border border-gray-200 text-gray-600 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all relative">
                            <i class="bi bi-building"></i>
                            @if(request('block') || request('lot'))
                                <span class="absolute top-2 right-2 w-2 h-2 bg-blue-500 rounded-full border border-white"></span>
                            @endif
                        </button>
                        
                        {{-- Tooltip (Moved to bottom) --}}
                        <div class="absolute top-full left-1/2 -translate-x-1/2 mt-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-50 shadow-lg">
                            Filter by Block & Lot
                        </div>

                        {{-- Dropdown --}}
                        <div id="blockLotDropdown" class="hidden absolute right-0 top-full mt-2 w-64 bg-white rounded-xl shadow-xl border border-gray-100 z-50 py-2 max-h-[400px] overflow-hidden flex flex-col">
                            
                            {{-- Search / Custom Input --}}
                            <div class="px-3 py-2 border-b border-gray-100 bg-gray-50/50">
                                <div class="relative">
                                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                    <input type="text" id="blockLotSearch" onkeyup="filterBlockLot(this)" 
                                        placeholder="Search Block or Lot..." 
                                        class="w-full pl-8 pr-3 py-2 rounded-lg border border-gray-200 text-xs focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all">
                                </div>
                                
                                {{-- Custom Filter Buttons (Hidden by default, shown via JS) --}}
                                <div id="customFilterOptions" class="hidden mt-2 grid grid-cols-2 gap-2">
                                    <a id="customBlockBtn" href="#" class="text-center px-2 py-1.5 bg-blue-50 text-blue-600 rounded text-xs font-bold hover:bg-blue-100 transition">
                                        Filter Block
                                    </a>
                                    <a id="customLotBtn" href="#" class="text-center px-2 py-1.5 bg-blue-50 text-blue-600 rounded text-xs font-bold hover:bg-blue-100 transition">
                                        Filter Lot
                                    </a>
                                </div>
                            </div>

                        {{-- Scrollable List --}}
                        <div class="overflow-y-auto custom-scrollbar flex-1 p-1">
                            <a href="{{ request()->fullUrlWithQuery(['block' => null, 'lot' => null]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 font-medium rounded-lg mb-1">
                                Show All
                            </a>
                            
                            <div id="blockLotList">
                                @foreach($blockLots as $block => $items)
                                    <div class="block-group mb-1">
                                        {{-- Block Header --}}
                                        <a href="{{ request()->fullUrlWithQuery(['block' => $block, 'lot' => null]) }}" 
                                           class="block-header block px-4 py-2 text-sm font-bold text-gray-800 hover:bg-gray-50 hover:text-blue-600 bg-gray-50/50 rounded-lg flex justify-between items-center group/block"
                                           data-search="block {{ $block }}">
                                            <span>Block {{ $block }}</span>
                                            <i class="bi bi-chevron-right text-[10px] text-gray-400 opacity-0 group-hover/block:opacity-100 transition-opacity"></i>
                                        </a>
                                        
                                        {{-- Lots --}}
                                        <div class="pl-2 mt-1 space-y-0.5 border-l-2 border-gray-100 ml-4">
                                            @foreach($items as $item)
                                                <a href="{{ request()->fullUrlWithQuery(['block' => $block, 'lot' => $item->lot]) }}" 
                                                   class="lot-item block px-4 py-1.5 text-sm text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors flex justify-between items-center {{ request('block') == $block && request('lot') == $item->lot ? 'bg-blue-50 text-blue-600 font-bold' : '' }}"
                                                   data-search="block {{ $block }} lot {{ $item->lot }}">
                                                    <span>Lot {{ $item->lot }}</span>
                                                    @if(request('block') == $block && request('lot') == $item->lot)
                                                        <i class="bi bi-check-lg text-blue-600"></i>
                                                    @endif
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            {{-- No Results Msg --}}
                            <div id="noBlockLotResults" class="hidden px-4 py-8 text-center">
                                <p class="text-gray-400 text-xs">No matches found</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sort --}}
                <div class="relative group">
                    <button onclick="toggleDropdown('sortDropdown')" class="w-10 h-10 flex items-center justify-center rounded-xl border border-gray-200 text-gray-600 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all">
                        <i class="bi bi-sort-alpha-down"></i>
                    </button>
                    
                    {{-- Tooltip (Moved to bottom) --}}
                    <div class="absolute top-full left-1/2 -translate-x-1/2 mt-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-50 shadow-lg">
                        Sort List
                    </div>

                    {{-- Dropdown --}}
                    <div id="sortDropdown" class="hidden absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-50 py-2">
                        <div class="px-4 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Sort By</div>
                        <a href="{{ request()->fullUrlWithQuery(['sort_option' => 'name_asc']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">Name (A-Z)</a>
                        <a href="{{ request()->fullUrlWithQuery(['sort_option' => 'name_desc']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">Name (Z-A)</a>
                        <a href="{{ request()->fullUrlWithQuery(['sort_option' => 'block_asc']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">Block (Asc)</a>
                        <a href="{{ request()->fullUrlWithQuery(['sort_option' => 'created_at_desc']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">Newest First</a>
                        <a href="{{ request()->fullUrlWithQuery(['sort_option' => 'created_at_asc']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">Oldest First</a>
                    </div>
                </div>

                {{-- Clear Filters (Icon) --}}
                @if(request()->anyFilled(['search', 'status', 'block', 'lot', 'sort_option']))
                    <a href="{{ route('admin.residents.index') }}" class="w-10 h-10 flex items-center justify-center rounded-xl border border-red-100 text-red-500 hover:bg-red-50 hover:border-red-200 transition-all group relative">
                        <i class="bi bi-x-lg"></i>
                        <div class="absolute top-full left-1/2 -translate-x-1/2 mt-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-50 shadow-lg">
                            Clear Filters
                        </div>
                    </a>
                @endif

            </div>

            {{-- VIEW TOGGLE --}}
            <div class="flex items-center gap-1 bg-gray-100 p-1 rounded-xl">
                <button onclick="toggleView('list')" id="listViewBtn" class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-500 hover:bg-white hover:shadow-sm transition-all">
                    <i class="bi bi-list-ul text-lg"></i>
                </button>
                <button onclick="toggleView('grid')" id="gridViewBtn" class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-500 hover:bg-white hover:shadow-sm transition-all">
                    <i class="bi bi-grid-fill text-lg"></i>
                </button>
            </div>

            {{-- Add Button --}}
            <a href="{{ route('admin.residents.create') }}" class="ml-2 flex items-center justify-center w-10 h-10 bg-gray-900 text-white rounded-xl hover:bg-black transition shadow-lg hover:-translate-y-0.5 transform">
                <i class="bi bi-plus-lg"></i>
            </a>
        </div>
    </div>

    {{-- ========================================= --}}
    {{-- CONTENT AREA (List & Grid)        --}}
    {{-- ========================================= --}}
    <div class="flex-1 overflow-y-auto bg-white custom-scrollbar relative">
        
        @if($residents->count() > 0)
            
            {{-- LIST VIEW --}}
            <div id="listView" class="block w-full pb-20">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 backdrop-blur-sm sticky top-0 z-20 border-b border-gray-100">
                        <tr>
                            <th class="p-4 w-12 text-center bulk-checkbox hidden">
                                <input type="checkbox" onchange="toggleAllCheckboxes(this)" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Resident</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Contact</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Block</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Lot</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Joined</th>
                            <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($residents as $resident)
                        <tr onclick="selectResident({{ $resident->id }})" 
                            data-id="{{ $resident->id }}"
                            class="resident-row cursor-pointer hover:bg-gray-50 transition-all duration-200 group border-l-4 border-transparent">
                            
                            {{-- Checkbox --}}
                            <td onclick="event.stopPropagation()" class="p-4 text-center bulk-checkbox hidden">
                                <input type="checkbox" name="selected_residents[]" value="{{ $resident->id }}" data-email="{{ $resident->email }}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 resident-checkbox">
                            </td>

                            {{-- Name & Photo --}}
                            <td class="p-4">
                                <div class="flex items-center gap-4">
                                    <img src="{{ $resident->photo ? asset('storage/' . $resident->photo) : asset('CDlogo.jpg') }}" 
                                        onerror="this.onerror=null; this.src='{{ asset('CDlogo.jpg') }}';"
                                        class="w-10 h-10 rounded-full object-cover ring-2 ring-white shadow-sm group-hover:scale-105 transition-transform">
                                    <div>
                                        <p class="font-bold text-gray-900 group-hover:text-blue-700 transition">{{ $resident->first_name }} {{ $resident->last_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $resident->email }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Contact --}}
                            <td class="p-4 text-sm text-gray-600 font-medium align-middle">{{ $resident->contact_number }}</td>

                            {{-- Block (No Pill) --}}
                            <td class="p-4 text-sm text-gray-600 font-medium align-middle">
                                Block {{ $resident->block }}
                            </td>

                            {{-- Lot --}}
                            <td class="p-4 text-sm text-gray-600 font-medium align-middle">
                                Lot {{ $resident->lot }}
                            </td>

                            {{-- Joined --}}
                            <td class="p-4 text-right text-sm text-gray-600 font-medium align-middle">
                                {{ $resident->move_in_date ? $resident->move_in_date->format('M d, Y') : '-' }}
                            </td>

                            {{-- Status --}}
                            <td class="p-4 text-center align-middle">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold border capitalize tracking-wide
                                    {{ $resident->status === 'active' 
                                        ? 'bg-emerald-50 text-emerald-700 border-emerald-100' 
                                        : 'bg-red-50 text-red-700 border-red-100' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $resident->status === 'active' ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                    {{ $resident->status }}
                                </span>
                            </td>

                            {{-- Kebab Action Menu (No Label) --}}
                            <td class="p-4 text-center align-middle" onclick="event.stopPropagation()">
                                <div class="relative inline-block text-left">
                                    <button onclick="toggleActionMenu('menu-{{ $resident->id }}')" 
                                            class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-all focus:outline-none focus:ring-2 focus:ring-gray-200">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>

                                    {{-- Dropdown Menu --}}
                                    <div id="menu-{{ $resident->id }}" 
                                         class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden origin-top-right transform transition-all">
                                        
                                        <div class="px-4 py-3 border-b border-gray-50 bg-gray-50/50">
                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Actions</p>
                                        </div>

                                        <div class="p-1">
                                            {{-- Edit --}}
                                            <a href="{{ route('admin.residents.edit', $resident->id) }}" class="w-full text-left flex items-center gap-3 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-blue-600 rounded-lg transition-colors">
                                                <i class="bi bi-pencil-square text-blue-500"></i>
                                                Edit Details
                                            </a>

                                            {{-- View --}}
                                            <button onclick="selectResident({{ $resident->id }})" class="w-full text-left flex items-center gap-3 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-green-600 rounded-lg transition-colors">
                                                <i class="bi bi-eye text-green-500"></i>
                                                View Profile
                                            </button>

                                            {{-- Delete --}}
                                            <form action="{{ route('admin.residents.destroy', $resident->id) }}" method="POST"
                                                  onsubmit="return confirm('Are you sure you want to delete this resident?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full text-left flex items-center gap-3 px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                                    <i class="bi bi-trash"></i>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- GRID VIEW --}}
            <div id="gridView" class="hidden p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 pb-20">
                @foreach($residents as $resident)
                <div onclick="selectResident({{ $resident->id }})"
                    data-id="{{ $resident->id }}"
                    class="resident-card bg-white rounded-xl p-5 border border-gray-200 hover:shadow-md transition-all duration-200 cursor-pointer relative group">
                    
                    {{-- Checkbox --}}
                    <div onclick="event.stopPropagation()" class="absolute top-4 left-4 z-10 bulk-checkbox hidden">
                        <input type="checkbox" name="selected_residents[]" value="{{ $resident->id }}" data-email="{{ $resident->email }}" class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 resident-checkbox">
                    </div>

                    {{-- Status Pill (Upper Right) --}}
                    <span class="absolute top-4 right-4 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider border
                        {{ $resident->status === 'active' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-red-50 text-red-600 border-red-100' }}">
                        {{ $resident->status }}
                    </span>

                    <div class="flex flex-col items-center text-center mt-2">
                        <img src="{{ $resident->photo ? asset('storage/' . $resident->photo) : asset('CDlogo.jpg') }}" 
                            onerror="this.onerror=null; this.src='{{ asset('CDlogo.jpg') }}';"
                            class="w-16 h-16 rounded-full object-cover mb-3 ring-2 ring-gray-100 group-hover:ring-blue-50 transition">
                        
                        <h3 class="text-base font-bold text-gray-900 group-hover:text-blue-700 transition leading-tight">{{ $resident->first_name }} {{ $resident->last_name }}</h3>
                        <p class="text-xs text-gray-500 mb-4">{{ $resident->email }}</p>

                        {{-- Footer: Location & Contact --}}
                        <div class="w-full border-t border-gray-50 pt-3 flex items-center justify-center gap-2 text-xs text-gray-600">
                            <span class="font-medium bg-gray-50 px-2 py-1 rounded">Blk {{ $resident->block }} - Lot {{ $resident->lot }}</span>
                            @if($resident->contact_number)
                                <span class="text-gray-300">|</span>
                                <span class="font-medium font-mono">{{ $resident->contact_number }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        @else
            <div class="flex flex-col items-center justify-center h-full text-center pb-20">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <i class="bi bi-people text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">No Residents Found</h3>
                <p class="text-gray-500 max-w-xs mx-auto mt-2">Try adjusting your filters to find who you're looking for.</p>
                <a href="{{ route('admin.residents.index') }}" class="mt-6 px-6 py-2 bg-gray-900 text-white rounded-lg hover:bg-black transition text-sm font-medium">Clear Filters</a>
            </div>
        @endif
    </div>

</div>

{{-- ========================================= --}}
{{-- DRAWER COMPONENT (Replaced manual div)    --}}
{{-- ========================================= --}}
<x-drawer id="residentDrawer" width="max-w-xl">
    <div id="drawerContent" class="h-full">
        {{-- Content loaded via AJAX --}}
    </div>
</x-drawer>

{{-- ========================================= --}}
{{-- JAVASCRIPT LOGIC                          --}}
{{-- ========================================= --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check for invitation link in session
        @if(session('invitation_link'))
            if (typeof openInvitationModal === 'function') {
                openInvitationModal('{{ session('invitation_link') }}');
            }
        @endif

        // Initialize View State
        const savedView = localStorage.getItem('residents_view_mode') || 'list';
        toggleView(savedView);

        // Check for active ID in URL
        const urlParams = new URLSearchParams(window.location.search);
        const activeId = urlParams.get('active_id');
        if (activeId) selectResident(activeId, false);

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.group') && !event.target.closest('.relative')) {
                closeAllDropdowns();
            }
        });
    });

    // ---------------------------------------------------------
    // 1. FILTER DROPDOWNS & LIVE SEARCH
    // ---------------------------------------------------------
    function toggleActionMenu(menuId) {
        const menu = document.getElementById(menuId);
        const isHidden = menu.classList.contains('hidden');
        
        // Close all other menus first
        document.querySelectorAll('[id^="menu-"]').forEach(el => {
            el.classList.add('hidden');
        });

        // Toggle current menu
        if (isHidden) {
            menu.classList.remove('hidden');
        }
    }
    
    function toggleDropdown(id) {
        const dropdown = document.getElementById(id);
        const isHidden = dropdown.classList.contains('hidden');
        
        closeAllDropdowns();

        if (isHidden) {
            dropdown.classList.remove('hidden');
            // Auto-focus search input if opening block/lot dropdown
            if (id === 'blockLotDropdown') {
                setTimeout(() => {
                    const searchInput = document.getElementById('blockLotSearch');
                    if (searchInput) searchInput.focus();
                }, 50);
            }
        }
    }

    function closeAllDropdowns() {
        document.querySelectorAll('[id$="Dropdown"]').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('[id^="menu-"]').forEach(el => el.classList.add('hidden'));
    }

    function filterBlockLot(input) {
        const filter = input.value.toLowerCase();
        const list = document.getElementById('blockLotList');
        const groups = list.getElementsByClassName('block-group');
        const customOptions = document.getElementById('customFilterOptions');
        const noResults = document.getElementById('noBlockLotResults');
        const customBlockBtn = document.getElementById('customBlockBtn');
        const customLotBtn = document.getElementById('customLotBtn');
        
        let hasVisible = false;

        // Handle Custom Input Buttons
        if (filter.length > 0) {
            customOptions.classList.remove('hidden');
            
            // Construct URLs for custom buttons
            const currentUrl = new URL(window.location.href);
            
            // Block Button
            currentUrl.searchParams.set('block', input.value);
            currentUrl.searchParams.delete('lot'); // Clear lot when filtering by block
            customBlockBtn.href = currentUrl.toString();
            customBlockBtn.textContent = `Filter Block "${input.value}"`;
            
            // Lot Button
            currentUrl.searchParams.set('lot', input.value);
            currentUrl.searchParams.delete('block'); // Clear block when filtering by lot (optional, or keep both? Usually lot is unique enough or combined)
            // If user wants Block X Lot Y, they select from list. If they type, it's ambiguous.
            // Let's assume generic Lot filter.
            customLotBtn.href = currentUrl.toString();
            customLotBtn.textContent = `Filter Lot "${input.value}"`;
        } else {
            customOptions.classList.add('hidden');
        }

        // Filter List Items
        Array.from(groups).forEach(group => {
            const header = group.querySelector('.block-header');
            const items = group.querySelectorAll('.lot-item');
            const blockText = header.getAttribute('data-search').toLowerCase();
            
            let groupHasMatch = false;

            // Check if Block Header matches
            if (blockText.includes(filter)) {
                groupHasMatch = true;
                items.forEach(item => item.classList.remove('hidden')); // Show all lots if block matches
            } else {
                // Check individual lots
                items.forEach(item => {
                    const lotText = item.getAttribute('data-search').toLowerCase();
                    if (lotText.includes(filter)) {
                        item.classList.remove('hidden');
                        groupHasMatch = true;
                    } else {
                        item.classList.add('hidden');
                    }
                });
            }

            if (groupHasMatch) {
                group.classList.remove('hidden');
                hasVisible = true;
            } else {
                group.classList.add('hidden');
            }
        });

        // Show/Hide No Results
        if (hasVisible) {
            noResults.classList.add('hidden');
        } else {
            noResults.classList.remove('hidden');
        }
    }

    // ---------------------------------------------------------
    // 2. BULK ACTIONS
    // ---------------------------------------------------------
    function handleBulkActionChange(select) {
        const checkboxes = document.querySelectorAll('.bulk-checkbox');
        const applyBtn = document.getElementById('applyBulkActionBtn');
        
        if (select.value !== "") {
            // Show checkboxes & Apply button
            checkboxes.forEach(el => el.classList.remove('hidden'));
            applyBtn.classList.remove('hidden');
        } else {
            // Hide checkboxes & Apply button
            checkboxes.forEach(el => el.classList.add('hidden'));
            applyBtn.classList.add('hidden');
            // Uncheck all
            document.querySelectorAll('.resident-checkbox').forEach(cb => cb.checked = false);
        }
    }

    function toggleAllCheckboxes(source) {
        document.querySelectorAll('.resident-checkbox').forEach(cb => cb.checked = source.checked);
    }

    function submitBulkAction() {
        const select = document.getElementById('bulkActionSelect');
        const action = select.value;
        const selectedCheckboxes = Array.from(document.querySelectorAll('.resident-checkbox:checked'));
        const selectedIds = selectedCheckboxes.map(cb => cb.value);
        const selectedEmails = selectedCheckboxes.map(cb => cb.getAttribute('data-email')).filter(e => e); // Filter out empty/null

        if (selectedIds.length === 0) {
            alert('Please select at least one resident.');
            return;
        }

        // DELETE ACTION
        if (action === 'delete') {
            if (confirm('Are you sure you want to DELETE ' + selectedIds.length + ' residents? This action cannot be undone.')) {
                const form = document.getElementById('bulkDeleteForm');
                const container = document.getElementById('bulkDeleteInputs');
                container.innerHTML = '';
                
                selectedIds.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = id;
                    container.appendChild(input);
                });
                
                form.submit();
            }
        } 
        // EMAIL ACTION
        else if (action === 'email') {
            if (selectedEmails.length === 0) {
                alert('None of the selected residents have a valid email address.');
                return;
            }
            if (confirm(`Send email to ${selectedEmails.length} residents? This will open your default email client.`)) {
                // Use BCC to keep emails private
                window.location.href = `mailto:?bcc=${selectedEmails.join(',')}`;
            }
        }
        // EXPORT ACTION
        else if (action === 'export') {
            if (confirm(`Export ${selectedIds.length} selected residents?`)) {
                const form = document.getElementById('bulkExportForm');
                const container = document.getElementById('bulkExportInputs');
                container.innerHTML = '';
                
                // Add selected IDs to the export form
                selectedIds.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]'; // Assuming backend might use this, or we just trigger the route
                    input.value = id;
                    container.appendChild(input);
                });
                
                form.submit();
            }
        }
        else {
            alert('Please select a valid action.');
        }
    }

    // ---------------------------------------------------------
    // 3. STATE MANAGEMENT (Single Source of Truth)
    // ---------------------------------------------------------
    let activeResidentId = null;

    window.toggleView = function(viewMode) {
        const listBtn = document.getElementById('listViewBtn');
        const gridBtn = document.getElementById('gridViewBtn');
        const listView = document.getElementById('listView');
        const gridView = document.getElementById('gridView');

        if (viewMode === 'grid') {
            listView.classList.add('hidden');
            gridView.classList.remove('hidden');
            listBtn.classList.remove('bg-white', 'shadow-sm', 'text-blue-600');
            gridBtn.classList.add('bg-white', 'shadow-sm', 'text-blue-600');
        } else {
            gridView.classList.add('hidden');
            listView.classList.remove('hidden');
            gridBtn.classList.remove('bg-white', 'shadow-sm', 'text-blue-600');
            listBtn.classList.add('bg-white', 'shadow-sm', 'text-blue-600');
        }
        localStorage.setItem('residents_view_mode', viewMode);
    }

    window.selectResident = function(id, pushState = true) {
        // Prevent selection if clicking checkbox
        if (event.target.closest('.bulk-checkbox') || event.target.tagName === 'INPUT') return;

        activeResidentId = id;
        highlightActiveRow();

        if (pushState) {
            const url = new URL(window.location);
            url.searchParams.set('active_id', id);
            window.history.pushState({}, '', url);
        }
        loadResidentDetails(id);
    }

    window.highlightActiveRow = function() {
        // Clear all highlights
        document.querySelectorAll('.resident-row, .resident-card').forEach(el => {
            if (el.classList.contains('resident-row')) {
                el.classList.remove('bg-blue-50', 'border-blue-600');
                el.classList.add('border-transparent');
            }
            if (el.classList.contains('resident-card')) {
                el.classList.remove('ring-2', 'ring-blue-600', 'bg-blue-50');
            }
        });

        if (!activeResidentId) return;

        // Apply Highlight
        document.querySelectorAll(`[data-id="${activeResidentId}"]`).forEach(el => {
            if (el.classList.contains('resident-row')) {
                el.classList.add('bg-blue-50', 'border-blue-600');
                el.classList.remove('border-transparent');
            }
            if (el.classList.contains('resident-card')) {
                el.classList.add('ring-2', 'ring-blue-600', 'bg-blue-50');
            }
        });
    }

    // ---------------------------------------------------------
    // 4. DRAWER LOGIC
    // ---------------------------------------------------------
    window.loadResidentDetails = async function(id) {
        // Use the component's open function to show the drawer UI
        if (typeof openResidentDrawer === 'function') {
            openResidentDrawer();
        }

        const content = document.getElementById('drawerContent');

        // Show Loading State
        content.innerHTML = `
            <div class="h-full flex items-center justify-center">
                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-900"></div>
            </div>
        `;

        try {
            const url = `{{ route('admin.residents.show', ':id') }}`.replace(':id', id);
            // Ensure we request AJAX to get only the partial
            const response = await fetch(url, { 
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                } 
            });
            
            if (!response.ok) throw new Error('Failed to load');
            
            const html = await response.text();
            content.innerHTML = html;
        } catch (error) {
            content.innerHTML = `
                <div class="p-8 text-center text-red-500">
                    <p class="font-bold">Failed to load resident details.</p>
                    <p class="text-sm mt-2">Please try again later.</p>
                </div>
            `;
        }
    }
    
    // Tab switching logic (used inside the drawer content)
    window.showDrawerTab = function(tab) {
        document.querySelectorAll('.drawer-tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.drawer-tab-btn').forEach(el => {
            el.classList.remove('text-[#800020]', 'border-[#800020]');
            el.classList.add('text-gray-500', 'border-transparent');
        });

        const activeContent = document.getElementById('drawer-tab-' + tab);
        if (activeContent) activeContent.classList.remove('hidden');

        const activeBtn = document.querySelector(`button[data-tab="${tab}"]`);
        if (activeBtn) {
            activeBtn.classList.remove('text-gray-500', 'border-transparent');
            activeBtn.classList.add('text-[#800020]', 'border-[#800020]');
        }
    }
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 3px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
</style>
@endsection
