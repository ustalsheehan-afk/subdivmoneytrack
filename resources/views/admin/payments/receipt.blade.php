<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Receipt #{{ str_pad($payment->id, 8, '0', STR_PAD_LEFT) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }

        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0; margin: 0; }
            .receipt-container { box-shadow: none !important; border: none !important; max-width: 100% !important; }
            .watermark { opacity: 0.03 !important; }
        }

        .receipt-gradient {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        }
    </style>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen p-4 sm:p-8">

    @php
        $receiptNo = str_pad($payment->id, 8, '0', STR_PAD_LEFT);
        
        // Use the current request's host if APP_URL is not scannable
        // This helps if testing via local IP
        $verificationURL = route('payments.verify', $payment->id);
        
        // QR Code API with higher error correction and size
        $qr = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=".urlencode($verificationURL)."&ecc=M&margin=1";
    @endphp

    <div class="receipt-container relative max-w-2xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-200">
        
        {{-- Watermark --}}
        <div class="watermark absolute inset-0 flex items-center justify-center pointer-events-none select-none opacity-[0.03] overflow-hidden">
            <span class="text-[150px] font-black tracking-widest text-slate-900 rotate-[-25deg] whitespace-nowrap">
                VISTABELLA
            </span>
        </div>

        {{-- Header --}}
        <div class="receipt-gradient px-8 py-10 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
            
            <div class="flex flex-col sm:flex-row justify-between items-center gap-6 relative z-10">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-white flex items-center justify-center shadow-xl">
                        <span class="text-slate-900 text-3xl font-black">V</span>
                    </div>
                    <div class="text-center sm:text-left">
                        <h2 class="text-xs font-bold tracking-[0.3em] uppercase text-slate-400 mb-1">Vistabella Subdivision</h2>
                        <h1 class="text-2xl font-black uppercase tracking-tight">Official Receipt</h1>
                    </div>
                </div>
                
                <div class="text-center sm:text-right">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">Receipt Number</p>
                    <p class="text-xl font-mono font-bold">#{{ $receiptNo }}</p>
                </div>
            </div>
        </div>

        <div class="p-8 sm:p-10 relative z-10 space-y-8">
            
            {{-- Top Info Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Payer Details</p>
                        <p class="text-lg font-extrabold text-slate-900 leading-tight">{{ $payment->resident->full_name }}</p>
                        <p class="text-sm text-slate-500 font-medium">Block {{ $payment->resident->block }} • Lot {{ $payment->resident->lot }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Payment Method</p>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-1 bg-slate-100 rounded text-[10px] font-bold text-slate-700 uppercase tracking-wider border border-slate-200">
                                {{ $payment->payment_method }}
                            </span>
                            @if($payment->reference_no)
                                <span class="text-[10px] font-mono text-slate-400">Ref: {{ $payment->reference_no }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="sm:text-right space-y-4">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Transaction Date</p>
                        <p class="text-sm font-bold text-slate-900">{{ \Carbon\Carbon::parse($payment->date_paid)->format('F j, Y') }}</p>
                        <p class="text-xs text-slate-500 font-medium">{{ \Carbon\Carbon::parse($payment->date_paid)->format('g:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Status</p>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-[10px] font-black uppercase tracking-widest border border-emerald-100">
                            <i class="bi bi-patch-check-fill text-xs"></i>
                            Verified & Approved
                        </span>
                    </div>
                </div>
            </div>

            {{-- Description Table --}}
            <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            <th class="px-6 py-4 text-left">Description</th>
                            <th class="px-6 py-4 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr class="text-slate-700 font-medium">
                            <td class="px-6 py-5">
                                <p class="text-slate-900 font-bold">{{ $payment->due->title }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">Subdivision Maintenance & Services</p>
                            </td>
                            <td class="px-6 py-5 text-right font-bold text-slate-900">₱{{ number_format($payment->amount, 2) }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-50/50">
                            <td class="px-6 py-5 text-right text-slate-500 font-bold uppercase tracking-widest text-[10px]">Total Amount Paid</td>
                            <td class="px-6 py-5 text-right text-2xl font-black text-emerald-600">₱{{ number_format($payment->amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- QR & Verification --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-8 pt-4">
                <div class="flex flex-col items-center sm:items-start text-center sm:text-left gap-3">
                    <div class="border-t-2 border-slate-900 pt-3 w-48">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Authorized Signature</p>
                        <p class="text-xs font-black text-slate-900 uppercase tracking-tight">Subdivision Administrator</p>
                    </div>
                    <p class="text-[9px] text-slate-400 uppercase tracking-widest italic mt-2">
                        This is a system-generated receipt.<br>No physical signature required.
                    </p>
                </div>

                <div class="flex flex-col items-center gap-2">
                    <div class="p-3 bg-white border border-slate-200 rounded-2xl shadow-sm">
                        <img src="{{ $qr }}" alt="Verification QR" class="w-24 h-24 sm:w-28 sm:h-28">
                    </div>
                    <div class="text-center">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Scan to Verify</p>
                        <p class="text-[8px] text-slate-300 font-mono mt-1 break-all max-w-[150px] leading-tight">
                            {{ str_replace(['http://', 'https://'], '', $verificationURL) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="bg-slate-50 px-8 py-6 border-t border-slate-200 flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-[0.2em]">Vistabella Subdivision Management System</p>
            <p class="text-[9px] text-slate-400 font-medium">Generated: {{ now()->format('M d, Y • h:i A') }}</p>
        </div>

        {{-- Buttons --}}
        <div class="no-print p-8 bg-white border-t border-slate-100 flex flex-wrap justify-center gap-4 relative z-20">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-8 py-3 bg-slate-900 text-white rounded-xl text-sm font-bold hover:bg-slate-800 transition-all shadow-lg hover:shadow-xl active:scale-95">
                <i class="bi bi-printer-fill"></i>
                Print Receipt
            </button>
            <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-50 transition-all active:scale-95">
                <i class="bi bi-arrow-left"></i>
                Back to Dashboard
            </a>
        </div>
    </div>

</body>
</html>