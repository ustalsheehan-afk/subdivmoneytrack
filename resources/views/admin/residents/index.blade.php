@extends('layouts.admin')

@section('title', 'Residents')
@section('page-title', 'Residents List')

@section('content')
@php
    $blockLots = \App\Models\Resident::select('block', 'lot')
        ->whereNotNull('block')
        ->whereNotNull('lot')
        ->distinct()
        ->orderBy('block')
        ->orderBy('lot')
        ->get()
        ->groupBy('block');
@endphp
<div class="space-y-8 animate-fade-in">

    {{-- ===================== --}}
    {{-- HEADER SECTION --}}
    {{-- ===================== --}}
    <div class="glass-card p-8 relative overflow-hidden group">
        {{-- Subtle gradient glow in background --}}
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-brand-accent/5 rounded-full blur-3xl group-hover:bg-brand-accent/10 transition-all duration-700"></div>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                    Residents
                </h1>
                <p class="mt-2 text-gray-600 text-lg max-w-xl">
                    Manage community members, property details, and resident accounts.
                </p>
            </div>

            <div class="flex items-center gap-3">
                {{-- Bulk Actions Dropdown --}}
                <div class="relative group/bulk">
                    <select id="bulkActionSelect" onchange="handleBulkActionChange(this)" 
                        class="appearance-none pl-5 pr-12 py-3.5 rounded-xl border border-gray-200 bg-gray-50 text-sm font-bold text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all cursor-pointer hover:bg-white hover:shadow-md">
                        <option value="">Select Action</option>
                        <option value="delete">Delete Selected</option>
                        <option value="email">Send Email</option>
                        <option value="export">Export Selected</option>
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400 group-hover/bulk:text-emerald-600 transition-colors">
                        <i class="bi bi-chevron-down text-xs"></i>
                    </div>
                </div>

                {{-- Add Resident Button --}}
             
            </div>
        </div>
    </div>

    {{-- ===================== --}}
    {{-- TOOLBAR SECTION --}}
    {{-- ===================== --}}
    <div class="glass-card p-4 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        
        {{-- Search Bar --}}
        <div class="flex-1 max-w-md">
            <form method="GET" action="{{ route('admin.residents.index') }}" class="relative group">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-600 transition-colors"></i>
                <input id="searchInput" type="text" name="search" value="{{ request('search') }}" autocomplete="off"
                    placeholder="Search name, email, or property..." 
                    class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all placeholder-gray-400">
                
                @foreach(request()->except(['search', 'page']) as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
            </form>
        </div>

        {{-- Filters & Toggles --}}
        <div class="flex flex-wrap items-center gap-3">
            
            {{-- Status Filter --}}
            <div class="relative group/filter">
                <button onclick="toggleDropdown('statusDropdown')" 
                    class="h-11 px-4 flex items-center gap-2 rounded-xl border border-gray-200 bg-white text-[10px] font-black uppercase tracking-widest text-gray-600 hover:border-emerald-500/30 hover:bg-gray-50 transition-all relative">
                    <i class="bi bi-funnel text-emerald-600"></i>
                    Status
                    <i class="bi bi-chevron-down text-[8px] opacity-50"></i>
                    @if(request('status'))
                        <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-emerald-500 rounded-full border-2 border-white"></span>
                    @endif
                </button>
                <div id="statusDropdown" class="hidden absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-50 py-2">
                    <div class="px-4 py-2 text-[10px] font-black text-gray-400 uppercase tracking-wider border-b border-gray-50 mb-1">Filter Status</div>
                    <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium transition-colors">All Statuses</a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium transition-colors">Active</a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'inactive']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium transition-colors">Inactive</a>
                </div>
            </div>

            {{-- Block/Lot Filter --}}
            <div class="relative group/filter">
                <button onclick="toggleDropdown('blockLotDropdown')" 
                    class="h-11 px-4 flex items-center gap-2 rounded-xl border border-gray-200 bg-white text-[10px] font-black uppercase tracking-widest text-gray-600 hover:border-emerald-500/30 hover:bg-gray-50 transition-all relative">
                    <i class="bi bi-building text-emerald-600"></i>
                    Property
                    <i class="bi bi-chevron-down text-[8px] opacity-50"></i>
                    @if(request('block') || request('lot'))
                        <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-emerald-500 rounded-full border-2 border-white"></span>
                    @endif
                </button>
                <div id="blockLotDropdown" class="hidden absolute right-0 top-full mt-2 w-64 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden flex flex-col">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
                        <input type="text" id="blockLotSearch" onkeyup="filterBlockLot(this)" 
                            placeholder="Filter block or lot..." 
                            class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs focus:ring-2 focus:ring-emerald-500/10 outline-none transition-all">
                    </div>
                    <div class="max-h-64 overflow-y-auto custom-scrollbar p-1" id="blockLotList">
                        <a href="{{ request()->fullUrlWithQuery(['block' => null, 'lot' => null]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium rounded-lg mb-1 transition-colors">Show All</a>
                        @foreach($blockLots as $block => $items)
                            <div class="block-group">
                                <a href="{{ request()->fullUrlWithQuery(['block' => $block, 'lot' => null]) }}" 
                                   class="block-header block px-4 py-2 text-xs font-black text-gray-900 hover:bg-emerald-50 hover:text-emerald-700 rounded-lg transition-colors"
                                   data-search="block {{ $block }}">Block {{ $block }}</a>
                                @foreach($items as $item)
                                    <a href="{{ request()->fullUrlWithQuery(['block' => $block, 'lot' => $item->lot]) }}" 
                                       class="lot-item block px-8 py-1.5 text-sm text-gray-500 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors {{ request('block') == $block && request('lot') == $item->lot ? 'bg-emerald-50 text-emerald-700 font-bold' : '' }}"
                                       data-search="block {{ $block }} lot {{ $item->lot }}">Lot {{ $item->lot }}</a>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- View Toggle --}}
            <div class="flex items-center bg-gray-50 p-1 rounded-xl border border-gray-100">
                <button onclick="toggleView('list')" id="listViewBtn" class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-400 hover:text-emerald-600 transition-all">
                    <i class="bi bi-list-ul text-lg"></i>
                </button>
                <button onclick="toggleView('grid')" id="gridViewBtn" class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-400 hover:text-emerald-600 transition-all">
                    <i class="bi bi-grid-fill text-lg"></i>
                </button>
            </div>

            {{-- Clear Button --}}
            @if(request()->anyFilled(['search', 'status', 'block', 'lot']))
                <a href="{{ route('admin.residents.index') }}" class="h-11 w-11 flex items-center justify-center rounded-xl border border-red-100 text-red-500 hover:bg-red-50 transition-all" title="Clear All Filters">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
        </div>
    </div>

    {{-- ===================== --}}
    {{-- TABLE CONTAINER --}}
    {{-- ===================== --}}
    <div class="glass-card overflow-hidden">
        
        {{-- LIST VIEW --}}
        <div id="listView" class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50/50 border-b border-gray-100">
                    <tr>
                        <th class="p-5 w-12 text-center bulk-checkbox hidden">
                            <input type="checkbox" onchange="toggleAllCheckboxes(this)" class="rounded-lg border-gray-300 text-emerald-600 focus:ring-emerald-500/20 transition-all">
                        </th>
                        <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Resident</th>
                        <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Block</th>
                        <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Lot</th>
                        <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Contact</th>
                        <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Joined</th>
                        <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                        <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($residents as $resident)
                    <tr onclick="selectResident({{ $resident->id }})" 
                        data-id="{{ $resident->id }}"
                        class="resident-row cursor-pointer hover:bg-emerald-50/30 transition-all duration-300 group border-l-4 border-transparent hover:border-emerald-500">
                        
                        <td onclick="event.stopPropagation()" class="p-5 text-center bulk-checkbox hidden">
                            <input type="checkbox" name="selected_residents[]" value="{{ $resident->id }}" data-email="{{ $resident->email }}" class="rounded-lg border-gray-300 text-emerald-600 focus:ring-emerald-500/20 resident-checkbox">
                        </td>

                        <td class="p-5">
                            <div class="flex items-center gap-4">
                                <div class="relative shrink-0">
                                    <img src="{{ $resident->photo ? asset('storage/' . $resident->photo) : asset('CDlogo.jpg') }}" 
                                        onerror="this.onerror=null; this.src='{{ asset('CDlogo.jpg') }}';"
                                        class="w-11 h-11 rounded-2xl object-cover border-2 border-white shadow-sm group-hover:scale-105 transition-transform duration-500">
                                    <span class="absolute -bottom-1 -right-1 w-3.5 h-3.5 rounded-full border-2 border-white {{ $resident->status === 'active' ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 group-hover:text-emerald-700 transition-colors">{{ $resident->first_name }} {{ $resident->last_name }}</p>
                                    <p class="text-[11px] text-gray-500 font-medium tracking-wide">{{ $resident->email }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="p-5 text-center">
                            <span class="text-sm font-bold text-gray-900">B{{ $resident->block }}</span>
                        </td>

                        <td class="p-5 text-center">
                            <span class="text-sm font-bold text-gray-900">L{{ $resident->lot }}</span>
                        </td>

                        <td class="p-5">
                            <span class="text-sm font-medium text-gray-600">{{ $resident->contact_number ?? 'No contact' }}</span>
                        </td>

                        <td class="p-5">
                            <span class="text-sm font-medium text-gray-600">{{ $resident->move_in_date ? $resident->move_in_date->format('M d, Y') : '-' }}</span>
                        </td>

                        <td class="p-5 text-center">
                            <span class="badge-standard 
                                {{ $resident->status === 'active' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-gray-50 text-gray-500 border border-gray-200' }}">
                                {{ $resident->status }}
                            </span>
                        </td>

                        <td class="p-5 text-center" onclick="event.stopPropagation()">
                            <div class="relative inline-block text-left">
                                <button onclick="toggleActionMenu('menu-{{ $resident->id }}')" 
                                        class="w-9 h-9 rounded-xl flex items-center justify-center text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 border border-transparent hover:border-emerald-100 transition-all">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>

                                {{-- Dropdown Menu --}}
                                <div id="menu-{{ $resident->id }}" 
                                     class="hidden absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 z-[60] overflow-hidden origin-top-right transform transition-all">
                                    
                                    <div class="px-4 py-3 border-b border-gray-50 bg-gray-50/50">
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Resident Actions</p>
                                    </div>

                                    <div class="p-1.5 space-y-0.5">
                                        <button onclick="selectResident({{ $resident->id }})" class="w-full text-left flex items-center gap-3 px-3 py-2 text-sm font-bold text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 rounded-xl transition-colors">
                                            <i class="bi bi-eye text-emerald-500"></i>
                                            View Profile
                                        </button>

                                        <a href="{{ route('admin.residents.edit', $resident->id) }}" class="w-full text-left flex items-center gap-3 px-3 py-2 text-sm font-bold text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 rounded-xl transition-colors">
                                            <i class="bi bi-pencil-square text-emerald-500"></i>
                                            Edit Details
                                        </a>

                                        <div class="h-px bg-gray-50 my-1"></div>

                                        <form action="{{ route('admin.residents.destroy', $resident->id) }}" method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this resident?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full text-left flex items-center gap-3 px-3 py-2 text-sm font-bold text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                                                <i class="bi bi-trash"></i>
                                                Delete Resident
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-20 text-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="bi bi-people text-2xl text-gray-300"></i>
                            </div>
                            <h3 class="text-gray-900 font-bold">No residents found</h3>
                            <p class="text-gray-500 text-sm mt-1">Try adjusting your filters or search terms.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- GRID VIEW --}}
        <div id="gridView" class="hidden p-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($residents as $resident)
            <div onclick="selectResident({{ $resident->id }})" class="glass-card p-6 flex flex-col items-center text-center group cursor-pointer relative">
                <div class="absolute top-4 right-4">
                    <span class="badge-standard {{ $resident->status === 'active' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-gray-50 text-gray-500 border border-gray-200' }}">
                        {{ $resident->status }}
                    </span>
                </div>
                <img src="{{ $resident->photo ? asset('storage/' . $resident->photo) : asset('CDlogo.jpg') }}" 
                    class="w-20 h-20 rounded-3xl object-cover border-4 border-white shadow-md group-hover:scale-110 transition-transform duration-500 mb-4">
                <h3 class="font-bold text-gray-900 group-hover:text-emerald-700 transition-colors leading-tight">{{ $resident->first_name }} {{ $resident->last_name }}</h3>
                <p class="text-xs text-gray-500 mb-4">{{ $resident->email }}</p>
                <div class="w-full pt-4 border-t border-gray-50 flex items-center justify-center gap-3">
                    <span class="px-2 py-1 bg-gray-50 rounded-lg text-[10px] font-black text-gray-600 uppercase tracking-widest">Blk {{ $resident->block }}</span>
                    <span class="px-2 py-1 bg-gray-50 rounded-lg text-[10px] font-black text-gray-600 uppercase tracking-widest">Lot {{ $resident->lot }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $residents->links() }}
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

        // Live search (debounced) for the Residents list
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            const form = searchInput.closest('form');
            let searchDebounceTimer;

            searchInput.addEventListener('input', function () {
                clearTimeout(searchDebounceTimer);
                searchDebounceTimer = setTimeout(() => {
                    if (form) form.submit();
                }, 350);
            });
        }
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
