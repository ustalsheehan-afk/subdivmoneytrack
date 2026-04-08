<?php $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php
    // Status Styles (matching system-wide uniform design)
    $statusStyles = match($req->status) {
        'pending' => ['bg' => 'bg-gray-50', 'text' => 'text-gray-700', 'border' => 'border-gray-200', 'dot' => 'bg-gray-500'],
        'in progress' => ['bg' => 'bg-emerald-50/50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-100', 'dot' => 'bg-emerald-500'],
        'completed' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'border' => 'border-emerald-100', 'dot' => 'bg-emerald-500'],
        'rejected' => ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'border' => 'border-red-100', 'dot' => 'bg-red-500'],
        default => ['bg' => 'bg-gray-50', 'text' => 'text-gray-700', 'border' => 'border-gray-200', 'dot' => 'bg-gray-500'],
    };

    // Priority Styles
    $priorityClass = match(strtolower($req->priority)) {
        'high' => 'text-red-600 bg-red-50 border-red-100',
        'medium' => 'text-orange-600 bg-orange-50 border-orange-100',
        'low' => 'text-emerald-600 bg-emerald-50 border-emerald-100',
        default => 'text-gray-600 bg-gray-50 border-gray-100',
    };

    // Data for Drawer
    $requestData = [
        'id' => $req->id,
        'customTitle' => $req->type,
        'type' => $req->type,
        'resident_initials' => substr($req->resident?->first_name ?? '?', 0, 1) . substr($req->resident?->last_name ?? '?', 0, 1),
        'resident_name' => $req->resident?->full_name ?? 'Unknown Resident',
        'resident_property' => 'Block ' . ($req->resident?->block ?? '-') . ' Lot ' . ($req->resident?->lot ?? '-'),
        'resident_contact' => $req->resident?->contact_number ?? 'No contact info',
        'status' => $req->status,
        'status_text' => ucfirst($req->status),
        'priority_text' => ucfirst($req->priority),
        'date' => $req->created_at->format('M d, Y h:i A'),
        'description' => $req->description,
        'photo_url' => $req->photo ? asset('storage/' . $req->photo) : null,
        'update_url' => route('admin.requests.updateStatus', $req->id),
        'view_url' => route('admin.requests.show', $req->id),
    ];
?>

<tr onclick="loadRequestDetails(<?php echo e(json_encode($requestData)); ?>)" 
    class="cursor-pointer hover:bg-emerald-50/20 transition-all duration-200 group border-l-4 border-transparent">
    
    
    <td class="p-6 align-middle text-left">
        <div class="flex items-center gap-4">
            <div class="relative shrink-0">
                <img 
                    src="<?php echo e($req->resident?->photo ? asset('storage/' . $req->resident->photo) : asset('CDlogo.jpg')); ?>"
                    onerror="this.onerror=null; this.src='<?php echo e(asset('CDlogo.jpg')); ?>';"
                    class="h-10 w-10 rounded-xl object-cover ring-4 ring-white shadow-sm group-hover:ring-emerald-50 transition-all duration-300"
                    alt="<?php echo e($req->resident?->full_name ?? 'Resident'); ?>">
                <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-white flex items-center justify-center shadow-sm">
                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                </div>
            </div>
            <div class="min-w-0">
                <p class="text-sm font-black text-gray-900 group-hover:text-brand-accent transition truncate"><?php echo e($req->resident?->full_name ?? 'Unknown Resident'); ?></p>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">Blk <?php echo e($req->resident?->block ?? '-'); ?> • Lot <?php echo e($req->resident?->lot ?? '-'); ?></p>
            </div>
        </div>
    </td>

    
    <td class="p-6 text-center align-middle">
        <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100"><?php echo e($req->type); ?></span>
    </td>

    
    <td class="p-6 text-center align-middle">
        <div class="flex flex-col items-center">
            <span class="text-sm font-black text-gray-900 tracking-tight"><?php echo e($req->created_at->format('M d, Y')); ?></span>
            <span class="text-[10px] font-bold text-gray-400 uppercase mt-0.5"><?php echo e($req->created_at->diffForHumans()); ?></span>
        </div>
    </td>

    
    <td class="p-6 text-center align-middle">
        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-[10px] font-black border uppercase tracking-widest <?php echo e($priorityClass); ?>">
            <?php echo e(ucfirst($req->priority)); ?>

        </span>
    </td>

    
    <td class="p-6 text-center align-middle">
        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-[10px] font-black border uppercase tracking-widest <?php echo e($statusStyles['bg']); ?> <?php echo e($statusStyles['text']); ?> <?php echo e($statusStyles['border']); ?>">
            <span class="w-1.5 h-1.5 rounded-full <?php echo e($statusStyles['dot']); ?>"></span>
            <?php echo e($req->status); ?>

        </span>
    </td>

    
    <td class="p-6 text-right align-middle">
        <i class="bi bi-chevron-right text-gray-300 group-hover:text-brand-accent transition-colors"></i>
    </td>

</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\requests\partials\list.blade.php ENDPATH**/ ?>