<?php $__env->startSection('content'); ?>

<style>
/* SaaS Design System */
:root {
    --brand-primary: #1F3B5C;
    --brand-accent: #2E5B8A;
    --brand-bg-overlay: rgba(15, 42, 68, 0.75);
    --input-border: #e5e7eb;
    --input-focus: rgba(31, 59, 92, 0.15);
}

body {
    margin: 0;
    padding: 0;
    min-height: 100vh;
    font-family: 'Inter', 'Poppins', sans-serif;
    background: #f8fafc;
}

/* Layout Wrapper */
.page-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    padding: 40px 20px;
}

/* Main Card */
.register-card {
    width: 1100px;
    background: #ffffff;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.15);
    display: flex;
    animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}

/* Left Side: Premium Visual */
.visual-side {
    width: 40%;
    background: linear-gradient(135deg, rgba(1, 32, 13, 0.85), rgba(15, 23, 42, 0.9)),
                url('<?php echo e(asset('bg.png')); ?>') center/cover no-repeat;
    color: white;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 60px;
    position: relative;
}

.visual-side .content-box {
    position: relative;
    z-index: 2;
}

.visual-side h2 {
    font-size: 2.5rem;
    font-weight: 800;
    line-height: 1.1;
    margin-bottom: 1.5rem;
    letter-spacing: -0.02em;
}

.visual-side p {
    font-size: 1.1rem;
    line-height: 1.6;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 2.5rem;
}

.visual-side .badge-premium {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(8px);
    padding: 8px 16px;
    border-radius: 100px;
    font-size: 0.85rem;
    font-weight: 600;
    border: 1px solid rgba(255, 255, 255, 0.2);
    margin-bottom: 2rem;
}

/* Right Side: Form */
.form-side {
    flex: 1;
    padding: 50px 60px 400px 60px; /* Even more bottom padding */
    background: #fff;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
    max-height: 90vh;
}

.form-header {
    text-align: center;
    margin-bottom: 2.5rem;
}

.form-logo {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    object-fit: cover;
    margin-bottom: 1.5rem;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
}

.form-header h1 {
    font-size: 1.85rem;
    font-weight: 800;
    color: var(--brand-primary);
    margin-bottom: 0.5rem;
    letter-spacing: -0.025em;
    line-height: 1.2;
}

.form-header p {
    color: #64748b;
    font-size: 0.9rem;
}

/* Section Styling */
.form-section-title {
    font-size: 0.85rem;
    font-weight: 800;
    color: var(--brand-primary);
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 1.5rem;
    padding-bottom: 8px;
    border-bottom: 2px solid #f1f5f9;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-section-title i {
    color: #94a3b8;
}

/* Form Groups */
.form-group-custom {
    margin-bottom: 1.25rem;
}

.form-label-custom {
    display: block;
    font-size: 0.75rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.6rem;
    padding-left: 4px;
}

.input-wrapper {
    position: relative;
}

.input-wrapper i.input-icon {
    position: absolute;
    left: 1.25rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 1.1rem;
    pointer-events: none;
    transition: color 0.3s ease;
}

.form-control-custom {
    width: 100%;
    height: 50px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 0 1.25rem 0 3.25rem;
    font-size: 0.95rem;
    color: #021c08;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}

.form-control-custom:focus {
    outline: none;
    background: #fff;
    border-color: var(--brand-primary);
    box-shadow: 0 0 0 4px var(--input-focus);
}

.form-control-custom:disabled {
    background: #f1f5f9;
    color: #021c08;
    cursor: not-allowed;
}

select.form-control-custom {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1.25rem center;
    background-size: 1.5rem;
    padding-right: 3.25rem;
    cursor: pointer;
}

select.form-control-custom option {
    padding: 12px;
    background: #fff;
    color: #013a17;
    font-size: 0.95rem;
}

/* Password Toggle */
.password-toggle {
    position: absolute;
    right: 1.25rem;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #94a3b8;
    font-size: 1.2rem;
    padding: 4px;
    transition: color 0.2s ease;
}

.password-toggle:hover {
    color: var(--brand-primary);
}

/* Grid Layout */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
}

/* Button */
.btn-create {
    width: 100%;
    height: 54px;
    background: var(--brand-primary);
    color: white;
    border: none;
    border-radius: 14px;
    font-weight: 700;
    font-size: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    transition: all 0.3s ease;
    margin-top: 2rem;
    box-shadow: 0 10px 20px -5px rgba(31, 59, 92, 0.3);
}

.btn-create:hover {
    background: #00220c;
    transform: translateY(-2px);
    box-shadow: 0 20px 30px -10px rgba(31, 59, 92, 0.4);
}

/* Animations */
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(40px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Responsive */
@media (max-width: 1024px) {
    .register-card { width: 95%; }
}

@media (max-width: 768px) {
    .register-card { flex-direction: column; width: 100%; }
    .visual-side { width: 100%; padding: 40px; text-align: center; }
    .form-side { padding: 40px 20px; max-height: none; }
    .form-grid { grid-template-columns: 1fr; }
}
</style>

<div class="page-wrapper">
    <div class="register-card">
        
        <!-- Left Side: Visual -->
        <div class="visual-side">
            <div class="content-box">
                <div class="badge-premium">
                    <i class="bi bi-patch-check-fill"></i>
                    <span>Resident Activation</span>
                </div>
                <h2>Join Our Community</h2>
                <p>
                    Welcome, <?php echo e($invitation->first_name); ?>! Please complete your registration to access your resident dashboard.
                </p>
                <div style="display: flex; gap: 20px; opacity: 0.6; font-size: 0.85rem; font-weight: 500;">
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <i class="bi bi-shield-lock"></i> Secure
                    </div>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <i class="bi bi-lightning-charge"></i> Instant
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="form-side custom-scrollbar">
            <div class="form-header">
                <img src="<?php echo e(asset('Cdlogo.jpg')); ?>" alt="Logo" class="form-logo mx-auto">
                <h1>Complete Your Registration,<br><span style="color: var(--brand-accent);"><?php echo e($invitation->first_name); ?> <?php echo e($invitation->last_name); ?></span>!</h1>
                <p>Fill in the remaining details to finish setting up your account.</p>
            </div>

            <?php if(session('error')): ?>
                <div class="alert alert-danger border-0 small text-center rounded-3 mb-4 py-2 bg-red-50 text-red-600">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('register.invitation.submit')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="token" value="<?php echo e(request('token')); ?>">

                <!-- 1. PERSONAL INFO -->
                <h3 class="form-section-title"><i class="bi bi-person-badge"></i> Personal Information</h3>
                
                <div class="form-grid">
                    <div class="form-group-custom">
                        <label class="form-label-custom">First Name</label>
                        <div class="input-wrapper">
                            <i class="bi bi-person input-icon"></i>
                            <input type="text" name="first_name" class="form-control-custom" 
                                   value="<?php echo e(old('first_name', $invitation->first_name)); ?>" readonly>
                        </div>
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Last Name</label>
                        <div class="input-wrapper">
                            <i class="bi bi-person input-icon"></i>
                            <input type="text" name="last_name" class="form-control-custom" 
                                   value="<?php echo e(old('last_name', $invitation->last_name)); ?>" readonly>
                        </div>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Email Address</label>
                        <div class="input-wrapper">
                            <i class="bi bi-envelope input-icon"></i>
                            <input type="email" class="form-control-custom" 
                                   value="<?php echo e($invitation->email); ?>" disabled>
                        </div>
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Contact Number</label>
                        <div class="input-wrapper">
                            <i class="bi bi-phone input-icon"></i>
                            <input type="text" name="contact_number" class="form-control-custom <?php $__errorArgs = ['contact_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   value="<?php echo e(old('contact_number', $invitation->phone)); ?>" placeholder="09XX XXX XXXX" required>
                        </div>
                        <?php $__errorArgs = ['contact_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger small mt-1 ps-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- 2. PROPERTY INFO -->
                <h3 class="form-section-title" style="margin-top: 1rem;"><i class="bi bi-house-door"></i> Property Details</h3>
                
                <div class="form-grid">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Block Number</label>
                        <div class="input-wrapper">
                            <i class="bi bi-grid-3x3-gap input-icon"></i>
                            <select name="block" class="form-control-custom <?php $__errorArgs = ['block'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="" disabled selected>Select Block</option>
                                <?php for($i = 1; $i <= 50; $i++): ?>
                                    <option value="<?php echo e($i); ?>" <?php echo e(old('block') == $i ? 'selected' : ''); ?>>Block <?php echo e($i); ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <?php $__errorArgs = ['block'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger small mt-1 ps-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Lot Number</label>
                        <div class="input-wrapper">
                            <i class="bi bi-geo-alt input-icon"></i>
                            <select name="lot" class="form-control-custom <?php $__errorArgs = ['lot'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="" disabled selected>Select Lot</option>
                                <?php for($i = 1; $i <= 100; $i++): ?>
                                    <option value="<?php echo e($i); ?>" <?php echo e(old('lot') == $i ? 'selected' : ''); ?>>Lot <?php echo e($i); ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <?php $__errorArgs = ['lot'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger small mt-1 ps-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="form-group-custom">
                    <label class="form-label-custom">Move-in Date</label>
                    <div class="input-wrapper">
                        <i class="bi bi-calendar-check input-icon"></i>
                        <input type="date" name="move_in_date" class="form-control-custom <?php $__errorArgs = ['move_in_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               value="<?php echo e(old('move_in_date')); ?>" required>
                    </div>
                    <?php $__errorArgs = ['move_in_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger small mt-1 ps-1"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- 3. SECURITY -->
                <h3 class="form-section-title" style="margin-top: 1rem;"><i class="bi bi-shield-lock"></i> Security</h3>

                <div class="form-grid">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Set Password</label>
                        <div class="input-wrapper">
                            <i class="bi bi-lock input-icon"></i>
                            <input type="password" name="password" id="password" 
                                   class="form-control-custom <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   placeholder="Min. 8 characters" required>
                            <i class="bi bi-eye password-toggle" onclick="togglePass('password', this)"></i>
                        </div>
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger small mt-1 ps-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Confirm Password</label>
                        <div class="input-wrapper">
                            <i class="bi bi-shield-check input-icon"></i>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="form-control-custom" placeholder="Repeat password" required>
                            <i class="bi bi-eye password-toggle" onclick="togglePass('password_confirmation', this)"></i>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-create">
                    <span>Activate My Resident Portal</span>
                    <i class="bi bi-arrow-right"></i>
                </button>
            </form>
            <div style="min-height: 400px;"></div> <!-- Even larger spacer for dropdown space -->
        </div>

    </div>
</div>

<script>
function togglePass(id, icon) {
    const input = document.getElementById(id);
    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
    input.setAttribute('type', type);
    
    if (type === 'text') {
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\auth\register-invitation.blade.php ENDPATH**/ ?>