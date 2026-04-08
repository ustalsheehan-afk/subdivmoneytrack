<?php $__currentLoopData = $residents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr onclick="openResidentDrawer(<?php echo e($resident->id); ?>)"
    id="resident-row-<?php echo e($resident->id); ?>"
    data-id="<?php echo e($resident->id); ?>"
    class="hover:bg-gray-50 cursor-pointer transition-all group border-b border-gray-100 last:border-0 shadow-sm hover:shadow-md">

    
    <td class="p-4 text-center w-12 resident-checkbox-col hidden" onclick="event.stopPropagation()">
        <input type="checkbox" value="<?php echo e($resident->id); ?>" class="resident-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500 transition-colors w-4 h-4" onchange="updateBulkAction()">
    </td>

    
    <td class="p-4 text-center w-20">
        <img 
            src="<?php echo e($resident->photo ? asset('storage/' . $resident->photo) : asset('CDlogo.jpg')); ?>"
            onerror="this.onerror=null; this.src='<?php echo e(asset('CDlogo.jpg')); ?>';"
            class="w-10 h-10 rounded-full object-cover mx-auto ring-2 ring-gray-100 group-hover:ring-blue-200 transition-all duration-300 transform group-hover:scale-105"
            alt="<?php echo e($resident->first_name); ?> <?php echo e($resident->last_name); ?>"
            title="Click to view full image"
            onclick="event.stopPropagation(); window.open(this.src,'_blank')">
    </td>

    
    <td class="p-4 font-bold text-gray-900 group-hover:text-blue-600 transition-colors duration-300 whitespace-nowrap">
        <?php echo e($resident->first_name); ?> <?php echo e($resident->last_name); ?>

    </td>

    
    <td class="p-4 text-gray-600 text-sm whitespace-nowrap"><?php echo e($resident->email); ?></td>

    
    <td class="p-4 text-gray-600 text-sm font-mono whitespace-nowrap"><?php echo e($resident->contact_number); ?></td>

    
    <td class="p-4 text-gray-600 text-sm text-center font-mono whitespace-nowrap">
        <span class="bg-gray-50 px-2 py-1 rounded-lg border border-gray-100 font-bold text-gray-700 block mx-auto w-12"><?php echo e($resident->block ?? '-'); ?></span>
    </td>

    
    <td class="p-4 text-center text-gray-600 text-sm whitespace-nowrap">
        <?php echo e($resident->move_in_date ? $resident->move_in_date->format('M d, Y') : '-'); ?>

    </td>

    
    <td class="p-4 text-center whitespace-nowrap">
        <?php
            $statusClasses = $resident->status === 'active' 
                ? 'bg-gradient-to-r from-green-100 to-green-200 text-green-800 border-green-300 shadow-sm'
                : 'bg-gray-100 text-gray-600 border-gray-200';
        ?>
        <span class="px-3 py-1 rounded-full text-xs font-bold border <?php echo e($statusClasses); ?> transition-all duration-300">
            <?php echo e(ucfirst($resident->status)); ?>

        </span>
    </td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\residents\partials\rows.blade.php ENDPATH**/ ?>