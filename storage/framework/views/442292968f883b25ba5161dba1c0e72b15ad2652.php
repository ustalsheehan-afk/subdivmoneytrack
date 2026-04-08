

<?php $__env->startSection('title', 'My Profile'); ?>
<?php $__env->startSection('page-title', 'My Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="h-full bg-[#F8F9FB] overflow-y-auto custom-scrollbar">
    <div class="max-w-5xl mx-auto px-6 py-8 flex flex-col gap-10 pb-24 animate-fade-in">

        
        
        
        <div class="relative overflow-hidden bg-[#081412] rounded-[40px] p-10 shadow-2xl group">
            
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl group-hover:bg-emerald-500/20 transition-all duration-1000"></div>
            <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl"></div>

            <div class="relative z-10 flex flex-col md:flex-row gap-10 items-center md:items-start">
                
                
                <div class="flex flex-col items-center shrink-0">
                    <div class="relative group/photo">
                        <div class="absolute inset-0 bg-emerald-500/20 rounded-full blur-xl group-hover/photo:bg-emerald-500/40 transition-all duration-500"></div>
                        <img
                            src="<?php echo e(($resident && $resident->photo) ? asset('storage/' . $resident->photo) : asset('CDlogo.jpg')); ?>"
                            onerror="this.onerror=null; this.src='<?php echo e(asset('CDlogo.jpg')); ?>';"
                            alt="Profile Photo"
                            class="w-40 h-40 rounded-full object-cover border-4 border-white/10 shadow-2xl relative z-10 bg-[#0D1F1C]"
                        >
                        <a href="<?php echo e(route('resident.profile.edit')); ?>" 
                           class="absolute bottom-2 right-2 w-10 h-10 bg-emerald-500 text-black rounded-2xl flex items-center justify-center shadow-2xl hover:bg-emerald-400 hover:scale-110 transition-all z-20 border-4 border-[#081412]" 
                           title="Edit Profile">
                            <i class="bi bi-pencil-fill text-sm"></i>
                        </a>
                    </div>
                    
                    <div class="mt-6 text-center">
                        <h2 class="text-3xl font-black text-white tracking-tight leading-none"><?php echo e($resident->first_name ?? 'Resident'); ?> <?php echo e($resident->last_name ?? ''); ?></h2>
                        <div class="mt-3 inline-flex items-center gap-2 px-4 py-1.5 rounded-xl border <?php echo e(($resident && $resident->status === 'active') ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20 shadow-[0_0_15px_rgba(16,185,129,0.1)]' : 'bg-red-500/10 text-red-400 border-red-500/20'); ?>">
                            <span class="w-1.5 h-1.5 rounded-full <?php echo e(($resident && $resident->status === 'active') ? 'bg-emerald-400 animate-pulse' : 'bg-red-400'); ?>"></span>
                            <span class="text-[10px] font-black uppercase tracking-widest"><?php echo e(ucfirst($resident->status ?? 'unknown')); ?></span>
                        </div>
                    </div>
                </div>

                
                <div class="flex-1 w-full grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <?php
                        $infoCards = [
                            ['icon'=>'bi-telephone-fill', 'label'=>'Contact Number', 'value'=>$resident->contact_number],
                            ['icon'=>'bi-envelope-fill', 'label'=>'Email Address', 'value'=>$resident->email],
                            ['icon'=>'bi-geo-alt-fill', 'label'=>'Address', 'value'=>'Block ' . ($resident->block ?? '-') . ' / Lot ' . ($resident->lot ?? '-')],
                            ['icon'=>'bi-calendar-check-fill', 'label'=>'Resident Since', 'value'=>$resident->move_in_date ? $resident->move_in_date->format('M d, Y') : '-'],
                        ];
                    ?>

                    <?php $__currentLoopData = $infoCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-start gap-5 p-6 rounded-[28px] bg-white/5 border border-white/5 hover:bg-white/10 transition-all duration-300 group/card">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/20 border border-emerald-400 group-hover/card:scale-110 transition-transform shrink-0">
                            <i class="bi <?php echo e($card['icon']); ?> text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-black text-white/30 uppercase tracking-[0.2em] mb-1.5"><?php echo e($card['label']); ?></p>
                            <p class="text-sm font-black text-white tracking-tight truncate"><?php echo e($card['value']); ?></p>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        
        
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            
            <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm p-10 relative overflow-hidden group/account">
                <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-emerald-500/5 rounded-full blur-3xl group-hover/account:bg-emerald-500/10 transition-all duration-700"></div>
                
                <div class="flex items-center gap-4 mb-10 relative z-10">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center border border-emerald-100 shadow-sm">
                        <i class="bi bi-shield-check text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-gray-900 text-xl tracking-tight">Account Integrity</h3>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Financial & Membership Standing</p>
                    </div>
                </div>

                <div class="space-y-5 relative z-10">
                    <div class="flex justify-between items-center p-6 rounded-[24px] bg-gray-50 border border-gray-100 hover:bg-white hover:shadow-xl transition-all duration-500 group">
                        <span class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Payment Status</span>
                        <?php
                            $isGoodStanding = $resident->payment_status === 'Good Standing';
                        ?>
                        <span class="inline-flex items-center px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm
                            <?php echo e($isGoodStanding ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-amber-50 text-amber-600 border border-amber-100'); ?>">
                            <i class="bi <?php echo e($isGoodStanding ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill'); ?> mr-2"></i>
                            <?php echo e($resident->payment_status); ?>

                        </span>
                    </div>

                    <div class="flex justify-between items-center p-6 rounded-[24px] bg-gray-50 border border-gray-100 hover:bg-white hover:shadow-xl transition-all duration-500">
                        <span class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Membership Type</span>
                        <span class="text-sm font-black text-gray-900 tracking-tight">
                            <?php echo e($resident->membership_type ?? 'Regular Member'); ?>

                        </span>
                    </div>

                    <div class="flex justify-between items-center p-6 rounded-[24px] bg-gray-50 border border-gray-100 hover:bg-white hover:shadow-xl transition-all duration-500">
                        <span class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Member Since</span>
                        <span class="text-sm font-black text-gray-900 tracking-tight"><?php echo e($resident->move_in_date ? $resident->move_in_date->year : '-'); ?></span>
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm p-10 relative overflow-hidden group/property">
                <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-emerald-500/5 rounded-full blur-3xl group-hover/property:bg-emerald-500/10 transition-all duration-700"></div>

                <div class="flex items-center gap-4 mb-10 relative z-10">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center border border-emerald-100 shadow-sm">
                        <i class="bi bi-houses-fill text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-gray-900 text-xl tracking-tight">Property Asset</h3>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Home & Lot Information</p>
                    </div>
                </div>

                <div class="space-y-5 relative z-10">
                    <div class="flex justify-between items-center p-6 rounded-[24px] bg-gray-50 border border-gray-100 hover:bg-white hover:shadow-xl transition-all duration-500">
                        <span class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Property Type</span>
                        <span class="text-sm font-black text-gray-900 tracking-tight"><?php echo e($resident->property_type ?? 'Residential House & Lot'); ?></span>
                    </div>

                    <div class="flex justify-between items-center p-6 rounded-[24px] bg-gray-50 border border-gray-100 hover:bg-white hover:shadow-xl transition-all duration-500">
                        <span class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Lot Area</span>
                        <div class="text-right">
                            <span class="text-lg font-black text-gray-900 tracking-tight tabular-nums"><?php echo e($resident->lot_area ? number_format($resident->lot_area, 0) : '0'); ?></span>
                            <span class="text-[9px] font-black text-gray-400 uppercase ml-1">SQ.M</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center p-6 rounded-[24px] bg-gray-50 border border-gray-100 hover:bg-white hover:shadow-xl transition-all duration-500">
                        <span class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Floor Area</span>
                        <div class="text-right">
                            <span class="text-lg font-black text-gray-900 tracking-tight tabular-nums"><?php echo e($resident->floor_area ? number_format($resident->floor_area, 0) : '0'); ?></span>
                            <span class="text-[9px] font-black text-gray-400 uppercase ml-1">SQ.M</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('resident.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\resident\profile\index.blade.php ENDPATH**/ ?>