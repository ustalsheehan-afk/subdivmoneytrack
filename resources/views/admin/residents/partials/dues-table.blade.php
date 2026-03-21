{{-- DUES TABLE --}}
<div class="overflow-x-auto">
    <table class="w-full text-sm border-collapse">
        <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider text-xs font-semibold">
            <tr>
                <th class="p-4 text-left">Date</th>
                <th class="p-4 text-left">Description</th>
                <th class="p-4 text-left">Type</th>
                <th class="p-4 text-left">Amount</th>
                <th class="p-4 text-left">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @if($resident->dues->count() > 0)
                @foreach($resident->dues as $due)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="p-4 text-sm text-gray-700 font-medium">
                        {{ \Carbon\Carbon::parse($due->due_date)->format('M d, Y') }}
                    </td>
                    <td class="p-4 text-sm text-gray-700 font-medium">
                        {{ $due->title }}
                    </td>
                    <td class="p-4 text-sm text-gray-700 font-medium">
                        @php
                            $typeLabel = match($due->type) {
                                'monthly_hoa' => 'Monthly HOA',
                                'regular_fees' => 'Regular Fees', 
                                'special_assessments' => 'Special Assessment',
                                default => ucwords(str_replace('_', ' ', $due->type ?? '-'))
                            };
                        @endphp
                        {{ $typeLabel }}
                    </td>
                    <td class="p-4 text-sm text-gray-700 font-medium">
                        ₱{{ number_format($due->amount, 2) }}
                    </td>
                    <td class="p-4">
                        @php
                            $statusClass = match(strtolower($due->status)) {
                                'paid', 'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                'pending' => 'bg-orange-50 text-orange-700 border-orange-100',
                                'unpaid', 'overdue' => 'bg-red-50 text-red-700 border-red-100',
                                default => 'bg-gray-50 text-gray-700 border-gray-100'
                            };
                            $dotClass = match(strtolower($due->status)) {
                                'paid', 'approved' => 'bg-emerald-500',
                                'pending' => 'bg-orange-500',
                                'unpaid', 'overdue' => 'bg-red-500',
                                default => 'bg-gray-500'
                            };
                        @endphp
                        <span class="inline-flex items-center justify-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border capitalize tracking-wide w-24 {{ $statusClass }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span>
                            {{ ucwords(str_replace('_', ' ', $due->status)) }}
                        </span>
                    </td>
                </tr>

                @endforeach
            @else
                <tr>
                    <td colspan="5" class="p-8 text-center text-gray-400">
                        <i class="bi bi-inbox text-2xl mb-2 block"></i>
                        No dues found
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

