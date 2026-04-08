

<?php $__env->startSection('title', 'Dues Statement'); ?>
<?php $__env->startSection('page-title', 'Dues Statement'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto px-4 py-6 space-y-6">

    <a href="<?php echo e(route('resident.dues.index')); ?>" class="text-sm text-[#800020] hover:underline">&larr; Back to My Dues</a>

    <h1 class="text-2xl font-semibold text-gray-900">Dues Statement</h1>

    <div class="bg-white rounded-xl shadow p-6 overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-3 px-4 font-medium text-gray-700">Title</th>
                    <th class="py-3 px-4 font-medium text-gray-700">Due Date</th>
                    <th class="py-3 px-4 font-medium text-gray-700">Amount</th>
                    <th class="py-3 px-4 font-medium text-gray-700">Paid</th>
                    <th class="py-3 px-4 font-medium text-gray-700">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if($dues->count() > 0): ?>
                    <?php $__currentLoopData = $dues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $due): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="py-3 px-4"><?php echo e($due->title); ?></td>
                        <td class="py-3 px-4 flex items-center gap-2">
                            <i class="bi bi-calendar3 text-gray-400"></i>
                            <?php echo e($due->due_date->format('M d, Y')); ?>

                        </td>
                        <td class="py-3 px-4">₱<?php echo e(number_format($due->amount,2)); ?></td>
                        <td class="py-3 px-4">₱<?php echo e(number_format($due->paid_amount,2)); ?></td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 text-xs font-medium rounded 
                                <?php echo e($due->status == 'paid' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700'); ?>">
                                <?php echo e(ucfirst($due->status)); ?>

                            </span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center text-gray-500 py-4">No dues found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="flex justify-end">
        <a href="<?php echo e(route('resident.dues.download')); ?>" 
           class="px-6 py-2 rounded-xl bg-[#800020] text-white font-medium hover:bg-[#6a001b] transition">
           Download PDF
        </a>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('resident.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\resident\dues\statement.blade.php ENDPATH**/ ?>