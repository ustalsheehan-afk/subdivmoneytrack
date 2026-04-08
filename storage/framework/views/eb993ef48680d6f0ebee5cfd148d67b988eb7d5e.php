

<?php $__env->startSection('title', 'Reservation Confirmation'); ?>
<?php $__env->startSection('page-title', 'Reservation Confirmed'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto p-4 md:p-8">
    
    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden text-center p-8 md:p-12">
        
        <!-- Success Icon -->
        <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="bi bi-check-lg text-5xl text-green-600"></i>
        </div>

        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Reservation Received!</h1>
        <p class="text-lg text-gray-600 mb-8 max-w-lg mx-auto">
            Thank you, <span class="font-bold text-gray-900"><?php echo e(Auth::user()->name); ?></span>. Your reservation request has been successfully submitted and is pending approval.
        </p>

        <!-- Reservation Details Card -->
        <div class="bg-gray-50 rounded-2xl p-6 md:p-8 text-left mb-8 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-200 pb-3">Reservation Details</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Amenity</p>
                    <p class="font-bold text-lg text-gray-900"><?php echo e($reservation->amenity->name); ?></p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm mb-1">Reference No.</p>
                    <p class="font-bold text-lg text-gray-900 font-mono tracking-wider">#<?php echo e(str_pad($reservation->id, 6, '0', STR_PAD_LEFT)); ?></p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm mb-1">Date & Time</p>
                    <p class="font-bold text-gray-900">
                        <?php echo e(\Carbon\Carbon::parse($reservation->date)->format('F j, Y')); ?>

                        <br>
                        <span class="text-blue-600"><?php echo e($reservation->time_slot); ?></span>
                    </p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm mb-1">Total Amount</p>
                    <p class="font-bold text-2xl text-blue-700">₱<?php echo e(number_format($reservation->total_price, 2)); ?></p>
                </div>
            </div>

            <!-- Payment Status -->
            <div class="mt-6 p-4 rounded-xl <?php echo e($reservation->payment_status === 'paid' ? 'bg-green-100 border border-green-200' : 'bg-yellow-50 border border-yellow-200'); ?>">
                <div class="flex items-start gap-3">
                    <?php if($reservation->payment_status === 'paid'): ?>
                        <i class="bi bi-check-circle-fill text-green-600 text-xl mt-0.5"></i>
                        <div>
                            <p class="font-bold text-green-800">Payment Complete</p>
                            <p class="text-green-700 text-sm">Your payment has been verified.</p>
                        </div>
                    <?php else: ?>
                        <i class="bi bi-exclamation-circle-fill text-yellow-600 text-xl mt-0.5"></i>
                        <div>
                            <p class="font-bold text-yellow-800">Payment Pending (<?php echo e(ucfirst($reservation->payment_method)); ?>)</p>
                            <?php if($reservation->payment_method === 'gcash'): ?>
                                <p class="text-yellow-700 text-sm mt-1">Please ensure you have sent the payment to the GCash number provided. Admin will verify it shortly.</p>
                            <?php else: ?>
                                <p class="text-yellow-700 text-sm mt-1">Please pay at the administration office to confirm your slot.</p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col md:flex-row gap-4 justify-center">
            <a href="<?php echo e(route('resident.my-reservations.index')); ?>" class="px-8 py-4 bg-blue-600 text-white font-bold text-lg rounded-xl hover:bg-blue-700 shadow-lg hover:shadow-xl transition-all flex items-center justify-center gap-2">
                <i class="bi bi-calendar-check"></i> Go to My Reservations
            </a>
            <a href="<?php echo e(route('resident.dashboard')); ?>" class="px-8 py-4 bg-white text-gray-700 font-bold text-lg rounded-xl border-2 border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition-all flex items-center justify-center gap-2">
                <i class="bi bi-house"></i> Back to Home
            </a>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('resident.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\resident\reservations\confirmation.blade.php ENDPATH**/ ?>