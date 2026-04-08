
<div id="scrollContainer" class="h-[70vh] overflow-y-auto">

    
    <div id="listView">
        <div class="overflow-x-auto bg-white rounded-2xl shadow border border-gray-100">
            <table class="min-w-full text-sm text-left border-collapse">
                <thead class="bg-gray-100 text-gray-700 font-semibold rounded-t-2xl">
                    <tr>
                        
                        <th class="resident-checkbox-col hidden px-4 py-3 border-b rounded-tl-2xl">
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                        </th>

                        <th class="px-6 py-3 border-b">#</th>
                        <th class="px-6 py-3 border-b">Name</th>
                        <th class="px-6 py-3 border-b">Email</th>
                        <th class="px-6 py-3 border-b">Contact</th>
                        <th class="px-6 py-3 border-b">Block & Lot</th>
                        <th class="px-6 py-3 border-b">Move-in Date</th>
                        <th class="px-6 py-3 border-b text-center rounded-tr-2xl">Actions</th>
                    </tr>
                </thead>

                
                <tbody id="residentsBody" class="text-gray-700 divide-y">
                    <?php $__empty_1 = true; $__currentLoopData = $residents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr onclick="openResidentDrawer(<?php echo e($user->id); ?>)"
                            class="hover:bg-gray-50 transition duration-150 cursor-pointer">

                            
                            <td class="resident-checkbox-col hidden px-4 py-3" onclick="event.stopPropagation()">
                                <input type="checkbox"
                                       class="resident-checkbox"
                                       value="<?php echo e($user->id); ?>"
                                       onchange="updateBulkAction()">
                            </td>

                            <td class="px-6 py-3">
                                <?php echo e($loop->iteration + ($residents->currentPage() - 1) * $residents->perPage()); ?>

                            </td>

                            <td class="px-6 py-3 font-medium text-gray-900"><?php echo e($user->name); ?></td>

                            <td class="px-6 py-3 text-gray-600 truncate"><?php echo e($user->email); ?></td>

                            <td class="px-6 py-3 text-gray-600">
                                <?php if($user->resident?->contact_number): ?>
                                    <?php echo e($user->resident->contact_number); ?>

                                <?php else: ?>
                                    <span class="text-gray-400 italic">—</span>
                                <?php endif; ?>
                            </td>

                            <td class="px-6 py-3">
                                <?php if($user->resident?->block_lot): ?>
                                    <span class="inline-block px-2 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-full">
                                        <?php echo e($user->resident->block_lot); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-gray-400 italic">—</span>
                                <?php endif; ?>
                            </td>

                            <td class="px-6 py-3 text-gray-600">
                                <?php if($user->resident?->move_in_date): ?>
                                    <?php echo e(\Carbon\Carbon::parse($user->resident->move_in_date)->format('M d, Y')); ?>

                                <?php else: ?>
                                    <span class="text-gray-400 italic">Not set</span>
                                <?php endif; ?>
                            </td>

                            
                            <td class="px-6 py-3 text-center" onclick="event.stopPropagation()">
                                <div class="flex justify-center gap-2">
                                    <a href="<?php echo e(route('admin.residents.edit', $user->id)); ?>"
                                       class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-semibold hover:bg-blue-100 hover:shadow transition">
                                        Edit
                                    </a>

                                    <form action="<?php echo e(route('admin.residents.destroy', $user->id)); ?>" method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this resident?')" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit"
                                                class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-semibold hover:bg-red-100 hover:shadow transition">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="px-6 py-6 text-center text-gray-500 italic">
                                No residents found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div id="gridView" class="hidden p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php $__currentLoopData = $residents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div onclick="openResidentDrawer(<?php echo e($user->id); ?>)"
                 class="bg-white p-4 rounded-xl shadow border border-gray-100 hover:shadow-lg transition cursor-pointer">
                <h3 class="font-bold text-gray-900"><?php echo e($user->name); ?></h3>
                <p class="text-sm text-gray-600"><?php echo e($user->email); ?></p>
                <p class="text-xs text-gray-500 mt-1">
                    <?php echo e($user->resident?->block_lot ?? 'No block/lot'); ?>

                </p>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div id="loadMoreContainer" class="text-center py-4">
        <?php if($residents->hasMorePages()): ?>
            <button id="loadMoreBtn"
                    onclick="loadMore()"
                    class="px-4 py-2 bg-gray-100 rounded-xl hover:bg-gray-200 transition">
                Load More
            </button>
        <?php else: ?>
            <p class="text-xs text-gray-400 font-medium uppercase tracking-widest mt-2">End of List</p>
        <?php endif; ?>

        <div id="loadingSpinner" class="hidden mt-2">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-gray-900 mx-auto"></div>
        </div>
    </div>

</div>
<?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\residents\partials\table.blade.php ENDPATH**/ ?>