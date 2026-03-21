<div class="bg-white p-8 md:p-12 shadow-sm rounded-2xl border border-gray-100 max-w-4xl mx-auto print:shadow-none print:border-none print:p-0">
    {{-- Header --}}
    <div class="text-center mb-10 border-b pb-8 border-gray-100">
        <h1 class="text-2xl font-black text-gray-900 uppercase tracking-widest">{{ config('app.name', 'Subdivision Association') }}</h1>
        <h2 class="text-lg font-bold text-gray-700 mt-1">Statement of Financial Position</h2>
        <p class="text-sm text-gray-500 mt-2 italic font-medium">As of {{ $results['as_of'] }}</p>
        <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-tighter">(Amounts in Philippine Peso)</p>
    </div>

    <div class="space-y-10">
        {{-- ASSETS --}}
        <div>
            <h3 class="text-sm font-black text-gray-900 border-b border-gray-900 pb-2 mb-4 flex justify-between uppercase tracking-widest">
                <span>ASSETS</span>
            </h3>
            
            {{-- Current Assets --}}
            <div class="mb-6">
                <h4 class="text-xs font-bold text-gray-700 mb-3 italic">Current Assets</h4>
                <div class="space-y-2 pl-4">
                    @foreach($results['assets']['current'] as $label => $amount)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ $label }}</span>
                        <span class="font-mono text-gray-900">{{ number_format($amount, 2) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Noncurrent Assets --}}
            <div class="mb-6">
                <h4 class="text-xs font-bold text-gray-700 mb-3 italic">Non-current Assets</h4>
                <div class="space-y-2 pl-4">
                    @foreach($results['assets']['noncurrent'] as $label => $amount)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ $label }}</span>
                        <span class="font-mono text-gray-900">{{ number_format($amount, 2) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-between items-center py-3 border-t-2 border-gray-900 font-black text-gray-900 uppercase tracking-widest text-sm bg-gray-50/50 px-4 rounded">
                <span>TOTAL ASSETS</span>
                <span class="font-mono border-b-4 border-double border-gray-900">₱ {{ number_format($results['total_assets'], 2) }}</span>
            </div>
        </div>

        {{-- LIABILITIES & EQUITY --}}
        <div>
            <h3 class="text-sm font-black text-gray-900 border-b border-gray-900 pb-2 mb-4 flex justify-between uppercase tracking-widest">
                <span>LIABILITIES AND EQUITY</span>
            </h3>

            {{-- Current Liabilities --}}
            <div class="mb-6">
                <h4 class="text-xs font-bold text-gray-700 mb-3 italic">Current Liabilities</h4>
                <div class="space-y-2 pl-4">
                    @foreach($results['liabilities']['current'] as $label => $amount)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ $label }}</span>
                        <span class="font-mono text-gray-900">{{ number_format($amount, 2) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Noncurrent Liabilities --}}
            <div class="mb-6">
                <h4 class="text-xs font-bold text-gray-700 mb-3 italic">Non-current Liabilities</h4>
                <div class="space-y-2 pl-4">
                    @foreach($results['liabilities']['noncurrent'] as $label => $amount)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ $label }}</span>
                        <span class="font-mono text-gray-900">{{ number_format($amount, 2) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-between items-center py-2 border-t border-gray-300 font-bold text-gray-900 text-sm mb-8 px-4">
                <span class="italic">Total Liabilities</span>
                <span class="font-mono underline">{{ number_format($results['total_liabilities'], 2) }}</span>
            </div>

            {{-- EQUITY --}}
            <div class="mb-6">
                <h4 class="text-xs font-bold text-gray-700 mb-3 italic uppercase tracking-widest">Equity</h4>
                <div class="space-y-2 pl-4">
                    @foreach($results['equity'] as $label => $amount)
                        @if($label !== 'total')
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ $label }}</span>
                            <span class="font-mono text-gray-900">{{ number_format($amount, 2) }}</span>
                        </div>
                        @endif
                    @endforeach
                </div>
                <div class="flex justify-between items-center py-2 mt-2 font-bold text-gray-900 text-sm px-4">
                    <span class="italic">Total Equity</span>
                    <span class="font-mono underline">{{ number_format($results['total_equity'], 2) }}</span>
                </div>
            </div>

            <div class="flex justify-between items-center py-3 border-t-2 border-gray-900 font-black text-gray-900 uppercase tracking-widest text-sm bg-gray-50/50 px-4 rounded">
                <span>TOTAL LIABILITIES AND EQUITY</span>
                <span class="font-mono border-b-4 border-double border-gray-900">₱ {{ number_format($results['total_liabilities'] + $results['total_equity'], 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="mt-16 pt-10 border-t border-gray-100 grid grid-cols-2 gap-20 text-center">
        <div class="space-y-12">
            <div class="border-b border-gray-900 w-full"></div>
            <p class="text-xs font-bold text-gray-600 uppercase tracking-widest">Prepared By</p>
        </div>
        <div class="space-y-12">
            <div class="border-b border-gray-900 w-full"></div>
            <p class="text-xs font-bold text-gray-600 uppercase tracking-widest">Certified Correct</p>
        </div>
    </div>
</div>

<style>
    @media print {
        body { background: white !important; }
        .bg-gray-50\/50 { background-color: #f9fafb !important; -webkit-print-color-adjust: exact; }
    }
</style>
