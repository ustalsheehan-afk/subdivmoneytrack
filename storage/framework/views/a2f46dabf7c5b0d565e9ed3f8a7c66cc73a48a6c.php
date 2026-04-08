<!DOCTYPE html>
<html>
<head>
    <title>Report - <?php echo e($title); ?></title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; }
        .header p { margin: 5px 0; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .summary { margin-top: 20px; border-top: 2px solid #333; padding-top: 10px; }
        .summary-item { font-size: 14px; font-weight: bold; margin-bottom: 5px; }
    </style>
</head>
<body>

    <div class="header">
        <h1><?php echo e($title); ?></h1>
        <p>Period: <?php echo e($period); ?></p>
    </div>

    <?php if(!empty($summary)): ?>
        <div class="summary">
            <h3>Summary</h3>
            <?php $__currentLoopData = $summary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="summary-item"><?php echo e($key); ?>: <?php echo e($value); ?></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    <h3>Details</h3>
    <table>
        <thead>
            <tr>
                <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th><?php echo e($col); ?></th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    
                    
                    
                    
                    
                    
                    
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

</body>
</html>
<?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\reports\pdf_generic.blade.php ENDPATH**/ ?>