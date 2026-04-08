

<?php $__env->startSection('title', 'Messages'); ?>
<?php $__env->startSection('page-title', 'Messages Center'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8" x-data="{ filter: 'all' }">
    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.resident-hero-header','data' => ['label' => 'Inbox & Notifications','icon' => 'bi-chat-dots-fill','title' => 'Messages','description' => 'Stay connected with the administration and keep track of your inquiries and subdivision alerts.','tabs' => [
            ['id' => 'all', 'label' => 'All', 'icon' => 'bi-grid-fill', 'click' => 'filter = \'all\'', 'active_condition' => 'filter === \'all\''],
            ['id' => 'unread', 'label' => 'Unread', 'icon' => 'bi-envelope-fill', 'click' => 'filter = \'unread\'', 'active_condition' => 'filter === \'unread\''],
            ['id' => 'replied', 'label' => 'Replied', 'icon' => 'bi-reply-fill', 'click' => 'filter = \'replied\'', 'active_condition' => 'filter === \'replied\''],
        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('resident-hero-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Inbox & Notifications','icon' => 'bi-chat-dots-fill','title' => 'Messages','description' => 'Stay connected with the administration and keep track of your inquiries and subdivision alerts.','tabs' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['id' => 'all', 'label' => 'All', 'icon' => 'bi-grid-fill', 'click' => 'filter = \'all\'', 'active_condition' => 'filter === \'all\''],
            ['id' => 'unread', 'label' => 'Unread', 'icon' => 'bi-envelope-fill', 'click' => 'filter = \'unread\'', 'active_condition' => 'filter === \'unread\''],
            ['id' => 'replied', 'label' => 'Replied', 'icon' => 'bi-reply-fill', 'click' => 'filter = \'replied\'', 'active_condition' => 'filter === \'replied\''],
        ])]); ?>
         <?php $__env->slot('actions', null, []); ?> 
            <a href="<?php echo e(route('resident.messages.create')); ?>" class="btn-premium">
                <i class="bi bi-plus-lg"></i>
                New Message
            </a>
         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-6">
            <div class="glass-card overflow-hidden min-h-[500px]">
                <div class="p-8 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Inbox Conversations</h3>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-black rounded-full uppercase">
                            <?php echo e($threads->count()); ?> Threads
                        </span>
                    </div>
                </div>

                <div class="divide-y divide-gray-50">
                    <?php $__empty_1 = true; $__currentLoopData = $threads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thread): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $unreadCount = $thread->unreadMessagesCount();
                            $latestMessage = $thread->latestMessage;
                            $isReplied = $latestMessage && $latestMessage->user_id != auth()->id();
                        ?>
                        <a href="<?php echo e(route('resident.messages.show', $thread->id)); ?>" 
                           class="block p-8 hover:bg-emerald-50/20 transition-all group relative"
                           x-show="filter === 'all' || 
                                  (filter === 'unread' && <?php echo e($unreadCount > 0 ? 'true' : 'false'); ?>) || 
                                  (filter === 'replied' && <?php echo e($isReplied ? 'true' : 'false'); ?>)">
                            <?php if($thread->unreadMessagesCount() > 0): ?>
                                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-12 bg-emerald-500 rounded-r-full"></div>
                            <?php endif; ?>
                            
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <span class="px-3 py-1 bg-gray-100 text-gray-500 text-[10px] font-black rounded-full uppercase tracking-widest"><?php echo e($thread->category); ?></span>
                                    <h4 class="text-sm font-black text-gray-900 group-hover:text-emerald-600 transition-colors uppercase tracking-tight"><?php echo e($thread->subject); ?></h4>
                                </div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest"><?php echo e($thread->last_message_at->diffForHumans()); ?></span>
                            </div>
                            
                            <div class="flex items-center justify-between gap-4">
                                <p class="text-[11px] text-gray-500 font-medium italic truncate max-w-[80%]">
                                    <?php echo e($thread->latestMessage->body ?? 'No messages yet'); ?>

                                </p>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-black uppercase tracking-widest <?php echo e($thread->status == 'closed' ? 'text-gray-400' : 'text-emerald-600'); ?>">
                                        <?php echo e($thread->status); ?>

                                    </span>
                                    <i class="bi bi-chevron-right text-gray-300 group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="p-20 text-center">
                            <div class="w-20 h-20 bg-emerald-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-emerald-600">
                                <i class="bi bi-chat-quote text-4xl"></i>
                            </div>
                            <h3 class="text-xl font-black text-gray-900 tracking-tight mb-2 uppercase">No Conversations Found</h3>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest max-w-[250px] mx-auto leading-relaxed">
                                You haven't started any conversations yet. Click "New Message" above to get in touch with the administration.
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="space-y-6">
            <div class="bg-[#0D1F1C] p-8 rounded-[2.5rem] shadow-xl shadow-emerald-900/20 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 rounded-full -mr-16 -mt-16 blur-2xl group-hover:scale-110 transition-transform"></div>
                <h4 class="text-[11px] font-black text-emerald-500 uppercase tracking-widest mb-6 relative z-10">Message Templates</h4>
                <div class="space-y-3 relative z-10">
                    <a href="<?php echo e(route('resident.messages.create', ['category' => 'payment', 'subject' => 'Payment Inquiry'])); ?>" class="block p-4 bg-white/5 border border-white/10 rounded-2xl hover:bg-white/10 transition-all text-xs font-bold text-white uppercase tracking-widest">
                        Payment Inquiry
                    </a>
                    <a href="<?php echo e(route('resident.messages.create', ['category' => 'complaint', 'subject' => 'Service Complaint'])); ?>" class="block p-4 bg-white/5 border border-white/10 rounded-2xl hover:bg-white/10 transition-all text-xs font-bold text-white uppercase tracking-widest">
                        Service Complaint
                    </a>
                    <a href="<?php echo e(route('resident.messages.create', ['category' => 'reservation', 'subject' => 'Booking Question'])); ?>" class="block p-4 bg-white/5 border border-white/10 rounded-2xl hover:bg-white/10 transition-all text-xs font-bold text-white uppercase tracking-widest">
                        Booking Question
                    </a>
                </div>
            </div>

            <div class="glass-card p-8">
                <h4 class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-6">Need Support?</h4>
                <div class="p-6 bg-emerald-50/50 rounded-2xl border border-emerald-100/50 text-center">
                    <i class="bi bi-headset text-2xl text-emerald-600 mb-2 inline-block"></i>
                    <p class="text-[10px] font-black text-emerald-700 uppercase tracking-widest">Customer Support</p>
                    <p class="text-[11px] text-gray-500 font-bold mt-2">Open Mon-Fri • 8AM-5PM</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('resident.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\resident\messages\index.blade.php ENDPATH**/ ?>