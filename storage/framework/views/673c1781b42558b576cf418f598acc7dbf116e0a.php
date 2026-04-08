<?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php echo $__env->make('admin.announcements.partials.card', [
        'announcement' => $announcement,
        'totalResidents' => $totalResidents ?? 0
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\announcements\partials\list.blade.php ENDPATH**/ ?>