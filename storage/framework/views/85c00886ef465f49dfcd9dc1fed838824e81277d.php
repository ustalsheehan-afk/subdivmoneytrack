<?php $__env->startSection('title', 'Billing Batches'); ?>
<?php $__env->startSection('page-title', 'Billing Batches'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8 animate-fade-in" x-data="{ search: '' }">

    
    
    
    <div class="glass-card p-8 relative overflow-hidden group">
        
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-brand-accent/5 rounded-full blur-3xl group-hover:bg-brand-accent/10 transition-all duration-700"></div>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                    Billing Batches
                </h1>
                <p class="mt-2 text-gray-600 text-lg max-w-xl">
                    Manage and track community financial statements and monthly dues.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="<?php echo e(route('admin.dues.dashboard')); ?>" class="btn-secondary">
                    <i class="bi bi-graph-up-arrow"></i>
                    Financial Overview
                </a>
                <a href="<?php echo e(route('admin.dues.create')); ?>" class="btn-premium">
                    <i class="bi bi-plus-lg"></i>
                    Create Batch
                </a>
            </div>
        </div>
    </div>

    
    
    
    <div class="glass-card p-4 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        
        
        <div class="flex-1 max-w-md">
            <div class="relative group">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-600 transition-colors"></i>
                <input type="text" x-model="search" 
                    placeholder="Search batches by title or description..." 
                    class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all placeholder-gray-400">
            </div>
        </div>

        
        <div class="flex flex-wrap items-center gap-3">
            <form method="GET" class="flex flex-wrap items-center gap-3">
                
                <div class="relative group">
                    <select name="year" onchange="this.form.submit()" 
                            class="appearance-none pl-4 pr-10 py-2.5 bg-white border border-gray-200 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-700 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all cursor-pointer hover:border-gray-300">
                        <?php $__currentLoopData = range(now()->year, now()->year - 5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($y); ?>" <?php echo e((request('year', now()->year) == $y) ? 'selected' : ''); ?>>Year <?php echo e($y); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none group-hover:text-gray-600 transition-colors"></i>
                </div>

                
                <div class="relative group">
                    <select name="sort" onchange="this.form.submit()" 
                            class="appearance-none pl-4 pr-10 py-2.5 bg-white border border-gray-200 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-700 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all appearance-none cursor-pointer hover:border-gray-300">
                        <?php $currentSort = $sortOption ?? 'newest'; ?>
                        <option value="newest" <?php echo e($currentSort == 'newest' ? 'selected' : ''); ?>>Newest First</option>
                        <option value="oldest" <?php echo e($currentSort == 'oldest' ? 'selected' : ''); ?>>Oldest First</option>
                        <option value="amount_desc" <?php echo e($currentSort == 'amount_desc' ? 'selected' : ''); ?>>Highest Amount</option>
                        <option value="amount_asc" <?php echo e($currentSort == 'amount_asc' ? 'selected' : ''); ?>>Lowest Amount</option>
                    </select>
                    <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none group-hover:text-gray-600 transition-colors"></i>
                </div>

                
                <?php if(request()->anyFilled(['search', 'year', 'sort'])): ?>
                    <a href="<?php echo e(route('admin.dues.index')); ?>" class="h-11 w-11 flex items-center justify-center rounded-xl border border-red-100 text-red-500 hover:bg-red-50 transition-all" title="Clear All Filters">
                        <i class="bi bi-x-lg"></i>
                    </a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    
    
    
    <div class="space-y-8">
        <?php $__empty_1 = true; $__currentLoopData = $groupedDues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $monthYear => $batches): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="glass-card overflow-hidden flex flex-col" 
                 x-show="'<?php echo e(strtolower($monthYear)); ?>'.includes(search.toLowerCase()) || <?php echo e($batches->map(fn($b) => strtolower($b->title . ' ' . $b->description))->toJson()); ?>.some(t => t.includes(search.toLowerCase()))">
                <?php
                    $monthExpected = $batches->sum('total_expected');
                    $monthCollected = $batches->sum(fn($b) => $b->collected_amount);
                ?>
                
                
                <div class="px-8 py-6 border-b border-gray-50 flex flex-col lg:flex-row lg:items-center justify-between gap-6 bg-gray-50/30">
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight"><?php echo e($monthYear); ?></h3>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1"><?php echo e($batches->count()); ?> Billing Statements</p>
                    </div>

                    <div class="flex items-center gap-12">
                        <div class="text-right">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Total Expected</p>
                            <p class="text-2xl font-black text-gray-900 leading-none">₱<?php echo e(number_format($monthExpected, 2)); ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-1.5">Total Paid</p>
                            <p class="text-2xl font-black text-emerald-600 leading-none">₱<?php echo e(number_format($monthCollected, 2)); ?></p>
                        </div>
                    </div>
                </div>

                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50/50 border-b border-gray-100">
                            <tr>
                                <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Date</th>
                                <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Description</th>
                                <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Type</th>
                                <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Amount</th>
                                <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                                <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php $__currentLoopData = $batches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $dueDate = $batch->due_date;
                                    $isOverdue = $dueDate->isPast() && ($batch->collected_amount < $batch->total_expected);
                                    $isPaid = $batch->collected_amount >= $batch->total_expected;
                                    
                                    $statusClass = $isPaid 
                                        ? 'bg-emerald-50 text-emerald-600 border-emerald-100' 
                                        : ($isOverdue ? 'bg-red-50 text-red-600 border-red-100' : 'bg-blue-50 text-blue-600 border-blue-100');
                                    
                                    $dotClass = $isPaid ? 'bg-emerald-500' : ($isOverdue ? 'bg-red-500' : 'bg-blue-500');
                                    $statusLabel = $isPaid ? 'Paid' : ($isOverdue ? 'Overdue' : 'Collecting');
                                ?>
                                <tr class="hover:bg-emerald-50/30 transition-all duration-300 group border-l-4 border-transparent hover:border-emerald-500"
                                    x-show="'<?php echo e(strtolower($batch->title . ' ' . $batch->description)); ?>'.includes(search.toLowerCase())">
                                    <td class="p-5">
                                        <span class="text-sm font-bold text-gray-600"><?php echo e($dueDate->format('M d, Y')); ?></span>
                                    </td>
                                    <td class="p-5">
                                        <span class="text-sm font-extrabold text-gray-900 group-hover:text-emerald-700 transition-colors"><?php echo e($batch->title); ?></span>
                                    </td>
                                    <td class="p-5">
                                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-500"><?php echo e(str_replace('_', ' ', $batch->type)); ?></span>
                                    </td>
                                    <td class="p-5 text-center">
                                        <span class="text-base font-black text-gray-900">₱<?php echo e(number_format($batch->total_expected, 2)); ?></span>
                                    </td>
                                    <td class="p-5 text-center">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border <?php echo e($statusClass); ?>">
                                            <span class="w-1.5 h-1.5 rounded-full <?php echo e($dotClass); ?>"></span>
                                            <?php echo e($statusLabel); ?>

                                        </span>
                                    </td>
                                    <td class="p-5 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="<?php echo e(route('admin.dues.show', $batch->id)); ?>" class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-900 text-white hover:bg-emerald-600 transition-all shadow-sm" title="View Details">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                            <a href="<?php echo e(route('admin.dues.edit', $batch->id)); ?>" class="w-9 h-9 flex items-center justify-center rounded-xl border border-gray-200 text-gray-400 hover:text-emerald-600 hover:border-emerald-600 transition-all bg-white" title="Edit Batch">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="<?php echo e(route('admin.dues.destroy', $batch->id)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this billing batch?')">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-xl border border-gray-200 text-gray-400 hover:text-red-600 hover:border-red-600 transition-all bg-white" title="Delete Batch">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="glass-card p-24 text-center">
                <div class="w-24 h-24 rounded-3xl bg-gray-50 flex items-center justify-center mx-auto mb-8 text-gray-200">
                    <i class="bi bi-receipt-cutoff text-5xl"></i>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-2">No billing batches found</h3>
                <p class="text-gray-500 text-sm max-w-xs mx-auto mb-10 leading-relaxed font-medium">
                    You haven't generated any billing statements for the selected criteria.
                </p>
                <a href="<?php echo e(route('admin.dues.create')); ?>" class="btn-premium px-10 py-5">
                    <i class="bi bi-plus-lg text-xl"></i>
                    Create Your First Batch
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #E2E8F0; border-radius: 20px; }
    .custom-scrollbar::-webkit-scrollbar-track { background-color: transparent; }
    [x-cloak] { display: none !important; }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\dues\index.blade.php ENDPATH**/ ?>