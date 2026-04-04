

<?php $__env->startSection('title', 'Edit Profile'); ?>
<?php $__env->startSection('page-title', 'Edit Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="h-full bg-[#F8F9FB] overflow-y-auto custom-scrollbar">
    <div class="max-w-4xl mx-auto px-6 py-8 pb-24 animate-fade-in">

        
        
        
        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-emerald-500/10 border border-emerald-500/20 mb-3">
                    <i class="bi bi-pencil-square text-emerald-500 text-xs"></i>
                    <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">Account Settings</span>
                </div>
                <h1 class="text-4xl font-black text-gray-900 tracking-tight leading-none">Edit Profile</h1>
                <p class="text-sm font-black text-gray-400 mt-3 uppercase tracking-widest">Update your personal and property information</p>
            </div>
            <a href="<?php echo e(route('resident.profile.index')); ?>" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-gray-200 rounded-2xl text-sm font-black text-gray-400 hover:text-gray-900 hover:border-gray-900 transition-all duration-300 shadow-sm">
                <i class="bi bi-arrow-left"></i>
                <span>BACK TO PROFILE</span>
            </a>
        </div>

        
        
        
        <form action="<?php echo e(route('resident.profile.update')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="grid grid-cols-1 gap-8">
                
                
                <div class="bg-[#081412] rounded-[40px] p-10 shadow-2xl relative overflow-hidden group">
                    <div class="absolute -right-20 -top-20 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl group-hover:bg-emerald-500/20 transition-all duration-1000"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row items-center gap-10">
                        <div class="relative shrink-0 group/photo">
                            <div class="absolute inset-0 bg-emerald-500/20 rounded-full blur-xl group-hover/photo:bg-emerald-500/40 transition-all duration-500"></div>
                            <img id="photoPreview"
                                 src="<?php echo e(($resident && $resident->photo) ? asset('storage/' . $resident->photo) : asset('CDlogo.jpg')); ?>"
                                 onerror="this.onerror=null; this.src='<?php echo e(asset('CDlogo.jpg')); ?>';"
                                 alt="Profile Photo"
                                 class="w-32 h-32 rounded-full object-cover border-4 border-white/10 shadow-2xl relative z-10 bg-[#0D1F1C]">
                            
                            <button type="button"
                                    onclick="document.getElementById('photoInput').click()"
                                    class="absolute bottom-1 right-1 w-10 h-10 bg-emerald-500 text-black rounded-2xl flex items-center justify-center shadow-2xl hover:bg-emerald-400 hover:scale-110 transition-all z-20 border-4 border-[#081412]"
                                    title="Change Photo">
                                <i class="bi bi-camera-fill text-sm"></i>
                            </button>
                            <input type="file" name="photo" id="photoInput" accept="image/*" class="hidden">
                        </div>

                        <div class="text-center md:text-left">
                            <h3 class="text-xl font-black text-white tracking-tight mb-2">Profile Picture</h3>
                            <p class="text-xs font-black text-white/30 uppercase tracking-[0.2em] mb-6">JPG, GIF OR PNG. MAX SIZE 2MB</p>
                            
                            <?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
                                <div class="mt-2 px-4 py-2 bg-red-500/10 border border-red-500/20 rounded-xl">
                                    <p class="text-red-400 text-[10px] font-black uppercase tracking-widest"><?php echo e($message); ?></p>
                                </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                
                <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm p-10 relative overflow-hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-10">
                        
                        
                        <div class="md:col-span-2 flex items-center gap-4 mb-2">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center border border-emerald-100">
                                <i class="bi bi-person-fill text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-black text-gray-900 text-lg tracking-tight leading-none">Personal Details</h3>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Identity & Contact Info</p>
                            </div>
                        </div>

                        
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">First Name</label>
                            <input type="text" name="first_name" value="<?php echo e(old('first_name', $resident->first_name ?? '')); ?>"
                                   class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-black text-gray-900 focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all outline-none"
                                   required>
                            <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-[10px] font-black uppercase tracking-widest mt-1 ml-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Last Name</label>
                            <input type="text" name="last_name" value="<?php echo e(old('last_name', $resident->last_name ?? '')); ?>"
                                   class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-black text-gray-900 focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all outline-none"
                                   required>
                            <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-[10px] font-black uppercase tracking-widest mt-1 ml-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Contact Number</label>
                            <input type="text" name="contact_number" value="<?php echo e(old('contact_number', $resident->contact_number ?? '')); ?>"
                                   class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-black text-gray-900 focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all outline-none"
                                   required>
                            <?php $__errorArgs = ['contact_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-[10px] font-black uppercase tracking-widest mt-1 ml-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Email Address</label>
                            <input type="email" name="email" value="<?php echo e(old('email', $resident->email ?? '')); ?>"
                                   class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-black text-gray-900 focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all outline-none"
                                   required>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-[10px] font-black uppercase tracking-widest mt-1 ml-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="md:col-span-2 flex items-center gap-4 mt-6 mb-2">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center border border-emerald-100">
                                <i class="bi bi-house-door-fill text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-black text-gray-900 text-lg tracking-tight leading-none">Property Info</h3>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Location & Assets</p>
                            </div>
                        </div>

                        
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Block Number</label>
                            <input type="number" name="block" value="<?php echo e(old('block', $resident->block ?? '')); ?>"
                                   class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-black text-gray-900 focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all outline-none"
                                   min="1" required>
                            <?php $__errorArgs = ['block'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-[10px] font-black uppercase tracking-widest mt-1 ml-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Lot Number</label>
                            <input type="number" name="lot" value="<?php echo e(old('lot', $resident->lot ?? '')); ?>"
                                   class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-black text-gray-900 focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all outline-none"
                                   min="1" required>
                            <?php $__errorArgs = ['lot'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-[10px] font-black uppercase tracking-widest mt-1 ml-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Property Type</label>
                            <input type="text" name="property_type" value="<?php echo e(old('property_type', $resident->property_type)); ?>"
                                   placeholder="e.g., Residential House & Lot"
                                   class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-black text-gray-900 focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all outline-none">
                            <?php $__errorArgs = ['property_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-[10px] font-black uppercase tracking-widest mt-1 ml-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Membership</label>
                            <input type="text" name="membership_type" value="<?php echo e(old('membership_type', $resident->membership_type)); ?>"
                                   placeholder="e.g., Regular Member"
                                   class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-black text-gray-900 focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all outline-none">
                            <?php $__errorArgs = ['membership_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-[10px] font-black uppercase tracking-widest mt-1 ml-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Lot Area (sq.m)</label>
                            <input type="number" name="lot_area" value="<?php echo e(old('lot_area', $resident->lot_area)); ?>"
                                   placeholder="150" step="0.01" min="0"
                                   class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-black text-gray-900 focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all outline-none">
                            <?php $__errorArgs = ['lot_area'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-[10px] font-black uppercase tracking-widest mt-1 ml-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Floor Area (sq.m)</label>
                            <input type="number" name="floor_area" value="<?php echo e(old('floor_area', $resident->floor_area)); ?>"
                                   placeholder="120" step="0.01" min="0"
                                   class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-black text-gray-900 focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all outline-none">
                            <?php $__errorArgs = ['floor_area'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-[10px] font-black uppercase tracking-widest mt-1 ml-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="md:col-span-2 flex items-center gap-4 mt-6 mb-2">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center border border-emerald-100">
                                <i class="bi bi-shield-lock-fill text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-black text-gray-900 text-lg tracking-tight leading-none">Security & Status</h3>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Access & Verification</p>
                            </div>
                        </div>

                        
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Move-in Date</label>
                            <input type="date" name="move_in_date" value="<?php echo e(old('move_in_date', $resident->move_in_date ? $resident->move_in_date->format('Y-m-d') : '')); ?>"
                                   class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-black text-gray-900 focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all outline-none"
                                   required>
                            <?php $__errorArgs = ['move_in_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-[10px] font-black uppercase tracking-widest mt-1 ml-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Resident Status</label>
                            <div class="relative">
                                <select name="status"
                                        class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-black text-gray-900 focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all outline-none appearance-none cursor-pointer"
                                        required>
                                    <option value="active" <?php echo e(old('status', $resident->status) === 'active' ? 'selected' : ''); ?>>ACTIVE</option>
                                    <option value="inactive" <?php echo e(old('status', $resident->status) === 'inactive' ? 'selected' : ''); ?>>INACTIVE</option>
                                </select>
                                <i class="bi bi-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                            </div>
                            <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-[10px] font-black uppercase tracking-widest mt-1 ml-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">
                                Update Password 
                                <span class="font-black text-emerald-500/40 ml-2">(LEAVE BLANK TO KEEP CURRENT)</span>
                            </label>
                            <input type="password" name="password"
                                   class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-black text-gray-900 focus:bg-white focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all outline-none"
                                   placeholder="••••••••">
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-[10px] font-black uppercase tracking-widest mt-1 ml-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    
                    <div class="flex flex-col sm:flex-row justify-end gap-4 mt-16 pt-10 border-t border-gray-50">
                        <a href="<?php echo e(route('resident.profile.index')); ?>" 
                           class="px-10 py-4 bg-gray-50 text-gray-400 text-sm font-black uppercase tracking-widest rounded-2xl hover:bg-gray-100 hover:text-gray-900 transition-all duration-300 text-center">
                            Cancel
                        </a>
                        <button type="submit"
                                class="px-12 py-4 bg-emerald-500 text-white text-sm font-black uppercase tracking-widest rounded-2xl hover:bg-emerald-400 hover:shadow-[0_20px_40px_rgba(16,185,129,0.2)] transition-all duration-300 transform active:scale-95">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
</style>


<script>
document.getElementById('photoInput').addEventListener('change', function(event) {
    const [file] = event.target.files;
    if (file) {
        document.getElementById('photoPreview').src = URL.createObjectURL(file);
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('resident.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views/resident/profile/edit.blade.php ENDPATH**/ ?>