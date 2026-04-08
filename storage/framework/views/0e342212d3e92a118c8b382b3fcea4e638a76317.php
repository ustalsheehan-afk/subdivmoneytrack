
<div id="listView" class="block">
    <table class="w-full text-left border-collapse">
        <thead class="text-xs font-bold text-gray-900 uppercase tracking-wider sticky top-0 z-50 bg-white shadow-sm ring-1 ring-gray-900/5">
            <tr>
                <th class="p-4 text-center w-12 bg-white rounded-tl-xl resident-checkbox-col hidden">
                    <input type="checkbox" id="selectAll" onclick="toggleSelectAll()" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer w-4 h-4">
                </th>
                <th class="p-4 text-center w-20 bg-white">Photo</th>
                <th class="p-4 w-[20%] bg-white text-left">Name</th>
                <th class="p-4 w-[25%] bg-white text-left">Email</th>
                <th class="p-4 w-[15%] bg-white text-left">Contact</th>
                <th class="p-4 text-center w-[10%] bg-white">Block</th>
                <th class="p-4 text-center w-[10%] bg-white">Move-in</th>
                <th class="p-4 text-center w-28 bg-white rounded-tr-xl">Status</th>
            </tr>
        </thead>
        <tbody id="residentsBody" class="divide-y divide-gray-100 bg-white">
            <?php echo $__env->make('admin.residents.partials.rows', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </tbody>
    </table>
</div>


<div id="gridView" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 pb-6">
    <?php $__currentLoopData = $residents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-[0_4px_20px_-5px_rgba(0,0,0,0.05)] hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative group flex flex-col items-center text-center">
            <div class="absolute top-4 right-4 z-10 resident-checkbox-col hidden">
                <input type="checkbox" value="<?php echo e($resident->id); ?>" class="resident-checkbox w-5 h-5 rounded-md border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer shadow-sm" onchange="updateBulkAction()">
            </div>
            
            <div class="relative mb-5 mt-2">
                <img src="<?php echo e($resident->photo ? asset('storage/' . $resident->photo) : asset('CDlogo.jpg')); ?>" 
                     onerror="this.onerror=null; this.src='<?php echo e(asset('CDlogo.jpg')); ?>';"
                    class="w-24 h-24 rounded-2xl object-cover ring-4 ring-gray-50 group-hover:ring-blue-100 transition-all shadow-md">
                <span class="absolute -bottom-2 -right-2 w-6 h-6 border-4 border-white rounded-full <?php echo e($resident->status === 'active' ? 'bg-green-500' : 'bg-gray-400'); ?> shadow-sm"></span>
            </div>
            
            <h3 class="font-bold text-gray-900 text-lg mb-1"><?php echo e($resident->first_name); ?> <?php echo e($resident->last_name); ?></h3>
            <p class="text-sm text-gray-400 mb-4 truncate w-full px-4"><?php echo e($resident->email); ?></p>
            
            <div class="flex items-center gap-2 mb-6">
                <span class="px-3 py-1.5 rounded-xl bg-gray-50 text-gray-600 text-xs font-bold border border-gray-100">Blk <?php echo e($resident->block); ?></span>
                <span class="px-3 py-1.5 rounded-xl bg-gray-50 text-gray-600 text-xs font-bold border border-gray-100">Lot <?php echo e($resident->lot); ?></span>
            </div>

            <div class="w-full pt-4 border-t border-gray-50 flex justify-between items-center mt-auto">
                <div class="text-left">
                    <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold mb-0.5">Move In</p>
                    <p class="text-xs font-bold text-gray-700"><?php echo e($resident->move_in_date ? $resident->move_in_date->format('M d, Y') : '-'); ?></p>
                </div>
                <button onclick="openResidentDrawer(<?php echo e($resident->id); ?>)" 
                    class="text-blue-600 hover:text-white hover:bg-blue-600 text-xs font-bold px-4 py-2 rounded-xl transition-all duration-300 bg-blue-50">
                    View Profile
                </button>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<div class="p-6 text-center" id="loadMoreContainer">
    <?php if($residents->hasMorePages()): ?>
        <button onclick="loadMore()" id="loadMoreBtn" 
            class="px-6 py-2 bg-white border border-gray-200 text-gray-600 rounded-full text-sm font-medium hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
            Load More Residents
        </button>
        <div id="loadingSpinner" class="hidden mt-2">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-gray-900 mx-auto"></div>
        </div>
    <?php else: ?>
        <p class="text-xs text-gray-400 font-medium uppercase tracking-widest mt-2">End of List</p>
    <?php endif; ?>
</div><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\residents\partials\list-grid-views.blade.php ENDPATH**/ ?>