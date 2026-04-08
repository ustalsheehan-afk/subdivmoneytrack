

<?php $__env->startSection('title', 'Archived Announcements'); ?>
<?php $__env->startSection('page-title', 'Archived Announcements'); ?>

<?php $__env->startSection('content'); ?>

<div class="max-w-6xl mx-auto space-y-6" x-data="{ 
    selectionMode: false, 
    selected: [],
    allIds: [<?php echo e($announcements->pluck('id')->implode(',')); ?>],
    toggleSelectionMode() {
        this.selectionMode = !this.selectionMode;
        if (!this.selectionMode) this.selected = [];
    },
    toggleAll() {
        if (this.selected.length === this.allIds.length) {
            this.selected = [];
        } else {
            this.selected = [...this.allIds];
        }
    }
}">




<div class="glass-card p-8 mb-8 relative overflow-hidden group">
    
    <div class="absolute -right-20 -top-20 w-64 h-64 bg-brand-accent/5 rounded-full blur-3xl group-hover:bg-brand-accent/10 transition-all duration-700"></div>
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
        <div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                Archived Announcements
            </h1>
            <p class="mt-2 text-gray-600 text-lg max-w-xl">
                Review and manage past updates that are no longer active.
            </p>
        </div>

        <div class="flex items-center gap-3">
            
            <button @click="toggleSelectionMode()" 
                    :class="selectionMode ? 'bg-gray-100 text-gray-700' : 'bg-emerald-50 text-emerald-700'"
                    class="inline-flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-bold transition-all border border-gray-200 shadow-sm hover:shadow-md">
                <i :class="selectionMode ? 'bi-x-lg' : 'bi-check2-square'"></i>
                <span x-text="selectionMode ? 'Cancel Selection' : 'Select'"></span>
            </button>

            <a href="<?php echo e(route('admin.announcements.create')); ?>"
               class="btn-premium">
                <i class="bi bi-plus-lg text-sm"></i>
                New Announcement
            </a>
        </div>
    </div>
</div>


<template x-if="selected.length > 0">
    <div class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 bg-gray-900 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-6 animate-fade-in border border-white/10">
        <span class="text-sm font-bold tracking-wide"><span x-text="selected.length"></span> Items Selected</span>
        <div class="h-6 w-px bg-white/10"></div>
        <div class="flex items-center gap-3">
            <form action="<?php echo e(route('admin.announcements.bulkRestore')); ?>" method="POST" onsubmit="return confirm('Restore selected announcements?')">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                <template x-for="id in selected">
                    <input type="hidden" name="announcements[]" :value="id">
                </template>
                <button type="submit" class="text-xs font-black uppercase tracking-widest text-emerald-400 hover:text-emerald-300 transition-colors flex items-center gap-2">
                    <i class="bi bi-arrow-clockwise"></i> Restore
                </button>
            </form>
            <form action="<?php echo e(route('admin.announcements.bulkTrash')); ?>" method="POST" onsubmit="return confirm('Move selected to trash?')">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <template x-for="id in selected">
                    <input type="hidden" name="announcements[]" :value="id">
                </template>
                <button type="submit" class="text-xs font-black uppercase tracking-widest text-red-400 hover:text-red-300 transition-colors flex items-center gap-2">
                    <i class="bi bi-trash"></i> Trash
                </button>
            </form>
        </div>
    </div>
</template>




<div class="glass-card p-4 mb-8 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
    
    
    <div class="flex items-center bg-gray-50 p-1.5 rounded-xl border border-gray-100 self-start">
        <a href="<?php echo e(route('admin.announcements.index')); ?>"
           class="px-6 py-2 rounded-lg text-xs font-black uppercase tracking-widest transition-all <?php echo e(request()->routeIs('admin.announcements.index') ? 'bg-white text-emerald-600 shadow-sm border border-gray-100' : 'text-gray-500 hover:text-gray-700'); ?>">
            Active
        </a>
        <a href="<?php echo e(route('admin.announcements.drafts')); ?>"
           class="px-6 py-2 rounded-lg text-xs font-black uppercase tracking-widest transition-all <?php echo e(request()->routeIs('admin.announcements.drafts') ? 'bg-white text-emerald-600 shadow-sm border border-gray-100' : 'text-gray-500 hover:text-gray-700'); ?>">
            Drafts
        </a>
        <a href="<?php echo e(route('admin.announcements.archive')); ?>"
           class="px-6 py-2 rounded-lg text-xs font-black uppercase tracking-widest transition-all <?php echo e(request()->routeIs('admin.announcements.archive') ? 'bg-white text-emerald-600 shadow-sm border border-gray-100' : 'text-gray-500 hover:text-gray-700'); ?>">
            Archive
        </a>
        <a href="<?php echo e(route('admin.announcements.trashed')); ?>"
           class="px-6 py-2 rounded-lg text-xs font-black uppercase tracking-widest transition-all <?php echo e(request()->routeIs('admin.announcements.trashed') ? 'bg-white text-emerald-600 shadow-sm border border-gray-100' : 'text-gray-500 hover:text-gray-700'); ?>">
            Trash
        </a>
    </div>

    
    <div class="flex flex-wrap items-center gap-3">
        <template x-if="selectionMode">
            <button @click="toggleAll()" 
                    class="px-4 py-2 bg-emerald-50 text-emerald-700 rounded-lg text-[10px] font-black uppercase tracking-widest border border-emerald-100 hover:bg-emerald-100 transition-colors">
                <span x-text="selected.length === allIds.length && allIds.length > 0 ? 'Deselect All' : 'Select All'"></span>
            </button>
        </template>

        <form method="GET" class="flex flex-wrap items-center gap-3">
            <div class="relative group">
                <select name="month" onchange="this.form.submit()"
                        class="pl-4 pr-10 py-2.5 bg-white border border-gray-200 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-700 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all appearance-none cursor-pointer hover:border-gray-300">
                    <option value="">All Months</option>
                    <?php $__currentLoopData = range(1,12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($m); ?>" <?php echo e(request('month') == $m ? 'selected' : ''); ?>>
                            <?php echo e(\Carbon\Carbon::create()->month($m)->format('F')); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none group-hover:text-gray-600 transition-colors"></i>
            </div>

            <div class="relative group">
                <select name="year" onchange="this.form.submit()"
                        class="pl-4 pr-10 py-2.5 bg-white border border-gray-200 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-700 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all appearance-none cursor-pointer hover:border-gray-300">
                    <option value="">All Years</option>
                    <?php $__currentLoopData = range(now()->year, now()->year - 5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($y); ?>" <?php echo e(request('year') == $y ? 'selected' : ''); ?>>
                            <?php echo e($y); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none group-hover:text-gray-600 transition-colors"></i>
            </div>
        </form>
    </div>
</div>


<div class="space-y-4">
    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 px-1">Archived Items</h4>
    <div class="grid grid-cols-1 gap-4">
        <?php $__empty_1 = true; $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php echo $__env->make('admin.announcements.partials.card', [
                'announcement' => $announcement,
                'isArchivedView' => true
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full text-center py-12 bg-white rounded-2xl border border-dashed border-gray-200">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="bi bi-archive text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-gray-900 font-medium">No archived announcements found</h3>
                <p class="text-gray-500 text-sm mt-1">Archived items will appear here.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

</div>

<?php $__env->startPush('scripts'); ?>
<?php echo $__env->make('admin.announcements.partials.modal-script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views/admin/announcements/archived-announcements.blade.php ENDPATH**/ ?>