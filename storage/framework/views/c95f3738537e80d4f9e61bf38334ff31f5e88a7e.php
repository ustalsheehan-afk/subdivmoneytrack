

<?php $__env->startSection('title', isset($payment) ? 'Edit Payment' : 'Add Payment'); ?>
<?php $__env->startSection('page-title', isset($payment) ? 'Edit Payment' : 'Add Payment'); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-form-card">

    <h2 class="text-xl font-semibold mb-6 text-gray-900"><?php echo e(isset($payment) ? 'Edit Payment' : 'Add New Payment'); ?></h2>

    <form action="<?php echo e(isset($payment) ? route('admin.payments.update', $payment->id) : route('admin.payments.review')); ?>" method="<?php echo e(isset($payment) ? 'POST' : 'POST'); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php if(isset($payment)): ?>
            <?php echo method_field('PUT'); ?>
        <?php endif; ?>

        <div class="mb-4">
            <label class="admin-form-label">Resident</label>
            <select name="resident_id" id="resident_id" class="admin-form-select" required>
                <option value="">Select Resident</option>
                <?php $__currentLoopData = $residents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($resident->id); ?>" <?php echo e((old('resident_id', $payment->resident_id ?? '') == $resident->id) ? 'selected' : ''); ?>>
                        <?php echo e($resident->full_name); ?> - Block <?php echo e($resident->block); ?>, Lot <?php echo e($resident->lot); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="mb-4">
            <label class="admin-form-label">Due</label>
            <select name="due_id" id="due_id" class="admin-form-select" required>
                <option value="">Select Due</option>
                <?php $__currentLoopData = $dues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $due): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($due->id); ?>" data-amount="<?php echo e($due->amount); ?>"
                        <?php echo e((old('due_id', $payment->due_id ?? '') == $due->id) ? 'selected' : ''); ?>>
                        <?php echo e($due->title); ?> - ₱<?php echo e(number_format($due->amount,2)); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="mb-4">
            <label class="admin-form-label">Amount Paid</label>
            <input type="number" step="0.01" name="amount" id="amount" value="<?php echo e(old('amount', $payment->amount ?? '')); ?>" class="admin-form-input" required>
        </div>

        <div class="mb-4">
            <label class="admin-form-label">Date & Time Paid</label>
            <input type="datetime-local" name="date_paid" value="<?php echo e(old('date_paid', isset($payment->date_paid) ? $payment->date_paid->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i'))); ?>" class="admin-form-input" required>
        </div>

        <div class="mb-4">
            <label class="admin-form-label">Payment Method</label>
            <input type="text" name="payment_method" value="<?php echo e(old('payment_method', $payment->payment_method ?? '')); ?>" class="admin-form-input" required>
        </div>

        <?php if(isset($payment)): ?>
        <div class="mb-4">
            <label class="admin-form-label">Status</label>
            <select name="status" class="admin-form-select">
                <option value="pending" <?php echo e($payment->status=='pending'?'selected':''); ?>>Pending</option>
                <option value="approved" <?php echo e($payment->status=='approved'?'selected':''); ?>>Approved</option>
                <option value="rejected" <?php echo e($payment->status=='rejected'?'selected':''); ?>>Rejected</option>
            </select>
        </div>
        <?php endif; ?>

        <div class="mb-4">
            <label class="admin-form-label">Proof (Optional)</label>
            <?php if(isset($payment) && $payment->proof): ?>
                <div class="mb-2">
                    <a href="<?php echo e(asset('storage/' . $payment->proof)); ?>" target="_blank" class="text-blue-600 underline">View Current Proof</a>
                </div>
            <?php endif; ?>
            <input type="file" name="proof" accept=".jpg,.jpeg,.png,.pdf">
        </div>

        <button type="submit" class="admin-btn-primary mt-2">
            <?php echo e(isset($payment) ? 'Update Payment' : 'Review Payment'); ?>

        </button>
    </form>
</div>

<script>
document.getElementById('resident_id').addEventListener('change', function() {
    const residentId = this.value;
    const dueSelect = document.getElementById('due_id');
    const amountInput = document.getElementById('amount');

    dueSelect.innerHTML = '<option value="">Select Due</option>';
    amountInput.value = '';

    if (!residentId) return;

    fetch(`/admin/residents/${residentId}/dues`)
        .then(res => res.json())
        .then(data => {
            data.forEach(due => {
                const option = document.createElement('option');
                option.value = due.id;
                option.textContent = `${due.title} - ₱${new Intl.NumberFormat().format(due.amount)}`;
                option.dataset.amount = due.amount;
                dueSelect.appendChild(option);
            });
        });
});

document.getElementById('due_id').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    document.getElementById('amount').value = selected?.dataset.amount ?? '';
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\payments\form.blade.php ENDPATH**/ ?>