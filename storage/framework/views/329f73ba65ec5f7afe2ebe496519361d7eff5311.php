<?php $__env->startComponent('mail::message'); ?>
# Password Reset Request

We received a request to reset the password for your account.

<?php $__env->startComponent('mail::button', ['url' => $resetUrl]); ?>
Reset Password
<?php echo $__env->renderComponent(); ?>

This link will expire in <?php echo e($expiresInMinutes); ?> minutes.

If you did not request a password reset, you can safely ignore this email.

Thanks,<br>
<?php echo e(config('app.name')); ?>

<?php echo $__env->renderComponent(); ?>
<?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\emails\auth\password-reset-link.blade.php ENDPATH**/ ?>