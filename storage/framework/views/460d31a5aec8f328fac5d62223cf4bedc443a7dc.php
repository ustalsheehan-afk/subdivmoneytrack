

<?php $__env->startSection('title', 'Message Thread - ' . $thread->subject); ?>
<?php $__env->startSection('page-title', 'Message Thread'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto pb-20">
    
    <div class="flex items-center gap-4 mb-8">
        <a href="<?php echo e(route('resident.messages.index')); ?>" class="w-12 h-12 flex items-center justify-center rounded-2xl border border-gray-100 text-gray-400 hover:text-emerald-600 hover:border-emerald-100 hover:bg-emerald-50 transition-all">
            <i class="bi bi-arrow-left text-lg"></i>
        </a>
        <div class="flex-1">
            <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase"><?php echo e($thread->subject); ?></h3>
            <div class="flex items-center gap-3 mt-1">
                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-black rounded-full uppercase tracking-widest"><?php echo e($thread->category); ?></span>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">ID: #<?php echo e(str_pad($thread->id, 5, '0', STR_PAD_LEFT)); ?></span>
            </div>
        </div>
        <div class="text-right">
            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-1">Status</span>
            <span class="px-4 py-1.5 bg-gray-100 text-gray-700 text-xs font-black rounded-xl uppercase tracking-widest"><?php echo e($thread->status); ?></span>
        </div>
    </div>

    
    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden flex flex-col min-h-[500px] mb-8">
        <div class="flex-1 p-8 overflow-y-auto space-y-8 bg-gray-50/30" id="chatContainer">
            <?php $__currentLoopData = $thread->messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex <?php echo e($message->isFromResident() ? 'justify-end' : 'justify-start'); ?>">
                    <div class="max-w-[80%] space-y-2">
                        <div class="flex items-center gap-2 <?php echo e($message->isFromResident() ? 'flex-row-reverse' : ''); ?>">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                <?php echo e($message->isFromResident() ? 'You' : 'Administration'); ?>

                            </span>
                            <span class="text-[9px] text-gray-300"><?php echo e($message->created_at->format('h:i A')); ?></span>
                        </div>
                        <div class="px-6 py-4 rounded-[2rem] text-sm font-medium shadow-sm <?php echo e($message->isFromResident() ? 'bg-[#0D1F1C] text-white rounded-tr-none' : 'bg-white border border-gray-100 text-gray-800 rounded-tl-none'); ?>">
                            <?php echo e($message->body); ?>

                            <?php if($message->attachment): ?>
                                <div class="mt-3 pt-3 border-t <?php echo e($message->isFromResident() ? 'border-white/10' : 'border-gray-50'); ?>">
                                    <a href="<?php echo e(asset('storage/' . $message->attachment)); ?>" target="_blank" class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest <?php echo e($message->isFromResident() ? 'text-[#B6FF5C]' : 'text-emerald-600'); ?>">
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
            <?php if($thread->status !== 'closed'): ?>
                <form action="<?php echo e(route('resident.messages.reply', $thread->id)); ?>" method="POST" enctype="multipart/form-data">
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
            <?php else: ?>
                <div class="p-6 text-center bg-gray-50 rounded-2xl border border-gray-100">
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest">This conversation has been closed.</p>
                </div>
            <?php endif; ?>
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

<?php echo $__env->make('resident.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\resident\messages\show.blade.php ENDPATH**/ ?>