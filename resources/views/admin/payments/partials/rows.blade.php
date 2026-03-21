@foreach($payments as $payment)
<tr onclick="selectPayment({{ $payment->id }})" 
    data-id="{{ $payment->id }}"
    class="payment-row cursor-pointer hover:bg-gray-50 transition-all duration-200 group border-l-4 border-transparent">
    
    {{-- Checkbox --}}
    <td onclick="event.stopPropagation()" class="p-4 text-center bulk-checkbox hidden">
        <input type="checkbox" name="selected_payments[]" value="{{ $payment->id }}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 payment-checkbox">
    </td>

    {{-- Resident --}}
    <td class="p-4">
        <div class="flex items-center gap-3">
            <img 
                src="{{ $payment->resident->photo && Storage::disk('public')->exists($payment->resident->photo) ? Storage::disk('public')->url($payment->resident->photo) : asset('CDlogo.jpg') }}"
                class="w-10 h-10 rounded-full object-cover ring-2 ring-gray-100 group-hover:ring-blue-200 transition-all duration-300"
                alt="{{ $payment->resident->first_name ?? 'Resident' }}">
            <div>
                <p class="font-bold text-gray-900 group-hover:text-blue-700 transition">{{ $payment->resident->first_name ?? 'Unknown' }} {{ $payment->resident->last_name ?? 'Resident' }}</p>
                <p class="text-xs text-gray-500">Blk {{ $payment->resident->block ?? '-' }} - Lot {{ $payment->resident->lot ?? '-' }}</p>
            </div>
        </div>
    </td>

    {{-- Reference No --}}
    <td class="p-4 text-sm text-gray-600 font-medium align-middle">#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>

    {{-- Amount --}}
    <td class="p-4 text-right text-sm text-gray-600 font-medium align-middle">
        ₱{{ number_format($payment->amount, 2) }}
    </td>

    {{-- Method --}}
    <td class="p-4 text-center text-sm text-gray-600 font-medium capitalize align-middle">
        {{ $payment->payment_method }}
    </td>

    {{-- Paid Date --}}
    <td class="px-6 py-4">
        <div class="flex flex-col">
            <span class="text-xs font-bold text-gray-900">{{ \Carbon\Carbon::parse($payment->date_paid)->format('M d, Y') }}</span>
            <span class="text-[10px] text-gray-500 font-medium mt-0.5">{{ \Carbon\Carbon::parse($payment->date_paid)->format('g:i A') }}</span>
        </div>
    </td>

    {{-- Status --}}
    <td class="p-4 text-center align-middle">
        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold border capitalize tracking-wide
            {{ $payment->status === 'approved' 
                ? 'bg-emerald-50 text-emerald-700 border-emerald-100' 
                : ($payment->status === 'rejected' ? 'bg-red-50 text-red-700 border-red-100' : 'bg-yellow-50 text-yellow-700 border-yellow-100') }}">
            <span class="w-1.5 h-1.5 rounded-full 
                {{ $payment->status === 'approved' ? 'bg-emerald-500' : ($payment->status === 'rejected' ? 'bg-red-500' : 'bg-yellow-500') }}"></span>
            {{ $payment->status }}
        </span>
    </td>
</tr>
@endforeach