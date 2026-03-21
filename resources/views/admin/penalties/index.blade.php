@extends('layouts.admin')

@section('title', 'Penalties')
@section('page-title', 'Penalties Management')

@section('content')
<div class="h-full bg-[#F8F9FB] overflow-y-auto">
    <div class="max-w-7xl mx-auto px-6 py-8 flex flex-col gap-8">

        {{-- STATS SECTION --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Total Penalties --}}
            <a href="{{ request()->fullUrlWithQuery(['status' => null, 'type' => null]) }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-shadow cursor-pointer">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="bi bi-exclamation-circle text-6xl text-blue-600"></i>
                </div>
                <p class="text-xs font-medium text-gray-600 uppercase tracking-wider mb-1">Total Penalties</p>
                <h3 class="text-3xl font-semibold text-gray-900">{{ $totalCount }}</h3>
                <div class="mt-4 flex items-center text-xs text-blue-700 font-bold">
                    <p class="text-sm font-medium text-blue-600">
                        Recorded violations
                    </p>
                </div>
            </a>

            {{-- Collected --}}
            <a href="{{ request()->fullUrlWithQuery(['status' => 'paid']) }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-shadow cursor-pointer">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="bi bi-check-circle text-6xl text-emerald-500"></i>
                </div>
                <p class="text-xs font-medium text-gray-600 uppercase tracking-wider mb-1">Total Collected</p>
                <h3 class="text-3xl font-semibold text-gray-900">₱{{ number_format($totalPaid, 2) }}</h3>
                <div class="mt-4 flex items-center text-xs text-emerald-600 font-medium">
                    <p class="text-sm font-medium text-emerald-600">
                        Paid penalties
                    </p>
                </div>
            </a>

            {{-- Pending --}}
            <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-shadow cursor-pointer">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="bi bi-hourglass-split text-6xl text-orange-500"></i>
                </div>
                <p class="text-xs font-medium text-gray-600 uppercase tracking-wider mb-1">Pending Payment</p>
                <h3 class="text-3xl font-semibold text-gray-900">₱{{ number_format($totalPending, 2) }}</h3>
                <div class="mt-4 flex items-center text-xs text-orange-600 font-medium">
                    <p class="text-sm font-medium text-orange-600">
                        Awaiting payment
                    </p>
                </div>
            </a>

            {{-- Unpaid --}}
            <a href="{{ request()->fullUrlWithQuery(['status' => 'unpaid']) }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-shadow cursor-pointer">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="bi bi-x-circle text-6xl text-red-500"></i>
                </div>
                <p class="text-xs font-medium text-gray-600 uppercase tracking-wider mb-1">Unpaid / Overdue</p>
                <h3 class="text-3xl font-semibold text-gray-900">₱{{ number_format($totalUnpaid, 2) }}</h3>
                <div class="mt-4 flex items-center text-xs text-red-600 font-medium">
                    <p class="text-sm font-medium text-red-600">
                        Past due date
                    </p>
                </div>
            </a>
        </div>

        {{-- TOOLBAR (Sticky, Filters, Actions) --}}
        <div class="p-5 bg-white rounded-2xl shadow-sm border border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 z-30">
            
            {{-- LEFT: Bulk Actions & Search --}}
            <div class="flex items-center gap-4 flex-1">
                
                {{-- Bulk Actions --}}
                <div class="flex items-center gap-2">
                    <div class="relative">
                        <select id="bulkActionSelect" onchange="handleBulkActionChange(this)" 
                            class="appearance-none pl-4 pr-10 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all cursor-pointer hover:bg-white hover:shadow-sm">
                            <option value="">Select Action</option>
                            <option value="delete">Delete Selected</option>
                            <option value="export">Export Selected</option>
                            <option value="email">Send Email Reminder</option>
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
                <form id="bulkDeleteForm" action="{{ route('admin.penalties.bulkDestroy') }}" method="POST" class="hidden">
                    @csrf
                    <div id="bulkDeleteInputs"></div>
                </form>

                {{-- Search --}}
                <form method="GET" action="{{ route('admin.penalties.index') }}" class="relative w-full max-w-xs group">
                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-hover:text-blue-500 transition-colors"></i>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Search resident, block, lot..." 
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all placeholder-gray-400">
                    
                    {{-- Preserve other filters --}}
                    @foreach(request()->except(['search', 'page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                </form>

            </div>

            {{-- RIGHT: Icon Filters --}}
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
                        
                        <div class="absolute top-full left-1/2 -translate-x-1/2 mt-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-50 shadow-lg">
                            Filter by Status
                        </div>

                        <div id="statusDropdown" class="hidden absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-50 py-2 transform origin-top-right transition-all">
                            <div class="px-4 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Status</div>
                            <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">All Statuses</a>
                            <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">Pending</a>
                            <a href="{{ request()->fullUrlWithQuery(['status' => 'paid']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">Paid</a>
                            <a href="{{ request()->fullUrlWithQuery(['status' => 'unpaid']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">Unpaid</a>
                        </div>
                    </div>

                    {{-- Type Filter --}}
                    <div class="relative group">
                        <button onclick="toggleDropdown('typeDropdown')" class="w-10 h-10 flex items-center justify-center rounded-xl border border-gray-200 text-gray-600 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all relative">
                            <i class="bi bi-tag-fill"></i>
                            @if(request('type'))
                                <span class="absolute top-2 right-2 w-2 h-2 bg-blue-500 rounded-full border border-white"></span>
                            @endif
                        </button>
                        
                        <div class="absolute top-full left-1/2 -translate-x-1/2 mt-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-50 shadow-lg">
                            Filter by Type
                        </div>

                        <div id="typeDropdown" class="hidden absolute right-0 top-full mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 z-50 py-2 transform origin-top-right transition-all">
                            <div class="px-4 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Filter Type</div>
                            <a href="{{ request()->fullUrlWithQuery(['type' => null]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">All Types</a>
                            <a href="{{ request()->fullUrlWithQuery(['type' => 'late_payment']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">Late Payment</a>
                            <a href="{{ request()->fullUrlWithQuery(['type' => 'overdue']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">Overdue</a>
                            <a href="{{ request()->fullUrlWithQuery(['type' => 'violation']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">Violation</a>
                            <a href="{{ request()->fullUrlWithQuery(['type' => 'damage']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600">Damage</a>
                        </div>
                    </div>

                    {{-- Combined Block & Lot Filter --}}
                    <div class="relative group">
                        <button onclick="toggleDropdown('blockLotDropdown')" class="w-10 h-10 flex items-center justify-center rounded-xl border border-gray-200 text-gray-600 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all relative">
                            <i class="bi bi-building"></i>
                            @if(request('block') || request('lot'))
                                <span class="absolute top-2 right-2 w-2 h-2 bg-blue-500 rounded-full border border-white"></span>
                            @endif
                        </button>
                        
                        <div class="absolute top-full left-1/2 -translate-x-1/2 mt-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-50 shadow-lg">
                            Filter by Block & Lot
                        </div>

                        <div id="blockLotDropdown" class="hidden absolute right-0 top-full mt-2 w-64 bg-white rounded-xl shadow-xl border border-gray-100 z-50 py-2 max-h-[400px] overflow-hidden flex flex-col">
                            
                            {{-- Search / Custom Input --}}
                            <div class="px-3 py-2 border-b border-gray-100 bg-gray-50/50">
                                <div class="relative">
                                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                    <input type="text" id="blockLotSearch" onkeyup="filterBlockLot(this)" 
                                        placeholder="Search Block or Lot..." 
                                        class="w-full pl-8 pr-3 py-2 rounded-lg border border-gray-200 text-xs focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all">
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

                    {{-- Clear Filters (Icon) --}}
                    @if(request()->anyFilled(['search', 'status', 'type', 'block', 'lot']))
                        <a href="{{ route('admin.penalties.index') }}" class="w-10 h-10 flex items-center justify-center rounded-xl border border-red-100 text-red-500 hover:bg-red-50 hover:border-red-200 transition-all group relative">
                            <i class="bi bi-x-lg"></i>
                            <div class="absolute top-full left-1/2 -translate-x-1/2 mt-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-50 shadow-lg">
                                Clear Filters
                            </div>
                        </a>
                    @endif

                </div>

                {{-- Add Button --}}
                <a href="{{ route('admin.penalties.create') }}" class="flex items-center justify-center w-10 h-10 bg-gray-900 text-white rounded-xl hover:bg-black transition shadow-lg hover:-translate-y-0.5 transform">
                    <i class="bi bi-plus-lg"></i>
                </a>
            </div>
        </div>

        {{-- SCROLLABLE LIST --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50/50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 w-12 text-center bulk-checkbox hidden">
                                <input type="checkbox" onchange="toggleAllCheckboxes(this)" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-left">Resident</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-left">Type</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-left">Reason</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-left">Date Issued</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Amount</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody id="penaltiesBody" class="divide-y divide-gray-100">
                        @include('admin.penalties.partials.rows')
                    </tbody>
                </table>
            </div>

            {{-- Load More / Spinner --}}
            <div class="p-6 text-center border-t border-gray-100" id="loadMoreContainer">
                @if($penalties->hasMorePages())
                    <button onclick="loadMore()" id="loadMoreBtn" 
                        class="px-6 py-2 bg-white border border-gray-200 text-gray-600 rounded-full text-sm font-medium hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
                        Load More Penalties
                    </button>
                    <div id="loadingSpinner" class="hidden">
                        <svg aria-hidden="true" class="w-8 h-8 mx-auto text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                        </svg>
                        <span class="sr-only">Loading...</span>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

{{-- ========================================= --}}
{{-- DRAWER COMPONENT                          --}}
{{-- ========================================= --}}
<script>
    // ------------------------------------------------------------------
    //  DRAWER LOGIC
    // ------------------------------------------------------------------
    function loadPenaltyDetails(id) {
        const url = `{{ route('admin.penalties.data', ['penalty' => ':id']) }}`.replace(':id', id);
        UniversalDrawer.open('penalty', url);
    }

    // ------------------------------------------------------------------
    //  DROPDOWN LOGIC
    // ------------------------------------------------------------------
    function toggleDropdown(id) {
        // Close all other dropdowns
        const allDropdowns = document.querySelectorAll('[id$="Dropdown"]');
        allDropdowns.forEach(dd => {
            if(dd.id !== id) dd.classList.add('hidden');
        });
        
        // Toggle the clicked one
        const el = document.getElementById(id);
        if(el) {
            el.classList.toggle('hidden');
        }
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.group') && !e.target.closest('[onclick^="toggleDropdown"]')) {
            const allDropdowns = document.querySelectorAll('[id$="Dropdown"]');
            allDropdowns.forEach(dd => dd.classList.add('hidden'));
        }
    });

    // ------------------------------------------------------------------
    //  BLOCK/LOT SEARCH (In Dropdown)
    // ------------------------------------------------------------------
    function filterBlockLot(input) {
        const term = input.value.toLowerCase();
        const list = document.getElementById('blockLotList');
        const groups = list.getElementsByClassName('block-group');
        let hasVisible = false;

        Array.from(groups).forEach(group => {
            // Check block header
            const header = group.querySelector('.block-header');
            const items = group.querySelectorAll('.lot-item');
            let groupVisible = false;

            // Check items
            items.forEach(item => {
                const text = item.getAttribute('data-search').toLowerCase();
                if (text.includes(term)) {
                    item.classList.remove('hidden');
                    groupVisible = true;
                    hasVisible = true;
                } else {
                    item.classList.add('hidden');
                }
            });

            // If header matches, show all items in that block? 
            // Or just show header? Let's keep it simple: 
            // If block header matches, show the whole group?
            // User probably searches "Block 1" or "Lot 5".
            // If I search "Block 1", I want to see Block 1 and its lots.
            const headerText = header.getAttribute('data-search').toLowerCase();
            if (headerText.includes(term)) {
                // Show all items if block matches?
                items.forEach(item => item.classList.remove('hidden'));
                groupVisible = true;
                hasVisible = true;
            }

            if (groupVisible) {
                group.classList.remove('hidden');
            } else {
                group.classList.add('hidden');
            }
        });

        const noRes = document.getElementById('noBlockLotResults');
        if (!hasVisible) {
            noRes.classList.remove('hidden');
        } else {
            noRes.classList.add('hidden');
        }
    }

    // ------------------------------------------------------------------
    //  BULK ACTIONS
    // ------------------------------------------------------------------
    function toggleAllCheckboxes(masterCheckbox) {
        const checkboxes = document.querySelectorAll('input[name="selected_penalties[]"]');
        checkboxes.forEach(cb => cb.checked = masterCheckbox.checked);
    }

    function handleBulkActionChange(select) {
        const action = select.value;
        const checkboxes = document.querySelectorAll('.bulk-checkbox');
        const applyBtn = document.getElementById('applyBulkActionBtn');
        
        if (action) {
            checkboxes.forEach(cb => cb.classList.remove('hidden'));
            applyBtn.classList.remove('hidden');
        } else {
            checkboxes.forEach(cb => cb.classList.add('hidden'));
            applyBtn.classList.add('hidden');
            // Uncheck all
            document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
        }
    }

    function submitBulkAction() {
        const select = document.getElementById('bulkActionSelect');
        const action = select.value;
        
        if (!action) return;

        // Collect selected IDs
        const selected = Array.from(document.querySelectorAll('input[name="selected_penalties[]"]:checked'))
            .map(cb => cb.value);

        if (selected.length === 0) {
            alert('Please select at least one penalty.');
            return;
        }

        if (action === 'delete') {
            if (!confirm('Are you sure you want to delete ' + selected.length + ' penalties? This action cannot be undone.')) {
                return;
            }
            
            // Populate hidden form
            const form = document.getElementById('bulkDeleteForm');
            const container = document.getElementById('bulkDeleteInputs');
            container.innerHTML = '';
            
            selected.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_ids[]';
                input.value = id;
                container.appendChild(input);
            });
            
            form.submit();
        } 
        else if (action === 'export') {
            // Implement export logic (maybe redirect to a route with IDs)
            alert('Export functionality coming soon!');
        }
        else if (action === 'email') {
            alert('Email functionality coming soon!');
        }
    }

    // ------------------------------------------------------------------
    //  LOAD MORE
    // ------------------------------------------------------------------
    let page = 1;
    function loadMore() {
        const btn = document.getElementById('loadMoreBtn');
        const spinner = document.getElementById('loadingSpinner');
        const container = document.getElementById('penaltiesBody');
        
        btn.classList.add('hidden');
        spinner.classList.remove('hidden');
        
        page++;
        
        // Construct URL with existing filters
        const url = new URL(window.location.href);
        url.searchParams.set('page', page);
        
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            if (html.trim() === '') {
                // No more data
                btn.parentElement.innerHTML = '<p class="text-gray-400 text-sm">No more penalties to load</p>';
            } else {
                container.insertAdjacentHTML('beforeend', html);
                btn.classList.remove('hidden');
                spinner.classList.add('hidden');
            }
        })
        .catch(err => {
            console.error(err);
            btn.classList.remove('hidden');
            spinner.classList.add('hidden');
            alert('Failed to load more penalties.');
        });
    }
</script>
@endsection