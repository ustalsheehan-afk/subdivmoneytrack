

<?php $__env->startSection('title', 'Edit Resident'); ?>
<?php $__env->startSection('page-title', 'Edit Resident'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8 animate-fade-in pb-20">

    
    
    
    <div class="glass-card p-8 relative overflow-hidden group">
        
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-brand-accent/5 rounded-full blur-3xl group-hover:bg-brand-accent/10 transition-all duration-700"></div>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div class="flex items-center gap-6">
                <a href="<?php echo e(route('admin.residents.index')); ?>" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white border border-gray-100 text-gray-400 hover:text-emerald-600 hover:border-emerald-100 hover:shadow-sm transition-all shadow-sm">
                    <i class="bi bi-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                        Edit Resident
                    </h1>
                    <p class="mt-2 text-gray-600 text-lg max-w-xl">
                        Update personal and property details for <span class="font-black text-emerald-600"><?php echo e($resident->full_name); ?></span>.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" form="edit-resident-form" class="btn-premium">
                    <i class="bi bi-check2-circle"></i>
                    Update Resident
                </button>
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

    <div class="max-w-5xl mx-auto">
        <form id="edit-resident-form" action="<?php echo e(route('admin.residents.update', $resident->id)); ?>" method="POST" enctype="multipart/form-data" class="space-y-8">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-1 space-y-8">
                    <div class="glass-card p-8 flex flex-col items-center text-center space-y-6">
                        <div class="relative group/photo">
                            <img id="photoPreview"
                                 src="<?php echo e($resident->photo ? asset('storage/' . $resident->photo) : asset('CDlogo.jpg')); ?>"
                                 onerror="this.onerror=null; this.src='<?php echo e(asset('CDlogo.jpg')); ?>';"
                                 class="w-40 h-40 rounded-[40px] object-cover border-4 border-white shadow-2xl group-hover/photo:scale-105 transition-all duration-500">
                            
                            <label for="photoInput" class="absolute -bottom-2 -right-2 w-12 h-12 bg-emerald-500 text-white rounded-2xl flex items-center justify-center shadow-lg border-4 border-white cursor-pointer hover:bg-emerald-600 transition-all active:scale-90">
                                <i class="bi bi-camera-fill text-xl"></i>
                            </label>
                            <input type="file" name="photo" id="photoInput" accept="image/*" class="hidden">
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest">Profile Photo</h4>
                            <p class="text-[10px] font-bold text-gray-400 mt-1 uppercase tracking-widest">Click icon to upload</p>
                        </div>

                        <div class="w-full pt-6 border-t border-gray-50">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-4">Account Status</label>
                            <div class="flex p-1.5 bg-gray-100 rounded-[20px] border border-gray-200 shadow-inner">
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="status" value="active" class="peer hidden" <?php echo e(old('status', $resident->status) === 'active' ? 'checked' : ''); ?>>
                                    <div class="py-3 text-center text-[10px] font-black uppercase tracking-widest rounded-[16px] transition-all peer-checked:bg-white peer-checked:text-emerald-600 peer-checked:shadow-lg text-gray-400 hover:text-gray-600">
                                        Active
                                    </div>
                                </label>
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="status" value="inactive" class="peer hidden" <?php echo e(old('status', $resident->status) === 'inactive' ? 'checked' : ''); ?>>
                                    <div class="py-3 text-center text-[10px] font-black uppercase tracking-widest rounded-[16px] transition-all peer-checked:bg-white peer-checked:text-red-600 peer-checked:shadow-lg text-gray-400 hover:text-gray-600">
                                        Inactive
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="lg:col-span-2 space-y-8">
                    <div class="glass-card p-8 space-y-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-xl border border-emerald-100 shadow-sm">
                                <i class="bi bi-person-lines-fill"></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-black text-gray-900 tracking-tight">Resident Information</h4>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Update personal and contact data</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">First Name</label>
                                <input type="text" name="first_name" value="<?php echo e(old('first_name', $resident->first_name)); ?>" 
                                    class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm font-medium focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all outline-none" required>
                            </div>

                            
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Last Name</label>
                                <input type="text" name="last_name" value="<?php echo e(old('last_name', $resident->last_name)); ?>" 
                                    class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm font-medium focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all outline-none" required>
                            </div>

                            
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Contact Number</label>
                                <input type="text" name="contact_number" value="<?php echo e(old('contact_number', $resident->contact_number)); ?>" 
                                    class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm font-medium focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all outline-none" required>
                            </div>

                            
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Email Address</label>
                                <input type="email" name="email" value="<?php echo e(old('email', $resident->email)); ?>" 
                                    class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm font-medium focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all outline-none" required>
                            </div>
                        </div>

                        <div class="pt-8 border-t border-gray-50">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-xl border border-emerald-100 shadow-sm">
                                    <i class="bi bi-house-door-fill"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-black text-gray-900 tracking-tight">Property Details</h4>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Location and move-in records</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                                
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Block No.</label>
                                    <input type="number" name="block" value="<?php echo e(old('block', $resident->block)); ?>" 
                                        class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm font-medium focus:bg-white focus:border-emerald-500 transition-all outline-none" required>
                                </div>

                                
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Lot No.</label>
                                    <input type="number" name="lot" value="<?php echo e(old('lot', $resident->lot)); ?>" 
                                        class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm font-medium focus:bg-white focus:border-emerald-500 transition-all outline-none" required>
                                </div>

                                
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Move-in Date</label>
                                    <input type="date" name="move_in_date" value="<?php echo e(old('move_in_date', $resident->move_in_date ? $resident->move_in_date->format('Y-m-d') : '')); ?>" 
                                        class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm font-medium focus:bg-white focus:border-emerald-500 transition-all outline-none" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4">
                        <a href="<?php echo e(route('admin.residents.index')); ?>" class="btn-secondary px-10 py-4">
                            Cancel Changes
                        </a>
                        <button type="submit" class="btn-premium px-10 py-4">
                            Save Resident Profile
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('photoInput').addEventListener('change', function(event) {
    const [file] = event.target.files;
    if (file) {
        document.getElementById('photoPreview').src = URL.createObjectURL(file);
    }
});
</script>


<script>
document.getElementById('photoInput').addEventListener('change', function(event) {
    const [file] = event.target.files;
    if (file) {
        document.getElementById('photoPreview').src = URL.createObjectURL(file);
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\residents\edit.blade.php ENDPATH**/ ?>