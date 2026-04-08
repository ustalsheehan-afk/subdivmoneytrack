<?php $__env->startSection('title', 'Create Account'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8 animate-fade-in">
    
    
    
    <div class="glass-card p-8 relative overflow-hidden group">
        
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-brand-accent/5 rounded-full blur-3xl group-hover:bg-brand-accent/10 transition-all duration-700"></div>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                    Create Account
                </h1>
                <p class="mt-2 text-gray-600 text-lg max-w-xl">
                    Provision a new system account for administrators, staff, or auditors.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="<?php echo e(route('admin.accounts.index')); ?>" class="btn-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Back to Accounts
                </a>
            </div>
        </div>
    </div>

    <?php if($errors->any()): ?>
        <div class="glass-card border-red-100 bg-red-50/50 p-6 animate-zoom-in">
            <div class="flex items-center gap-3 mb-4 text-red-600">
                <i class="bi bi-exclamation-triangle-fill text-xl"></i>
                <h3 class="font-black uppercase tracking-widest text-sm">Validation Errors</h3>
            </div>
            <ul class="space-y-1">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="text-sm font-bold text-red-500 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                        <?php echo e($error); ?>

                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="max-w-3xl mx-auto">
        <form action="<?php echo e(route('admin.accounts.store')); ?>" method="POST" class="space-y-8">
            <?php echo csrf_field(); ?>

            <div class="glass-card overflow-hidden">
                <div class="p-8 space-y-8">
                    
                    <div class="space-y-8">
                        
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">1</div>
                                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Identity</h3>
                            </div>

                            <div class="space-y-6">
                                
                                <div class="space-y-2">
                                    <label for="name" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Full Name</label>
                                    <input type="text" name="name" id="name" value="<?php echo e(old('name')); ?>" required
                                        class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold text-gray-700 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none"
                                        placeholder="e.g. John D. Administrator">
                                </div>
                            </div>
                        </div>

                        
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">2</div>
                                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Security Credentials</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <div class="space-y-2">
                                    <label for="email" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Account Email</label>
                                    <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>" required
                                        class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold text-gray-700 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none"
                                        placeholder="e.g. login@example.com">
                                </div>

                                
                                <div class="space-y-2">
                                    <label for="password" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Initial Password</label>
                                    <input type="password" name="password" id="password" required
                                        class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold text-gray-700 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none"
                                        placeholder="Minimum 8 characters">
                                </div>

                                
                                <div class="space-y-2 md:col-span-2">
                                    <label for="password_confirmation" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Confirm Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" required
                                        class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold text-gray-700 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none"
                                        placeholder="Repeat the password">
                                </div>
                            </div>
                        </div>

                        
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">3</div>
                                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Permissions & Status</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <div class="space-y-2">
                                    <label for="role_id" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Role</label>
                                    <div class="relative group">
                                        <select name="role_id" id="role_id" required
                                            class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold text-gray-700 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none appearance-none cursor-pointer">
                                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($role->name === 'super_admin'): ?>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('system_settings')): ?>
                                                        <option value="<?php echo e($role->id); ?>" <?php echo e((string) old('role_id') === (string) $role->id ? 'selected' : ''); ?>>
                                                            <?php echo e(strtoupper(str_replace('_',' ', $role->name))); ?>

                                                        </option>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <option value="<?php echo e($role->id); ?>" <?php echo e((string) old('role_id') === (string) $role->id ? 'selected' : ''); ?>>
                                                        <?php echo e(strtoupper(str_replace('_',' ', $role->name))); ?>

                                                    </option>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <i class="bi bi-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none group-focus-within:text-emerald-500 transition-colors"></i>
                                    </div>
                                    <p class="text-[10px] font-bold text-gray-400 mt-2 ml-1">User Role determines access level</p>
                                </div>

                                
                                <div class="flex items-center px-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl h-[60px] self-end">
                                    <label for="active" class="flex items-center gap-3 cursor-pointer group">
                                        <div class="relative inline-flex items-center">
                                            <input type="checkbox" name="active" id="active" value="1" checked class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-500/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                                        </div>
                                        <span class="text-[11px] font-black text-gray-500 uppercase tracking-widest group-hover:text-emerald-600 transition-colors">Active Account</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-8 bg-gray-50/50 border-t border-gray-100 flex items-center justify-end gap-4">
                    <a href="<?php echo e(route('admin.accounts.index')); ?>" class="btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn-premium px-12">
                        <i class="bi bi-check-lg"></i>
                        Create Account
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\accounts\create.blade.php ENDPATH**/ ?>