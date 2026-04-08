

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto p-6">
<h1 class="text-3xl font-bold mb-6">Announcements</h1>

<?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="border p-4 rounded mb-4 bg-white">
<h2 class="font-bold text-xl">
<?php if($a->is_pinned): ?> 📌 <?php endif; ?> <?php echo e($a->title); ?>

</h2>
<p class="text-sm text-gray-500">
<?php echo e($a->category); ?> • <?php echo e($a->date_posted->format('M d, Y')); ?>

</p>

<?php if($a->image): ?>
<img src="<?php echo e(asset('storage/'.$a->image)); ?>" class="my-3 rounded">
<?php endif; ?>

<p><?php echo e($a->content); ?></p>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\announcements\public.blade.php ENDPATH**/ ?>