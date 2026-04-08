<?php $__env->startSection('title', 'Manage Board Members'); ?>
<?php $__env->startSection('page-title', 'Board Members'); ?>

<?php $__env->startSection('content'); ?>

<div class="space-y-8 animate-fade-in">

    
    
    
    <div class="glass-card p-8 relative overflow-hidden group">
        
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-brand-accent/5 rounded-full blur-3xl group-hover:bg-brand-accent/10 transition-all duration-700"></div>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                    Board Members
                </h1>
                <p class="mt-2 text-gray-600 text-lg max-w-xl">
                    Manage subdivision leadership and board member profiles.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="<?php echo e(route('admin.board.create')); ?>" class="btn-premium">
                    <i class="bi bi-person-plus-fill"></i>
                    Add Member
                </a>
            </div>
        </div>
    </div>

    
    
    
    <div class="glass-card p-4 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        
        <div class="flex-1 max-w-md">
            <div class="relative group">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-600 transition-colors"></i>
                <input type="text" id="memberSearch" onkeyup="filterMembers(this)" 
                    placeholder="Search by name or position..." 
                    class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all placeholder-gray-400">
            </div>
        </div>

        
        <div class="flex flex-wrap items-center gap-3">
            <div class="relative group/filter">
                <select id="statusFilter" onchange="filterByStatus(this.value)"
                    class="h-11 px-4 flex items-center gap-2 rounded-xl border border-gray-200 bg-white text-[10px] font-black uppercase tracking-widest text-gray-600 hover:border-emerald-500/30 hover:bg-gray-50 transition-all outline-none appearance-none cursor-pointer pr-10">
                    <option value="all">All Status</option>
                    <option value="active">Active Only</option>
                    <option value="inactive">Inactive Only</option>
                </select>
                <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[8px] opacity-50 pointer-events-none"></i>
            </div>
        </div>
    </div>

    
    
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="membersContainer">
        <?php $__empty_1 = true; $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="member-card glass-card p-8 flex flex-col group relative overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300" 
                 data-name="<?php echo e(strtolower($member->name)); ?>" 
                 data-position="<?php echo e(strtolower($member->position)); ?>"
                 data-status="<?php echo e($member->is_active ? 'active' : 'inactive'); ?>">
                
                
                <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-emerald-500/5 rounded-full blur-2xl group-hover:bg-emerald-500/10 transition-all duration-500"></div>

                
                <div x-data="{open:false}" class="absolute top-6 right-6 z-20">
                    <button @click="open=!open" 
                            class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 transition-all">
                        <i class="bi bi-three-dots-vertical text-lg"></i>
                    </button>
                    <div x-show="open" @click.outside="open=false" x-transition 
                         class="absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded-2xl shadow-2xl overflow-hidden z-50 p-1">
                        <a href="<?php echo e(route('admin.board.edit',$member->id)); ?>" 
                           class="flex items-center gap-3 px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition-all">
                            <i class="bi bi-pencil"></i>
                            Edit Member
                        </a>
                        <form action="<?php echo e(route('admin.board.destroy',$member->id)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to remove this board member?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button class="w-full flex items-center gap-3 px-4 py-3 text-[10px] font-black uppercase tracking-widest text-red-500 hover:bg-red-50 rounded-xl transition-all text-left">
                                <i class="bi bi-trash"></i>
                                Delete Member
                            </button>
                        </form>
                    </div>
                </div>

                
                <div class="absolute top-8 left-8">
                    <?php if($member->is_active): ?>
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-[9px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Active
                        </span>
                    <?php else: ?>
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-[9px] font-black uppercase tracking-widest bg-gray-50 text-gray-400 border border-gray-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                            Inactive
                        </span>
                    <?php endif; ?>
                </div>

                
                <div class="flex flex-col items-center text-center mt-8 mb-8">
                    <div class="relative mb-6">
                        <img src="<?php echo e($member->photo ? asset('storage/'.$member->photo) : asset('CDlogo.jpg')); ?>" 
                             onerror="this.onerror=null; this.src='<?php echo e(asset('CDlogo.jpg')); ?>';"
                             class="w-32 h-32 rounded-[32px] object-cover border-4 border-white shadow-2xl group-hover:scale-105 transition-transform duration-500 relative z-10">
                        <div class="absolute -inset-2 bg-emerald-500/5 rounded-[40px] blur-xl group-hover:bg-emerald-500/10 transition-all"></div>
                    </div>

                    <h3 class="text-xl font-black text-gray-900 tracking-tight mb-1 group-hover:text-emerald-600 transition-colors"><?php echo e($member->name); ?></h3>
                    <p class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.2em]"><?php echo e($member->position); ?></p>
                </div>

                
                <?php if($member->bio): ?>
                    <div class="bg-gray-50/50 rounded-2xl p-5 border border-gray-50 mb-8 relative flex-1">
                        <p class="text-xs font-bold text-gray-500 italic leading-relaxed text-center">
                            "<?php echo e(Str::limit($member->bio, 120)); ?>"
                        </p>
                    </div>
                <?php endif; ?>

                
                <div class="mt-auto pt-6 border-t border-gray-100 space-y-3">
                    <?php if($member->email): ?>
                        <div class="flex items-center gap-3 text-gray-500 group/item hover:text-emerald-600 transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center border border-gray-100 group-hover/item:border-emerald-100 group-hover/item:bg-emerald-50 transition-all">
                                <i class="bi bi-envelope text-xs"></i>
                            </div>
                            <span class="text-[11px] font-bold truncate"><?php echo e($member->email); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if($member->phone): ?>
                        <div class="flex items-center gap-3 text-gray-500 group/item hover:text-emerald-600 transition-colors">
                            <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center border border-gray-100 group-hover/item:border-emerald-100 group-hover/item:bg-emerald-50 transition-all">
                                <i class="bi bi-telephone text-xs"></i>
                            </div>
                            <span class="text-[11px] font-bold"><?php echo e($member->phone); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if($member->facebook): ?>
                        <a href="<?php echo e($member->facebook); ?>" target="_blank" 
                           class="flex items-center gap-3 text-gray-500 hover:text-emerald-600 transition-colors group/link">
                            <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center border border-gray-100 group-hover/link:border-emerald-100 group-hover/link:bg-emerald-50 transition-all">
                                <i class="bi bi-facebook text-xs"></i>
                            </div>
                            <span class="text-[11px] font-black uppercase tracking-widest">View Profile</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full flex flex-col items-center justify-center py-24 text-center glass-card">
                <div class="w-24 h-24 bg-gray-50 rounded-[32px] flex items-center justify-center mb-6 text-gray-200 shadow-inner">
                    <i class="bi bi-people text-5xl"></i>
                </div>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">No Board Members</h3>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mt-3">Click "Add Member" to start building leadership</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function filterMembers(input) {
    const filter = input.value.toLowerCase();
    const cards = document.querySelectorAll('.member-card');
    
    cards.forEach(card => {
        const name = card.getAttribute('data-name');
        const position = card.getAttribute('data-position');
        if (name.includes(filter) || position.includes(filter)) {
            card.classList.remove('hidden');
        } else {
            card.classList.add('hidden');
        }
    });
}

function filterByStatus(status) {
    const cards = document.querySelectorAll('.member-card');
    cards.forEach(card => {
        if (status === 'all' || card.getAttribute('data-status') === status) {
            card.classList.remove('hidden');
        } else {
            card.classList.add('hidden');
        }
    });
}
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #CBD5E0; }
</style>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views/admin/board/index.blade.php ENDPATH**/ ?>