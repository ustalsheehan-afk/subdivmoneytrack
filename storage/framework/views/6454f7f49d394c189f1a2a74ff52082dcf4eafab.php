<?php if($requests->count() > 0): ?>
    <?php $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr onclick="viewRequest(<?php echo e($req->id); ?>)" 
        class="hover:bg-gray-50 cursor-pointer transition group border-b border-gray-100 last:border-0">
        
        <td class="px-4 py-3">
            <div class="flex items-center gap-3">
                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 text-xs font-bold">
                    <?php echo e(substr($req->resident->first_name ?? '?', 0, 1)); ?><?php echo e(substr($req->resident->last_name ?? '?', 0, 1)); ?>

                </div>
                <div>
                    <p class="font-medium text-gray-900"><?php echo e($req->resident->full_name ?? 'Unknown'); ?></p>
                    <p class="text-xs text-gray-500">B<?php echo e($req->resident->block ?? '-'); ?> L<?php echo e($req->resident->lot ?? '-'); ?></p>
                </div>
            </div>
        </td>

        
        <td class="px-4 py-3 text-gray-700 font-medium">
            <?php echo e($req->type); ?>

        </td>

        
        <td class="px-4 py-3 text-gray-500 text-sm max-w-xs truncate" title="<?php echo e($req->description); ?>">
            <?php echo e($req->description); ?>

        </td>

        
        <td class="px-4 py-3 text-center">
            <?php
                $priorityClass = match(strtolower($req->priority)) {
                    'high' => 'bg-red-100 text-red-700',
                    'medium' => 'bg-yellow-100 text-yellow-700',
                    default => 'bg-green-100 text-green-700',
                };
            ?>
            <span class="px-2.5 py-1 rounded-full text-xs font-semibold <?php echo e($priorityClass); ?>">
                <?php echo e(ucfirst($req->priority)); ?>

            </span>
        </td>

        
        <td class="px-4 py-3 text-center">
            <?php
                $statusClass = match($req->status) {
                    'pending' => 'bg-gray-100 text-gray-700',
                    'in progress' => 'bg-blue-100 text-blue-700',
                    'completed' => 'bg-green-100 text-green-700',
                    'rejected' => 'bg-red-100 text-red-700',
                    default => 'bg-gray-100 text-gray-700',
                };
            ?>
            <span class="px-2.5 py-1 rounded-full text-xs font-semibold <?php echo e($statusClass); ?>">
                <?php echo e(ucfirst($req->status)); ?>

            </span>
        </td>

        
        <td class="px-4 py-3 text-center text-gray-500 whitespace-nowrap">
            <?php echo e($req->created_at->format('M d, Y')); ?>

        </td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php else: ?>
    <tr>
        <td colspan="6" class="px-4 py-8 text-center text-gray-500 bg-gray-50">
            <div class="flex flex-col items-center justify-center">
                <i class="bi bi-inbox text-4xl mb-3 text-gray-300"></i>
                <p>No service requests found matching your filters.</p>
            </div>
        </td>
    </tr>
<?php endif; ?>
<?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\requests\partials\rows.blade.php ENDPATH**/ ?>