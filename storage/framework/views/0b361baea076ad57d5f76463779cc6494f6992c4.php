<?php $__env->startSection('title', 'Notifications'); ?>
<?php $__env->startSection('page-title', 'Notifications'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8 animate-fade-in">

    
    
    
    <div class="glass-card p-8 relative overflow-hidden group">
        
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-brand-accent/5 rounded-full blur-3xl group-hover:bg-brand-accent/10 transition-all duration-700"></div>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                    Notifications
                </h1>
                <p class="mt-2 text-gray-600 text-lg max-w-xl">
                    System alerts and important subdivision updates.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <form action="<?php echo e(route('admin.messages.notifications.markAllRead')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-premium">
                        <i class="bi bi-check2-all"></i>
                        Mark All as Read
                    </button>
                </form>
            </div>
        </div>
    </div>

    
    
    
    <div class="glass-card p-4 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div class="flex flex-wrap items-center gap-4 flex-1">
            <form action="<?php echo e(route('admin.messages.notifications.index')); ?>" method="GET" class="flex flex-wrap items-center gap-4 w-full">
                
                <div class="relative group min-w-[200px]">
                    <i class="bi bi-funnel absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                    <select name="type" onchange="this.form.submit()" 
                        class="w-full pl-11 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-600 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all appearance-none cursor-pointer">
                        <option value="">All Categories</option>
                        <option value="payment" <?php echo e(request('type') == 'payment' ? 'selected' : ''); ?>>Payments</option>
                        <option value="request" <?php echo e(request('type') == 'request' ? 'selected' : ''); ?>>Requests</option>
                        <option value="reservation" <?php echo e(request('type') == 'reservation' ? 'selected' : ''); ?>>Reservations</option>
                        <option value="alert" <?php echo e(request('type') == 'alert' ? 'selected' : ''); ?>>Alerts</option>
                    </select>
                    <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[8px] opacity-50 pointer-events-none"></i>
                </div>

                
                <div class="relative group min-w-[200px]">
                    <i class="bi bi-check2-circle absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                    <select name="status" onchange="this.form.submit()" 
                        class="w-full pl-11 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-600 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all appearance-none cursor-pointer">
                        <option value="">All Status</option>
                        <option value="unread" <?php echo e(request('status') == 'unread' ? 'selected' : ''); ?>>Unread</option>
                        <option value="read" <?php echo e(request('status') == 'read' ? 'selected' : ''); ?>>Read</option>
                    </select>
                    <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[8px] opacity-50 pointer-events-none"></i>
                </div>

                
                <?php if(request()->anyFilled(['type', 'status'])): ?>
                    <a href="<?php echo e(route('admin.messages.notifications.index')); ?>" class="h-11 w-11 flex items-center justify-center rounded-xl border border-red-100 text-red-500 hover:bg-red-50 transition-all" title="Clear Filters">
                        <i class="bi bi-x-lg"></i>
                    </a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    
    
    
    <div class="glass-card overflow-hidden">
        <div class="divide-y divide-gray-50">
            <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="p-8 flex items-center justify-between hover:bg-emerald-50/20 transition-all group relative border-l-4 <?php echo e(!$notification->is_read ? 'bg-emerald-50/10 border-emerald-500' : 'border-transparent'); ?>">
                    <div class="flex items-center gap-8">
                        <div class="w-16 h-16 rounded-[24px] flex items-center justify-center shadow-sm shrink-0 group-hover:scale-110 transition-transform duration-500
                            <?php if(($notification->category ?? '') == 'payment'): ?> bg-blue-50 text-blue-500
                            <?php elseif(($notification->category ?? '') == 'billing'): ?> bg-indigo-50 text-indigo-500
                            <?php elseif(($notification->category ?? '') == 'request'): ?> bg-emerald-50 text-emerald-500
                            <?php elseif(($notification->category ?? '') == 'reservation'): ?> bg-amber-50 text-amber-500
                            <?php elseif(($notification->category ?? '') == 'alert'): ?> bg-red-50 text-red-500
                            <?php else: ?> bg-gray-50 text-gray-500 <?php endif; ?>">
                            <i class="bi 
                                <?php if(($notification->category ?? '') == 'payment'): ?> bi-cash-stack
                                <?php elseif(($notification->category ?? '') == 'billing'): ?> bi-receipt
                                <?php elseif(($notification->category ?? '') == 'request'): ?> bi-tools
                                <?php elseif(($notification->category ?? '') == 'reservation'): ?> bi-calendar-check
                                <?php elseif(($notification->category ?? '') == 'alert'): ?> bi-exclamation-triangle
                                <?php else: ?> bi-bell <?php endif; ?> text-3xl"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <h4 class="text-lg font-black text-gray-900 group-hover:text-emerald-700 transition-colors uppercase tracking-tight"><?php echo e($notification->title); ?></h4>
                                <span class="text-[9px] font-black uppercase tracking-widest px-3 py-1 rounded-full bg-gray-100 text-gray-500 border border-gray-200"><?php echo e($notification->type ?? 'SYSTEM'); ?></span>
                            </div>
                            <p class="text-base text-gray-600 font-medium leading-relaxed max-w-3xl"><?php echo e($notification->message); ?></p>
                            <div class="flex items-center gap-4 mt-3">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                                    <i class="bi bi-clock text-emerald-500"></i>
                                    <?php echo e($notification->created_at->format('M d, Y • h:i A')); ?>

                                </p>
                                <?php if(!$notification->is_read): ?>
                                    <span class="text-[9px] font-black text-emerald-600 uppercase tracking-widest px-2 py-0.5 bg-emerald-50 rounded-lg animate-pulse">New Notification</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <?php if($notification->link): ?>
                            <form action="<?php echo e(route('admin.messages.notifications.read', $notification->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="px-8 py-3 bg-[#0D1F1C] text-white text-[10px] font-black rounded-xl uppercase tracking-widest hover:shadow-xl hover:shadow-emerald-900/20 transition-all active:scale-95 flex items-center gap-2">
                                    View Details
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="p-24 text-center">
                    <div class="w-24 h-24 bg-gray-50 rounded-[32px] flex items-center justify-center mx-auto mb-8 text-gray-200 shadow-inner">
                        <i class="bi bi-bell-slash text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 tracking-tight mb-3 uppercase">No Notifications</h3>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">You're all caught up with system alerts.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-8">
        <?php echo e($notifications->links()); ?>

    </div>
</div>

    <div class="mt-8">
        <?php echo e($notifications->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\messages\notifications.blade.php ENDPATH**/ ?>