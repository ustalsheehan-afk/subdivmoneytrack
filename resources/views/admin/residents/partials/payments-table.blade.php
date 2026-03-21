{{-- PAYMENTS TABLE --}}
<div class="overflow-x-auto">
    <table class="w-full text-sm border-collapse">
        <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider text-xs font-semibold">
            <tr>
                <th class="p-4 text-left">Date</th>
                <th class="p-4 text-left">Amount</th>
                <th class="p-4 text-left">Method</th>
                <th class="p-4 text-left">Reference</th>
                <th class="p-4 text-left">For</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @if($resident->payments->count() > 0)
                @foreach($resident->payments as $payment)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="p-4 text-sm text-gray-700 font-medium">
                        {{ \Carbon\Carbon::parse($payment->date_paid)->format('M d, Y') }}
                    </td>
                    <td class="p-4 text-sm text-gray-700 font-medium">
                        ₱{{ number_format($payment->amount, 2) }}
                    </td>
                    <td class="p-4 text-sm text-gray-700 font-medium">
                        {{ ucfirst($payment->payment_method) }}
                    </td>
                    <td class="p-4 text-sm text-gray-700 font-medium font-mono">
                        #{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="p-4 text-sm text-gray-700 font-medium">
                        {{ $payment->due->title ?? '—' }}
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="p-6 text-center text-gray-500">
                        No payment records found
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
