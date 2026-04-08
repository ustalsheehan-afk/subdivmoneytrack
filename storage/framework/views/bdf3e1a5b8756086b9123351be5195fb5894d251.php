<?php $__env->startSection('content'); ?>
<style>
    :root {
        --brand-primary: #1F3B5C;
        --brand-accent: #2E5B8A;
    }
    body {
        background: #f8fafc;
        font-family: 'Inter', sans-serif;
    }
    .status-card {
        max-width: 500px;
        margin: 100px auto;
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.15);
        animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .card-header-status {
        padding: 40px 20px;
        text-align: center;
        color: white;
    }
    .header-invalid { background: #e11d48; }
    .header-accepted { background: #059669; }
    .header-expired { background: #d97706; }
    .header-cancelled { background: #4b5563; }

    .status-icon {
        font-size: 3.5rem;
        margin-bottom: 15px;
        display: block;
    }
    .card-body-status {
        padding: 40px;
        text-align: center;
    }
    .status-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 12px;
        letter-spacing: -0.025em;
    }
    .status-message {
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 30px;
    }
    .btn-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 52px;
        background: var(--brand-primary);
        color: white;
        border-radius: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .btn-status:hover {
        background: #162b44;
        transform: translateY(-2px);
        color: white;
    }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="status-card">
    <?php
        $type = $type ?? 'invalid';
        $headerClass = 'header-' . $type;
        $icon = 'bi-exclamation-octagon';
        $title = 'Invitation Invalid';

        if($type === 'accepted') {
            $icon = 'bi-check-circle-fill';
            $title = 'Already Registered';
            $headerClass = 'header-accepted';
        } elseif($type === 'expired') {
            $icon = 'bi-clock-history';
            $title = 'Link Expired';
            $headerClass = 'header-expired';
        } elseif($type === 'cancelled') {
            $icon = 'bi-x-circle-fill';
            $title = 'Invitation Cancelled';
            $headerClass = 'header-cancelled';
        }
    ?>

    <div class="card-header-status <?php echo e($headerClass); ?>">
        <i class="bi <?php echo e($icon); ?> status-icon"></i>
        <h2 class="fw-bold mb-0" style="font-size: 1.75rem;"><?php echo e($title); ?></h2>
    </div>

    <div class="card-body-status">
        <p class="status-message">
            <?php echo e($message ?? 'This invitation link is no longer valid or has expired.'); ?>

        </p>

        <a href="<?php echo e(route('resident.login')); ?>" class="btn-status">
            <span>Go to Login Portal</span>
            <i class="bi bi-arrow-right ms-2"></i>
        </a>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #f1f5f9;">
            <p class="small text-muted mb-0">
                If you believe this is an error, please contact your subdivision administrator.
            </p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\auth\invitation-invalid.blade.php ENDPATH**/ ?>