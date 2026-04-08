


<div class="w-16 flex-shrink-0 flex flex-col items-center bg-white border border-gray-200 rounded-2xl py-4 gap-2 overflow-y-auto hidden md:flex shadow-sm no-scrollbar">
    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Block</span>
    
    <button onclick="filterByBlock('')" 
        class="w-10 h-10 rounded-xl flex items-center justify-center text-sm font-bold transition-all
        <?php echo e(request('block') == '' ? 'bg-gray-800 text-white shadow-lg' : 'bg-gray-50 text-gray-600 hover:bg-gray-100'); ?>">
        All
    </button>

    <?php $__currentLoopData = $blocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $block): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <button onclick="filterByBlock('<?php echo e($block); ?>')"
        class="w-10 h-10 rounded-xl flex items-center justify-center text-sm font-bold transition-all
        <?php echo e(request('block') == $block ? 'bg-blue-600 text-white shadow-lg' : 'bg-white border border-gray-200 text-gray-600 hover:border-blue-400 hover:text-blue-600'); ?>">
        <?php echo e($block); ?>

    </button>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\residents\partials\sidebar.blade.php ENDPATH**/ ?>