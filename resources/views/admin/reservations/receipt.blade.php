<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Receipt #{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        
        :root {
            --brand-green: #0D1F1C;
            --brand-accent: #B6FF5C;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
            -webkit-print-color-adjust: exact;
        }

        /* A4 Page Wrapper for PDF/Print centering */
        .a4-wrapper {
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
            position: relative;
        }

        /* Half A4 Receipt Design */
        .receipt-half-a4 {
            width: 148mm;
            min-height: 210mm;
            padding: 15mm;
            background: white;
            position: relative;
            display: flex;
            flex-direction: column;
            border: 1px solid #f0f0f0;
            box-sizing: border-box;
        }

        @media screen {
            .receipt-half-a4 {
                border-radius: 12px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            }
        }

        @media print {
            body {
                background: white;
            }
            .a4-wrapper {
                margin: 0;
                box-shadow: none;
                width: 210mm;
                height: 297mm;
            }
            .receipt-half-a4 {
                border: none;
                box-shadow: none;
                border-radius: 0;
            }
            .no-print {
                display: none !important;
            }
            @page {
                size: A4;
                margin: 0;
            }
        }

        .custom-dashed {
            background-image: linear-gradient(to right, #e5e7eb 50%, transparent 50%);
            background-position: bottom;
            background-size: 10px 1px;
            background-repeat: repeat-x;
        }
    </style>
</head>
<body class="bg-gray-100">

    @php
        $downloadRoute = request()->routeIs('resident.*')
            ? route('resident.amenities.reservation.download.receipt', $reservation->id)
            : route('admin.amenity-reservations.download.receipt', $reservation->id);
    @endphp

    <div class="no-print fixed top-6 right-6 z-50 flex gap-3">
        <button onclick="window.print()" class="flex items-center gap-2 px-6 py-3 bg-[#0D1F1C] text-white rounded-xl font-bold text-sm shadow-xl hover:bg-emerald-900 transition-all active:scale-95">
            <i class="bi bi-printer"></i>
            Print Receipt
        </button>
        <a href="{{ $downloadRoute }}" class="flex items-center gap-2 px-6 py-3 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-xl font-bold text-sm shadow-xl hover:bg-emerald-600 hover:text-white transition-all active:scale-95" target="_blank">
            <i class="bi bi-download"></i>
            Download PDF
        </a>
        <a href="{{ url()->previous() }}" class="flex items-center gap-2 px-6 py-3 bg-white text-gray-600 border border-gray-200 rounded-xl font-bold text-sm shadow-xl hover:bg-gray-50 transition-all active:scale-95">
            <i class="bi bi-arrow-left"></i>
            Back
        </a>
    </div>

    <div class="a4-wrapper">
        <div class="receipt-half-a4">
            {{-- HEADER SECTION --}}
            <div class="flex justify-between items-start mb-10">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-[#0D1F1C] rounded-xl flex items-center justify-center shadow-lg">
                        <span class="text-[#B6FF5C] text-2xl font-black italic">V</span>
                    </div>
                    <div>
                        <h1 class="text-lg font-black text-[#0D1F1C] tracking-tight leading-none uppercase">Vistabella</h1>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.2em] mt-1">Subdivision Management</p>
                    </div>
                </div>
                <div class="text-right">
                    <h2 class="text-2xl font-black text-[#0D1F1C] uppercase tracking-tighter italic">Reservation Receipt</h2>
                    <div class="inline-block px-3 py-1 bg-emerald-50 border border-emerald-100 rounded-lg mt-2">
                        <p class="text-[10px] font-black text-emerald-700 uppercase tracking-widest">Receipt No. #{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>
            </div>

            {{-- INFO GRID --}}
            <div class="grid grid-cols-2 gap-8 mb-10 pb-8 border-b border-gray-100">
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Date & Time Issued</p>
                        <p class="text-sm font-bold text-gray-800">{{ \Carbon\Carbon::parse($verified_at)->format('M d, Y • h:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Processed By</p>
                        <p class="text-sm font-bold text-gray-800">System Administrator</p>
                    </div>
                </div>
                <div class="space-y-4 text-right">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">{{ $reservation->customer_type === 'non_resident' ? 'Guest Booker' : 'Resident Booker' }}</p>
                        <p class="text-sm font-black text-gray-900 uppercase">{{ $reservation->customer_name }}</p>
                        <p class="text-[11px] font-bold text-emerald-600 mt-0.5">
                            {{ $reservation->customer_type === 'non_resident' ? ($reservation->guest_contact ?? 'Non-Resident Booking') : 'Block ' . (optional($reservation->resident)->block ?? '?') . ' / Lot ' . (optional($reservation->resident)->lot ?? '?') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- AMENITY DETAILS --}}
            <div class="flex-grow">
                <div class="bg-gray-50 rounded-2xl p-6 mb-8 border border-gray-100/50">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Reservation Information</p>
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-base font-black text-gray-900 uppercase">{{ optional($reservation->amenity)->name ?? 'N/A' }}</h3>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">{{ \Carbon\Carbon::parse($reservation->date)->format('F j, Y') }} • {{ $reservation->time_slot }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xl font-black text-gray-900 italic">₱{{ number_format($reservation->total_price, 2) }}</p>
                        </div>
                    </div>
                </div>

                {{-- BOOKING DETAILS --}}
                <div class="space-y-3 px-2 mb-8">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 font-medium">Guests</span>
                        <span class="font-black text-gray-900 uppercase tracking-widest text-xs px-3 py-1 bg-white border border-gray-200 rounded-lg">{{ $reservation->guest_count }} People</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 font-medium">Payment Method</span>
                        <span class="font-black text-gray-900 uppercase tracking-widest text-xs px-3 py-1 bg-white border border-gray-200 rounded-lg">{{ $reservation->payment_method === 'cash' ? 'On-site' : ucfirst($reservation->payment_method) }}</span>
                    </div>
                    @if($reservation->payment_reference_no)
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 font-medium">Reference No.</span>
                        <span class="font-bold text-gray-800">{{ $reservation->payment_reference_no }}</span>
                    </div>
                    @endif
                </div>

                {{-- CHARGES BREAKDOWN --}}
                <div class="bg-gray-50 rounded-xl p-4 mb-8">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Charges Breakdown</p>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Amenity Rental</span>
                            <span class="font-bold text-gray-800">₱{{ number_format(optional($reservation->amenity)->price ?? 0, 2) }}/hr</span>
                        </div>
                        @if(!empty($reservation->equipment_addons))
                            @foreach($reservation->equipment_addons as $addon)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">{{ $addon['name'] ?? 'Equipment' }}</span>
                                    <span class="font-bold text-gray-800">₱{{ number_format($addon['price'] ?? 0, 2) }}</span>
                                </div>
                            @endforeach
                        @endif
                        <div class="pt-3 mt-3 border-t border-gray-200 flex justify-between">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total</span>
                            <span class="text-lg font-black text-[#0D1F1C] italic">₱{{ number_format($reservation->total_price, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- PAYMENT STATUS --}}
                <div class="bg-[#0D1F1C] rounded-xl p-4 text-white mb-8">
                    <p class="text-[10px] font-bold text-[#B6FF5C] uppercase tracking-widest mb-2">✓ Payment Status</p>
                    <p class="text-sm font-bold">Payment Verified & Received</p>
                    <p class="text-[10px] text-gray-300 mt-1">Verified by: {{ $verified_by }}</p>
                </div>
            </div>

            {{-- FOOTER SECTION --}}
            <div class="mt-8 pt-8 border-t border-gray-100">
                <div class="max-w-full">
                    <p class="text-[10px] font-black text-[#0D1F1C] uppercase tracking-widest leading-relaxed">
                        Thank you for booking with us! This receipt serves as your official reservation confirmation and payment receipt.
                    </p>
                    <p class="text-[9px] text-gray-400 font-bold uppercase mt-3 tracking-wider">
                        System Generated • Valid without signature
                    </p>
                </div>
                
                <div class="mt-6 flex justify-center">
                    <div class="px-6 py-2 bg-gray-50 rounded-full border border-gray-100">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.4em]">Vistabella Subdivision System</p>
                    </div>
                </div>

                <p class="text-[8px] font-bold text-gray-300 uppercase tracking-[0.2em] text-center mt-4">
                    Generated: {{ now()->format('F j, Y \a\t g:i A') }}
                </p>
            </div>
        </div>
    </div>

</body>
</html>
