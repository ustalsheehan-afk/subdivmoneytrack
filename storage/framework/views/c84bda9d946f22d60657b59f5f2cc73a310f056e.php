<div class="payment-card bg-white rounded-[24px] p-6 border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative group overflow-hidden">
    
    <div class="absolute -right-12 -top-12 w-24 h-24 bg-gray-50 rounded-full blur-2xl group-hover:bg-emerald-50 transition-colors duration-500"></div>

    
    <div class="absolute top-6 right-6">
        <?php
            $statusColors = [
                'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                'pending' => 'bg-amber-50 text-amber-700 border-amber-100',
                'rejected' => 'bg-red-50 text-red-700 border-red-100'
            ];
            $statusDots = [
                'approved' => 'bg-emerald-500',
                'pending' => 'bg-amber-500',
                'rejected' => 'bg-red-500'
            ];
        ?>
        <span class="px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border <?php echo e($statusColors[$payment->status] ?? 'bg-gray-50 text-gray-600 border-gray-100'); ?>">
            <span class="w-1.5 h-1.5 rounded-full <?php echo e($statusDots[$payment->status] ?? 'bg-gray-400'); ?> mr-1"></span>
            <?php echo e($payment->status); ?>

        </span>
    </div>

    <div class="flex flex-col items-center text-center mt-4 relative z-10">
        <div class="relative mb-4">
            <img 
                src="<?php echo e($payment->resident?->photo ? asset('storage/' . $payment->resident->photo) : asset('CDlogo.jpg')); ?>"
                onerror="this.onerror=null; this.src='<?php echo e(asset('CDlogo.jpg')); ?>';"
                class="w-20 h-20 rounded-[24px] object-cover ring-4 ring-white shadow-md group-hover:ring-emerald-50 transition-all duration-300"
                alt="<?php echo e($payment->resident?->first_name ?? 'Resident'); ?>">
            <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-white flex items-center justify-center shadow-md">
                <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
            </div>
        </div>
        
        <h3 class="text-base font-black text-gray-900 group-hover:text-emerald-600 transition leading-tight mb-1 truncate w-full">
            <?php echo e($payment->resident?->full_name ?? 'Unknown Resident'); ?>

        </h3>
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">BLK <?php echo e($payment->resident?->block ?? '-'); ?> • LOT <?php echo e($payment->resident?->lot ?? '-'); ?></p>
        
        <p class="text-2xl font-black text-gray-900 mb-6 tracking-tight">₱<?php echo e(number_format($payment->amount, 2)); ?></p>

        
        <div class="w-full border-t border-gray-50 pt-5 flex flex-col gap-3">
            <div class="flex justify-between items-center w-full">
                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">METHOD</span>
                <span class="text-[10px] font-black text-gray-600 uppercase tracking-widest bg-gray-50 px-2.5 py-1 rounded-lg border border-gray-100 capitalize"><?php echo e(str_replace('_', ' ', $payment->payment_method)); ?></span>
            </div>
            <div class="flex justify-between items-center w-full">
                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">PAID DATE</span>
                <div class="text-right">
                    <span class="text-[10px] font-black text-gray-600 tracking-tight block"><?php echo e(\Carbon\Carbon::parse($payment->date_paid)->format('M d, Y')); ?></span>
                    <span class="text-[8px] font-bold text-gray-400 uppercase tracking-tight"><?php echo e(\Carbon\Carbon::parse($payment->date_paid)->format('g:i A')); ?></span>
                </div>
            </div>
        </div>

        <div class="w-full mt-6 flex items-center gap-2">
            <a href="<?php echo e(route('admin.payments.review', $payment->id)); ?>" 
               class="flex-1 flex items-center justify-center gap-2 py-3 rounded-xl bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-lg active:scale-95">
                <i class="bi bi-eye-fill"></i>
                Review
            </a>
            <?php if($payment->status === 'approved'): ?>
            <a href="<?php echo e(route('admin.payments.receipt', $payment->id)); ?>" target="_blank"
               class="w-12 h-11 flex items-center justify-center rounded-xl border border-gray-100 bg-white text-gray-400 hover:text-emerald-600 hover:border-emerald-100 transition-all shadow-sm active:scale-95" title="Print Receipt">
                <i class="bi bi-printer-fill"></i>
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\payments\partials\payment-card.blade.php ENDPATH**/ ?>