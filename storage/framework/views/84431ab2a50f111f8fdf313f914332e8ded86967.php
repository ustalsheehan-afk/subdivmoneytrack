<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Subdivision Financial Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            color: #1a1a1a;
            font-size: 24px;
        }
        .header p {
            margin: 0;
            color: #666;
            font-size: 12px;
        }
        .summary-grid {
            display: table;
            width: 100%;
            margin-bottom: 40px;
            border-spacing: 10px;
        }
        .summary-card {
            display: table-cell;
            background: #f8f9fa;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 8px;
            width: 33%;
            text-align: center;
        }
        .summary-label {
            display: block;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #666;
            margin-bottom: 5px;
        }
        .summary-value {
            display: block;
            font-size: 18px;
            font-weight: bold;
            color: #1a1a1a;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 15px;
            text-transform: uppercase;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #f8f9fa;
            color: #666;
            font-weight: bold;
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
            text-transform: uppercase;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            color: #333;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-approved { background: #d1fae5; color: #065f46; }
        .badge-pending { background: #ffedd5; color: #9a3412; }
        .badge-rejected { background: #fee2e2; color: #991b1b; }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Financial Report</h1>
        <p>Generated on <?php echo e(now()->format('F d, Y h:i A')); ?></p>
    </div>

    
    <div class="summary-grid">
        <div class="summary-card">
            <span class="summary-label">Total Collections</span>
            <span class="summary-value" style="color: #059669;">₱<?php echo e(number_format($collectedDues, 2)); ?></span>
        </div>
        <div class="summary-card">
            <span class="summary-label">Pending Payments</span>
            <span class="summary-value" style="color: #d97706;">₱<?php echo e(number_format($totalPayments - $collectedDues, 2)); ?></span>
        </div>
        <div class="summary-card">
            <span class="summary-label">Unpaid Dues</span>
            <span class="summary-value" style="color: #dc2626;">₱<?php echo e(number_format($unpaidDues, 2)); ?></span>
        </div>
    </div>

    <div class="summary-grid" style="margin-top: -30px;">
        <div class="summary-card">
            <span class="summary-label">Total Residents</span>
            <span class="summary-value"><?php echo e($totalResidents); ?></span>
        </div>
        <div class="summary-card">
            <span class="summary-label">Total Penalties</span>
            <span class="summary-value">₱<?php echo e(number_format($totalPenalties, 2)); ?></span>
        </div>
        <div class="summary-card">
            <span class="summary-label">Total Expected</span>
            <span class="summary-value">₱<?php echo e(number_format($totalDues, 2)); ?></span>
        </div>
    </div>

    
    <div class="section-title">Recent Transactions</div>
    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Date</th>
                <th style="width: 30%;">Resident</th>
                <th style="width: 20%;">Type</th>
                <th style="width: 20%; text-align: right;">Amount</th>
                <th style="width: 15%; text-align: right;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($transaction->created_at->format('M d, Y')); ?></td>
                <td>
                    <div style="font-weight: bold;"><?php echo e($transaction->resident->full_name ?? 'Unknown'); ?></div>
                    <div style="font-size: 9px; color: #666;">B<?php echo e($transaction->resident->block ?? '-'); ?> L<?php echo e($transaction->resident->lot ?? '-'); ?></div>
                </td>
                <td style="text-transform: capitalize;"><?php echo e($transaction->payment_method ?? 'Payment'); ?></td>
                <td class="text-right" style="font-family: monospace;">₱<?php echo e(number_format($transaction->amount, 2)); ?></td>
                <td class="text-right">
                    <span class="badge badge-<?php echo e($transaction->status); ?>">
                        <?php echo e($transaction->status); ?>

                    </span>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="5" class="text-center" style="padding: 20px; color: #999;">No recent transactions found.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>This report is system-generated. &copy; <?php echo e(date('Y')); ?> Subdivision Dues Management System.</p>
    </div>
</body>
</html><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\reports\pdf.blade.php ENDPATH**/ ?>