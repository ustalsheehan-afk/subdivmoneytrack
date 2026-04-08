<?php $__env->startSection('title', 'Create Resident'); ?>
<?php $__env->startSection('page-title', 'Add New Resident'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-form-card">

    
    <?php if(session('success')): ?>
        <div class="bg-green-100 text-green-800 p-4 rounded-xl mb-6 flex items-center gap-3">
            <i class="bi bi-check-circle-fill text-green-600"></i>
            <span class="font-medium"><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>

    
    <?php if(session('error')): ?>
        <div class="bg-red-100 text-red-800 p-4 rounded-xl mb-6 flex items-center gap-3">
            <i class="bi bi-exclamation-triangle-fill text-red-600"></i>
            <span class="font-medium"><?php echo e(session('error')); ?></span>
        </div>
    <?php endif; ?>

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-900">Create Resident</h2>
        <a href="<?php echo e(route('admin.residents.index')); ?>"
           class="admin-btn-secondary">
           ← Back
        </a>
    </div>

    
    <form action="<?php echo e(route('admin.residents.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-5">
        <?php echo csrf_field(); ?>

        <div class="flex flex-col items-center mb-5">
            <label class="admin-form-label">Profile Photo</label>
            <div class="relative">
                <img id="photoPreview"
                     src="<?php echo e(asset('CDlogo.jpg')); ?>"
                     alt="Profile Photo"
                     class="w-32 h-32 rounded-full object-cover shadow mb-2">
                <input type="file" name="photo" id="photoInput" accept="image/*" class="hidden">
                <button type="button"
                        onclick="document.getElementById('photoInput').click()"
                        class="admin-btn-secondary">
                    Upload Photo
                </button>
            </div>
            <?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label class="admin-form-label">First Name *</label>
            <input type="text" name="first_name" value="<?php echo e(old('first_name')); ?>"
                   class="admin-form-input"
                   required>
            <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label class="admin-form-label">Last Name *</label>
            <input type="text" name="last_name" value="<?php echo e(old('last_name')); ?>"
                   class="admin-form-input"
                   required>
            <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label class="admin-form-label">Contact Number *</label>
            <input type="text" name="contact_number" value="<?php echo e(old('contact_number')); ?>"
                   class="admin-form-input"
                   required>
            <?php $__errorArgs = ['contact_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label class="admin-form-label">Block *</label>
            <input type="number" name="block" value="<?php echo e(old('block')); ?>"
                   class="admin-form-input"
                   min="1" required>
            <?php $__errorArgs = ['block'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label class="admin-form-label">Lot *</label>
            <input type="number" name="lot" value="<?php echo e(old('lot')); ?>"
                   class="admin-form-input"
                   min="1" required>
            <?php $__errorArgs = ['lot'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label class="admin-form-label">Email Address *</label>
            <input type="email" name="email" value="<?php echo e(old('email')); ?>"
                   class="admin-form-input"
                   required>
            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label class="admin-form-label">Move-in Date *</label>
            <input type="date" name="move_in_date"
                   value="<?php echo e(old('move_in_date')); ?>"
                   class="admin-form-input"
                   required>
            <?php $__errorArgs = ['move_in_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label class="admin-form-label">Status *</label>
            <select name="status"
                    class="admin-form-select"
                    required>
                <option value="active" <?php echo e(old('status') === 'active' ? 'selected' : ''); ?>>Active</option>
                <option value="inactive" <?php echo e(old('status') === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
            </select>
            <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="flex justify-end gap-3">
            <a href="<?php echo e(route('admin.residents.index')); ?>"
               class="admin-btn-secondary">
               Cancel
            </a>
            <button type="submit"
                    class="admin-btn-primary">
                Create Resident
            </button>
        </div>
    </form>
</div>


<script>
document.getElementById('photoInput').addEventListener('change', function(event) {
    const [file] = event.target.files;
    if (file) {
        document.getElementById('photoPreview').src = URL.createObjectURL(file);
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\residents\create.blade.php ENDPATH**/ ?>