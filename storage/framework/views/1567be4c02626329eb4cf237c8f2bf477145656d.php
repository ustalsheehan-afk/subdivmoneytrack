

<?php $__env->startSection('title', $announcement->title); ?>
<?php $__env->startSection('page-title', 'Announcements'); ?> 

<?php $__env->startPush('modals'); ?>
<!-- Modal Overlay (Fixed) -->
<div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm transition-opacity duration-300"
     role="dialog" aria-modal="true">
    
    <!-- Modal Panel -->
    <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl ring-1 ring-gray-200 transform transition-all scale-100 opacity-100 flex flex-col max-h-[85vh]">
        
        <!-- Close Button -->
        <a href="<?php echo e(route('admin.announcements.index')); ?>" 
           class="absolute top-4 right-4 p-2 text-gray-400 hover:text-gray-600 bg-white hover:bg-gray-100 rounded-full transition z-10 shadow-sm border border-gray-100">
            <i class="bi bi-x-lg text-lg"></i>
        </a>

        <!-- Accent Bar (Dynamic Color) -->
        <?php
            $categoryColors = [
                'Maintenance' => '#E6B566',
                'Meeting'     => '#7DA2D6',
                'Event'       => '#7FB69A',
                'Security'    => '#8B8F9C',
                'Finance'     => '#8FAE9E',
                'Emergency'   => '#C97A7A',
            ];
            $cat = $announcement->category ?? 'General';
            $accentColor = $categoryColors[$cat] ?? '#94a3b8';
            $icon = match($cat) {
                'Maintenance' => 'bi-tools',
                'Meeting' => 'bi-people-fill',
                'Event' => 'bi-calendar-event-fill',
                'Security' => 'bi-shield-lock-fill',
                'Finance' => 'bi-cash-stack',
                'Emergency' => 'bi-exclamation-octagon-fill',
                default => 'bi-megaphone-fill',
            };
        ?>
        <div class="absolute left-0 top-0 bottom-0 w-[6px] rounded-l-2xl z-20" style="background-color: <?php echo e($accentColor); ?>"></div>

        <!-- Scrollable Content Area -->
        <div class="overflow-y-auto custom-scrollbar p-6 md:p-8 pl-8 md:pl-10 rounded-2xl">
            
            <!-- Header -->
            <div class="flex items-start gap-4 mb-6 pr-8">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0" 
                     style="background-color: <?php echo e($accentColor); ?>20; color: <?php echo e($accentColor); ?>">
                    <i class="bi <?php echo e($icon); ?> text-xl"></i>
                </div>
                
                <div class="space-y-1">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs font-bold uppercase tracking-wider text-gray-500"><?php echo e($cat); ?></span>
                        <span class="text-gray-300">•</span>
                        <span class="text-xs font-medium text-gray-400"><?php echo e($announcement->date_posted->format('M d, Y • g:i A')); ?></span>
                    </div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight">
                        <?php echo e($announcement->title); ?>

                    </h2>
                </div>
            </div>

            <!-- Content -->
          <div class="prose prose-blue prose-lg max-w-none leading-relaxed text-gray-800 prose-p:text-gray-800 prose-li:text-gray-800 prose-strong:text-gray-900">
    <?php echo nl2br(e($announcement->content)); ?>

</div>
            <!-- Footer -->
            <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <i class="bi bi-person-circle text-gray-400"></i>
                    <span>Posted by Administration</span>
                </div>
                <a href="<?php echo e(route('admin.announcements.index')); ?>" 
                   class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition">
                    Close
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Lock Scroll Script -->
<script>
    document.body.style.overflow = 'hidden';
    
    // Allow closing by clicking backdrop
    document.querySelector('.fixed.inset-0').addEventListener('click', function(e) {
        if (e.target === this) {
            window.location.href = "<?php echo e(route('admin.announcements.index')); ?>";
        }
    });

    // Cleanup on exit
    window.addEventListener('beforeunload', () => {
        document.body.style.overflow = '';
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\announcements\show.blade.php ENDPATH**/ ?>