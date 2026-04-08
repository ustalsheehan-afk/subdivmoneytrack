
<?php $__env->startSection('title','My Penalties'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto px-4 py-8 space-y-8">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm animate-fade-in">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight mb-2">My Penalties</h2>
            <p class="text-sm font-medium text-slate-500 flex items-center gap-2">
                <i class="bi bi-exclamation-octagon text-red-500"></i>
                Review and settle any outstanding penalties on your account.
            </p>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">Reason</th>
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">Amount</th>
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">Date Issued</th>
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php $__empty_1 = true; $__currentLoopData = $penalties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $penalty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="group hover:bg-slate-50 transition-all cursor-pointer" onclick="window.location.href='<?php echo e(route('resident.penalties.show', $penalty->id)); ?>'">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-red-50 text-red-500 flex items-center justify-center">
                                    <i class="bi bi-exclamation-triangle"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900 leading-none mb-1"><?php echo e($penalty->reason); ?></p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider"><?php echo e($penalty->type); ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-sm font-black text-slate-900">₱<?php echo e(number_format($penalty->amount, 2)); ?></td>
                        <td class="px-8 py-6 text-[11px] font-bold text-slate-400 uppercase tracking-wider"><?php echo e($penalty->date_issued->format('M d, Y')); ?></td>
                        <td class="px-8 py-6 text-center">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border
                                <?php if($penalty->status == 'paid'): ?> bg-emerald-50 text-emerald-600 border-emerald-100
                                <?php elseif($penalty->status == 'overdue'): ?> bg-red-50 text-red-600 border-red-100
                                <?php else: ?> bg-orange-50 text-orange-600 border-orange-100 <?php endif; ?> shadow-sm">
                                <?php echo e($penalty->status); ?>

                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <i class="bi bi-chevron-right text-slate-300 group-hover:text-blue-500 transition-colors"></i>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="bi bi-check2-circle text-emerald-500 text-3xl"></i>
                            </div>
                            <p class="text-sm text-slate-500 font-bold uppercase tracking-widest">No penalties assigned.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
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

<?php echo $__env->make('resident.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\resident\penalties\index.blade.php ENDPATH**/ ?>