<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica', sans-serif; padding: 20px; color: #111827; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-sm { font-size: 12px; }
        .text-xs { font-size: 10px; }
        .text-lg { font-size: 16px; }
        .font-bold { font-weight: bold; }
        .font-black { font-weight: 900; }
        .uppercase { text-transform: uppercase; }
        .tracking-widest { letter-spacing: 0.1em; }
        .border-b { border-bottom: 1px solid #e5e7eb; }
        .border-b-900 { border-bottom: 2px solid #111827; }
        .border-t { border-top: 1px solid #e5e7eb; }
        .border-t-900 { border-top: 2px solid #111827; }
        .mb-4 { margin-bottom: 1rem; }
        .mb-10 { margin-bottom: 2.5rem; }
        .mt-1 { margin-top: 0.25rem; }
        .mt-2 { margin-top: 0.5rem; }
        .mt-4 { margin-top: 1rem; }
        .mt-10 { margin-top: 2.5rem; }
        .pb-2 { padding-bottom: 0.5rem; }
        .pb-8 { padding-bottom: 2rem; }
        .pt-10 { padding-top: 2.5rem; }
        .pl-4 { padding-left: 1rem; }
        .flex { display: flex; }
        .justify-between { justify-content: space-between; }
        .bg-gray-50 { background-color: #f9fafb; }
        .p-4 { padding: 1rem; }
        .w-full { width: 100%; }
        .italic { font-style: italic; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 4px 0; }
        .total-line { border-top: 2px solid #111827; font-weight: 900; }
        .double-underline { border-bottom: 4px double #111827; }
        .prepared-by { margin-top: 60px; }
        .prepared-by table { width: 100%; }
        .prepared-by td { width: 40%; text-align: center; }
        .prepared-by .spacer { width: 20%; }
        .signature-line { border-top: 1px solid #111827; margin-top: 40px; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="text-center mb-10 pb-8 border-b">
        <h1 class="text-lg font-black uppercase tracking-widest">{{ config('app.name', 'Subdivision Association') }}</h1>
        <h2 class="text-sm font-bold mt-1">Statement of Financial Position</h2>
        <p class="text-sm mt-2 italic">As of {{ $results['as_of'] }}</p>
        <p class="text-xs mt-1 uppercase tracking-widest">(Amounts in Philippine Peso)</p>
    </div>

    {{-- ASSETS --}}
    <h3 class="text-sm font-black border-b-900 pb-2 mb-4 uppercase tracking-widest">ASSETS</h3>
    
    <p class="text-xs font-bold italic mb-4">Current Assets</p>
    <table>
        @foreach($results['assets']['current'] as $label => $amount)
        <tr>
            <td class="text-sm pl-4">{{ $label }}</td>
            <td class="text-sm text-right font-bold">{{ number_format($amount, 2) }}</td>
        </tr>
        @endforeach
    </table>

    <p class="text-xs font-bold italic mt-4 mb-4">Non-current Assets</p>
    <table>
        @foreach($results['assets']['noncurrent'] as $label => $amount)
        <tr>
            <td class="text-sm pl-4">{{ $label }}</td>
            <td class="text-sm text-right font-bold">{{ number_format($amount, 2) }}</td>
        </tr>
        @endforeach
    </table>

    <table class="mt-4 bg-gray-50">
        <tr class="total-line">
            <td class="text-sm font-black uppercase tracking-widest p-4">TOTAL ASSETS</td>
            <td class="text-sm text-right font-black p-4 double-underline">₱ {{ number_format($results['total_assets'], 2) }}</td>
        </tr>
    </table>

    <div style="margin-top: 40px;"></div>

    {{-- LIABILITIES & EQUITY --}}
    <h3 class="text-sm font-black border-b-900 pb-2 mb-4 uppercase tracking-widest">LIABILITIES AND EQUITY</h3>

    <p class="text-xs font-bold italic mb-4">Current Liabilities</p>
    <table>
        @foreach($results['liabilities']['current'] as $label => $amount)
        <tr>
            <td class="text-sm pl-4">{{ $label }}</td>
            <td class="text-sm text-right font-bold">{{ number_format($amount, 2) }}</td>
        </tr>
        @endforeach
    </table>

    <p class="text-xs font-bold italic mt-4 mb-4">Non-current Liabilities</p>
    <table>
        @foreach($results['liabilities']['noncurrent'] as $label => $amount)
        <tr>
            <td class="text-sm pl-4">{{ $label }}</td>
            <td class="text-sm text-right font-bold">{{ number_format($amount, 2) }}</td>
        </tr>
        @endforeach
    </table>

    <table class="mt-4">
        <tr>
            <td class="text-sm italic pl-4">Total Liabilities</td>
            <td class="text-sm text-right font-bold" style="border-bottom: 1px solid #111827;">{{ number_format($results['total_liabilities'], 2) }}</td>
        </tr>
    </table>

    <p class="text-xs font-bold italic mt-4 mb-4 uppercase tracking-widest">Equity</p>
    <table>
        @foreach($results['equity'] as $label => $amount)
            @if($label !== 'total')
            <tr>
                <td class="text-sm pl-4">{{ $label }}</td>
                <td class="text-sm text-right font-bold">{{ number_format($amount, 2) }}</td>
            </tr>
            @endif
        @endforeach
        <tr>
            <td class="text-sm italic pl-4">Total Equity</td>
            <td class="text-sm text-right font-bold" style="border-bottom: 1px solid #111827;">{{ number_format($results['total_equity'], 2) }}</td>
        </tr>
    </table>

    <table class="mt-4 bg-gray-50">
        <tr class="total-line">
            <td class="text-sm font-black uppercase tracking-widest p-4">TOTAL LIABILITIES AND EQUITY</td>
            <td class="text-sm text-right font-black p-4 double-underline">₱ {{ number_format($results['total_liabilities'] + $results['total_equity'], 2) }}</td>
        </tr>
    </table>

    <div class="prepared-by">
        <table>
            <tr>
                <td>
                    <div class="signature-line">
                        <span class="text-xs font-bold uppercase tracking-widest">Prepared By</span>
                    </div>
                </td>
                <td class="spacer"></td>
                <td>
                    <div class="signature-line">
                        <span class="text-xs font-bold uppercase tracking-widest">Certified Correct</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
