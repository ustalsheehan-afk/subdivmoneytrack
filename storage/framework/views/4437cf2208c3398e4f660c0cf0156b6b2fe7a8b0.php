<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'label' => null,
    'icon' => null,
    'title',
    'description' => null,
    'tabs' => [],
    'actions' => null,
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'label' => null,
    'icon' => null,
    'title',
    'description' => null,
    'tabs' => [],
    'actions' => null,
]); ?>
<?php foreach (array_filter(([
    'label' => null,
    'icon' => null,
    'title',
    'description' => null,
    'tabs' => [],
    'actions' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div class="relative overflow-hidden bg-[#081412] rounded-2xl p-6 sm:p-8 shadow-2xl group animate-fade-in">
    
    <div class="absolute -right-20 -top-20 w-80 h-80 bg-[rgba(182,255,92,0.10)] rounded-full blur-3xl group-hover:bg-[rgba(182,255,92,0.20)] transition-all duration-1000"></div>
    <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl"></div>
    
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 relative z-10">
        <div class="flex-1 space-y-3">
            <?php if($label): ?>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-emerald-500/10 border border-emerald-500/20">
                <?php if($icon): ?> <i class="bi <?php echo e($icon); ?> text-emerald-400 text-[10px]"></i> <?php endif; ?>
                <span class="text-[9px] font-black text-emerald-400 uppercase tracking-[0.2em]"><?php echo e($label); ?></span>
            </div>
            <?php endif; ?>
            
            <div class="flex items-center gap-4">
                <h2 class="text-2xl md:text-3xl font-black text-white tracking-tight leading-none"><?php echo e($title); ?></h2>
            </div>

            <p class="text-[13px] font-medium text-white/70 max-w-2xl leading-relaxed">
                <?php echo e($description); ?>

            </p>

        </div>
        
        <?php if($actions || count($tabs) > 0): ?>
        <div class="w-full lg:w-auto lg:min-w-[260px] flex flex-col gap-3">
            <?php if($actions): ?>
                <div class="flex w-full justify-start lg:justify-end">
                    <?php echo e($actions); ?>

                </div>
            <?php endif; ?>

            <?php if(count($tabs) > 0): ?>
            <div class="relative">
                <select
                    class="w-full px-4 py-3 pr-10 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all border bg-white/5 text-white/80 border-white/10 hover:bg-white/10 hover:border-white/20 focus:bg-white/10 focus:border-white/30 outline-none appearance-none"
                    @change="
                        const opt = $event.target.selectedOptions[0];
                        if (opt?.dataset?.href) { window.location = opt.dataset.href; return; }
                        if (opt?.dataset?.click) { eval(opt.dataset.click); return; }
                    "
                >
                    <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $href = $tab['href'] ?? null;
                            $click = $tab['click'] ?? null;
                            $active = $tab['active'] ?? false;
                            $activeCondition = $tab['active_condition'] ?? null;
                        ?>
                        <option
                            value="<?php echo e($href ?? ($tab['id'] ?? '')); ?>"
                            <?php echo e($href && $active ? 'selected' : ''); ?>

                            <?php if($href): ?> data-href="<?php echo e($href); ?>" <?php endif; ?>
                            <?php if($click): ?> data-click="<?php echo e($click); ?>" <?php endif; ?>
                            <?php if(!$href && $activeCondition): ?> :selected="<?php echo e($activeCondition); ?>" <?php endif; ?>
                            class="text-black"
                        >
                            <?php echo e($tab['label']); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <i class="bi bi-funnel-fill absolute right-4 top-1/2 -translate-y-1/2 text-xs text-white/60 pointer-events-none"></i>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views/components/resident-hero-header.blade.php ENDPATH**/ ?>