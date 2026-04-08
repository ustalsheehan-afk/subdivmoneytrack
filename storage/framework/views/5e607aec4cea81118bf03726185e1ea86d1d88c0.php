
<?php $__env->startSection('title', 'Penalty Details'); ?>
<?php $__env->startSection('page-title', 'Penalty Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="<?php echo e(route('resident.penalties.index')); ?>" class="text-sm font-bold text-slate-400 hover:text-blue-500 transition-colors flex items-center gap-2 uppercase tracking-widest">
            <i class="bi bi-arrow-left"></i> Back to Penalties
        </a>
    </div>

    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden animate-fade-in">
        
        <div class="p-8 border-b border-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center shadow-sm">
                    <i class="bi bi-exclamation-octagon text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-900 tracking-tight leading-tight"><?php echo e($penalty->reason); ?></h2>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mt-1">Issued on <?php echo e($penalty->date_issued->format('M d, Y')); ?></p>
                </div>
            </div>
            <div class="text-right">
                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border
                    <?php if($penalty->status == 'paid'): ?> bg-emerald-50 text-emerald-600 border-emerald-100
                    <?php elseif($penalty->status == 'overdue'): ?> bg-red-50 text-red-600 border-red-100
                    <?php else: ?> bg-orange-50 text-orange-600 border-orange-100 <?php endif; ?> shadow-sm">
                    <?php echo e(strtoupper($penalty->status)); ?>

                </span>
            </div>
        </div>

        <div class="p-8 space-y-10">
            
            <div class="space-y-4">
                <h3 class="text-[11px] font-black text-blue-500 uppercase tracking-widest px-1">Penalty Breakdown</h3>
                <div class="bg-slate-50/50 rounded-2xl border border-slate-100 overflow-hidden shadow-inner">
                    <div class="p-5 space-y-3">
                        <div class="flex justify-between items-center text-sm font-medium text-slate-600">
                            <span>Base Penalty Amount</span>
                            <span class="font-bold text-slate-900">₱<?php echo e(number_format($penalty->amount, 2)); ?></span>
                        </div>
                        <div class="flex justify-between items-center text-sm font-medium text-slate-400">
                            <span>Administrative Charges</span>
                            <span class="font-bold">₱0.00</span>
                        </div>
                        <div class="pt-3 border-t border-slate-200 flex justify-between items-center">
                            <span class="text-sm font-black text-slate-900 uppercase tracking-widest">Total Amount Due</span>
                            <span class="text-xl font-black text-red-600">₱<?php echo e(number_format($penalty->amount, 2)); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="space-y-4">
                <h3 class="text-[11px] font-black text-blue-500 uppercase tracking-widest px-1">Why this penalty was issued</h3>
                <div class="p-6 bg-red-50/30 border border-red-100/50 rounded-2xl space-y-4">
                    <ul class="space-y-3">
                        <?php if($penalty->type == 'late_payment'): ?>
                        <li class="flex gap-3 text-sm font-medium text-slate-600">
                            <i class="bi bi-dot text-red-500 text-xl leading-none"></i>
                            <span>A payment for <strong>'<?php echo e($penalty->due->title ?? 'Association Fee'); ?>'</strong> was submitted after the due date.</span>
                        </li>
                        <li class="flex gap-3 text-sm font-medium text-slate-600">
                            <i class="bi bi-dot text-red-500 text-xl leading-none"></i>
                            <span>The system automatically calculates a daily late fee based on the number of days overdue.</span>
                        </li>
                        <?php else: ?>
                        <li class="flex gap-3 text-sm font-medium text-slate-600">
                            <i class="bi bi-dot text-red-500 text-xl leading-none"></i>
                            <span>Violation of subdivision rules and regulations regarding: <strong><?php echo e($penalty->reason); ?></strong>.</span>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            
            <div class="space-y-4">
                <h3 class="text-[11px] font-black text-blue-500 uppercase tracking-widest px-1">Deadline & Warning</h3>
                <div class="p-6 bg-slate-50 rounded-2xl flex items-center justify-between border border-slate-100">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 shadow-sm">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Settle before</p>
                            <p class="text-sm font-black text-slate-900"><?php echo e($penalty->due_date ? $penalty->due_date->format('F d, Y') : 'N/A'); ?></p>
                        </div>
                    </div>
                    <p class="text-[10px] font-bold text-red-500 italic max-w-[200px] text-right leading-relaxed">
                        If unpaid after the due date, additional charges or escalation may apply.
                    </p>
                </div>
            </div>

            
            <div class="space-y-4">
                <h3 class="text-[11px] font-black text-blue-500 uppercase tracking-widest px-1">What you can do</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php if($penalty->status != 'paid'): ?>
                    <a href="<?php echo e(route('resident.payments.pay', ['id' => $penalty->id, 'type' => 'penalty'])); ?>" class="flex flex-col p-5 bg-[#385780] hover:bg-[#2B3A4F] text-white rounded-2xl transition-all shadow-lg shadow-blue-900/10 group">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                                <i class="bi bi-credit-card"></i>
                            </div>
                            <i class="bi bi-arrow-right text-white/40 group-hover:translate-x-1 transition-transform"></i>
                        </div>
                        <span class="text-sm font-black uppercase tracking-widest">Pay Now</span>
                        <span class="text-[10px] font-medium text-white/60 mt-1">Settle this penalty immediately</span>
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('resident.contact')); ?>" class="flex flex-col p-5 bg-white border border-slate-100 hover:border-blue-200 hover:bg-blue-50/30 rounded-2xl transition-all shadow-sm group">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center">
                                <i class="bi bi-chat-left-text"></i>
                            </div>
                            <i class="bi bi-arrow-right text-slate-300 group-hover:translate-x-1 transition-transform group-hover:text-blue-500"></i>
                        </div>
                        <span class="text-sm font-black text-slate-900 uppercase tracking-widest">Contact Admin</span>
                        <span class="text-[10px] font-medium text-slate-400 mt-1">Dispute or ask questions</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .animate-fade-in {
        animation: fadeIn 0.4s ease-out forwards;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('resident.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\resident\penalties\show.blade.php ENDPATH**/ ?>