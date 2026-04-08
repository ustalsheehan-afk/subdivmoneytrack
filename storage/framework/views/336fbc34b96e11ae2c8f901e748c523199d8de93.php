

<?php $__env->startSection('title', 'Amenities'); ?>
<?php $__env->startSection('page-title', 'Amenities & Facilities'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.resident-hero-header','data' => ['label' => 'Facilities','icon' => 'bi-building-fill','title' => 'Amenities & Facilities','description' => 'Browse our community facilities and make a reservation for your events and activities.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('resident-hero-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Facilities','icon' => 'bi-building-fill','title' => 'Amenities & Facilities','description' => 'Browse our community facilities and make a reservation for your events and activities.']); ?>
             <?php $__env->slot('actions', null, []); ?> 
                <a href="<?php echo e(route('resident.my-reservations.index')); ?>" class="btn-premium">
                    <i class="bi bi-calendar-check"></i>
                    My Bookings
                </a>
             <?php $__env->endSlot(); ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__empty_1 = true; $__currentLoopData = $amenities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $amenity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $priceLabel = $amenity->price > 0 ? '₱' . number_format($amenity->price, 2) . '/hr' : 'Free';
                    $scheduleLabel = implode(', ', array_map(function($day) { return substr($day, 0, 3); }, $amenity->days_available ?? []));
                ?>
                <div class="glass-card overflow-hidden group hover:-translate-y-1 transition-all duration-300 hover:shadow-xl hover:border-emerald-200">
                    <div class="relative h-36 bg-gray-50 overflow-hidden">
                        <?php if($amenity->image): ?>
                            <img src="<?php echo e(Storage::url($amenity->image)); ?>" alt="<?php echo e($amenity->name); ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.03]">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-gray-200">
                                <i class="bi bi-building text-4xl"></i>
                            </div>
                        <?php endif; ?>

                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>

                        <div class="absolute top-3 left-3">
                            <?php if($amenity->status === 'maintenance'): ?>
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50/95 text-amber-700 border border-amber-100 text-[9px] font-black uppercase tracking-widest">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Maintenance
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50/95 text-emerald-700 border border-emerald-100 text-[9px] font-black uppercase tracking-widest">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Available
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="absolute left-0 right-0 bottom-0 p-4">
                            <div class="flex items-end justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="text-white font-black text-sm tracking-tight truncate"><?php echo e($amenity->name); ?></div>
                                    <div class="text-white/70 text-[9px] font-black uppercase tracking-widest">Amenity</div>
                                </div>
                                <div class="shrink-0 text-right">
                                    <div class="text-white font-black text-sm tabular-nums"><?php echo e($priceLabel); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 flex flex-col gap-4">
                        <p class="text-[12px] text-gray-600 font-medium leading-relaxed line-clamp-2">
                            <?php echo e($amenity->description); ?>

                        </p>

                        <div class="flex flex-wrap gap-2">
                            <span class="inline-flex items-center gap-2 px-3 py-2 rounded-full bg-gray-50 border border-gray-100 text-[10px] font-black uppercase tracking-widest text-gray-600">
                                <i class="bi bi-people-fill text-emerald-600"></i>
                                <?php echo e($amenity->max_capacity); ?> Pax
                            </span>
                            <span class="inline-flex items-center gap-2 px-3 py-2 rounded-full bg-gray-50 border border-gray-100 text-[10px] font-black uppercase tracking-widest text-gray-600">
                                <i class="bi bi-calendar-check text-emerald-600"></i>
                                <span class="truncate max-w-[160px]"><?php echo e($scheduleLabel ?: 'Schedule N/A'); ?></span>
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <a href="<?php echo e(route('resident.amenities.show', $amenity)); ?>" class="btn-secondary justify-center w-full">
                                View Details
                                <i class="bi bi-arrow-right"></i>
                            </a>
                            <?php if($amenity->status === 'maintenance'): ?>
                                <button disabled class="w-full px-4 py-3 bg-gray-100 text-gray-400 text-[10px] font-black uppercase tracking-widest rounded-xl border border-gray-200">
                                    Book Now
                                </button>
                            <?php else: ?>
                                <a href="<?php echo e(route('resident.amenities.show', $amenity)); ?>" class="btn-premium justify-center w-full">
                                    Book Now
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-16 glass-card">
                    <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-gray-200">
                        <i class="bi bi-building-slash text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-black text-gray-900 tracking-tight">No amenities found</h3>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-2">Check back later for updates</p>
                </div>
            <?php endif; ?>
        </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('resident.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\resident\amenities\index.blade.php ENDPATH**/ ?>