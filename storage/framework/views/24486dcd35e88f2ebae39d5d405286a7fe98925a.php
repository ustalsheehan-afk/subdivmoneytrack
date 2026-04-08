
<?php $__env->startSection('title','Pay a Due'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6">

    <h1 class="text-2xl font-semibold mb-4">Pay a Due</h1>

    <form action="<?php echo e(route('resident.payments.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
        <?php echo csrf_field(); ?>
        <div>
            <label for="due_id" class="block mb-1 font-medium">Select Due</label>
            <select name="due_id" id="due_id" class="w-full border p-2 rounded">
                <?php $__currentLoopData = $dues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $due): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($due->id); ?>"><?php echo e($due->title); ?> - ₱<?php echo e(number_format($due->amount,2)); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div>
            <label for="amount" class="block mb-1 font-medium">Amount</label>
            <input type="number" name="amount" id="amount" step="0.01" class="w-full border p-2 rounded" required>
        </div>

        <div>
            <label for="payment_method" class="block mb-1 font-medium">Payment Method</label>
            <input type="text" name="payment_method" id="payment_method" class="w-full border p-2 rounded" required>
        </div>

        <div>
            <label for="proof" class="block mb-1 font-medium">Proof of Payment</label>
            <input type="file" name="proof" id="proof" class="w-full border p-2 rounded">
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Submit Payment</button>
    </form>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('resident.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\resident\payments\create.blade.php ENDPATH**/ ?>