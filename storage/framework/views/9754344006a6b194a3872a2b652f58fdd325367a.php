<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dues Statement</title>
    <style>
        body { font-family: sans-serif; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; font-weight: bold; }
        td { text-align: left; }
        .status-paid { background-color: #d1fae5; color: #065f46; padding: 2px 6px; border-radius: 4px; }
        .status-unpaid { background-color: #ffedd5; color: #7c2d12; padding: 2px 6px; border-radius: 4px; }
    </style>
</head>
<body>
    <h2>Dues Statement</h2>
    <p>Resident: <?php echo e($user->name); ?></p>
    <p>Date: <?php echo e(now()->format('M d, Y')); ?></p>

    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Due Date</th>
                <th>Amount</th>
                <th>Paid</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $dues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $due): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($due->title); ?></td>
                <td><?php echo e($due->due_date->format('M d, Y')); ?></td>
                <td>₱<?php echo e(number_format($due->amount,2)); ?></td>
                <td>₱<?php echo e(number_format($due->paid_amount,2)); ?></td>
                <td>
                    <span class="<?php echo e($due->status == 'paid' ? 'status-paid' : 'status-unpaid'); ?>">
                        <?php echo e(ucfirst($due->status)); ?>

                    </span>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>
</html>
<?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\resident\dues\statement-pdf.blade.php ENDPATH**/ ?>