

<?php $__env->startSection('title', 'New Message'); ?>
<?php $__env->startSection('page-title', 'New Message'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto pb-20">
    <div class="flex items-center gap-4 mb-8">
        <a href="<?php echo e(route('resident.messages.index')); ?>" class="w-12 h-12 flex items-center justify-center rounded-2xl border border-gray-100 text-gray-400 hover:text-emerald-600 hover:border-emerald-100 hover:bg-emerald-50 transition-all">
            <i class="bi bi-arrow-left text-lg"></i>
        </a>
        <div>
            <h3 class="text-2xl font-black text-gray-900 tracking-tight">New Message</h3>
            <p class="text-xs font-medium text-gray-500 uppercase tracking-widest mt-1">Send an inquiry to the administration</p>
        </div>
    </div>

    <form action="<?php echo e(route('resident.messages.store')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        
        <?php if($moduleType): ?>
            <input type="hidden" name="module_type" value="<?php echo e($moduleType); ?>">
            <input type="hidden" name="module_id" value="<?php echo e($moduleId); ?>">
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm space-y-8">
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest ml-1">Subject</label>
                            <input type="text" name="subject" value="<?php echo e(old('subject', $subject)); ?>" 
                                class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50/50 text-sm font-medium focus:bg-white focus:border-emerald-500 focus:ring-8 focus:ring-emerald-500/5 transition-all outline-none" 
                                placeholder="Enter message subject..." required>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest ml-1">Message Body</label>
                            <textarea name="body" rows="6" 
                                class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50/50 text-sm font-medium focus:bg-white focus:border-emerald-500 focus:ring-8 focus:ring-emerald-500/5 transition-all outline-none resize-none" 
                                placeholder="Describe your inquiry in detail..." required><?php echo e(old('body')); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="space-y-6">
                <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm space-y-6">
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest ml-1">Category</label>
                        <div class="relative">
                            <select name="category" class="w-full px-6 py-4 rounded-2xl border border-gray-100 bg-gray-50/50 text-sm font-bold appearance-none focus:bg-white focus:border-emerald-500 transition-all outline-none">
                                <option value="general">General Inquiry</option>
                                <option value="payment">Payment Concern</option>
                                <option value="complaint">Complaint</option>
                                <option value="reservation">Reservation</option>
                                <option value="service_request">Service Request</option>
                            </select>
                            <i class="bi bi-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest ml-1">Attachment (Optional)</label>
                        <label class="block w-full px-6 py-4 rounded-2xl border border-dashed border-gray-200 bg-gray-50/50 hover:bg-emerald-50/50 hover:border-emerald-200 transition-all cursor-pointer text-center group">
                            <input type="file" name="attachment" class="hidden">
                            <i class="bi bi-paperclip text-lg text-gray-400 group-hover:text-emerald-600"></i>
                            <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1 group-hover:text-emerald-600">Choose File</span>
                        </label>
                    </div>
                </div>

                <div class="space-y-4 pt-2">
                    <button type="submit" class="w-full py-5 rounded-2xl bg-[#0D1F1C] text-[#B6FF5C] font-black uppercase tracking-widest hover:shadow-[0_0_20px_rgba(182,255,92,0.3)] transition-all active:scale-95">
                        Send Message
                    </button>
                    <a href="<?php echo e(route('resident.messages.index')); ?>" class="block w-full py-5 text-center text-gray-500 text-sm font-bold hover:text-gray-700 transition-all">
                        Cancel / Back
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('resident.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\resident\messages\create.blade.php ENDPATH**/ ?>