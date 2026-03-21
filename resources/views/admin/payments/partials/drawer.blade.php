<div class="h-full flex flex-col bg-white shadow-2xl">

    {{-- Header --}}
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-white sticky top-0 z-10">
        <div>
            <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Payment Details</h2>
            <p class="text-lg font-bold text-gray-900">
                {{ $payment->reference_no ?? '#' . str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}
            </p>
        </div>
        <div class="flex gap-2">
            @if($payment->status === 'approved')
                <a href="{{ route('admin.payments.receipt', $payment->id) }}" target="_blank"
                   class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-gray-100 transition text-gray-500" title="Download Receipt">
                    <i class="bi bi-printer"></i>
                </a>
            @endif
             <a href="{{ route('admin.payments.edit', $payment->id) }}" 
               class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-gray-100 transition text-gray-500" title="Edit">
                <i class="bi bi-pencil"></i>
            </a>
            <button onclick="closePaymentDrawer()" 
                    class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-gray-100 transition text-gray-500" title="Close">
                <i class="bi bi-x-lg text-lg"></i>
            </button>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto p-6 space-y-8 bg-gray-50 custom-scrollbar">

        {{-- Resident Profile --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex flex-col items-center text-center">
                <div class="relative p-1 rounded-full bg-gradient-to-tr from-[#800020]/30 to-transparent">
                    <img 
                        src="{{ $payment->resident->photo && Storage::disk('public')->exists($payment->resident->photo) ? Storage::disk('public')->url($payment->resident->photo) : asset('CDlogo.jpg') }}"
                        class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md"
                        alt="{{ $payment->resident->first_name ?? 'Resident' }}">
                </div>

                <h3 class="mt-3 text-xl font-bold text-gray-900 leading-tight">
                    {{ $payment->resident->first_name ?? 'Unknown' }} {{ $payment->resident->last_name ?? 'Resident' }}
                </h3>
                <p class="text-sm text-gray-500 mt-1">
                    Block {{ $payment->resident->block ?? '-' }} • Lot {{ $payment->resident->lot ?? '-' }}
                </p>
                
                <div class="flex gap-3 mt-4">
                    <a href="{{ route('admin.residents.show', $payment->resident->id) }}"
                       class="text-sm font-semibold text-[#800020] hover:underline flex items-center gap-1">
                        View Profile <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

      {{-- Main Amount Card --}}
<div class="bg-gray-50 rounded-2xl p-3 border border-gray-200 text-center">
                    <p class="text-xs text-green-600 font-medium uppercase tracking-wide mb-1">Amount Paid</p>
                    <p class="text-lg font-bold text-green-700 font-mono">₱{{ number_format($payment->amount, 2) }}</p>
                </div>
            </div>
        </div>

        {{-- DETAILS --}}
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Transaction Details</p>
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Date Paid</p>
                        <p class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($payment->date_paid)->format('M d, Y') }}</p>
                    </div>
                     <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Method</p>
                        <p class="text-sm font-bold text-gray-900 capitalize">{{ $payment->payment_method }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- PROOF OF PAYMENT --}}
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Proof of Payment</p>
            @if($payment->proof)
                <div class="rounded-xl overflow-hidden border border-gray-200 group relative shadow-sm">
                    <img src="{{ Storage::disk('public')->url($payment->proof) }}" 
                         class="w-full h-auto object-cover">

                    <a href="{{ Storage::disk('public')->url($payment->proof) }}" target="_blank"
                       class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity text-white font-bold text-sm">
                        <i class="bi bi-box-arrow-up-right mr-2 text-lg"></i> View Full Image
                    </a>
                </div>
            @else
                <div class="text-center py-8 text-gray-400 border border-dashed border-gray-300 rounded-xl bg-gray-50">
                    <i class="bi bi-image-alt text-2xl mb-2 block"></i>
                    <span class="text-xs">No proof uploaded</span>
                </div>
            @endif
        </div>

    </div>

    {{-- FOOTER ACTIONS --}}
    @if($payment->status === 'pending')
    <div class="p-6 border-t border-gray-100 bg-white z-10 grid grid-cols-2 gap-3">
        <form action="{{ route('admin.payments.reject', $payment->id) }}" method="POST">
            @csrf
            <button class="w-full bg-white border border-red-200 text-red-600 hover:bg-red-50 font-bold py-3 rounded-xl transition-colors shadow-sm">
                Reject
            </button>
        </form>
        <form action="{{ route('admin.payments.approve', $payment->id) }}" method="POST">
            @csrf
            <button class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl transition-colors shadow-lg hover:shadow-xl">
                Approve
            </button>
        </form>
    </div>
    @endif
</div>
