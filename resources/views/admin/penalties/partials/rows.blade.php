@if($penalties->count() > 0)
    @php $previousDate = null; @endphp
    @foreach($penalties as $penalty)
    @php
        $currentDate = $penalty->date_issued ? $penalty->date_issued->format('F d, Y') : 'No Date';
    @endphp

    <tr onclick="loadPenaltyDetails({{ $penalty->id }})"
        class="hover:bg-gray-50 cursor-pointer transition group border-b border-gray-100 last:border-0">
        
        {{-- Checkbox --}}
        <td class="px-6 py-4 text-center bulk-checkbox hidden" onclick="event.stopPropagation()">
            <input type="checkbox" name="selected_penalties[]" value="{{ $penalty->id }}" class="penalty-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
        </td>

        {{-- Resident --}}
        <td class="px-6 py-4">
            <div class="flex items-center gap-3">
                @if($penalty->resident)
                    <img 
                        src="{{ $penalty->resident->photo ? asset('storage/' . $penalty->resident->photo) : asset('CDlogo.jpg') }}"
                        onerror="this.onerror=null; this.src='{{ asset('CDlogo.jpg') }}';"
                        class="h-10 w-10 rounded-full object-cover ring-2 ring-gray-100 group-hover:ring-indigo-200 transition-all duration-300"
                        alt="{{ $penalty->resident->full_name ?? 'Resident' }}">
                    <div>
                        <p class="font-bold text-gray-900 group-hover:text-blue-700 transition">{{ $penalty->resident->full_name ?? 'Unknown' }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">B{{ $penalty->resident->block ?? '-' }} L{{ $penalty->resident->lot ?? '-' }}</p>
                    </div>
                @else
                    <img 
                        src="{{ asset('CDlogo.jpg') }}"
                        class="h-10 w-10 rounded-full object-cover ring-2 ring-gray-100 group-hover:ring-indigo-200 transition-all duration-300"
                        alt="Resident">
                    <div>
                        <p class="font-bold text-gray-900 group-hover:text-blue-700 transition">Unknown</p>
                        <p class="text-xs text-gray-500 mt-0.5">B- L-</p>
                    </div>
                @endif
            </div>
        </td>

        {{-- Type --}}
        <td class="px-6 py-4">
            <span class="text-sm font-medium text-gray-700">
                {{ ucfirst(strtolower(str_replace('_', ' ', $penalty->type ?? 'General'))) }}
            </span>
        </td>

        {{-- Reason --}}
        <td class="px-6 py-4 max-w-xs">
            <span class="text-gray-600 text-sm block truncate" title="{{ $penalty->reason }}">
                {{ $penalty->reason ?? '-' }}
            </span>
        </td>

        {{-- Date Issued --}}
        <td class="px-6 py-6 whitespace-nowrap">
            <div class="flex flex-col">
                <span class="text-gray-900 font-medium text-sm">{{ $penalty->date_issued ? $penalty->date_issued->format('M d, Y') : '-' }}</span>
                <span class="text-xs text-gray-400">{{ $penalty->date_issued ? $penalty->date_issued->diffForHumans() : '' }}</span>
            </div>
        </td>

        {{-- Amount --}}
        <td class="px-6 py-6 text-right">
            <span class="text-sm font-bold text-gray-900">₱{{ number_format($penalty->amount, 2) }}</span>
        </td>

        {{-- Status --}}
        <td class="px-6 py-6 text-center">
            @php
                $statusConfig = [
                    'paid' => [
                        'pill' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                        'dot' => 'bg-emerald-500'
                    ],
                    'unpaid' => [
                        'pill' => 'bg-red-50 text-red-700 border-red-100',
                        'dot' => 'bg-red-500'
                    ],
                    'pending' => [
                        'pill' => 'bg-orange-50 text-orange-700 border-orange-100',
                        'dot' => 'bg-orange-500'
                    ]
                ];
                $config = $statusConfig[$penalty->status] ?? [
                    'pill' => 'bg-gray-50 text-gray-700 border-gray-100',
                    'dot' => 'bg-gray-500'
                ];
            @endphp
            <span class="inline-flex items-center justify-center w-24 px-3 py-1 rounded-full text-xs font-bold border capitalize tracking-wide {{ $config['pill'] }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $config['dot'] }} mr-1.5"></span>
                {{ $penalty->status }}
            </span>
        </td>

        {{-- Action Arrow --}}
        <td class="px-6 py-6 text-right">
            <i class="bi bi-chevron-right text-gray-300 group-hover:text-blue-500 transition-colors"></i>
        </td>
    </tr>
    @endforeach
@elseif($penalties->currentPage() == 1)
    <tr>
        <td colspan="8" class="px-6 py-12 text-center text-gray-500 bg-gray-50/50">
            <div class="flex flex-col items-center justify-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="bi bi-clipboard-x text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-sm font-bold text-gray-900 mb-1">No penalties found</h3>
                <p class="text-xs text-gray-500 mb-4">Try adjusting your filters or search terms</p>
            </div>
        </td>
    </tr>
@endif
