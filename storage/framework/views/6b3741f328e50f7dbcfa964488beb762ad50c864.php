

<?php $__env->startSection('title', 'Submit Request'); ?>
<?php $__env->startSection('page-title', 'Service Requests'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white shadow-lg rounded-2xl p-6">
    <form action="<?php echo e(route('resident.requests.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-1">Request Type</label>
            <input type="text" name="type" required class="w-full border p-3 rounded-lg focus:ring focus:ring-blue-300">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-1">Description</label>
            <textarea name="description" rows="4" required class="w-full border p-3 rounded-lg focus:ring focus:ring-blue-300"></textarea>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Submit Request</button>
        </div>
    </form>

    <h3 class="text-xl font-semibold mt-8 mb-4 text-gray-800">My Requests</h3>

    <table class="min-w-full border border-gray-300 rounded-lg overflow-hidden">
        <thead class="bg-blue-600 text-white">
            <tr>
                <th class="py-3 px-4 text-left">#</th>
                <th class="py-3 px-4 text-left">Type</th>
                <th class="py-3 px-4 text-left">Description</th>
                <th class="py-3 px-4 text-left">Status</th>
                <th class="py-3 px-4 text-left">Submitted</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <?php if($requests->count() > 0): ?>
                <?php $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="py-3 px-4"><?php echo e($loop->iteration); ?></td>
                    <td class="py-3 px-4"><?php echo e($req->type); ?></td>
                    <td class="py-3 px-4"><?php echo e($req->description); ?></td>
                    <td class="py-3 px-4">
                        <span class="px-3 py-1 rounded-full text-sm <?php echo e($req->status == 'Approved' ? 'bg-green-100 text-green-800' : ($req->status == 'Rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')); ?>">
                            <?php echo e($req->status); ?>

                        </span>
                    </td>
                    <td class="py-3 px-4"><?php echo e($req->created_at->format('M d, Y')); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center py-4 text-gray-500">No requests submitted yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.resident', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\resident\requests.blade.php ENDPATH**/ ?>