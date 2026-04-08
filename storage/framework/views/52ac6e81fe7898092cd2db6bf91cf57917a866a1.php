<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5 text-center">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-check2 display-4"></i>
                        </div>
                    </div>
                    
                    <h2 class="fw-bold text-gray-900 mb-3">Account Created!</h2>
                    <p class="text-muted mb-5">
                        Your resident account has been successfully created. You can now log in using your email and the password you just set.
                    </p>
                    
                    <div class="d-grid gap-3">
                        <form action="<?php echo e(route('logout')); ?>" method="POST" class="d-grid">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-primary py-3 rounded-3 fw-bold shadow-sm transition-all">
                                Log In to My Account
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <p class="text-center text-muted small mt-4 opacity-50">
                &copy; <?php echo e(date('Y')); ?> Subdivision Management System. All rights reserved.
            </p>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\auth\registration-success.blade.php ENDPATH**/ ?>