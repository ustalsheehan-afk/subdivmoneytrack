<?php $__env->startSection('content'); ?>

<style>
/* Reuse Login Styles */
body {
    margin: 0;
    padding: 0;
    height: 100vh;
    background: linear-gradient(rgba(0,0,0,0.45), rgba(0,0,0,0.45)),
                url('<?php echo e(asset('bg.png')); ?>') no-repeat center center fixed;
    background-size: cover;
    overflow: hidden;
    color: #fff;
    font-family: 'Poppins', sans-serif;
}

.login-card {
    width: 360px;
    background-color: rgba(255,255,255,0.93);
    backdrop-filter: blur(8px);
    border: none;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    animation: fadeInUp 1s ease;
    margin-bottom: 160px;
    padding: 2rem 2.2rem;
}

.login-logo {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    object-fit: cover;
    box-shadow: 0 0 15px rgba(13,110,253,0.7);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.login-logo:hover {
    transform: scale(1.05);
    box-shadow: 0 0 25px rgba(13,110,253,0.9);
}

.form-control {
    border-radius: 10px;
    height: 42px;
    font-size: 0.9rem;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.25);
}

.btn-primary {
    background: linear-gradient(90deg, #007bff, #0056b3);
    border: none;
    transition: all 0.3s ease;
    font-size: 0.95rem;
    padding: 0.55rem 0;
}

.btn-primary:hover {
    background: linear-gradient(90deg, #0056b3, #004080);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,86,179,0.3);
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 420px) {
    .login-card {
        width: 90%;
        padding: 1.8rem;
        margin-top: -40px;
    }
}
</style>

<div class="d-flex justify-content-center align-items-center min-vh-100">
    <div class="card login-card">

        <!-- Logo & Header -->
        <div class="text-center mb-3">
            <img src="<?php echo e(asset('Cdlogo.jpg')); ?>" alt="Logo" class="login-logo mb-3">
            <h4 class="fw-bold text-primary mb-1">Change Password</h4>
            <p class="text-muted small mb-3">Please change your password to continue</p>
        </div>

        <!-- Error Message -->
        <?php if($errors->any()): ?>
            <div class="alert alert-danger small text-center rounded-3 mb-3 py-2">
                <?php echo e($errors->first()); ?>

            </div>
        <?php endif; ?>

        <!-- Change Password Form -->
        <form method="POST" action="<?php echo e(route('resident.change-password.update')); ?>">
            <?php echo csrf_field(); ?>

            <!-- New Password -->
            <div class="mb-3">
                <input type="password" name="password" id="password"
                       class="form-control rounded-pill ps-4 pe-5 py-2 <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       placeholder="New Password"
                       required autofocus>
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="invalid-feedback small ps-3" role="alert">
                        <strong><?php echo e($message); ?></strong>
                    </span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <input type="password" name="password_confirmation" id="password-confirm"
                       class="form-control rounded-pill ps-4 pe-5 py-2"
                       placeholder="Confirm Password"
                       required>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary w-100 fw-semibold rounded-3 shadow-sm">
                <i class="bi bi-key me-1"></i> Update Password
            </button>
        </form>

    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\auth\resident-change-password.blade.php ENDPATH**/ ?>