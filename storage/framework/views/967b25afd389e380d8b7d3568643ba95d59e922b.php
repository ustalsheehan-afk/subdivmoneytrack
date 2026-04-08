

<?php $__env->startSection('title', 'My Reservations'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">

    <!-- HERO -->
   <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.resident-hero-header','data' => ['label' => 'Reservation History','icon' => 'bi-calendar-check-fill','title' => 'My Reservations','description' => 'Manage and track your community amenity bookings, schedules, and payment status.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('resident-hero-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => 'Reservation History','icon' => 'bi-calendar-check-fill','title' => 'My Reservations','description' => 'Manage and track your community amenity bookings, schedules, and payment status.']); ?>
     <?php $__env->slot('actions', null, []); ?> 
        <a href="<?php echo e(route('resident.amenities.index')); ?>" class="btn-premium whitespace-nowrap flex items-center gap-2">
            <i class="bi bi-plus-lg"></i>
            <span>New Booking</span>
        </a>
     <?php $__env->endSlot(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>

    <!-- SUCCESS MESSAGE -->
    <?php if(session('success')): ?>
        <div class="bg-emerald-50 border border-emerald-100 p-5 rounded-[24px] shadow-sm flex items-center gap-4 animate-fade-in">
            <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/20">
                <i class="bi bi-check-lg text-xl"></i>
            </div>
            <p class="text-sm font-black text-emerald-800 uppercase tracking-tight">
                <?php echo e(session('success')); ?>

            </p>
        </div>
    <?php endif; ?>

    <!-- TABLE -->
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">

                <!-- HEADER -->
                <thead class="bg-gray-50/50 border-b border-gray-100">
                    <tr>
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Amenity</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Schedule</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Payment</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Actions</th>
                    </tr>
                </thead>

                <!-- BODY -->
                <tbody class="divide-y divide-gray-50">
                    <?php $__empty_1 = true; $__currentLoopData = $reservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reservation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <tr class="cursor-pointer hover:bg-emerald-50/30 transition-all duration-300 group border-l-4 border-transparent hover:border-emerald-500"
                            onclick="window.location.href='<?php echo e(route('resident.amenities.reservation.show', $reservation->id)); ?>'">

                            <!-- AMENITY -->
                            <td class="p-6">
                                <div class="flex items-center gap-5">
                                    
                                    <div class="relative shrink-0">
                                        <?php if($reservation->amenity->image): ?>
                                            <img src="<?php echo e(Storage::url($reservation->amenity->image)); ?>" 
                                                 class="w-14 h-14 rounded-2xl object-cover border-2 border-white shadow-sm group-hover:scale-105 transition-transform duration-500">
                                        <?php else: ?>
                                            <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-300 border-2 border-white shadow-sm">
                                                <i class="bi bi-building text-2xl"></i>
                                            </div>
                                        <?php endif; ?>

                                        <!-- STATUS DOT -->
                                        <span class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-white 
                                            <?php echo e(match($reservation->status) {
                                                'approved' => 'bg-emerald-500',
                                                'rejected' => 'bg-red-500',
                                                'cancelled' => 'bg-gray-400',
                                                default => 'bg-orange-400'
                                            }); ?>">
                                        </span>
                                    </div>

                                    <div>
                                        <p class="font-black text-gray-900 group-hover:text-emerald-700 transition-colors tracking-tight text-lg">
                                            <?php echo e($reservation->amenity->name); ?>

                                        </p>
                                        <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.2em] mt-0.5">
                                            ID #<?php echo e(str_pad($reservation->id, 5, '0', STR_PAD_LEFT)); ?>

                                        </p>
                                    </div>

                                </div>
                            </td>

                            <!-- DATE -->
                            <td class="p-6 text-center">
                                <p class="text-sm font-black text-gray-900 tracking-tight tabular-nums">
                                    <?php echo e($reservation->date->format('M d, Y')); ?>

                                </p>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1 tabular-nums">
                                    <?php echo e($reservation->time_slot); ?>

                                </p>
                            </td>

                            <!-- STATUS -->
                            <td class="p-6 text-center">
                                <?php
                                    $statusClass = match($reservation->status) {
                                        'approved'  => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                        'rejected'  => 'bg-red-50 text-red-600 border-red-100',
                                        'cancelled' => 'bg-gray-50 text-gray-600 border-gray-100',
                                        default     => 'bg-orange-50 text-orange-600 border-orange-100'
                                    };

                                    $dotClass = match($reservation->status) {
                                        'approved'  => 'bg-emerald-500',
                                        'rejected'  => 'bg-red-500',
                                        'cancelled' => 'bg-gray-400',
                                        default     => 'bg-orange-500'
                                    };
                                ?>

                                <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest border <?php echo e($statusClass); ?>">
                                    <span class="w-1.5 h-1.5 rounded-full <?php echo e($dotClass); ?>"></span>
                                    <?php echo e($reservation->status); ?>

                                </span>
                            </td>

                            <!-- PAYMENT -->
                            <td class="p-6 text-right">
                                <p class="text-lg font-black text-black tracking-tighter tabular-nums mb-2">
                                    ₱<?php echo e(number_format($reservation->total_price, 2)); ?>

                                </p>

                                <?php
                                    $pStatus = $reservation->payment_status;
                                    $pStyle = match($pStatus) {
                                        'paid'      => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'border' => 'border-emerald-100', 'dot' => 'bg-emerald-500', 'label' => 'Verified'],
                                        'submitted' => ['bg' => 'bg-blue-50',    'text' => 'text-blue-600',    'border' => 'border-blue-100',    'dot' => 'bg-blue-500',    'label' => 'Awaiting'],
                                        'rejected'  => ['bg' => 'bg-red-50',     'text' => 'text-red-600',     'border' => 'border-red-100',     'dot' => 'bg-red-500',     'label' => 'Rejected'],
                                        default     => ['bg' => 'bg-gray-50',    'text' => 'text-gray-600',    'border' => 'border-gray-100',    'dot' => 'bg-gray-400',    'label' => 'Pending'],
                                    };
                                ?>

                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest border <?php echo e($pStyle['bg']); ?> <?php echo e($pStyle['text']); ?> <?php echo e($pStyle['border']); ?>">
                                    <span class="w-1 h-1 rounded-full <?php echo e($pStyle['dot']); ?>"></span>
                                    <?php echo e($pStyle['label']); ?>

                                </span>
                            </td>

                            <!-- ACTION -->
                            <td class="p-6 text-center" onclick="event.stopPropagation()">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?php echo e(route('resident.amenities.reservation.show', $reservation->id)); ?>" 
                                       class="w-11 h-11 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 border border-transparent hover:border-emerald-100 transition-all group-hover:scale-110 shadow-sm"
                                       title="View Details">
                                        <i class="bi bi-arrow-right-short text-3xl"></i>
                                    </a>
                                    <?php if($reservation->payment_status === 'paid'): ?>
                                        <a href="<?php echo e(route('resident.amenities.reservation.receipt', $reservation->id)); ?>" 
                                           target="_blank"
                                           class="w-11 h-11 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-500 hover:text-white hover:bg-emerald-600 border border-emerald-100 hover:border-emerald-600 transition-all group-hover:scale-110 shadow-sm"
                                           title="View Receipt">
                                            <i class="bi bi-receipt text-lg"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>

                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="py-32 text-center">
                                <div class="max-w-sm mx-auto space-y-8">
                                    <div class="w-24 h-24 bg-gray-50 rounded-[40px] flex items-center justify-center mx-auto text-gray-200 shadow-inner">
                                        <i class="bi bi-calendar-x text-5xl"></i>
                                    </div>

                                    <div class="space-y-3">
                                        <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tight">
                                            No reservations yet
                                        </h3>
                                        <p class="text-[11px] font-black text-gray-400 uppercase tracking-[0.3em]">
                                            Start by exploring amenities
                                        </p>
                                    </div>

                                    <a href="<?php echo e(route('resident.amenities.index')); ?>" class="btn-premium inline-flex mx-auto">
                                        Explore Amenities <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- PAGINATION -->
    <div class="mt-6">
        <?php echo e($reservations->links()); ?>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('resident.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\resident\reservations\index.blade.php ENDPATH**/ ?>