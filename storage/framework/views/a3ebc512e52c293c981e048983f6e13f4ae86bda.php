
<?php $__env->startSection('title', 'Request Details'); ?>
<?php $__env->startSection('page-title', 'Request Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="h-full bg-[#F8F9FB] overflow-y-auto custom-scrollbar">
    <div class="max-w-6xl mx-auto px-6 py-8 flex flex-col gap-8 pb-24 animate-fade-in">

        
        
        
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-emerald-500/10 border border-emerald-500/20 mb-3">
                    <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">Ticket #<?php echo e(str_pad($requestItem->id, 4, '0', STR_PAD_LEFT)); ?></span>
                </div>
                <h1 class="text-4xl font-black text-gray-900 tracking-tight leading-none capitalize"><?php echo e($requestItem->type); ?></h1>
                <p class="text-sm font-black text-gray-400 mt-3 uppercase tracking-widest">Submitted <?php echo e($requestItem->created_at->diffForHumans()); ?></p>
            </div>
            
            <a href="<?php echo e(route('resident.requests.index')); ?>" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-gray-200 rounded-2xl text-sm font-black text-gray-400 hover:text-gray-900 hover:border-gray-900 transition-all duration-300 shadow-sm">
                <i class="bi bi-arrow-left"></i>
                <span>BACK TO LIST</span>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            
            
            
            <div class="lg:col-span-2 space-y-8">
                
                
                <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm p-10 relative overflow-hidden group">
                    <div class="absolute -right-20 -top-20 w-80 h-80 bg-emerald-500/5 rounded-full blur-3xl group-hover:bg-emerald-500/10 transition-all duration-1000"></div>
                    
                    <div class="flex items-center gap-4 mb-8 relative z-10">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center border border-emerald-100 shadow-sm">
                            <i class="bi bi-text-left text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-gray-900 text-xl tracking-tight">Request Description</h3>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Problem Details & Context</p>
                        </div>
                    </div>

                    <div class="relative z-10 p-8 rounded-[28px] bg-gray-50 border border-gray-100 text-gray-700 leading-relaxed font-medium">
                        <?php echo e($requestItem->description); ?>

                    </div>

                    <?php if($requestItem->photo): ?>
                        <div class="mt-10 relative z-10">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 ml-2">ATTACHED EVIDENCE</p>
                            <div class="relative group/photo inline-block">
                                <div class="absolute inset-0 bg-emerald-500/20 rounded-[32px] blur-xl opacity-0 group-hover/photo:opacity-100 transition-opacity duration-500"></div>
                                <img src="<?php echo e(asset('storage/' . $requestItem->photo)); ?>" 
                                     class="max-h-96 rounded-[32px] border-4 border-white shadow-2xl relative z-10 cursor-zoom-in hover:scale-[1.02] transition-transform duration-500"
                                     onclick="window.open(this.src,'_blank')">
                                <div class="absolute bottom-6 right-6 z-20 w-12 h-12 bg-[#081412] text-white rounded-2xl flex items-center justify-center opacity-0 group-hover/photo:opacity-100 transition-all duration-300 translate-y-2 group-hover/photo:translate-y-0 shadow-2xl">
                                    <i class="bi bi-arrows-fullscreen"></i>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            
            
            
            <div class="space-y-8">
                
                
                <div class="bg-[#081412] rounded-[40px] p-10 shadow-2xl relative overflow-hidden group">
                    <div class="absolute -right-20 -top-20 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl group-hover:bg-emerald-500/20 transition-all duration-1000"></div>
                    
                    <h3 class="relative z-10 font-black text-white text-xl tracking-tight mb-8">Quick Overview</h3>
                    
                    <div class="relative z-10 space-y-5">
                        <div class="flex items-center justify-between p-5 rounded-2xl bg-white/5 border border-white/5">
                            <span class="text-[10px] font-black text-white/30 uppercase tracking-widest">Current Status</span>
                            <?php
                                $statusMap = [
                                    'pending'    => ['bg' => 'bg-amber-500/20',   'text' => 'text-amber-400'],
                                    'in progress' => ['bg' => 'bg-blue-500/20',    'text' => 'text-blue-400'],
                                    'completed'  => ['bg' => 'bg-emerald-500/20', 'text' => 'text-emerald-400'],
                                    'approved'   => ['bg' => 'bg-emerald-500/20', 'text' => 'text-emerald-400'],
                                    'rejected'   => ['bg' => 'bg-red-500/20',     'text' => 'text-red-400'],
                                ];
                                $s = $statusMap[strtolower($requestItem->status)] ?? ['bg' => 'bg-gray-500/20', 'text' => 'text-gray-400'];
                            ?>
                            <span class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest <?php echo e($s['bg']); ?> <?php echo e($s['text']); ?>">
                                <?php echo e($requestItem->status); ?>

                            </span>
                        </div>

                        <div class="flex items-center justify-between p-5 rounded-2xl bg-white/5 border border-white/5">
                            <span class="text-[10px] font-black text-white/30 uppercase tracking-widest">Priority</span>
                            <?php
                                $pMap = [
                                    'high'   => ['bg' => 'bg-red-500/20',    'text' => 'text-red-400'],
                                    'medium' => ['bg' => 'bg-amber-500/20',  'text' => 'text-amber-400'],
                                    'low'    => ['bg' => 'bg-emerald-500/20', 'text' => 'text-emerald-400'],
                                ];
                                $p = $pMap[strtolower($requestItem->priority)] ?? ['bg' => 'bg-gray-500/20', 'text' => 'text-gray-400'];
                            ?>
                            <span class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest <?php echo e($p['bg']); ?> <?php echo e($p['text']); ?>">
                                <?php echo e($requestItem->priority); ?>

                            </span>
                        </div>

                        <div class="flex items-center justify-between p-5 rounded-2xl bg-white/5 border border-white/5">
                            <span class="text-[10px] font-black text-white/30 uppercase tracking-widest">Category</span>
                            <span class="text-sm font-black text-white tracking-tight capitalize"><?php echo e($requestItem->type); ?></span>
                        </div>
                    </div>
                </div>

                
                <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm p-10 relative overflow-hidden group">
                    <div class="flex items-center gap-4 mb-10 relative z-10">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center border border-emerald-100 shadow-sm">
                            <i class="bi bi-clock-history text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-gray-900 text-xl tracking-tight">Timeline</h3>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Request Journey</p>
                        </div>
                    </div>

                    <div class="relative pl-8 space-y-10">
                        <div class="absolute left-[11px] top-2 bottom-2 w-[2px] bg-gray-100"></div>

                        
                        <div class="relative">
                            <div class="absolute -left-[30px] top-1 w-[18px] h-[18px] rounded-full bg-emerald-500 border-4 border-white shadow-lg z-10"></div>
                            <p class="text-sm font-black text-gray-900 tracking-tight leading-none mb-1">Submitted</p>
                            <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-2">
                                <?php echo e($requestItem->created_at->format('M d, Y • g:i A')); ?>

                            </p>
                            <p class="text-[11px] font-medium text-gray-400 leading-relaxed">Request received and added to queue.</p>
                        </div>

                        
                        <?php
                            $isInProgress = in_array($requestItem->status,['in progress','completed','approved']);
                            $progressDate = $requestItem->processed_at ?? ($isInProgress ? $requestItem->updated_at : null);
                        ?>
                        <div class="relative <?php echo e(!$isInProgress ? 'opacity-30 grayscale' : ''); ?>">
                            <div class="absolute -left-[30px] top-1 w-[18px] h-[18px] rounded-full <?php echo e($isInProgress ? 'bg-blue-500' : 'bg-gray-200'); ?> border-4 border-white shadow-lg z-10"></div>
                            <p class="text-sm font-black text-gray-900 tracking-tight leading-none mb-1">Processing</p>
                            <?php if($progressDate): ?>
                                <p class="text-[10px] font-black text-blue-500 uppercase tracking-widest mb-2">
                                    <?php echo e($progressDate->format('M d, Y • g:i A')); ?>

                                </p>
                            <?php endif; ?>
                            <p class="text-[11px] font-medium text-gray-400 leading-relaxed">Maintenance team is working on this.</p>
                        </div>

                        
                        <?php
                            $isCompleted = in_array($requestItem->status, ['completed', 'approved']);
                            $completedDate = $requestItem->completed_at ?? ($isCompleted ? $requestItem->updated_at : null);
                        ?>
                        <div class="relative <?php echo e(!$isCompleted ? 'opacity-30 grayscale' : ''); ?>">
                            <div class="absolute -left-[30px] top-1 w-[18px] h-[18px] rounded-full <?php echo e($isCompleted ? 'bg-emerald-500' : 'bg-gray-200'); ?> border-4 border-white shadow-lg z-10"></div>
                            <p class="text-sm font-black text-gray-900 tracking-tight leading-none mb-1">Resolution</p>
                            <?php if($completedDate): ?>
                                <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-2">
                                    <?php echo e($completedDate->format('M d, Y • g:i A')); ?>

                                </p>
                            <?php endif; ?>
                            <p class="text-[11px] font-medium text-gray-400 leading-relaxed">The request has been resolved.</p>
                        </div>
                    </div>
                </div>

                
                <?php if($requestItem->status == 'pending'): ?>
                <div class="p-8 rounded-[40px] bg-emerald-500/5 border border-emerald-500/10 text-center group/edit">
                    <div class="w-16 h-16 bg-emerald-500/10 text-emerald-500 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover/edit:scale-110 transition-transform">
                        <i class="bi bi-pencil-square text-2xl"></i>
                    </div>
                    <h4 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-2">Need to update?</h4>
                    <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest leading-relaxed mb-8 px-4">
                        You can modify this request while it is still in the pending queue.
                    </p>
                    <a href="<?php echo e(route('resident.requests.edit', $requestItem->id)); ?>" 
                       class="inline-flex items-center gap-3 px-8 py-4 bg-[#081412] text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-emerald-600 transition-all duration-300 shadow-xl shadow-emerald-500/10">
                        Edit Request Details
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('resident.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\resident\requests\show.blade.php ENDPATH**/ ?>