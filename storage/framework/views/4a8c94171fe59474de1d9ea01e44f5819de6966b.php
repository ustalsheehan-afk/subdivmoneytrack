<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['id', 'width' => 'max-w-md']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['id', 'width' => 'max-w-md']); ?>
<?php foreach (array_filter((['id', 'width' => 'max-w-md']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>


<div id="<?php echo e($id); ?>Overlay"
     onclick="close<?php echo e(ucfirst($id)); ?>()"
     class="x-drawer-overlay fixed inset-0 bg-black/50 hidden opacity-0 transition-opacity duration-300 z-[9998] backdrop-blur-sm">
</div>


<div id="<?php echo e($id); ?>"
     class="x-drawer-component fixed top-0 right-0 h-full w-full <?php echo e($width); ?> bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-out z-[9999] flex flex-col overflow-hidden">

    
    <div class="flex-1 overflow-hidden">
        <?php echo e($slot); ?>

    </div>

</div>

<script>
    window.open<?php echo e(ucfirst($id)); ?> = function () {
        const overlay = document.getElementById('<?php echo e($id); ?>Overlay');
        const drawer = document.getElementById('<?php echo e($id); ?>');

        if (!overlay || !drawer) return;

        // Close any other open drawers (GLOBAL FIX)
        document.querySelectorAll('.x-drawer-overlay').forEach(el => el.classList.add('hidden', 'opacity-0'));
        document.querySelectorAll('.x-drawer-component').forEach(el => {
            if (el.classList.contains('translate-x-0')) {
                el.classList.add('translate-x-full');
                el.classList.remove('translate-x-0');
            }
        });

        // Lock background scroll
        document.body.classList.add('overflow-hidden');

        overlay.classList.remove('hidden');
        requestAnimationFrame(() => {
            overlay.classList.remove('opacity-0');
            drawer.classList.remove('translate-x-full');
            drawer.classList.add('translate-x-0');
        });
    }

    window.close<?php echo e(ucfirst($id)); ?> = function () {
        const overlay = document.getElementById('<?php echo e($id); ?>Overlay');
        const drawer = document.getElementById('<?php echo e($id); ?>');

        if (!overlay || !drawer) return;

        drawer.classList.add('translate-x-full');
        drawer.classList.remove('translate-x-0');
        overlay.classList.add('opacity-0');

        setTimeout(() => {
            overlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 300);
    }
</script>
<?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views/components/drawer.blade.php ENDPATH**/ ?>