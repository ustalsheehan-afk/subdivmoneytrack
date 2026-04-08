<?php $__env->startSection('title', 'Edit Penalty'); ?>
<?php $__env->startSection('page-title', 'Edit Penalty'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8 animate-fade-in pb-20" x-data="penaltyForm()">

    
    
    
    <div class="glass-card p-8 relative overflow-hidden group">
        
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-brand-accent/5 rounded-full blur-3xl group-hover:bg-brand-accent/10 transition-all duration-700"></div>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div class="flex items-center gap-6">
                <a href="<?php echo e(route('admin.penalties.index')); ?>" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white border border-gray-100 text-gray-400 hover:text-emerald-600 hover:border-emerald-100 hover:shadow-sm transition-all shadow-sm">
                    <i class="bi bi-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                        Edit Penalty
                    </h1>
                    <p class="mt-2 text-gray-600 text-lg max-w-xl">
                        Update penalty records and enforcement status for <span class="font-black text-emerald-600"><?php echo e($penalty->resident->full_name); ?></span>.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" form="edit-penalty-form" class="btn-premium">
                    <i class="bi bi-check2-circle"></i>
                    Update Penalty
                </button>
            </div>
        </div>
    </div>

    <form id="edit-penalty-form" action="<?php echo e(route('admin.penalties.update', $penalty->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="grid grid-cols-1 lg:grid-cols-10 gap-8">
            
            <div class="lg:col-span-7 space-y-8">
                <div class="glass-card p-8 space-y-8 relative overflow-hidden group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-xl border border-emerald-100 shadow-sm">
                            <i class="bi bi-info-circle"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-black text-gray-900 tracking-tight">Penalty Information</h4>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Core violation and timing details</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-8">
                        
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Reason / Description</label>
                            <input type="text" name="reason" value="<?php echo e(old('reason', $penalty->reason)); ?>" 
                                class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm font-medium focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all outline-none" 
                                placeholder="Enter penalty reason..." required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Penalty Type</label>
                                <div class="relative group/select">
                                    <select name="type" x-model="penaltyType" class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm font-medium appearance-none focus:bg-white focus:border-emerald-500 transition-all outline-none cursor-pointer">
                                        <option value="general">General</option>
                                        <option value="late_payment">Late Payment</option>
                                        <option value="overdue">Overdue</option>
                                        <option value="violation">Violation</option>
                                        <option value="damage">Damage</option>
                                    </select>
                                    <i class="bi bi-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none group-hover/select:text-emerald-600 transition-colors"></i>
                                </div>
                            </div>

                            
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Date Issued</label>
                                <input type="date" name="date_issued" value="<?php echo e(old('date_issued', $penalty->date_issued ? \Carbon\Carbon::parse($penalty->date_issued)->format('Y-m-d') : '')); ?>" 
                                    class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm font-medium focus:bg-white focus:border-emerald-500 transition-all outline-none">
                            </div>
                        </div>
                    </div>

                    <div class="pt-8 border-t border-gray-50">
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Financial Amount</label>
                            <div class="relative max-w-xs group/input">
                                <span class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 font-black text-lg transition-colors group-focus-within/input:text-emerald-600">₱</span>
                                <input type="number" step="0.01" name="amount" x-model="amount" 
                                    class="w-full pl-12 pr-6 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-lg font-black text-gray-900 focus:bg-white focus:border-emerald-500 transition-all outline-none shadow-sm" 
                                    placeholder="0.00" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="lg:col-span-3 space-y-8">
                
                <div class="glass-card p-8 space-y-6 relative overflow-hidden group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-lg border border-emerald-100 shadow-sm">
                            <i class="bi bi-person"></i>
                        </div>
                        <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest">Resident</h4>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="relative group/select">
                            <select name="resident_id" class="w-full px-5 py-4 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm font-black appearance-none focus:bg-white focus:border-emerald-500 outline-none transition-all cursor-pointer" required>
                                <?php $__currentLoopData = $residents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($resident->id); ?>" <?php echo e(old('resident_id', $penalty->resident_id) == $resident->id ? 'selected' : ''); ?>>
                                        <?php echo e($resident->full_name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <i class="bi bi-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none group-hover/select:text-emerald-600 transition-colors"></i>
                        </div>

                        <?php if($penalty->resident): ?>
                        <div class="p-5 bg-gray-900 rounded-2xl border border-white/10 shadow-xl group/info">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-xs font-black text-emerald-500 shadow-inner group-hover/info:scale-110 transition-transform duration-500">
                                    <?php echo e(strtoupper(substr($penalty->resident->first_name, 0, 1))); ?><?php echo e(strtoupper(substr($penalty->resident->last_name, 0, 1))); ?>

                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-emerald-400 uppercase tracking-widest leading-none mb-1.5">Property Location</p>
                                    <p class="text-[11px] font-black text-white uppercase tracking-tight">Block <?php echo e($penalty->resident->block); ?> / Lot <?php echo e($penalty->resident->lot); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="glass-card p-8 space-y-6 relative overflow-hidden group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-lg border border-emerald-100 shadow-sm">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest">Enforcement</h4>
                    </div>

                    <div class="flex p-1.5 bg-gray-100 rounded-[20px] border border-gray-200 shadow-inner">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="status" value="unpaid" class="peer hidden" <?php echo e(old('status', $penalty->status) == 'unpaid' ? 'checked' : ''); ?>>
                            <div class="py-3 text-center text-[10px] font-black uppercase tracking-widest rounded-[16px] transition-all peer-checked:bg-white peer-checked:text-red-600 peer-checked:shadow-lg text-gray-400 hover:text-gray-600">
                                Unpaid
                            </div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="status" value="paid" class="peer hidden" <?php echo e(old('status', $penalty->status) == 'paid' ? 'checked' : ''); ?>>
                            <div class="py-3 text-center text-[10px] font-black uppercase tracking-widest rounded-[16px] transition-all peer-checked:bg-white peer-checked:text-emerald-600 peer-checked:shadow-lg text-gray-400 hover:text-gray-600">
                                Paid
                            </div>
                        </label>
                    </div>
                </div>

                
                <div class="space-y-3 pt-4">
                    <button type="submit" class="btn-premium w-full py-5 text-sm">
                        <i class="bi bi-check2-circle"></i>
                        Save Changes
                    </button>
                    <a href="<?php echo e(route('admin.penalties.index')); ?>" class="btn-secondary w-full py-5 text-sm justify-center">
                        Discard Changes
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
<?php $__env->startPush('scripts'); ?>
<script>
    function penaltyForm() {
        return {
            penaltyType: '<?php echo e(old('type', $penalty->type)); ?>',
            amount: '<?php echo e(old('amount', $penalty->amount)); ?>',
            init() {
                // Watch penalty type to sync standard amounts
                this.$watch('penaltyType', (newVal) => {
                    const amounts = {
                        'late_payment': 50.00,
                        'overdue': 100.00,
                        'violation': 200.00,
                        'damage': 500.00,
                        'general': 50.00
                    };
                    
                    if (amounts[newVal]) {
                        this.amount = amounts[newVal].toFixed(2);
                    }
                });
            }
        }
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views/admin/penalties/edit.blade.php ENDPATH**/ ?>