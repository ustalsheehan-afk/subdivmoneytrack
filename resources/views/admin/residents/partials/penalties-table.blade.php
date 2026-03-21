{{-- PENALTIES TABLE --}}
<div class="overflow-x-auto">
    <table class="w-full text-sm border-collapse">
        <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider text-xs font-semibold">
            <tr>
                <th class="p-4 text-left">Date</th>
                <th class="p-4 text-left">Amount</th>
                <th class="p-4 text-left">Reason</th>
                <th class="p-4 text-left">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @if($resident->penalties->count() > 0)
                @foreach($resident->penalties as $penalty)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="p-4 text-sm text-gray-700 font-medium">
                        @if($penalty->date_issued)
                            {{ $penalty->date_issued->format('M d, Y') }}
                        @else
                            <span class="text-gray-400 italic">N/A</span>
                        @endif
                    </td>
                    <td class="p-4 text-sm text-gray-700 font-medium">
                        ₱{{ number_format($penalty->amount, 2) }}
                    </td>
                    <td class="p-4 text-sm text-gray-700 font-medium">
                        {{ $penalty->reason }}
                    </td>
                    <td class="p-4">
                        @php
                            $statusConfig = match(strtolower($penalty->status)) {
                                'paid' => ['class' => 'bg-emerald-50 text-emerald-700 border-emerald-100', 'dot' => 'bg-emerald-500'],
                                default => ['class' => 'bg-red-50 text-red-700 border-red-100', 'dot' => 'bg-red-500']
                            };
                        @endphp
                        <span class="inline-flex items-center justify-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border capitalize tracking-wide w-24 {{ $statusConfig['class'] }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $statusConfig['dot'] }}"></span>
                            {{ ucwords(str_replace('_', ' ', $penalty->status)) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" class="p-6 text-center text-gray-500">
                        No penalties found
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
