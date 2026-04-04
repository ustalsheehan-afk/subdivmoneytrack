<?php $__env->startSection('title', 'Dues Dashboard'); ?>
<?php $__env->startSection('page-title', 'Financial Overview'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    
    <div class="flex items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <div>
            <h3 class="text-xl font-bold text-gray-900">Dues Dashboard</h3>
            <p class="text-sm text-gray-500">Track and manage community financial metrics</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative group">
                <select class="appearance-none pl-4 pr-10 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all cursor-pointer hover:bg-white" onchange="window.location.href='?range=' + this.value">
                    <option value="month" <?php echo e(request('range') == 'month' ? 'selected' : ''); ?>>This Month</option>
                    <option value="quarter" <?php echo e(request('range') == 'quarter' ? 'selected' : ''); ?>>This Quarter</option>
                    <option value="year" <?php echo e(request('range') == 'year' ? 'selected' : ''); ?>>This Year</option>
                </select>
                <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                    <i class="bi bi-chevron-down text-xs"></i>
                </div>
            </div>
            <a href="<?php echo e(route('admin.dues.create')); ?>" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-xl hover:bg-blue-700 transition-all shadow-md shadow-blue-100 flex items-center gap-2">
                <i class="bi bi-plus-lg"></i>
                <span>Create Dues</span>
            </a>
        </div>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                    <i class="bi bi-calendar-check-fill text-xl"></i>
                </div>
                <span class="px-2.5 py-1 rounded-lg bg-green-50 text-green-700 text-[10px] font-bold uppercase tracking-wider border border-green-100">
                    Active
                </span>
            </div>
            <div class="text-2xl font-black text-gray-900 mb-1"><?php echo e($totalActiveDues); ?></div>
            <div class="flex items-center gap-1.5 text-gray-400 text-xs font-medium">
                <span>Total Active Dues</span>
                <i class="bi bi-info-circle cursor-help" title="Total number of active billing batches"></i>
            </div>
        </div>

        
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                    <i class="bi bi-wallet-fill text-xl"></i>
                </div>
                <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-[10px] font-bold uppercase tracking-wider border border-blue-100">
                    <?php echo e(number_format(($totalCollected / max(1, $totalExpected)) * 100, 1)); ?>%
                </span>
            </div>
            <div class="text-2xl font-black text-gray-900 mb-1">₱<?php echo e(number_format($totalCollected, 2)); ?></div>
            <div class="flex items-center gap-1.5 text-gray-400 text-xs font-medium">
                <span>Total Collected</span>
                <i class="bi bi-info-circle cursor-help" title="Total amount collected from all dues"></i>
            </div>
        </div>

        
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow group text-orange-600">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                    <i class="bi bi-clock-history text-xl"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-gray-900 mb-1">₱<?php echo e(number_format($pendingCollection, 2)); ?></div>
            <div class="flex items-center gap-1.5 text-gray-400 text-xs font-medium">
                <span>Pending Collection</span>
                <i class="bi bi-info-circle cursor-help" title="Amount still expected to be collected"></i>
            </div>
        </div>

        
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                    <i class="bi bi-graph-up-arrow text-xl"></i>
                </div>
                <span class="px-2.5 py-1 rounded-lg <?php echo e($growth >= 0 ? 'bg-green-50 text-green-700 border-green-100' : 'bg-red-50 text-red-700 border-red-100'); ?> text-[10px] font-bold uppercase tracking-wider border">
                    <?php echo e($growth >= 0 ? '+' : ''); ?><?php echo e(number_format($growth, 1)); ?>%
                </span>
            </div>
            <div class="text-2xl font-black text-gray-900 mb-1">MoM Growth</div>
            <div class="flex items-center gap-1.5 text-gray-400 text-xs font-medium">
                <span>Performance</span>
                <i class="bi bi-info-circle cursor-help" title="Month-over-month collection growth"></i>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-50 flex items-center justify-between">
            <h4 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="bi bi-list-task text-blue-500"></i>
                <span>Recent Dues Batches</span>
            </h4>
            <a href="<?php echo e(route('admin.dues.index')); ?>" class="text-sm font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1 transition-all">
                <span>View All</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Title & Type</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider text-center">Billing Period</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Progress</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php $__empty_1 = true; $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="group hover:bg-blue-50/30 transition-all duration-200">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                    <i class="bi bi-collection-fill text-lg"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900"><?php echo e($batch->title); ?></div>
                                    <div class="text-[11px] text-gray-400 font-bold uppercase tracking-wider"><?php echo e(str_replace('_', ' ', $batch->type)); ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="text-sm font-medium text-gray-700"><?php echo e($batch->billing_period_start ? $batch->billing_period_start->format('M Y') : 'N/A'); ?></div>
                            <div class="text-[10px] text-gray-400 font-bold uppercase">Due: <?php echo e($batch->due_date ? $batch->due_date->format('M d, Y') : 'N/A'); ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="w-48">
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-[11px] font-bold text-gray-500"><?php echo e(number_format($batch->progress, 0)); ?>%</span>
                                    <span class="text-[10px] text-gray-400">₱<?php echo e(number_format($batch->collected_amount, 0)); ?> / ₱<?php echo e(number_format($batch->total_expected, 0)); ?></span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-blue-500 h-full rounded-full transition-all duration-1000" style="width: <?php echo e($batch->progress); ?>%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="<?php echo e(route('admin.dues.show', $batch)); ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 text-xs font-bold rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                <i class="bi bi-eye"></i>
                                <span>Details</span>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-20 text-center">
                            <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-4 text-gray-300">
                                <i class="bi bi-receipt text-2xl"></i>
                            </div>
                            <p class="text-gray-400 text-sm font-medium">No billing batches found for this period.</p>
                            <div class="mt-4">
                                <a href="<?php echo e(route('admin.dues.create')); ?>" class="px-5 py-2.5 bg-blue-600 text-white text-xs font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-100 transition-all">
                                    Generate First Statement
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views/admin/dues/dashboard.blade.php ENDPATH**/ ?>