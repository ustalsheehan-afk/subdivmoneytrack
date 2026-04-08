<?php $__env->startSection('title', 'Add Amenity'); ?>
<?php $__env->startSection('page-title', 'Add New Amenity'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8 animate-fade-in pb-20">

    
    
    
    <div class="glass-card p-8 relative overflow-hidden group">
        
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-brand-accent/5 rounded-full blur-3xl group-hover:bg-brand-accent/10 transition-all duration-700"></div>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div class="flex items-center gap-6">
                <a href="<?php echo e(route('admin.amenities.index')); ?>" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white border border-gray-100 text-gray-400 hover:text-emerald-600 hover:border-emerald-100 hover:shadow-sm transition-all shadow-sm">
                    <i class="bi bi-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                        Add New Amenity
                    </h1>
                    <p class="mt-2 text-gray-600 text-lg max-w-xl">
                        Create a new subdivision facility and configure its booking rules.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" form="amenity-form" class="btn-premium">
                    <i class="bi bi-check2-circle"></i>
                    Save Amenity
                </button>
            </div>
        </div>
    </div>

    <?php if($errors->any()): ?>
        <div class="glass-card border-red-100 bg-red-50/50 p-6 animate-fade-in">
            <div class="flex items-center gap-3 mb-4 text-red-700">
                <i class="bi bi-exclamation-circle-fill text-xl"></i>
                <span class="font-black text-sm uppercase tracking-widest">Validation Errors</span>
            </div>
            <ul class="space-y-2">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="text-sm font-bold text-red-600 flex items-center gap-2">
                        <span class="w-1 h-1 rounded-full bg-red-400"></span>
                        <?php echo e($error); ?>

                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form id="amenity-form" action="<?php echo e(route('admin.amenities.store')); ?>" method="POST" enctype="multipart/form-data" 
          x-data="{ 
              equipmentList: [],
              addEquipment() { this.equipmentList.push({ name: '', price: '' }); },
              removeEquipment(index) { this.equipmentList.splice(index, 1); },
              imagePreview: null,
              fileChosen(event) {
                  this.fileToDataUrl(event, src => this.imagePreview = src)
              },
              fileToDataUrl(event, callback) {
                  if (! event.target.files.length) return
                  let file = event.target.files[0],
                      reader = new FileReader()
                  reader.readAsDataURL(file)
                  reader.onload = e => callback(e.target.result)
              }
          }">
        <?php echo csrf_field(); ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-8">
                
                
                <section class="glass-card p-8 space-y-8 relative overflow-hidden group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-xl border border-emerald-100 shadow-sm">1</div>
                        <div>
                            <h4 class="text-xl font-black text-gray-900 tracking-tight">Basic Information</h4>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Identify and describe the facility</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-8">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Amenity Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="<?php echo e(old('name')); ?>" 
                                class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm font-medium focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all outline-none" 
                                placeholder="e.g. Community Swimming Pool" required>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Description</label>
                            <textarea name="description" rows="4" 
                                class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm font-medium focus:bg-white focus:border-emerald-500 transition-all outline-none resize-none" 
                                placeholder="Provide a brief description of the amenity and its features..."><?php echo e(old('description')); ?></textarea>
                        </div>
                    </div>
                </section>

                
                <section class="glass-card p-8 space-y-8 relative overflow-hidden group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-xl border border-emerald-100 shadow-sm">2</div>
                        <div>
                            <h4 class="text-xl font-black text-gray-900 tracking-tight">Availability & Capacity</h4>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Set operational constraints</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Operational Days <span class="text-red-500">*</span></label>
                            <div class="flex flex-wrap gap-2">
                                <?php $__currentLoopData = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="cursor-pointer group">
                                        <input type="checkbox" name="days_available[]" value="<?php echo e($day); ?>" class="peer sr-only"
                                            <?php echo e(in_array($day, old('days_available', ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'])) ? 'checked' : ''); ?>>
                                        <div class="px-4 py-2.5 rounded-xl border border-gray-100 text-[10px] font-black text-gray-400 bg-gray-50 group-hover:bg-gray-100 peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:border-emerald-600 peer-checked:shadow-sm transition-all text-center min-w-[3.5rem] uppercase tracking-widest">
                                            <?php echo e($day); ?>

                                        </div>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Available Slots <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-3 gap-2">
                                <?php $__currentLoopData = ['Morning', 'Afternoon', 'Evening']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="cursor-pointer group">
                                        <input type="checkbox" name="time_slots[]" value="<?php echo e($slot); ?>" class="peer sr-only"
                                               <?php echo e(in_array($slot, old('time_slots', ['Morning', 'Afternoon', 'Evening'])) ? 'checked' : ''); ?>>
                                        <div class="px-3 py-2.5 rounded-xl border border-gray-100 text-[10px] font-black text-gray-400 bg-gray-50 group-hover:bg-gray-100 peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:border-emerald-600 peer-checked:shadow-sm transition-all text-center uppercase tracking-widest">
                                            <?php echo e($slot); ?>

                                        </div>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4 border-t border-gray-50">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Max Capacity (PAX) <span class="text-red-500">*</span></label>
                            <div class="relative group/input">
                                <i class="bi bi-people absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within/input:text-emerald-600 transition-colors"></i>
                                <input type="number" name="max_capacity" value="<?php echo e(old('max_capacity', 1)); ?>" min="1" required
                                    class="w-full pl-12 pr-6 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm font-black focus:bg-white focus:border-emerald-500 transition-all outline-none tabular-nums">
                            </div>
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Buffer Time (Minutes)</label>
                            <div class="relative group/input">
                                <i class="bi bi-clock-history absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within/input:text-emerald-600 transition-colors"></i>
                                <input type="number" name="buffer_minutes" value="<?php echo e(old('buffer_minutes', 30)); ?>" min="0" step="5"
                                    class="w-full pl-12 pr-6 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm font-black focus:bg-white focus:border-emerald-500 transition-all outline-none tabular-nums">
                                <span class="absolute right-6 top-1/2 -translate-y-1/2 text-[10px] font-black text-gray-300 uppercase tracking-widest">MINS</span>
                            </div>
                        </div>
                    </div>
                </section>

                
                <section class="glass-card p-8 space-y-8 relative overflow-hidden group">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-xl border border-emerald-100 shadow-sm">3</div>
                            <div>
                                <h4 class="text-xl font-black text-gray-900 tracking-tight">Equipment Rental</h4>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Optional equipment for booking</p>
                            </div>
                        </div>
                        <button type="button" @click="addEquipment()" class="btn-secondary py-2 px-4 text-[10px]">
                            <i class="bi bi-plus-lg"></i>
                            Add Item
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <template x-for="(item, index) in equipmentList" :key="index">
                            <div class="flex items-center gap-3 bg-gray-50/50 p-4 rounded-2xl border border-gray-100 animate-fade-in group/item">
                                <div class="flex-1 grid grid-cols-2 gap-3">
                                    <input type="text" :name="`equipment[${index}][name]`" x-model="item.name" placeholder="EQUIPMENT NAME" required
                                           class="w-full px-4 py-3 rounded-xl border border-gray-100 bg-white text-[10px] font-black uppercase tracking-widest focus:border-emerald-500 transition-all outline-none">
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[10px] font-black text-gray-400">₱</span>
                                        <input type="number" :name="`equipment[${index}][price]`" x-model="item.price" placeholder="0.00" step="0.01" min="0" required
                                               class="w-full pl-8 pr-4 py-3 rounded-xl border border-gray-100 bg-white text-[10px] font-black uppercase tracking-widest focus:border-emerald-500 transition-all outline-none tabular-nums">
                                    </div>
                                </div>
                                <button type="button" @click="removeEquipment(index)" class="w-10 h-10 flex items-center justify-center rounded-xl text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </div>
                        </template>
                    </div>

                    <div x-show="equipmentList.length === 0" class="py-12 text-center border-2 border-dashed border-gray-100 rounded-3xl">
                        <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center mx-auto text-gray-200 mb-3">
                            <i class="bi bi-tools text-2xl"></i>
                        </div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">No rental equipment added</p>
                    </div>
                </section>
            </div>

            
            <div class="space-y-8">
                
                
                <div class="glass-card bg-gray-900 p-8 relative overflow-hidden group border-none">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all duration-700"></div>
                    
                    <div class="relative z-10 space-y-6">
                        <div class="space-y-2">
                            <p class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Financial Configuration</p>
                            <h4 class="text-xl font-black text-white tracking-tight leading-tight">Booking Rate</h4>
                        </div>
                        
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Amount (₱) <span class="text-emerald-500">*</span></label>
                            <div class="relative group/price">
                                <span class="absolute left-6 top-1/2 -translate-y-1/2 text-emerald-500 font-black text-2xl tracking-tighter transition-all group-focus-within/price:scale-110">₱</span>
                                <input type="number" name="price" value="<?php echo e(old('price', 0)); ?>" step="0.01" min="0" required
                                    class="w-full pl-14 pr-6 py-6 bg-white/5 border border-white/10 rounded-2xl text-3xl font-black text-white focus:bg-white/10 focus:border-emerald-500 transition-all outline-none tabular-nums">
                            </div>
                        </div>

                        <p class="text-[11px] font-medium text-gray-500 leading-relaxed">
                            This is the base price for booking one slot of this amenity.
                        </p>
                    </div>
                </div>

                
                <div class="glass-card p-8 space-y-8">
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Visibility Status</label>
                        <div class="relative group/select">
                            <select name="status" class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-[10px] font-black uppercase tracking-widest text-gray-700 appearance-none focus:bg-white focus:border-emerald-500 transition-all outline-none cursor-pointer">
                                <option value="active" <?php echo e(old('status')=='active'?'selected':''); ?>>Active (Public)</option>
                                <option value="maintenance" <?php echo e(old('status')=='maintenance'?'selected':''); ?>>Maintenance</option>
                                <option value="inactive" <?php echo e(old('status')=='inactive'?'selected':''); ?>>Inactive (Hidden)</option>
                            </select>
                            <i class="bi bi-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none group-hover/select:text-emerald-600 transition-colors"></i>
                        </div>
                    </div>

                    <label class="flex items-center gap-4 cursor-pointer group p-5 bg-emerald-50/30 rounded-2xl border border-emerald-100 hover:bg-emerald-50 transition-all shadow-sm">
                        <div class="relative flex items-center">
                            <input type="hidden" name="highlight" value="0">
                            <input type="checkbox" name="highlight" value="1" class="peer h-6 w-6 rounded-lg border-emerald-300 text-emerald-600 focus:ring-emerald-500/20 transition-all" <?php echo e(old('highlight') == '1' ? 'checked' : ''); ?>>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-emerald-900 uppercase tracking-widest">Featured Amenity</p>
                            <p class="text-[9px] font-bold text-emerald-600 uppercase tracking-tighter mt-0.5">Showcase on dashboard</p>
                        </div>
                    </label>
                </div>

                
                <div class="glass-card p-8 space-y-6">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Amenity Photo</label>
                    <div class="relative group/upload">
                        <div class="border-2 border-dashed border-gray-100 rounded-3xl p-8 text-center bg-gray-50/50 group-hover/upload:bg-emerald-50/30 group-hover/upload:border-emerald-200 transition-all duration-300">
                            <input type="file" name="image" accept="image/*" @change="fileChosen"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            
                            <div x-show="!imagePreview" class="space-y-3">
                                <div class="w-16 h-16 bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center mx-auto text-emerald-500 group-hover/upload:scale-110 transition-transform duration-500">
                                    <i class="bi bi-cloud-arrow-up text-2xl"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-gray-900 uppercase tracking-widest">Upload Image</p>
                                    <p class="text-[9px] font-bold text-gray-400 mt-1 uppercase tracking-tighter">PNG, JPG up to 5MB</p>
                                </div>
                            </div>

                            <div x-show="imagePreview" class="relative z-20 animate-fade-in">
                                <img :src="imagePreview" class="h-40 mx-auto rounded-2xl object-cover shadow-xl ring-8 ring-white/50">
                                <div class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/90 backdrop-blur-sm border border-gray-100 text-[9px] font-black text-gray-500 uppercase tracking-widest shadow-sm">
                                    <i class="bi bi-arrow-repeat"></i> Change Photo
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="glass-card p-8 space-y-6">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Facility Rules (PDF)</label>
                    <div class="p-5 bg-gray-50/50 rounded-2xl border border-gray-100 flex items-center gap-4 group/pdf">
                        <label class="shrink-0 cursor-pointer">
                            <input type="file" name="pdf_rules" accept="application/pdf" class="hidden" id="pdf_rules_input">
                            <div class="w-12 h-12 bg-white rounded-xl shadow-sm border border-gray-100 flex items-center justify-center text-gray-400 group-hover/pdf:text-emerald-500 group-hover/pdf:border-emerald-200 transition-all">
                                <i class="bi bi-file-earmark-pdf text-xl"></i>
                            </div>
                        </label>
                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] font-black text-gray-900 uppercase tracking-widest truncate" id="pdf-filename">No file selected</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter mt-0.5">Guidelines & Policies</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.getElementById('pdf_rules_input')?.addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name || 'No file selected';
        document.getElementById('pdf-filename').textContent = fileName;
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\amenities\create.blade.php ENDPATH**/ ?>