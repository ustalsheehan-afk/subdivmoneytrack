
<div class="overflow-x-auto">
    <table class="w-full text-sm border-collapse">
        <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider text-xs font-semibold">
            <tr>
                <th class="p-4 text-left">Date</th>
                <th class="p-4 text-left">Amount</th>
                <th class="p-4 text-left">Reason</th>
                <th class="p-4 text-left">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <?php if($resident->penalties->count() > 0): ?>
                <?php $__currentLoopData = $resident->penalties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $penalty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="p-4 text-sm text-gray-700 font-medium">
                        <?php if($penalty->date_issued): ?>
                            <?php echo e($penalty->date_issued->format('M d, Y')); ?>

                        <?php else: ?>
                            <span class="text-gray-400 italic">N/A</span>
                        <?php endif; ?>
                    </td>
                    <td class="p-4 text-sm text-gray-700 font-medium">
                        ₱<?php echo e(number_format($penalty->amount, 2)); ?>

                    </td>
                    <td class="p-4 text-sm text-gray-700 font-medium">
                        <?php echo e($penalty->reason); ?>

                    </td>
                    <td class="p-4">
                        <?php
                            $statusConfig = match(strtolower($penalty->status)) {
                                'paid' => ['class' => 'bg-emerald-50 text-emerald-700 border-emerald-100', 'dot' => 'bg-emerald-500'],
                                default => ['class' => 'bg-red-50 text-red-700 border-red-100', 'dot' => 'bg-red-500']
                            };
                        ?>
                        <span class="inline-flex items-center justify-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border capitalize tracking-wide w-24 <?php echo e($statusConfig['class']); ?>">
                            <span class="w-1.5 h-1.5 rounded-full <?php echo e($statusConfig['dot']); ?>"></span>
                            <?php echo e(ucwords(str_replace('_', ' ', $penalty->status))); ?>

                        </span>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="p-6 text-center text-gray-500">
                        No penalties found
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\residents\partials\penalties-table.blade.php ENDPATH**/ ?>