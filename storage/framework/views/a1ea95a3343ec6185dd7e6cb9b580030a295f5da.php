

<?php $__env->startSection('title', 'My Payments'); ?>
<?php $__env->startSection('page-title', 'My Payment Records'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white shadow-sm rounded-3 p-4 border">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-semibold text-dark mb-0">
            <i class="bi bi-wallet2 me-2"></i> My Payment Records
        </h4>
        <a href="<?php echo e(route('resident.payments.create')); ?>" class="btn btn-dark px-4 py-2 shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Add Payment
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 border">
            <thead class="bg-dark text-white text-uppercase small">
                <tr>
                    <th scope="col" class="ps-3">#</th>
                    <th scope="col">Due Description</th>
                    <th scope="col" class="text-end">Amount (₱)</th>
                    <th scope="col" class="text-center">Status</th>
                    <th scope="col" class="text-end pe-3">Date Paid</th>
                </tr>
            </thead>
            <tbody>
                <?php if($payments->count() > 0): ?>
                    <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="ps-3 text-dark"><?php echo e($loop->iteration); ?></td>
                        <td class="text-dark fw-medium">
                            <?php echo e($payment->dues->description ?? 'N/A'); ?>

                        </td>
                        <td class="text-end text-dark fw-semibold">
                            ₱<?php echo e(number_format($payment->amount, 2)); ?>

                        </td>
                        <td class="text-center">
                            <span class="badge rounded-pill px-3 py-2 
                                <?php if($payment->status === 'approved' || $payment->status === 'Paid'): ?> bg-success-subtle text-success
                                <?php elseif($payment->status === 'pending'): ?> bg-warning-subtle text-warning
                                <?php else: ?> bg-danger-subtle text-danger
                                <?php endif; ?>">
                                <?php echo e(ucfirst($payment->status)); ?>

                            </span>
                        </td>
                        <td class="text-end pe-3 text-secondary">
                            <?php echo e($payment->date_paid 
                                ? \Carbon\Carbon::parse($payment->date_paid)->format('M d, Y') 
                                : ($payment->created_at ? $payment->created_at->format('M d, Y') : '—')); ?>

                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="bi bi-receipt text-muted fs-4 d-block mb-2"></i>
                            No payment records found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    
    <div class="mt-4 d-flex justify-content-center">
        <?php echo e($payments->links('pagination::bootstrap-5')); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.resident', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\resident\payments.blade.php ENDPATH**/ ?>