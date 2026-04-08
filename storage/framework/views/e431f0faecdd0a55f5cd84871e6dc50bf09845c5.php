<div class="h-full flex flex-col bg-white">
    
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-white sticky top-0 z-10">
        <div>
            <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Penalty Details</h2>
            <p class="text-lg font-bold text-gray-900">
                #<?php echo e(str_pad($penalty->id, 5, '0', STR_PAD_LEFT)); ?>

            </p>
        </div>
        <button onclick="closePenaltyDrawer()" class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-gray-100 transition">
            <i class="bi bi-x-lg text-gray-500"></i>
        </button>
    </div>

    
    <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-gray-50 custom-scrollbar">
        
        
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex flex-col items-center text-center">
                <div class="relative p-1 rounded-full bg-gradient-to-tr from-[#800020]/30 to-transparent">
                    <img src="<?php echo e($penalty->resident->photo && Storage::disk('public')->exists($penalty->resident->photo) ? Storage::disk('public')->url($penalty->resident->photo) : asset('CDlogo.jpg')); ?>"
                         class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md">
                </div>

                <h3 class="mt-3 text-xl font-bold text-gray-900">
                    <?php echo e($penalty->resident->full_name ?? 'Unknown'); ?>

                </h3>
                <p class="text-sm text-gray-500">Block <?php echo e($penalty->resident->block ?? '-'); ?> Lot <?php echo e($penalty->resident->lot ?? '-'); ?></p>
                
                <div class="flex gap-3 mt-4">
                    <a href="<?php echo e(route('admin.residents.show', $penalty->resident->id)); ?>"
                       class="text-sm font-semibold text-[#800020] hover:underline flex items-center gap-1">
                        View Profile <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        
        <div>
             <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Penalty Overview</p>
                <?php
                    $statusColors = [
                        'paid' => 'bg-green-100 text-green-700',
                        'unpaid' => 'bg-red-100 text-red-700',
                        'pending' => 'bg-yellow-100 text-yellow-700',
                    ];
                    $colorClass = $statusColors[$penalty->status] ?? 'bg-gray-100 text-gray-700';
                ?>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wide <?php echo e($colorClass); ?>">
                    <?php echo e(ucfirst($penalty->status)); ?>

                </span>
             </div>
            <div class="grid grid-cols-2 gap-3">
                <div class="p-4 rounded-xl bg-white border border-gray-100 shadow-sm text-center">
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-1">Type</p>
                    <p class="text-sm font-bold text-gray-900 uppercase">
                        <?php echo e(str_replace('_', ' ', $penalty->type)); ?>

                    </p>
                </div>

                <div class="p-4 rounded-xl bg-red-50 border border-red-100 text-center">
                    <p class="text-xs text-red-600 font-medium uppercase tracking-wide mb-1">Amount</p>
                    <p class="text-lg font-bold text-red-700 font-mono">₱<?php echo e(number_format($penalty->amount, 2)); ?></p>
                </div>
            </div>
        </div>

        
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Penalty Information</p>
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm space-y-4">
                 <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Reason / Description</p>
                    <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap bg-gray-50 p-3 rounded-lg border border-gray-100">
                        <?php echo e($penalty->reason ?? 'No description provided.'); ?>

                    </p>
                </div>
                <div class="pt-4 border-t border-gray-100 grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Date Issued</p>
                        <p class="text-sm font-bold text-gray-900"><?php echo e($penalty->date_issued ? $penalty->date_issued->format('M d, Y') : '-'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        
        <?php if($penalty->payment): ?>
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Related Payment</p>
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-xs font-semibold text-blue-700 uppercase">Payment Ref</span>
                    <a href="#" class="text-xs text-blue-600 hover:underline">ID: <?php echo e($penalty->payment->id); ?></a>
                </div>
                <p class="font-mono text-gray-900 font-bold text-lg"><?php echo e($penalty->payment->or_number ?? 'No OR Number'); ?></p>
                <p class="text-xs text-gray-500 mt-1">Paid on <?php echo e($penalty->payment->date_paid ? $penalty->payment->date_paid->format('M d, Y') : '-'); ?></p>
            </div>
        </div>
        <?php endif; ?>
    </div>

    
    <div class="p-6 border-t border-gray-100 bg-white sticky bottom-0 z-10 flex gap-3">
        <a href="<?php echo e(route('admin.penalties.edit', $penalty->id)); ?>" class="flex-1 bg-white border border-gray-300 text-gray-700 font-bold py-3 rounded-xl hover:bg-gray-50 transition shadow-sm text-center">
            Edit Penalty
        </a>
        <form action="<?php echo e(route('admin.penalties.destroy', $penalty->id)); ?>" method="POST" class="flex-1" onsubmit="return confirm('Are you sure you want to delete this penalty?');">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="submit" class="w-full bg-[#800020] text-white font-bold py-3 rounded-xl hover:bg-[#600018] transition shadow-lg hover:shadow-xl">
                Delete
            </button>
        </form>
    </div>
</div>
<?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\penalties\partials\drawer.blade.php ENDPATH**/ ?>