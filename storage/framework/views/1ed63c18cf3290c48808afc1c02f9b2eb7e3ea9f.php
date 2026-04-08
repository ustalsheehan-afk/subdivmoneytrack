<?php if($dues->count() > 0): ?>
    <?php $__currentLoopData = $dues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $due): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <?php
            $progress = $due->total_residents > 0 ? ($due->paid_residents / $due->total_residents) * 100 : 0;

            if ($due->type === 'monthly_hoa') {
                $colorClass = 'bg-blue-50 text-blue-700 border-blue-100';
            } elseif ($due->type === 'regular_fees') {
                $colorClass = 'bg-purple-50 text-purple-700 border-purple-100';
            } elseif ($due->type === 'special_assessments') {
                $colorClass = 'bg-amber-50 text-amber-700 border-amber-100';
            } else {
                $colorClass = 'bg-gray-50 text-gray-700 border-gray-100';
            }
        ?>

        <tr class="hover:bg-gray-50/50 transition group">
            <td class="px-6 py-4 w-[25%]">
                <div class="flex flex-col">
                    <span class="font-bold text-gray-900"><?php echo e($due->title); ?></span>
                    <span class="text-xs text-gray-500 mt-0.5"><?php echo e($due->description); ?></span>
                    <span class="inline-block mt-2 px-2 py-0.5 rounded text-[10px] font-bold uppercase w-fit border <?php echo e($colorClass); ?>">
                        <?php echo e(str_replace('_', ' ', $due->type)); ?>

                    </span>
                </div>
            </td>

            <td class="px-6 py-4 text-gray-600 w-[15%]">
                <?php if($due->billing_period_start): ?>
                    <?php echo e(\Carbon\Carbon::parse($due->billing_period_start)->format('M d')); ?> - 
                    <?php echo e(\Carbon\Carbon::parse($due->billing_period_end)->format('M d')); ?>

                <?php else: ?>
                    <span class="text-gray-400 italic">N/A</span>
                <?php endif; ?>
            </td>

            <td class="px-6 py-4 text-right font-medium text-gray-900 w-[15%]">
                ₱<?php echo e(number_format($due->amount, 2)); ?>

            </td>

            <td class="px-6 py-4 text-center w-[10%]">
                <div class="flex flex-col items-center">
                    <span class="font-bold text-gray-800"><?php echo e(\Carbon\Carbon::parse($due->due_date)->format('d')); ?></span>
                    <span class="text-xs text-gray-500 uppercase"><?php echo e(\Carbon\Carbon::parse($due->due_date)->format('M')); ?></span>
                </div>
            </td>

            <td class="px-6 py-4 w-[20%]">
                <div class="w-full max-w-[140px]">
                    <div class="flex justify-between text-xs mb-1">
                        <span class="font-semibold text-gray-700">
                            <?php echo e($due->paid_residents); ?>/<?php echo e($due->total_residents); ?> Paid
                        </span>
                        <span class="font-bold <?php echo e($progress >= 100 ? 'text-emerald-600' : 'text-blue-600'); ?>">
                            <?php echo e(round($progress)); ?>%
                        </span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="h-2 rounded-full transition-all duration-500 <?php echo e($progress >= 100 ? 'bg-emerald-500' : 'bg-blue-500'); ?>"
                             style="width: <?php echo e($progress); ?>%"></div>
                    </div>
                </div>
            </td>

            <td class="px-6 py-4 text-right w-[15%]">
                <div class="flex justify-end gap-2">
                    <?php if(!$due->archived_at): ?>

                        <form action="<?php echo e(route('admin.dues.archive', $due->batch_id)); ?>" method="POST"
                              onsubmit="return confirm('Archive this batch?');">
                            <?php echo csrf_field(); ?>
                            <button type="submit"
                                class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600"
                                title="Archive">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                </svg>
                            </button>
                        </form>

                        <form action="<?php echo e(route('admin.dues.destroy', $due->batch_id)); ?>" method="POST"
                              onsubmit="return confirm('Delete this batch? This will remove dues for ALL residents.');">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit"
                                class="p-1.5 rounded-lg text-red-400 hover:bg-red-50 hover:text-red-600"
                                title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>

                    <?php else: ?>
                        <span class="text-xs text-gray-400 italic">Archived</span>
                    <?php endif; ?>
                </div>
            </td>
        </tr>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php else: ?>
    <tr>
        <td colspan="6" class="px-6 py-12 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900">No dues found</h3>
            <p class="text-gray-500 mt-1">Try adjusting your filters or create a new due.</p>
        </td>
    </tr>
<?php endif; ?>
<?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\dues\partials\rows.blade.php ENDPATH**/ ?>