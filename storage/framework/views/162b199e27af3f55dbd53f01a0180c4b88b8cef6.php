<?php $__env->startSection('title', 'Reservation Layout Preview'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">Reservation Layout Preview</h1>
        <div class="text-sm text-gray-500">
            Simulating Resource-Based Timeline View
        </div>
    </div>

    <!-- Calendar Container -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 h-[calc(100vh-12rem)]">
        <?php $__currentLoopData = $amenities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $amenity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                // Dynamic color classes based on amenity color
                $color = $amenity['color'];
                $headerBg = "bg-{$color}-100";
                $headerText = "text-{$color}-800";
                $colBorder = "border-{$color}-200";
                $colBg = "bg-{$color}-50";
            ?>

            <!-- Amenity Column -->
            <div class="flex flex-col bg-white rounded-xl shadow-sm border <?php echo e($colBorder); ?> overflow-hidden h-full">
                <!-- Column Header -->
                <div class="p-4 <?php echo e($headerBg); ?> border-b <?php echo e($colBorder); ?> flex justify-between items-center">
                    <h2 class="font-bold <?php echo e($headerText); ?> text-lg flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-current"></span>
                        <?php echo e($amenity['name']); ?>

                    </h2>
                    <span class="text-xs font-semibold <?php echo e($headerText); ?> bg-white bg-opacity-50 px-2 py-1 rounded-full">
                        <?php echo e(count($amenity['reservations'])); ?> Reservations
                    </span>
                </div>

                <!-- Reservations List (Vertical Stack) -->
                <div class="flex-1 overflow-y-auto p-4 <?php echo e($colBg); ?> space-y-3">
                    <?php $__empty_1 = true; $__currentLoopData = $amenity['reservations']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reservation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $status = $reservation['status'];
                            $statusClasses = match($status) {
                                'approved' => 'bg-green-100 text-green-800 border-green-200 hover:bg-green-50',
                                'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200 hover:bg-yellow-50',
                                'rejected' => 'bg-red-100 text-red-800 border-red-200 hover:bg-red-50',
                                default => 'bg-gray-100 text-gray-800 border-gray-200',
                            };
                            
                            $statusIcon = match($status) {
                                'approved' => 'bi-check-circle-fill',
                                'pending' => 'bi-clock-fill',
                                'rejected' => 'bi-x-circle-fill',
                                default => 'bi-circle',
                            };
                        ?>

                        <!-- Reservation Card -->
                        <div class="group relative p-3 rounded-lg border <?php echo e($statusClasses); ?> transition-all duration-200 shadow-sm hover:shadow-md cursor-pointer">
                            
                            <!-- Tooltip (Optional Requirement) -->
                            <div class="absolute left-1/2 -translate-x-1/2 -top-10 hidden group-hover:block z-50 whitespace-nowrap">
                                <div class="bg-gray-800 text-white text-xs rounded py-1 px-2 shadow-lg">
                                    <?php echo e($reservation['resident_name']); ?> | <?php echo e($reservation['time_slot']); ?> | <?php echo e(ucfirst($status)); ?>

                                    <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-gray-800 rotate-45"></div>
                                </div>
                            </div>

                            <div class="flex justify-between items-start mb-2">
                                <span class="font-semibold text-sm truncate pr-2"><?php echo e($reservation['resident_name']); ?></span>
                                <i class="bi <?php echo e($statusIcon); ?>"></i>
                            </div>
                            
                            <div class="text-xs opacity-75 mb-1 flex items-center gap-1">
                                <i class="bi bi-clock"></i>
                                <?php echo e($reservation['time_slot']); ?>

                            </div>

                            <div class="flex justify-between items-center mt-2">
                                <span class="text-[10px] uppercase font-bold tracking-wider px-1.5 py-0.5 rounded border border-current opacity-75">
                                    <?php echo e(ucfirst($status)); ?>

                                </span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="flex flex-col items-center justify-center h-40 text-gray-400">
                            <i class="bi bi-calendar-x text-3xl mb-2"></i>
                            <span class="text-sm">No reservations</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\dummy-reservation.blade.php ENDPATH**/ ?>