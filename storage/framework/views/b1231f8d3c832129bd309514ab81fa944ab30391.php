<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt Verified - Vistabella</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-4">

    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-100">
        
        <div class="bg-emerald-500 p-8 text-center relative">
            <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white"></path>
                </svg>
            </div>
            
            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg relative z-10">
                <i class="bi bi-patch-check-fill text-emerald-500 text-4xl"></i>
            </div>
            
            <h1 class="text-white text-2xl font-black uppercase tracking-tight relative z-10">
                Receipt Verified
            </h1>
            <p class="text-emerald-100 text-sm mt-1 font-medium relative z-10">
                Official Payment Record Found
            </p>
        </div>

        <div class="p-6 space-y-6">
            
            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Receipt No</span>
                    <span class="font-mono font-bold text-slate-900">#<?php echo e(str_pad($payment->id, 8, '0', STR_PAD_LEFT)); ?></span>
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-xs text-slate-500">Resident Name</span>
                        <span class="text-xs font-bold text-slate-900"><?php echo e($payment->resident?->full_name ?? 'Unknown'); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-slate-500">Property</span>
                        <span class="text-xs font-bold text-slate-900">Blk <?php echo e($payment->resident?->block ?? '-'); ?> Lot <?php echo e($payment->resident?->lot ?? '-'); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-slate-500">Payment For</span>
                        <span class="text-xs font-bold text-slate-900"><?php echo e($payment->due?->title ?? 'N/A'); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-slate-500">Amount Paid</span>
                        <span class="text-sm font-black text-emerald-600">₱<?php echo e(number_format($payment->amount, 2)); ?></span>
                    </div>
                </div>
            </div>

            
            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase">Date of Payment</p>
                        <p class="text-sm font-semibold text-slate-900"><?php echo e(\Carbon\Carbon::parse($payment->date_paid)->format('F j, Y • g:i A')); ?></p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                        <i class="bi bi-credit-card"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase">Payment Method</p>
                        <p class="text-sm font-semibold text-slate-900 uppercase"><?php echo e($payment->payment_method); ?></p>
                    </div>
                </div>

                <?php if($payment->reference_no): ?>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                        <i class="bi bi-hash"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold text-slate-400 uppercase">Reference Number</p>
                        <p class="text-sm font-mono font-semibold text-slate-900 break-all"><?php echo e($payment->reference_no); ?></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            
            <div class="pt-6 border-t border-slate-100 text-center">
                <div class="inline-flex items-center gap-2 text-emerald-600 bg-emerald-50 px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wider mb-4">
                    <i class="bi bi-shield-check"></i>
                    Authentic Record
                </div>
                <p class="text-[10px] text-slate-400 leading-relaxed uppercase tracking-widest">
                    Vistabella Subdivision Management System<br>
                    Verified at <?php echo e(now()->format('g:i A, M d, Y')); ?>

                </p>
            </div>
        </div>
    </div>

</body>
</html><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\payments\verify-success.blade.php ENDPATH**/ ?>