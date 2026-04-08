<?php $__env->startSection('title', 'Reservation Created'); ?>
<?php $__env->startSection('page-title', 'Reservation Created'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto p-4 md:p-8">
    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden text-center p-8 md:p-12">
        <div class="w-24 h-24 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="bi bi-check-lg text-5xl text-emerald-600"></i>
        </div>

        <h1 class="text-3xl md:text-4xl font-black text-gray-900 mb-4 tracking-tight">Reservation Created</h1>
        <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
            The reservation has been successfully created for
            <span class="font-black text-gray-900"><?php echo e($reservation->customer_name); ?></span>.
        </p>

        <div class="bg-gray-50 rounded-3xl p-6 md:p-8 text-left mb-8 border border-gray-200">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 border-b border-gray-200 pb-5 mb-6">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Reference Number</p>
                    <p class="font-black text-2xl text-gray-900 font-mono tracking-wider"><?php echo e($reservation->reference_code); ?></p>
                </div>
                <div class="text-left md:text-right">
                    <p class="text-gray-500 text-sm mb-1">Booking Source</p>
                    <p class="font-black text-emerald-700 uppercase tracking-widest text-sm">Admin-Created</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Amenity</p>
                    <p class="font-black text-lg text-gray-900"><?php echo e($reservation->amenity->name); ?></p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm mb-1">Customer Type</p>
                    <p class="font-black text-lg text-gray-900"><?php echo e($reservation->customer_type_label); ?></p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm mb-1">Customer</p>
                    <p class="font-black text-gray-900"><?php echo e($reservation->customer_name); ?></p>
                    <p class="text-sm text-gray-500 mt-1"><?php echo e($reservation->customer_contact); ?></p>
                    <?php if($reservation->customer_email): ?>
                    <p class="text-sm text-gray-500"><?php echo e($reservation->customer_email); ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <p class="text-gray-500 text-sm mb-1">Date & Time</p>
                    <p class="font-black text-gray-900"><?php echo e(\Carbon\Carbon::parse($reservation->date)->format('F j, Y')); ?></p>
                    <p class="text-sm text-emerald-700 font-bold mt-1"><?php echo e($reservation->time_slot); ?></p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm mb-1">Guests</p>
                    <p class="font-black text-gray-900"><?php echo e($reservation->guest_count); ?> People</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm mb-1">Total Amount</p>
                    <p class="font-black text-2xl text-emerald-700">₱<?php echo e(number_format($reservation->total_price, 2)); ?></p>
                </div>
            </div>

            <div class="mt-6 p-4 rounded-xl <?php echo e($reservation->payment_status === 'paid' ? 'bg-emerald-100 border border-emerald-200' : 'bg-amber-50 border border-amber-200'); ?>">
                <div class="flex items-start gap-3">
                    <?php if($reservation->payment_status === 'paid'): ?>
                        <i class="bi bi-check-circle-fill text-emerald-600 text-xl mt-0.5"></i>
                        <div>
                            <p class="font-black text-emerald-800">Payment Recorded as Paid</p>
                            <p class="text-emerald-700 text-sm">This reservation was marked paid during admin creation.</p>
                        </div>
                    <?php else: ?>
                        <i class="bi bi-exclamation-circle-fill text-amber-600 text-xl mt-0.5"></i>
                        <div>
                            <p class="font-black text-amber-800">Payment Pending</p>
                            <p class="text-amber-700 text-sm">The reservation is confirmed, but payment is still pending collection or verification.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-4 justify-center">
            <a href="<?php echo e(route('admin.amenity-reservations.index', ['date' => $reservation->date->format('Y-m-d'), 'active_id' => $reservation->id])); ?>" class="px-8 py-4 bg-[#081412] text-[#B6FF5C] font-black text-lg rounded-xl transition-all flex items-center justify-center gap-2 border border-[#B6FF5C]/20">
                <i class="bi bi-eye"></i> View Reservation
            </a>
            <a href="<?php echo e(route('admin.amenity-reservations.create')); ?>" class="px-8 py-4 bg-white text-gray-700 font-black text-lg rounded-xl border-2 border-gray-200 hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
                <i class="bi bi-plus-circle"></i> Create Another
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\reservations\confirmation.blade.php ENDPATH**/ ?>