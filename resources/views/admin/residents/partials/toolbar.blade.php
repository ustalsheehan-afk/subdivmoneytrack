{{-- TOOLBAR --}}
<div class="p-5 border-b border-gray-100 bg-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 z-30">

    {{-- LEFT: Search --}}
    <div class="flex-1 max-w-lg relative">
        <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
        <input type="text" id="searchInput" 
            class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border border-transparent focus:bg-white focus:border-blue-200 rounded-xl text-sm focus:ring-4 focus:ring-blue-500/10 transition-all"
            placeholder="Search residents..." value="{{ request('search') }}">
    </div>

    {{-- RIGHT: Actions --}}
    <div class="flex items-center gap-3 flex-wrap justify-end">

        {{-- Select Mode --}}
        <button id="selectModeBtn" onclick="toggleSelectionMode()" 
            class="hidden sm:flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all">
            <i class="bi bi-check2-square"></i> Select
        </button>

        {{-- Bulk Delete Form (Hidden by default) --}}
        <form id="bulkDeleteForm" method="POST" action="{{ route('admin.residents.bulkDestroy') }}" class="hidden flex items-center gap-2">
            @csrf
            <div id="bulkDeleteInputs"></div>
            <button type="submit" class="flex items-center gap-2 px-3 py-2 bg-red-600 text-white rounded-xl text-sm font-bold hover:bg-red-700 transition-all shadow-md">
                <i class="bi bi-trash-fill"></i>
                Delete (<span id="selectedCount">0</span>)
            </button>
        </form>

        {{-- Filters --}}
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = !open" 
                class="flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all">
                <i class="bi bi-sliders2 text-gray-500"></i>
                Filters
                @php
                    $activeFilters = collect(['status','month','year'])->filter(fn($f) => request($f))->count();
                @endphp
                @if($activeFilters)
                    <span class="flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-blue-600 rounded-full">
                        {{ $activeFilters }}
                    </span>
                @endif
                <i class="bi bi-chevron-down text-[10px] text-gray-400 ml-1 transition-transform duration-200" :class="{'rotate-180': open}"></i>
            </button>

            {{-- Filter Popover --}}
            <div x-show="open" x-transition class="absolute top-full left-0 mt-2 w-72 bg-white rounded-2xl shadow-lg border border-gray-100 z-50 p-4"
                style="display:none;">
                <div class="flex justify-between items-center mb-4 pb-3 border-b border-gray-50">
                    <h3 class="font-bold text-gray-900 text-sm">Filter Residents</h3>
                    <button type="button" onclick="clearFilters()" class="text-xs text-red-500 hover:text-red-700 font-medium">Clear All</button>
                </div>

                <div class="space-y-4">
                    {{-- Status --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Status</label>
                        <div class="flex flex-col gap-2">
                            @foreach(['' => 'All', 'active' => 'Active', 'inactive' => 'Inactive'] as $val => $label)
                                <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-1 rounded-lg transition-colors">
                                    <input type="radio" name="status" value="{{ $val }}" onclick="updateFilter('status','{{ $val }}')"
                                        class="w-4 h-4 border-gray-300 text-blue-600 focus:ring-blue-500" {{ request('status') === $val ? 'checked' : '' }}>
                                    <span class="text-sm text-gray-600">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Lot --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Lot Number</label>
                        <input type="number" id="popoverLot" value="{{ request('lot') }}" placeholder="Enter Lot #"
                            class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none">
                    </div>

                    {{-- Move-in --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Move-in Date</label>
                        <div class="grid grid-cols-2 gap-2">
                            <select id="popoverMonth" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                                <option value="">Month</option>
                                @foreach(range(1,12) as $m)
                                    <option value="{{ $m }}" {{ request('month')==$m ? 'selected':'' }}>{{ date('M', mktime(0,0,0,$m,1)) }}</option>
                                @endforeach
                            </select>
                            <select id="popoverYear" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                                <option value="">Year</option>
                                @foreach(range(date('Y'),2000) as $y)
                                    <option value="{{ $y }}" {{ request('year')==$y ? 'selected':'' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <button onclick="applyAllFilters()" class="w-full py-2.5 bg-gray-900 text-white rounded-xl text-sm font-bold hover:bg-black transition-all shadow-md mt-2">
                        Apply Filters
                    </button>
                </div>
            </div>
        </div>

        {{-- Sort --}}
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = !open"
                class="flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all">
                <span class="text-gray-500">Sort:</span>
                <span class="font-bold text-gray-900">
                    @switch(request('sort_option'))
                        @case('newest') Recent @break
                        @case('last_added') ID (Desc) @break
                        @case('oldest') Oldest @break
                        @default A-Z
                    @endswitch
                </span>
                <i class="bi bi-chevron-down text-[10px] text-gray-400 ml-1 transition-transform duration-200" :class="{'rotate-180': open}"></i>
            </button>

            <div x-show="open" x-transition class="absolute top-full left-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 z-50 overflow-hidden py-1" style="display:none;">
                @foreach(['default'=>'A-Z','last_added'=>'ID (Desc)','newest'=>'Recent','oldest'=>'Oldest'] as $val=>$label)
                    <button onclick="updateSort('{{ $val }}')"
                        class="w-full text-left px-4 py-2.5 text-sm hover:bg-gray-50 {{ request('sort_option', 'default')==$val?'text-blue-600 font-bold bg-blue-50':'text-gray-600' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- View Toggle --}}
        <div class="flex items-center bg-gray-100 p-1 rounded-xl">
            <button onclick="toggleView('list')" id="listViewBtn" class="w-8 h-8 flex items-center justify-center rounded-lg text-blue-600 bg-white shadow-sm transition-all">
                <i class="bi bi-list-ul"></i>
            </button>
            <button onclick="toggleView('grid')" id="gridViewBtn" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-white hover:shadow-sm transition-all">
                <i class="bi bi-grid-fill"></i>
            </button>
        </div>

        {{-- Add Resident --}}
        <a href="{{ route('admin.residents.create') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-900 text-white hover:bg-black transition-all shadow-lg">
            <i class="bi bi-plus-lg text-lg"></i>
        </a>

    </div>
</div>