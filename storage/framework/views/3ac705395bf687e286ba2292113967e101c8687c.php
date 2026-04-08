

<?php $__env->startSection('title', 'Announcements'); ?>
<?php $__env->startSection('page-title', 'Announcements'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.resident-hero-header','data' => ['label' => 'Community Notice','icon' => 'bi-megaphone-fill','title' => 'Announcements','description' => 'Stay updated with the latest news and alerts from the administration.','tabs' => [
                ['id' => '', 'label' => 'All Categories', 'icon' => 'bi-grid-fill', 'href' => route('resident.announcements.index'), 'active' => !request('category')],
                ['id' => 'Emergency', 'label' => 'Emergency', 'icon' => 'bi-exclamation-octagon-fill', 'href' => route('resident.announcements.index', ['category' => 'Emergency']), 'active' => request('category') == 'Emergency'],
                ['id' => 'Maintenance', 'label' => 'Maintenance', 'icon' => 'bi-tools', 'href' => route('resident.announcements.index', ['category' => 'Maintenance']), 'active' => request('category') == 'Maintenance'],
                ['id' => 'Meeting', 'label' => 'Meeting', 'icon' => 'bi-people-fill', 'href' => route('resident.announcements.index', ['category' => 'Meeting']), 'active' => request('category') == 'Meeting'],
                ['id' => 'Event', 'label' => 'Event', 'icon' => 'bi-calendar-event-fill', 'href' => route('resident.announcements.index', ['category' => 'Event']), 'active' => request('category') == 'Event'],
                ['id' => 'Security', 'label' => 'Security', 'icon' => 'bi-shield-lock-fill', 'href' => route('resident.announcements.index', ['category' => 'Security']), 'active' => request('category') == 'Security'],
                ['id' => 'Finance', 'label' => 'Finance', 'icon' => 'bi-cash-stack', 'href' => route('resident.announcements.index', ['category' => 'Finance']), 'active' => request('category') == 'Finance'],
            ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('resident-hero-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Community Notice','icon' => 'bi-megaphone-fill','title' => 'Announcements','description' => 'Stay updated with the latest news and alerts from the administration.','tabs' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                ['id' => '', 'label' => 'All Categories', 'icon' => 'bi-grid-fill', 'href' => route('resident.announcements.index'), 'active' => !request('category')],
                ['id' => 'Emergency', 'label' => 'Emergency', 'icon' => 'bi-exclamation-octagon-fill', 'href' => route('resident.announcements.index', ['category' => 'Emergency']), 'active' => request('category') == 'Emergency'],
                ['id' => 'Maintenance', 'label' => 'Maintenance', 'icon' => 'bi-tools', 'href' => route('resident.announcements.index', ['category' => 'Maintenance']), 'active' => request('category') == 'Maintenance'],
                ['id' => 'Meeting', 'label' => 'Meeting', 'icon' => 'bi-people-fill', 'href' => route('resident.announcements.index', ['category' => 'Meeting']), 'active' => request('category') == 'Meeting'],
                ['id' => 'Event', 'label' => 'Event', 'icon' => 'bi-calendar-event-fill', 'href' => route('resident.announcements.index', ['category' => 'Event']), 'active' => request('category') == 'Event'],
                ['id' => 'Security', 'label' => 'Security', 'icon' => 'bi-shield-lock-fill', 'href' => route('resident.announcements.index', ['category' => 'Security']), 'active' => request('category') == 'Security'],
                ['id' => 'Finance', 'label' => 'Finance', 'icon' => 'bi-cash-stack', 'href' => route('resident.announcements.index', ['category' => 'Finance']), 'active' => request('category') == 'Finance'],
            ])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>

<?php
$normal = $announcements->getCollection();

$categoryColors = [
    'Maintenance' => '#E6B566',
    'Meeting'     => '#7DA2D6',
    'Event'       => '#7FB69A',
    'Security'    => '#8B8F9C',
    'Finance'     => '#8FAE9E',
    'Emergency'   => '#C97A7A',
];

$categoryIcons = [
    'Maintenance' => 'bi-tools',
    'Meeting'     => 'bi-people-fill',
    'Event'       => 'bi-calendar-event-fill',
    'Security'    => 'bi-shield-lock-fill',
    'Finance'     => 'bi-cash-stack',
    'Emergency'   => 'bi-exclamation-octagon-fill',
];

$defaultColor = '#94a3b8';
$defaultIcon  = 'bi-megaphone-fill';
?>


<?php if($pinned->count()): ?>
<div class="space-y-8 relative z-10">
    <div class="flex items-center gap-4">
        <h4 class="text-[11px] font-black text-gray-400 uppercase tracking-[0.3em] flex items-center gap-3">
            <span class="w-8 h-px bg-gray-200"></span>
            Pinned Updates
        </h4>
        <div class="px-3 py-1 rounded-full bg-orange-500/10 border border-orange-500/20 text-orange-500 text-[9px] font-black uppercase tracking-widest animate-pulse">
            Priority
        </div>
    </div>

    <div class="grid gap-8">
        <?php $__currentLoopData = $pinned; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $cat = $announcement->category ?? 'General';
            $accentColor = $categoryColors[$cat] ?? $defaultColor;
            $icon = $categoryIcons[$cat] ?? $defaultIcon;
            $prio = $announcement->priority ?? 'normal';
            $isUrgent = in_array($prio, ['high', 'urgent']);
            $isRead = $announcement->is_read ?? false;
        ?>

        <div onclick="window.location.href='<?php echo e(route('resident.announcements.show', $announcement)); ?>'" 
             class="relative block bg-white rounded-[24px] border border-gray-100 transition-all duration-500 overflow-hidden hover:shadow-[0_20px_50px_rgba(0,0,0,0.05)] group cursor-pointer hover:-translate-y-1">

            
            <div class="absolute left-0 top-0 bottom-0 w-[6px] transition-all duration-500 group-hover:w-[10px]" style="background-color: <?php echo e($accentColor); ?>;"></div>

            <div class="p-8">
                <div class="flex flex-col md:flex-row gap-8">
                    
                    <div class="w-16 h-16 rounded-[20px] bg-gray-50 flex items-center justify-center shrink-0 transition-all duration-500 group-hover:scale-110"
                         style="color: <?php echo e($accentColor); ?>;">
                        <i class="bi <?php echo e($icon); ?> text-2xl"></i>
                    </div>

                    <div class="flex-1 space-y-4">
                        
                        <div class="flex flex-wrap items-center gap-3">
                            <p class="text-[10px] font-black uppercase tracking-[0.2em]" style="color: <?php echo e($accentColor); ?>;"><?php echo e($cat); ?></p>
                            <span class="text-gray-300">•</span>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest"><?php echo e($announcement->created_at->format('M d, Y')); ?></p>
                            
                            <div class="flex items-center gap-2 ml-auto">
                                <span class="px-3 py-1 rounded-lg bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase tracking-widest border border-emerald-100 flex items-center gap-1.5">
                                    <i class="bi bi-pin-angle-fill"></i> Pinned
                                </span>
                                <?php if($isUrgent): ?>
                                    <span class="px-3 py-1 rounded-lg bg-red-50 text-red-600 text-[9px] font-black uppercase tracking-widest border border-red-100">
                                        Urgent
                                    </span>
                                <?php endif; ?>
                                <?php if(!$isRead): ?>
                                    <div class="px-3 py-1 rounded-lg bg-blue-50 text-blue-600 text-[9px] font-black uppercase tracking-widest border border-blue-100 flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> New
                                    </div>
                                <?php else: ?>
                                    <div class="px-3 py-1 rounded-lg bg-gray-50 text-gray-400 text-[9px] font-black uppercase tracking-widest border border-gray-100 flex items-center gap-1.5">
                                        <i class="bi bi-check2-all"></i> Seen
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <h3 class="text-gray-900 font-black text-2xl tracking-tight leading-tight group-hover:text-emerald-600 transition-colors duration-300">
                            <?php echo e($announcement->title); ?>

                        </h3>
                        
                        <p class="text-[14px] text-gray-500 leading-relaxed font-medium line-clamp-2">
                             <?php echo nl2br(e($announcement->content)); ?>

                        </p>

                        
                        <div class="pt-2 flex items-center gap-4 text-[10px] font-black uppercase tracking-widest text-gray-400">
                            <span class="flex items-center gap-1.5">
                                <i class="bi bi-clock"></i> <?php echo e($announcement->created_at->diffForHumans()); ?>

                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?>


<div class="space-y-8 relative z-10">
<?php if($normal->count() > 0): ?>

<h4 class="text-[11px] font-black text-gray-400 uppercase tracking-[0.3em] flex items-center gap-3">
    <span class="w-8 h-px bg-gray-200"></span>
    Recent Updates
</h4>

<div class="grid gap-8">
        <?php $__currentLoopData = $normal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $cat = $announcement->category ?? 'General';
            $accentColor = $categoryColors[$cat] ?? $defaultColor;
            $icon = $categoryIcons[$cat] ?? $defaultIcon;
            $prio = $announcement->priority ?? 'normal';
            $isUrgent = in_array($prio, ['high', 'urgent']);
            $isRead = $announcement->is_read ?? false;
        ?>

        <div onclick="window.location.href='<?php echo e(route('resident.announcements.show', $announcement)); ?>'" 
             class="relative block bg-white rounded-[24px] border border-gray-100 transition-all duration-500 overflow-hidden hover:shadow-[0_20px_50px_rgba(0,0,0,0.05)] group cursor-pointer hover:-translate-y-1">

            
            <div class="absolute left-0 top-0 bottom-0 w-[6px] transition-all duration-500 group-hover:w-[10px]" style="background-color: <?php echo e($accentColor); ?>;"></div>

            <div class="p-8">
                <div class="flex flex-col md:flex-row gap-8">
                    
                    <div class="w-16 h-16 rounded-[20px] bg-gray-50 flex items-center justify-center shrink-0 transition-all duration-500 group-hover:scale-110"
                         style="color: <?php echo e($accentColor); ?>;">
                        <i class="bi <?php echo e($icon); ?> text-2xl"></i>
                    </div>

                    <div class="flex-1 space-y-4">
                        
                        <div class="flex flex-wrap items-center gap-3">
                            <p class="text-[10px] font-black uppercase tracking-[0.2em]" style="color: <?php echo e($accentColor); ?>;"><?php echo e($cat); ?></p>
                            <span class="text-gray-300">•</span>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest"><?php echo e($announcement->created_at->format('M d, Y')); ?></p>
                            
                            <div class="flex items-center gap-2 ml-auto">
                                <?php if($isUrgent): ?>
                                    <span class="px-3 py-1 rounded-lg bg-red-50 text-red-600 text-[9px] font-black uppercase tracking-widest border border-red-100">
                                        Urgent
                                    </span>
                                <?php endif; ?>
                                <?php if(!$isRead): ?>
                                    <div class="px-3 py-1 rounded-lg bg-blue-50 text-blue-600 text-[9px] font-black uppercase tracking-widest border border-blue-100 flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> New
                                    </div>
                                <?php else: ?>
                                    <div class="px-3 py-1 rounded-lg bg-gray-50 text-gray-400 text-[9px] font-black uppercase tracking-widest border border-gray-100 flex items-center gap-1.5">
                                        <i class="bi bi-check2-all"></i> Seen
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <h3 class="text-gray-900 font-black text-2xl tracking-tight leading-tight group-hover:text-emerald-600 transition-colors duration-300">
                            <?php echo e($announcement->title); ?>

                        </h3>
                        
                        <p class="text-[14px] text-gray-500 leading-relaxed font-medium line-clamp-2">
                             <?php echo nl2br(e($announcement->content)); ?>

                        </p>

                        
                        <div class="pt-2 flex items-center gap-4 text-[10px] font-black uppercase tracking-widest text-gray-400">
                            <span class="flex items-center gap-1.5">
                                <i class="bi bi-clock"></i> <?php echo e($announcement->created_at->diffForHumans()); ?>

                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

<?php else: ?>

<div class="text-center py-24 bg-white rounded-[40px] border border-gray-100 relative overflow-hidden group">
    <div class="absolute inset-0 bg-gradient-to-b from-gray-50/50 to-transparent"></div>
    <div class="relative z-10">
        <div class="w-24 h-24 bg-gray-50 rounded-[32px] flex items-center justify-center mx-auto mb-8 text-gray-200 shadow-inner border border-gray-100 group-hover:scale-110 transition-transform duration-500">
            <i class="bi bi-inbox text-5xl"></i>
        </div>
        <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tight">No announcements found</h3>
        <p class="text-[11px] font-black text-gray-400 uppercase tracking-[0.3em] mt-4">Check back later for community updates</p>
    </div>
</div>

<?php endif; ?>


<div class="mt-8">
    <?php echo e($announcements->links()); ?>

</div>

</div>
<?php $__env->startPush('scripts'); ?>
<script>
    async function markAsRead(btn, id) {
        // Optimistic UI update
        if (btn) btn.remove();

        try {
            await fetch(`<?php echo e(url('resident/announcements')); ?>/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
        } catch (error) {
            console.error('Failed to mark as read', error);
        }
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('resident.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views/resident/announcements/index.blade.php ENDPATH**/ ?>