<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Failed - Vistabella</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-4">

    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-100">
        {{-- Failed Header --}}
        <div class="bg-red-500 p-8 text-center relative">
            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg relative z-10">
                <i class="bi bi-shield-x text-red-500 text-4xl"></i>
            </div>
            
            <h1 class="text-white text-2xl font-black uppercase tracking-tight relative z-10">
                Invalid Receipt
            </h1>
            <p class="text-red-100 text-sm mt-1 font-medium relative z-10">
                No record found or payment not approved
            </p>
        </div>

        <div class="p-8 text-center space-y-6">
            <div class="bg-red-50 border border-red-100 rounded-2xl p-6">
                <p class="text-red-700 text-sm font-medium leading-relaxed">
                    The receipt you are trying to verify could not be found in our official records. This could be because the receipt is invalid, has been tampered with, or the payment is still pending approval.
                </p>
            </div>

            <div class="space-y-4">
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">What should I do?</p>
                <div class="text-left space-y-3">
                    <div class="flex items-center gap-3 text-slate-600">
                        <i class="bi bi-1-circle-fill text-slate-300"></i>
                        <span class="text-xs">Check if you scanned the correct QR code.</span>
                    </div>
                    <div class="flex items-center gap-3 text-slate-600">
                        <i class="bi bi-2-circle-fill text-slate-300"></i>
                        <span class="text-xs">Ensure the payment has been approved by admin.</span>
                    </div>
                    <div class="flex items-center gap-3 text-slate-600">
                        <i class="bi bi-3-circle-fill text-slate-300"></i>
                        <span class="text-xs">Contact subdivision office for assistance.</span>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100">
                <p class="text-[10px] text-slate-400 uppercase tracking-widest mb-2">Vistabella Subdivision Management System</p>
                <p class="text-[10px] text-slate-300 font-mono">ERR_CODE: RCV_NOT_FOUND_OR_UNAPPROVED</p>
            </div>
        </div>
    </div>

</body>
</html>