

<?php $__env->startSection('title', 'Add Payment'); ?>
<?php $__env->startSection('page-title', 'Add Payment'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('admin.payments.form', ['payment' => null], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\payments\create.blade.php ENDPATH**/ ?>