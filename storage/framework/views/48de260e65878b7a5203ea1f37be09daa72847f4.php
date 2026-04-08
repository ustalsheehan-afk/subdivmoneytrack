

<?php $__env->startSection('title', 'View Payment'); ?>
<?php $__env->startSection('page-title', 'Payment Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white border border-gray-200 shadow rounded-3 p-5 transition-all duration-300">

    
    <div class="mb-4 border-bottom pb-3">
        <h4 class="fw-bold text-primary mb-1">Payment #<?php echo e($payment->id); ?></h4>
        <p class="text-muted small mb-0">Detailed view of your submitted payment record.</p>
    </div>

    
    <div class="row g-3">
        <div class="col-md-6">
            <div class="p-3 border rounded-3 bg-light-subtle shadow-sm h-100">
                <h6 class="text-secondary text-uppercase small fw-semibold mb-1">Due</h6>
                <p class="fs-6 mb-0"><?php echo e($payment->due->title ?? '—'); ?></p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="p-3 border rounded-3 bg-light-subtle shadow-sm h-100">
                <h6 class="text-secondary text-uppercase small fw-semibold mb-1">Amount Paid</h6>
                <p class="fw-bold text-success fs-5 mb-0">₱<?php echo e(number_format($payment->amount, 2)); ?></p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="p-3 border rounded-3 bg-light-subtle shadow-sm h-100">
                <h6 class="text-secondary text-uppercase small fw-semibold mb-1">Date Paid</h6>
                <p class="fs-6 mb-0"><?php echo e(\Carbon\Carbon::parse($payment->date_paid)->format('F d, Y')); ?></p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="p-3 border rounded-3 bg-light-subtle shadow-sm h-100">
                <h6 class="text-secondary text-uppercase small fw-semibold mb-1">Payment Method</h6>
                <p class="text-capitalize fs-6 mb-0"><?php echo e($payment->payment_method ?? '—'); ?></p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="p-3 border rounded-3 bg-light-subtle shadow-sm h-100">
                <h6 class="text-secondary text-uppercase small fw-semibold mb-1">Status</h6>
                <?php if($payment->status === 'approved'): ?>
                    <span class="badge bg-success bg-opacity-75 px-3 py-2">Approved</span>
                <?php elseif($payment->status === 'rejected'): ?>
                    <span class="badge bg-danger bg-opacity-75 px-3 py-2">Rejected</span>
                <?php else: ?>
                    <span class="badge bg-warning text-dark bg-opacity-75 px-3 py-2">Pending</span>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-6">
            <div class="p-3 border rounded-3 bg-light-subtle shadow-sm h-100">
                <h6 class="text-secondary text-uppercase small fw-semibold mb-1">Proof of Payment</h6>
                <?php if($payment->proof): ?>
                    <a href="<?php echo e(asset('storage/' . $payment->proof)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye me-1"></i> View Proof
                    </a>
                <?php else: ?>
                    <p class="text-muted mb-0">No proof uploaded</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="mt-4 text-end border-top pt-3">
        <a href="<?php echo e(route('resident.payments.index')); ?>" class="btn btn-secondary px-4 py-2">
            <i class="bi bi-arrow-left-circle me-1"></i> Back to My Payments
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.resident', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\resident\payments\show.blade.php ENDPATH**/ ?>