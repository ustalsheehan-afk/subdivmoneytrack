<?php $__env->startSection('title', 'Vistabellas Board'); ?>
<?php $__env->startSection('page-title', 'Vistabellas Board'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.resident-hero-header','data' => ['label' => 'Leadership Team','icon' => 'bi-people-fill','title' => 'Meet Your Vistabellas Board','description' => 'Dedicated to making Vistabellas a better place to call home. Our board of directors is composed of resident volunteers committed to maintaining the beauty, safety, and community spirit.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('resident-hero-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Leadership Team','icon' => 'bi-people-fill','title' => 'Meet Your Vistabellas Board','description' => 'Dedicated to making Vistabellas a better place to call home. Our board of directors is composed of resident volunteers committed to maintaining the beauty, safety, and community spirit.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php $__currentLoopData = $boardMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $photoUrl = $member->photo ? asset('storage/' . $member->photo) : asset('images/default-member.jpg');
                $email = trim((string) ($member->email ?? ''));
                $phoneRaw = trim((string) ($member->phone ?? ''));
                $phoneTel = preg_replace('/[^\d\+]/', '', $phoneRaw);
                $facebookRaw = trim((string) ($member->facebook ?? ''));
                $facebookUrl = $facebookRaw && !preg_match('/^https?:\/\//i', $facebookRaw) ? 'https://' . $facebookRaw : $facebookRaw;
            ?>

            <div class="glass-card overflow-hidden p-6 sm:p-7 flex flex-col gap-6">
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 text-[9px] font-black uppercase tracking-widest">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Active
                    </span>
                </div>

                <div class="flex flex-col items-center text-center gap-3">
                    <div class="w-24 h-24 rounded-[28px] overflow-hidden border border-gray-100 bg-gray-50 shadow-lg shadow-gray-200/50">
                        <img src="<?php echo e($photoUrl); ?>" alt="<?php echo e($member->name); ?>" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <div class="text-xl font-black text-gray-900 tracking-tight"><?php echo e($member->name); ?></div>
                        <div class="mt-1 text-[9px] font-black text-emerald-700 uppercase tracking-[0.35em]">
                            <?php echo e($member->position); ?>

                        </div>
                    </div>
                </div>

                <div class="rounded-2xl bg-gray-50 border border-gray-100 p-5 text-center">
                    <p class="text-[12px] text-gray-600 font-medium italic leading-relaxed">
                        "<?php echo e($member->bio); ?>"
                    </p>
                </div>

                <div class="pt-2 border-t border-gray-100 space-y-3">
                    <?php if($email): ?>
                        <a href="mailto:<?php echo e($email); ?>" class="flex items-center gap-3 p-3 rounded-2xl bg-white border border-gray-100 hover:bg-gray-50 transition">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-500">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Email</div>
                                <div class="text-[12px] font-bold text-gray-700 truncate"><?php echo e($email); ?></div>
                            </div>
                        </a>
                    <?php endif; ?>

                    <?php if($phoneTel): ?>
                        <a href="tel:<?php echo e($phoneTel); ?>" class="flex items-center gap-3 p-3 rounded-2xl bg-white border border-gray-100 hover:bg-gray-50 transition">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-500">
                                <i class="bi bi-telephone"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Phone</div>
                                <div class="text-[12px] font-bold text-gray-700 truncate"><?php echo e($phoneRaw); ?></div>
                            </div>
                        </a>
                    <?php endif; ?>

                    <?php if($facebookUrl): ?>
                        <a href="<?php echo e($facebookUrl); ?>" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 p-3 rounded-2xl bg-white border border-gray-100 hover:bg-gray-50 transition">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-500">
                                <i class="bi bi-facebook"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Facebook</div>
                                <div class="text-[12px] font-bold text-gray-700 truncate"><?php echo e($facebookRaw); ?></div>
                            </div>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('resident.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\resident\about\board.blade.php ENDPATH**/ ?>