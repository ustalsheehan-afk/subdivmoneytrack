<?php if(isset($user)): ?>
    <h2>Welcome, <?php echo e($user->name); ?></h2>
<?php else: ?>
    <p>Please log in first.</p>
<?php endif; ?>
<?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\dashboard.blade.php ENDPATH**/ ?>