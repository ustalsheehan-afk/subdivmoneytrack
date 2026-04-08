<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'searchName' => 'search',
    'searchValue' => request('search'),
    'searchPlaceholder' => 'Search...',
    'statusName' => 'status',
    'statusOptions' => [],
    'statusValue' => request('status'),
    'dateName' => 'date',
    'dateOptions' => [],
    'dateValue' => request('date'),
    'clearHref' => url()->current(),
    'primary' => null,
    'method' => 'GET',
    'action' => url()->current(),
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'searchName' => 'search',
    'searchValue' => request('search'),
    'searchPlaceholder' => 'Search...',
    'statusName' => 'status',
    'statusOptions' => [],
    'statusValue' => request('status'),
    'dateName' => 'date',
    'dateOptions' => [],
    'dateValue' => request('date'),
    'clearHref' => url()->current(),
    'primary' => null,
    'method' => 'GET',
    'action' => url()->current(),
]); ?>
<?php foreach (array_filter(([
    'searchName' => 'search',
    'searchValue' => request('search'),
    'searchPlaceholder' => 'Search...',
    'statusName' => 'status',
    'statusOptions' => [],
    'statusValue' => request('status'),
    'dateName' => 'date',
    'dateOptions' => [],
    'dateValue' => request('date'),
    'clearHref' => url()->current(),
    'primary' => null,
    'method' => 'GET',
    'action' => url()->current(),
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>
<form method="<?php echo e($method); ?>" action="<?php echo e($action); ?>" class="glass-card p-4 sm:p-5">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 sm:gap-4 items-center">
        <div class="lg:col-span-4">
            <div class="relative">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="<?php echo e($searchName); ?>" value="<?php echo e($searchValue); ?>"
                       placeholder="<?php echo e($searchPlaceholder); ?>"
                       class="input pl-9" />
            </div>
        </div>
        <div class="lg:col-span-2">
            <div class="relative">
                <select name="<?php echo e($statusName); ?>" class="select">
                    <option value="">All Status</option>
                    <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($val); ?>" <?php echo e((string)$statusValue === (string)$val ? 'selected' : ''); ?>><?php echo e(strtoupper($label)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <i class="bi bi-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 pointer-events-none"></i>
            </div>
        </div>
        <div class="lg:col-span-2">
            <div class="relative">
                <select name="<?php echo e($dateName); ?>" class="select">
                    <option value="">Any Date</option>
                    <?php $__currentLoopData = $dateOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($val); ?>" <?php echo e((string)$dateValue === (string)$val ? 'selected' : ''); ?>><?php echo e(strtoupper($label)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <i class="bi bi-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 pointer-events-none"></i>
            </div>
        </div>
        <div class="lg:col-span-2 hidden lg:block"></div>
        <div class="lg:col-span-2 flex items-center gap-2 justify-end">
            <a href="<?php echo e($clearHref); ?>" class="btn-secondary">
                Clear
            </a>
            <?php if($primary): ?>
                <a href="<?php echo e($primary['href']); ?>" class="btn-premium">
                    <?php if(isset($primary['icon'])): ?> <i class="bi <?php echo e($primary['icon']); ?>"></i> <?php endif; ?>
                    <?php echo e($primary['label']); ?>

                </a>
            <?php endif; ?>
            <button type="submit" class="btn-secondary">
                Apply
            </button>
        </div>
    </div>
</form>
<?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\components\resident-filter-bar.blade.php ENDPATH**/ ?>