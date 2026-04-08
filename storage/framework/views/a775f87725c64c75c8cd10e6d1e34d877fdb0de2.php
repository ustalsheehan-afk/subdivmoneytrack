<?php $__env->startSection('title', 'Messages - ' . $thread->resident->full_name); ?>
<?php $__env->startSection('page-title', 'Message Thread'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto pb-20">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <div class="lg:col-span-1 hidden lg:block space-y-4">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Inbox</h3>
                </div>
                
                <div class="divide-y divide-gray-50 max-h-[700px] overflow-y-auto">
                    <?php $__currentLoopData = \App\Models\MessageThread::with(['resident', 'latestMessage'])->orderBy('last_message_at', 'desc')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sidebarThread): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('admin.messages.show', $sidebarThread->id)); ?>" 
                           class="block p-5 hover:bg-emerald-50/30 transition-all relative group <?php echo e($thread->id === $sidebarThread->id ? 'bg-emerald-50/50' : ''); ?>">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 rounded-lg bg-[#0D1F1C] text-[#B6FF5C] flex items-center justify-center text-[10px] font-black italic">
                                    <?php echo e(substr($sidebarThread->resident->first_name, 0, 1)); ?><?php echo e(substr($sidebarThread->resident->last_name, 0, 1)); ?>

                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-xs font-black text-gray-900 truncate uppercase"><?php echo e($sidebarThread->resident->full_name); ?></h4>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest"><?php echo e($sidebarThread->category); ?></p>
                                </div>
                            </div>
                            <p class="text-[11px] text-gray-500 line-clamp-1 font-medium italic">
                                <?php echo e($sidebarThread->latestMessage->body ?? 'No messages'); ?>

                            </p>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        
        <div class="lg:col-span-3 space-y-6">
            
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-[#0D1F1C] text-[#B6FF5C] flex items-center justify-center text-lg font-black italic shadow-lg">
                        <?php echo e(substr($thread->resident->first_name, 0, 1)); ?><?php echo e(substr($thread->resident->last_name, 0, 1)); ?>

                    </div>
                    <div>
                        <h2 class="text-lg font-black text-gray-900 uppercase tracking-tight"><?php echo e($thread->resident->full_name); ?></h2>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="px-2 py-0.5 bg-gray-100 text-gray-500 text-[10px] font-black rounded-full uppercase"><?php echo e($thread->category); ?></span>
                            <span class="text-[10px] font-bold text-gray-400">#<?php echo e(str_pad($thread->id, 5, '0', STR_PAD_LEFT)); ?></span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <form action="<?php echo e(route('admin.messages.updateStatus', $thread->id)); ?>" method="POST" class="flex items-center gap-2">
                        <?php echo csrf_field(); ?>
                        <select name="status" onchange="this.form.submit()" class="text-[10px] font-black uppercase tracking-widest px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-4 focus:ring-emerald-500/5 outline-none transition-all">
                            <option value="pending" <?php echo e($thread->status == 'pending' ? 'selected' : ''); ?>>Pending</option>
                            <option value="in_progress" <?php echo e($thread->status == 'in_progress' ? 'selected' : ''); ?>>In Progress</option>
                            <option value="replied" <?php echo e($thread->status == 'replied' ? 'selected' : ''); ?>>Replied</option>
                            <option value="closed" <?php echo e($thread->status == 'closed' ? 'selected' : ''); ?>>Closed</option>
                        </select>
                    </form>
                </div>
            </div>

            
            <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden flex flex-col h-[600px]">
                <div class="flex-1 p-8 overflow-y-auto space-y-8 bg-gray-50/30" id="chatContainer">
                    <?php $__currentLoopData = $thread->messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex <?php echo e($message->isFromAdmin() ? 'justify-end' : 'justify-start'); ?>">
                            <div class="max-w-[70%] space-y-2">
                                <div class="flex items-center gap-2 <?php echo e($message->isFromAdmin() ? 'flex-row-reverse' : ''); ?>">
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                        <?php echo e($message->isFromAdmin() ? 'Admin' : $thread->resident->first_name); ?>

                                    </span>
                                    <span class="text-[9px] text-gray-300"><?php echo e($message->created_at->format('h:i A')); ?></span>
                                </div>
                                <div class="px-6 py-4 rounded-[2rem] text-sm font-medium shadow-sm <?php echo e($message->isFromAdmin() ? 'bg-[#0D1F1C] text-white rounded-tr-none' : 'bg-white border border-gray-100 text-gray-800 rounded-tl-none'); ?>">
                                    <?php echo e($message->body); ?>

                                    <?php if($message->attachment): ?>
                                        <div class="mt-3 pt-3 border-t <?php echo e($message->isFromAdmin() ? 'border-white/10' : 'border-gray-50'); ?>">
                                            <a href="<?php echo e(asset('storage/' . $message->attachment)); ?>" target="_blank" class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest <?php echo e($message->isFromAdmin() ? 'text-[#B6FF5C]' : 'text-emerald-600'); ?>">
                                                <i class="bi bi-paperclip"></i> View Attachment
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                
                <div class="p-6 bg-white border-t border-gray-50">
                    <form action="<?php echo e(route('admin.messages.reply', $thread->id)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="relative">
                            <textarea name="body" rows="2" placeholder="Write your reply here..." 
                                class="w-full px-8 py-5 rounded-[2rem] bg-gray-50 border border-gray-100 text-sm font-medium focus:bg-white focus:border-emerald-500 focus:ring-8 focus:ring-emerald-500/5 transition-all outline-none resize-none pr-32" required></textarea>
                            
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center gap-2">
                                <label class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-100 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 cursor-pointer transition-all">
                                    <input type="file" name="attachment" class="hidden">
                                    <i class="bi bi-paperclip text-lg"></i>
                                </label>
                                <button type="submit" class="w-10 h-10 flex items-center justify-center rounded-full bg-[#0D1F1C] text-[#B6FF5C] hover:shadow-[0_0_15px_rgba(182,255,92,0.3)] transition-all">
                                    <i class="bi bi-send-fill"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatContainer = document.getElementById('chatContainer');
        chatContainer.scrollTop = chatContainer.scrollHeight;
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\messages\show.blade.php ENDPATH**/ ?>