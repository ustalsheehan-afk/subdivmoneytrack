<?php if($penalties->count() > 0): ?>
    <?php $previousDate = null; ?>
    <?php $__currentLoopData = $penalties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $penalty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $currentDate = $penalty->date_issued ? $penalty->date_issued->format('F d, Y') : 'No Date';
    ?>

    <tr onclick="loadPenaltyDetails(<?php echo e($penalty->id); ?>)"
        class="hover:bg-emerald-50/20 cursor-pointer transition-all duration-200 group border-b border-gray-50 last:border-0">
        
        
        <td class="p-6 text-center bulk-checkbox hidden" onclick="event.stopPropagation()">
            <input type="checkbox" name="selected_penalties[]" value="<?php echo e($penalty->id); ?>" class="rounded-lg border-gray-200 text-brand-accent focus:ring-brand-accent/20 focus:ring-offset-0 penalty-checkbox">
        </td>

        
        <td class="p-6">
            <div class="flex items-center gap-4">
                <div class="relative shrink-0">
                    <img 
                        src="<?php echo e($penalty->resident?->photo ? asset('storage/' . $penalty->resident->photo) : asset('CDlogo.jpg')); ?>"
                        onerror="this.onerror=null; this.src='<?php echo e(asset('CDlogo.jpg')); ?>';"
                        class="w-10 h-10 rounded-xl object-cover ring-4 ring-white shadow-sm group-hover:ring-emerald-50 transition-all duration-300"
                        alt="<?php echo e($penalty->resident?->full_name ?? 'Resident'); ?>">
                    <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-white flex items-center justify-center shadow-sm">
                        <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                    </div>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-black text-gray-900 group-hover:text-brand-accent transition truncate"><?php echo e($penalty->resident?->full_name ?? 'Unknown'); ?></p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">Blk <?php echo e($penalty->resident?->block ?? '-'); ?> - Lot <?php echo e($penalty->resident?->lot ?? '-'); ?></p>
                </div>
            </div>
        </td>

        
        <td class="p-6">
            <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">
                <?php echo e(str_replace('_', ' ', $penalty->type ?? 'GENERAL')); ?>

            </span>
        </td>

        
        <td class="p-6 max-w-xs">
            <div class="flex items-center gap-2">
                <span class="text-sm font-medium text-gray-600 block truncate" title="<?php echo e($penalty->reason); ?>">
                    <?php echo e($penalty->reason ?? '-'); ?>

                </span>
                <?php if(str_contains($penalty->reason, 'Auto-generated')): ?>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[8px] font-black uppercase tracking-widest bg-blue-50 text-blue-600 border border-blue-100">
                        Auto
                    </span>
                <?php endif; ?>
            </div>
        </td>

        
        <td class="p-6 text-center whitespace-nowrap">
            <div class="flex flex-col items-center">
                <span class="text-sm font-black text-gray-900 tracking-tight"><?php echo e($penalty->date_issued ? $penalty->date_issued->format('M d, Y') : '-'); ?></span>
                <span class="text-[10px] font-bold text-gray-400 uppercase mt-0.5"><?php echo e($penalty->date_issued ? $penalty->date_issued->diffForHumans() : ''); ?></span>
            </div>
        </td>

        
        <td class="p-6 text-right">
            <span class="text-base font-black text-gray-900 tabular-nums">₱<?php echo e(number_format($penalty->amount, 2)); ?></span>
        </td>

        
        <td class="p-6 text-center">
            <?php
                $statusConfig = [
                    'paid' => [
                        'pill' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                        'dot' => 'bg-emerald-500'
                    ],
                    'unpaid' => [
                        'pill' => 'bg-red-50 text-red-600 border-red-100',
                        'dot' => 'bg-red-500'
                    ],
                    'pending' => [
                        'pill' => 'bg-orange-50 text-orange-600 border-orange-100',
                        'dot' => 'bg-orange-500'
                    ]
                ];
                $config = $statusConfig[$penalty->status] ?? [
                    'pill' => 'bg-gray-50 text-gray-600 border-gray-100',
                    'dot' => 'bg-gray-500'
                ];
            ?>
            <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest border <?php echo e($config['pill']); ?>">
                <span class="w-1.5 h-1.5 rounded-full <?php echo e($config['dot']); ?>"></span>
                <?php echo e($penalty->status); ?>

            </span>
        </td>

        
        <td class="p-6 text-right">
            <i class="bi bi-chevron-right text-gray-300 group-hover:text-brand-accent transition-colors"></i>
        </td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php elseif($penalties->currentPage() == 1): ?>
    <tr>
        <td colspan="8" class="px-6 py-12 text-center text-gray-500 bg-gray-50/50">
            <div class="flex flex-col items-center justify-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="bi bi-clipboard-x text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-sm font-bold text-gray-900 mb-1">No penalties found</h3>
                <p class="text-xs text-gray-500 mb-4">Try adjusting your filters or search terms</p>
            </div>
        </td>
    </tr>
<?php endif; ?>
<?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\penalties\partials\rows.blade.php ENDPATH**/ ?>