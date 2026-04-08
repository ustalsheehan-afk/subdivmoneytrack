<?php
    $accentColor = match($announcement->category) {
        'Maintenance' => '#E6B566',
        'Meeting'     => '#7DA2D6',
        'Event'       => '#7FB69A',
        'Security'    => '#8B8F9C',
        'Finance'     => '#8FAE9E',
        'Emergency'   => '#C97A7A',
        default       => '#94a3b8',
    };
    
    $icon = match($announcement->category) {
        'Maintenance' => 'bi-tools',
        'Meeting' => 'bi-people-fill',
        'Event' => 'bi-calendar-event-fill',
        'Security' => 'bi-shield-lock-fill',
        'Finance' => 'bi-cash-stack',
        'Emergency' => 'bi-exclamation-octagon-fill',
        default => 'bi-megaphone-fill',
    };

    $readersCount = $announcement->readers()->count();
    $totalResidents = \App\Models\Resident::where('status', 'active')->count();
    $isPinned = $announcement->is_pinned;
?>

<div @click="openModal(<?php echo e($announcement->id); ?>, '<?php echo e(addslashes($announcement->title)); ?>', '<?php echo e(addslashes(nl2br(e($announcement->content)))); ?>', '<?php echo e($announcement->created_at->format('M d, Y • g:i A')); ?>', '<?php echo e($announcement->category); ?>', '<?php echo e($accentColor); ?>', '<?php echo e($icon); ?>', '<?php echo e($announcement->image); ?>')"
     class="glass-card group relative overflow-hidden transition-all duration-300 animate-fade-in cursor-pointer border border-gray-100 hover:border-emerald-500/20 shadow-sm hover:shadow-md">
    
    
    <div class="absolute left-0 top-0 bottom-0 w-[4px] group-hover:w-[6px] transition-all duration-300" 
         style="background-color: <?php echo e($isPinned ? '#10B981' : $accentColor); ?>"></div>

    <div class="p-6 pl-8 flex flex-col md:flex-row md:items-center gap-6">
        
        
        <template x-if="selectionMode">
            <div class="shrink-0" @click.stop>
                <input type="checkbox" 
                       :value="<?php echo e($announcement->id); ?>" 
                       x-model="selected"
                       class="w-5 h-5 rounded-lg border-gray-300 text-emerald-600 focus:ring-emerald-500/20 transition-all cursor-pointer">
            </div>
        </template>

        
        <div class="shrink-0 flex flex-row md:flex-col items-center gap-3">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 shadow-sm border border-gray-100 group-hover:scale-105 transition-all duration-300"
                 style="background-color: <?php echo e($accentColor); ?>10; color: <?php echo e($accentColor); ?>">
                <i class="bi <?php echo e($icon); ?> text-2xl"></i>
            </div>
        </div>

        
        <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-2 mb-2">
                <span class="text-[10px] font-black uppercase tracking-[0.1em]" style="color: <?php echo e($accentColor); ?>">
                    <?php echo e($announcement->category); ?>

                </span>
                <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                    <?php echo e($announcement->created_at->format('M d, Y')); ?>

                </span>
                
                <?php if($isPinned): ?>
                    <span class="badge-standard bg-emerald-50 text-emerald-600 border border-emerald-100 ml-auto md:ml-0">
                        <i class="bi bi-pin-angle-fill mr-1"></i> Pinned
                    </span>
                <?php endif; ?>

                <?php if($announcement->priority && !in_array($announcement->priority, ['low', 'normal'], true)): ?>
                    <span class="badge-standard 
                        <?php echo e($announcement->priority === 'urgent' ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-amber-50 text-amber-600 border border-amber-100'); ?>">
                        <?php echo e($announcement->priority === 'high' ? 'important' : $announcement->priority); ?>

                    </span>
                <?php endif; ?>
            </div>

            <h4 class="text-lg font-bold text-gray-900 leading-tight group-hover:text-emerald-600 transition-colors duration-300 mb-1">
                <?php echo e($announcement->title); ?>

            </h4>
            
            <p class="text-sm text-gray-600 leading-relaxed font-medium line-clamp-1 opacity-80 group-hover:opacity-100 transition-opacity">
                <?php echo e($announcement->content); ?>

            </p>

            <div class="flex items-center gap-4 mt-3">
                <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    <i class="bi bi-eye-fill text-emerald-600/50"></i>
                    <span><?php echo e($readersCount); ?> / <?php echo e($totalResidents); ?> Seen</span>
                </div>
                <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    <i class="bi bi-clock-history"></i>
                    <span><?php echo e($announcement->created_at->diffForHumans()); ?></span>
                </div>
            </div>
        </div>

        
        <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 -translate-x-2 group-hover:translate-x-0" @click.stop>
            <?php if(isset($isTrashedView) && $isTrashedView): ?>
                
                <form action="<?php echo e(route('admin.announcements.restore', $announcement)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <button type="submit" title="Restore" class="w-10 h-10 rounded-xl bg-gray-50 text-gray-400 hover:bg-emerald-50 hover:text-emerald-600 border border-gray-100 transition-all duration-200 flex items-center justify-center">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </form>

                <form action="<?php echo e(route('admin.announcements.forceDelete', $announcement)); ?>" method="POST" onsubmit="return confirm('PERMANENTLY DELETE? This cannot be undone.')">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" title="Delete Permanently" class="w-10 h-10 rounded-xl bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-600 border border-gray-100 transition-all duration-200 flex items-center justify-center">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </form>
            <?php elseif(isset($isArchivedView) && $isArchivedView): ?>
                
                <form action="<?php echo e(route('admin.announcements.restore', $announcement)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <button type="submit" title="Restore to Active" class="w-10 h-10 rounded-xl bg-gray-50 text-gray-400 hover:bg-emerald-50 hover:text-emerald-600 border border-gray-100 transition-all duration-200 flex items-center justify-center">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </form>

                <form action="<?php echo e(route('admin.announcements.destroy', $announcement)); ?>" method="POST" onsubmit="return confirm('Move to trash?')">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" title="Move to Trash" class="w-10 h-10 rounded-xl bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-600 border border-gray-100 transition-all duration-200 flex items-center justify-center">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            <?php else: ?>
                
                <form action="<?php echo e(route('admin.announcements.togglePin', $announcement)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <button type="submit" class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-200 <?php echo e($isPinned ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-gray-50 text-gray-400 hover:bg-emerald-50 hover:text-emerald-600 border border-gray-100'); ?>">
                        <i class="bi bi-pin-angle"></i>
                    </button>
                </form>
                
                <a href="<?php echo e(route('admin.announcements.edit', $announcement)); ?>"
                   class="w-10 h-10 rounded-xl bg-gray-50 text-gray-400 hover:bg-emerald-50 hover:text-emerald-600 border border-gray-100 transition-all duration-200 flex items-center justify-center">
                    <i class="bi bi-pencil"></i>
                </a>

                <form action="<?php echo e(route('admin.announcements.destroy', $announcement)); ?>" method="POST" onsubmit="return confirm('Move to trash?')">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="w-10 h-10 rounded-xl bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-600 border border-gray-100 transition-all duration-200 flex items-center justify-center">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views/admin/announcements/partials/card.blade.php ENDPATH**/ ?>