@extends('layouts.admin')

@section('title', 'Billing Batches')
@section('page-title', 'Billing Batches')

@section('content')
<div class="space-y-6 pb-12">
    {{-- COMPACT TOOLBAR --}}
    <div class="flex items-center justify-between gap-4 bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
        <div class="flex items-center gap-3 flex-1">
            <a href="{{ route('admin.dues.dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-200 bg-gray-50 text-xs font-bold text-gray-700 hover:bg-white hover:shadow-sm transition-all group">
                <i class="bi bi-graph-up text-blue-500 group-hover:scale-110 transition-transform"></i>
                <span>Financial Overview</span>
            </a>
            <div class="relative w-full max-w-xs">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" id="liveSearch" placeholder="Search batches..." 
                    class="w-full pl-9 pr-4 py-2 rounded-lg border border-gray-200 bg-gray-50 text-xs focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/5 transition-all outline-none">
            </div>
        </div>
        <a href="{{ route('admin.dues.create') }}" class="px-5 py-2 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 transition-all shadow-md shadow-blue-100 flex items-center gap-2">
            <i class="bi bi-plus-lg"></i>
            <span>Create Batch</span>
        </a>
    </div>

    @forelse($groupedBatches as $month => $batchesInMonth)
        <div class="month-section animate__animated animate__fadeIn" data-month="{{ strtolower($month) }}">
            {{-- MINIMAL MONTH CARD --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm mb-6">
                {{-- CARD HEADER --}}
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50 rounded-t-2xl">
                    <div>
                        <h3 class="text-base font-bold text-gray-900 tracking-tight">{{ $month }}</h3>
                        @php
                            $monthTotalExpected = $batchesInMonth->sum('total_expected');
                            $monthTotalCollected = $batchesInMonth->sum('collected_amount');
                        @endphp
                    </div>
                    <div class="flex items-center gap-6">
                        <div class="flex flex-col items-end">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Total Expected</span>
                            <span class="text-sm font-bold text-gray-900">₱{{ number_format($monthTotalExpected, 2) }}</span>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest mb-0.5">Total Paid</span>
                            <span class="text-sm font-bold text-emerald-700">₱{{ number_format($monthTotalCollected, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- COMPACT MINIMAL TABLE --}}
                <div class="relative min-h-[150px]">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($batchesInMonth as $batch)
                                <tr class="batch-row hover:bg-gray-50 transition-all duration-150" 
                                    data-title="{{ strtolower($batch->title) }}" 
                                    data-type="{{ strtolower($batch->type) }}">
                                    
                                    {{-- Due Date --}}
                                    <td class="px-6 py-4 text-sm text-gray-500 font-medium whitespace-nowrap">
                                        {{ $batch->due_date ? $batch->due_date->format('M d, Y') : '-' }}
                                    </td>

                                    {{-- Title --}}
                                    <td class="px-6 py-4 text-sm text-gray-900 font-bold">
                                        {{ $batch->title }}
                                    </td>

                                    {{-- Type --}}
                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium whitespace-nowrap">
                                        {{ str_replace('_', ' ', $batch->type) }}
                                    </td>

                                    {{-- Amount --}}
                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium tabular-nums whitespace-nowrap">
                                        ₱{{ number_format($batch->total_expected, 2) }}
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        @php
                                            $statusColor = $batch->status_color;
                                            $statusLabel = $batch->status_label;
                                            $statusClass = match($statusColor) {
                                                'blue' => 'bg-blue-50 text-blue-700 border-blue-100',
                                                'green', 'emerald' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                                'orange' => 'bg-orange-50 text-orange-700 border-orange-100',
                                                'red' => 'bg-red-50 text-red-700 border-red-100',
                                                default => 'bg-gray-50 text-gray-700 border-gray-100'
                                            };
                                            $dotClass = match($statusColor) {
                                                'blue' => 'bg-blue-500',
                                                'green', 'emerald' => 'bg-emerald-500',
                                                'orange' => 'bg-orange-500',
                                                'red' => 'bg-red-500',
                                                default => 'bg-gray-500'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold border capitalize tracking-wide {{ $statusClass }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span>
                                            {{ $statusLabel }}
                                        </span>
                                    </td>

                                    {{-- Action --}}
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.dues.show', $batch) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 transition shadow-sm">
                                                View Now <i class="bi bi-arrow-right"></i>
                                            </a>
                                            <div class="relative">
                                                <button onclick="toggleDropdown('batchAction-{{ $batch->id }}')" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 hover:text-gray-600 hover:bg-white transition-all">
                                                    <i class="bi bi-three-dots"></i>
                                                </button>
                                                <div id="batchAction-{{ $batch->id }}" class="hidden absolute right-0 top-full mt-2 w-44 bg-white rounded-xl shadow-2xl border border-gray-100 z-[100] py-2 transform origin-top-right">
                                                    <a href="{{ route('admin.dues.edit', $batch) }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-blue-50 hover:text-blue-600 flex items-center gap-2 transition-colors font-medium">
                                                        <i class="bi bi-pencil-square"></i> Edit Statement
                                                    </a>
                                                    <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-blue-50 hover:text-blue-600 flex items-center gap-2 transition-colors font-medium">
                                                        <i class="bi bi-bell"></i> Send Reminders
                                                    </a>
                                                    <div class="border-t border-gray-50 my-1 mx-2"></div>
                                                    <form id="delete-batch-{{ $batch->id }}" action="{{ route('admin.dues.destroy', $batch->id) }}" method="POST" class="hidden">
                                                        @csrf @method('DELETE')
                                                    </form>
                                                    <button type="button" 
                                                            onclick="if(confirm('Are you sure you want to delete this billing statement? This will also delete all resident dues and payments associated with it.')) document.getElementById('delete-batch-{{ $batch->id }}').submit();"
                                                            class="w-full text-left px-4 py-2 text-xs text-red-600 hover:bg-red-50 flex items-center gap-2 transition-colors font-medium">
                                                        <i class="bi bi-trash3"></i> Delete Batch
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                {{-- MINIMAL FOOTER --}}
                <div class="px-6 py-3 bg-gray-50/30 border-t border-gray-100">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $batchesInMonth->count() }} billing statements found</span>
                </div>
            </div>
        </div>
    @empty
        {{-- EMPTY STATE --}}
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-20 text-center">
            <div class="w-24 h-24 rounded-3xl bg-gray-50 flex items-center justify-center mx-auto mb-6 text-gray-200">
                <i class="bi bi-receipt-cutoff text-5xl"></i>
            </div>
            <h3 class="text-xl font-black text-gray-900 mb-2">No billing batches found</h3>
            <p class="text-gray-500 text-sm max-w-xs mx-auto mb-8 leading-relaxed">
                It looks like you haven't generated any billing statements yet.
            </p>
            <a href="{{ route('admin.dues.create') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-blue-600 text-white font-black rounded-2xl hover:bg-blue-700 shadow-xl shadow-blue-100 transition-all">
                <i class="bi bi-plus-lg"></i>
                <span>Create Your First Batch</span>
            </a>
        </div>
    @endforelse

    {{-- PAGINATION --}}
    @if($batches->hasPages())
        <div class="pt-6">
            {{ $batches->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
    // LIVE SEARCH LOGIC
    document.getElementById('liveSearch')?.addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        const monthSections = document.querySelectorAll('.month-section');

        monthSections.forEach(section => {
            let hasVisibleRows = false;
            const rows = section.querySelectorAll('.batch-row');
            
            rows.forEach(row => {
                const title = row.getAttribute('data-title');
                const type = row.getAttribute('data-type');
                
                if (title.includes(term) || type.includes(term)) {
                    row.style.display = '';
                    hasVisibleRows = true;
                } else {
                    row.style.display = 'none';
                }
            });

            // Hide the entire month section if no batches match
            section.style.display = hasVisibleRows ? '' : 'none';
        });
    });

    function toggleDropdown(id) {
        const dropdown = document.getElementById(id);
        const allDropdowns = document.querySelectorAll('[id^="batchAction-"]');
        
        allDropdowns.forEach(d => {
            if (d.id !== id) d.classList.add('hidden');
        });
        
        if (dropdown) {
            dropdown.classList.toggle('hidden');
        }
    }

    window.addEventListener('click', function(e) {
        if (!e.target.closest('.relative')) {
            document.querySelectorAll('[id^="batchAction-"]').forEach(d => d.classList.add('hidden'));
        }
    });
</script>
@endpush
@endsection
