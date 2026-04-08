<?php $__env->startSection('title', 'Reports Management'); ?>
<?php $__env->startSection('page-title', 'Reports Management'); ?>

<?php $__env->startSection('content'); ?>
<div x-data="{ showSummary: false }" class="space-y-8 animate-fade-in pb-20">

    
    
    
    <div class="glass-card p-8 relative overflow-hidden group">
        
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-brand-accent/5 rounded-full blur-3xl group-hover:bg-brand-accent/10 transition-all duration-700"></div>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                    Reports Center
                </h1>
                <p class="mt-2 text-gray-600 text-lg max-w-xl">
                    Generate data-driven insights and administrative records.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 shadow-sm border border-emerald-100">
                    <i class="bi bi-bar-chart-line text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    
    <?php if($category): ?>
    <div class="flex items-center gap-3 px-6 py-4 bg-white/50 border border-gray-100 rounded-2xl">
        <a href="<?php echo e(route('admin.reports.index')); ?>" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-emerald-600 transition-colors flex items-center gap-2">
            <i class="bi bi-grid"></i> Reports
        </a>
        <i class="bi bi-chevron-right text-[8px] text-gray-300"></i>
        <a href="<?php echo e(route('admin.reports.index', ['category' => $category])); ?>" class="text-[10px] font-black uppercase tracking-widest transition-colors <?php echo e($type ? 'text-gray-400 hover:text-emerald-600' : 'text-emerald-600 underline decoration-2 underline-offset-4'); ?>">
            <?php echo e($category); ?> Reports
        </a>
        <?php if($type): ?>
            <i class="bi bi-chevron-right text-[8px] text-gray-300"></i>
            <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest underline decoration-2 underline-offset-4"><?php echo e(str_replace('_', ' ', $type)); ?></span>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    
    <?php if(!$category): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <a href="<?php echo e(route('admin.reports.index', ['category' => 'financial'])); ?>" class="glass-card p-10 group hover:border-emerald-500/30 hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-emerald-500/5 rounded-full blur-3xl group-hover:bg-emerald-500/10 transition-all duration-700"></div>
            <div class="flex items-start justify-between mb-8 relative z-10">
                <div class="w-16 h-16 bg-emerald-50 rounded-[20px] flex items-center justify-center text-emerald-600 border border-emerald-100 shadow-sm group-hover:scale-110 group-hover:bg-gray-900 group-hover:text-white transition-all duration-500">
                    <i class="bi bi-wallet2 text-3xl"></i>
                </div>
                <i class="bi bi-arrow-right text-gray-200 group-hover:text-emerald-500 text-2xl transition-all translate-x-0 group-hover:translate-x-2"></i>
            </div>
            <h3 class="text-2xl font-black text-gray-900 tracking-tight group-hover:text-emerald-700 transition-colors mb-3">Financial Reports</h3>
            <p class="text-gray-500 font-medium text-base leading-relaxed">Collections, unpaid dues, penalties, and financial forecasting.</p>
        </a>

        
        <a href="<?php echo e(route('admin.reports.index', ['category' => 'resident'])); ?>" class="glass-card p-10 group hover:border-emerald-500/30 hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-emerald-500/5 rounded-full blur-3xl group-hover:bg-emerald-500/10 transition-all duration-700"></div>
            <div class="flex items-start justify-between mb-8 relative z-10">
                <div class="w-16 h-16 bg-emerald-50 rounded-[20px] flex items-center justify-center text-emerald-600 border border-emerald-100 shadow-sm group-hover:scale-110 group-hover:bg-gray-900 group-hover:text-white transition-all duration-500">
                    <i class="bi bi-people text-3xl"></i>
                </div>
                <i class="bi bi-arrow-right text-gray-200 group-hover:text-emerald-500 text-2xl transition-all translate-x-0 group-hover:translate-x-2"></i>
            </div>
            <h3 class="text-2xl font-black text-gray-900 tracking-tight group-hover:text-emerald-700 transition-colors mb-3">Resident Reports</h3>
            <p class="text-gray-500 font-medium text-base leading-relaxed">Resident lists, active status tracking, and move-in history.</p>
        </a>

        
        <a href="<?php echo e(route('admin.reports.index', ['category' => 'amenities'])); ?>" class="glass-card p-10 group hover:border-emerald-500/30 hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-emerald-500/5 rounded-full blur-3xl group-hover:bg-emerald-500/10 transition-all duration-700"></div>
            <div class="flex items-start justify-between mb-8 relative z-10">
                <div class="w-16 h-16 bg-emerald-50 rounded-[20px] flex items-center justify-center text-emerald-600 border border-emerald-100 shadow-sm group-hover:scale-110 group-hover:bg-gray-900 group-hover:text-white transition-all duration-500">
                    <i class="bi bi-tree text-3xl"></i>
                </div>
                <i class="bi bi-arrow-right text-gray-200 group-hover:text-emerald-500 text-2xl transition-all translate-x-0 group-hover:translate-x-2"></i>
            </div>
            <h3 class="text-2xl font-black text-gray-900 tracking-tight group-hover:text-emerald-700 transition-colors mb-3">Amenities Reports</h3>
            <p class="text-gray-500 font-medium text-base leading-relaxed">Usage statistics, reservation trends, and facility popularity.</p>
        </a>

        
        <a href="<?php echo e(route('admin.reports.index', ['category' => 'maintenance'])); ?>" class="glass-card p-10 group hover:border-emerald-500/30 hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-emerald-500/5 rounded-full blur-3xl group-hover:bg-emerald-500/10 transition-all duration-700"></div>
            <div class="flex items-start justify-between mb-8 relative z-10">
                <div class="w-16 h-16 bg-emerald-50 rounded-[20px] flex items-center justify-center text-emerald-600 border border-emerald-100 shadow-sm group-hover:scale-110 group-hover:bg-gray-900 group-hover:text-white transition-all duration-500">
                    <i class="bi bi-tools text-3xl"></i>
                </div>
                <i class="bi bi-arrow-right text-gray-200 group-hover:text-emerald-500 text-2xl transition-all translate-x-0 group-hover:translate-x-2"></i>
            </div>
            <h3 class="text-2xl font-black text-gray-900 tracking-tight group-hover:text-emerald-700 transition-colors mb-3">Requests & Maintenance</h3>
            <p class="text-gray-500 font-medium text-base leading-relaxed">Service tickets, maintenance logs, and resolution performance.</p>
        </a>

        
        <a href="<?php echo e(route('admin.reports.index', ['category' => 'custom'])); ?>" class="glass-card p-10 group hover:border-emerald-500/30 hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 relative overflow-hidden md:col-span-2">
            <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-brand-accent/5 rounded-full blur-3xl group-hover:bg-brand-accent/10 transition-all duration-700"></div>
            <div class="flex items-start justify-between mb-8 relative z-10">
                <div class="w-16 h-16 bg-gray-900 rounded-[20px] flex items-center justify-center text-white border border-white/10 shadow-lg group-hover:scale-110 transition-all duration-500">
                    <i class="bi bi-sliders text-3xl"></i>
                </div>
                <i class="bi bi-arrow-right text-gray-200 group-hover:text-emerald-500 text-2xl transition-all translate-x-0 group-hover:translate-x-2"></i>
            </div>
            <h3 class="text-2xl font-black text-gray-900 tracking-tight group-hover:text-emerald-700 transition-colors mb-3">Custom Report Builder</h3>
            <p class="text-gray-500 font-medium text-base leading-relaxed max-w-2xl">Tailor your own reports by selecting specific columns and applying advanced filters.</p>
        </a>
    </div>
    <?php endif; ?>

    
    <?php if($category && !$type): ?>
    <div class="glass-card overflow-hidden">
        <div class="p-8 border-b border-gray-50 bg-gray-50/50">
            <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight capitalize"><?php echo e($category); ?> Reports</h3>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mt-1">Choose a specific report template to generate</p>
        </div>
        <div class="divide-y divide-gray-50">
            <?php if($category == 'financial'): ?>
                <?php $__currentLoopData = [
                    'monthly_collection' => 'Monthly Dues Collection',
                    'outstanding_balances' => 'Outstanding Balances',
                    'payment_history' => 'Payment History',
                    'penalties' => 'Penalties & Late Fees',
                    'financial_forecasting' => 'Financial Forecasting',
                    'statement_financial_position' => 'Statement of Financial Position'
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('admin.reports.index', ['category' => $category, 'type' => $key])); ?>" class="flex items-center justify-between p-8 hover:bg-emerald-50/30 transition-all group border-l-4 border-transparent hover:border-emerald-500">
                        <div class="flex items-center gap-6">
                            <div class="w-12 h-12 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-emerald-50 group-hover:text-emerald-600 transition-all duration-500 border border-gray-100 shadow-sm">
                                <i class="bi bi-file-earmark-text text-xl"></i>
                            </div>
                            <span class="text-lg font-black text-gray-900 group-hover:text-emerald-700 transition-colors"><?php echo e($label); ?></span>
                        </div>
                        <i class="bi bi-chevron-right text-gray-300 group-hover:text-emerald-500 transition-all translate-x-0 group-hover:translate-x-2"></i>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php elseif($category == 'resident'): ?>
                 <a href="<?php echo e(route('admin.reports.index', ['category' => $category, 'type' => 'resident_list'])); ?>" class="flex items-center justify-between p-8 hover:bg-emerald-50/30 transition-all group border-l-4 border-transparent hover:border-emerald-500">
                    <div class="flex items-center gap-6">
                        <div class="w-12 h-12 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-emerald-50 group-hover:text-emerald-600 transition-all duration-500 border border-gray-100 shadow-sm">
                            <i class="bi bi-people-fill text-xl"></i>
                        </div>
                        <span class="text-lg font-black text-gray-900 group-hover:text-emerald-700 transition-colors">Resident List & Status Summary</span>
                    </div>
                    <i class="bi bi-chevron-right text-gray-300 group-hover:text-emerald-500 transition-all translate-x-0 group-hover:translate-x-2"></i>
                </a>
            <?php elseif($category == 'amenities'): ?>
                <?php $__currentLoopData = [
                    'amenity_usage' => 'Amenity Usage Summary',
                    'reservation_history' => 'Reservation History',
                    'most_used' => 'Most/Least Used Amenities',
                    'amenity_revenue' => 'Amenity Revenue Report'
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('admin.reports.index', ['category' => $category, 'type' => $key])); ?>" class="flex items-center justify-between p-8 hover:bg-emerald-50/30 transition-all group border-l-4 border-transparent hover:border-emerald-500">
                        <div class="flex items-center gap-6">
                            <div class="w-12 h-12 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-emerald-50 group-hover:text-emerald-600 transition-all duration-500 border border-gray-100 shadow-sm">
                                <i class="bi bi-calendar-check text-xl"></i>
                            </div>
                            <span class="text-lg font-black text-gray-900 group-hover:text-emerald-700 transition-colors"><?php echo e($label); ?></span>
                        </div>
                        <i class="bi bi-chevron-right text-gray-300 group-hover:text-emerald-500 transition-all translate-x-0 group-hover:translate-x-2"></i>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php elseif($category == 'maintenance'): ?>
                <?php $__currentLoopData = [
                    'request_summary' => 'Maintenance Requests Summary',
                    'complaints_by_category' => 'Total Complaints by Category',
                    'maintenance_repeated' => 'Repeated Issues per Area'
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                 <a href="<?php echo e(route('admin.reports.index', ['category' => $category, 'type' => $key])); ?>" class="flex items-center justify-between p-8 hover:bg-emerald-50/30 transition-all group border-l-4 border-transparent hover:border-emerald-500">
                    <div class="flex items-center gap-6">
                        <div class="w-12 h-12 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-emerald-50 group-hover:text-emerald-600 transition-all duration-500 border border-gray-100 shadow-sm">
                            <i class="bi bi-tools text-xl"></i>
                        </div>
                        <span class="text-lg font-black text-gray-900 group-hover:text-emerald-700 transition-colors"><?php echo e($label); ?></span>
                    </div>
                    <i class="bi bi-chevron-right text-gray-300 group-hover:text-emerald-500 transition-all translate-x-0 group-hover:translate-x-2"></i>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php elseif($category == 'custom'): ?>
                <a href="<?php echo e(route('admin.reports.index', ['category' => $category, 'type' => 'custom_financial'])); ?>" class="flex items-center justify-between p-8 hover:bg-emerald-50/30 transition-all group border-l-4 border-transparent hover:border-emerald-500">
                    <div class="flex items-center gap-6">
                        <div class="w-12 h-12 rounded-xl bg-gray-900 text-brand-accent flex items-center justify-center border border-white/10 shadow-lg group-hover:scale-110 transition-all duration-500">
                            <i class="bi bi-currency-dollar text-xl"></i>
                        </div>
                        <span class="text-lg font-black text-gray-900 group-hover:text-emerald-700 transition-colors">Custom Financial Data (Payments/Dues)</span>
                    </div>
                    <i class="bi bi-chevron-right text-gray-300 group-hover:text-emerald-500 transition-all translate-x-0 group-hover:translate-x-2"></i>
                </a>
                <a href="<?php echo e(route('admin.reports.index', ['category' => $category, 'type' => 'custom_resident'])); ?>" class="flex items-center justify-between p-8 hover:bg-emerald-50/30 transition-all group border-l-4 border-transparent hover:border-emerald-500">
                    <div class="flex items-center gap-6">
                        <div class="w-12 h-12 rounded-xl bg-gray-900 text-brand-accent flex items-center justify-center border border-white/10 shadow-lg group-hover:scale-110 transition-all duration-500">
                            <i class="bi bi-person-lines-fill text-xl"></i>
                        </div>
                        <span class="text-lg font-black text-gray-900 group-hover:text-emerald-700 transition-colors">Custom Resident Data</span>
                    </div>
                    <i class="bi bi-chevron-right text-gray-300 group-hover:text-emerald-500 transition-all translate-x-0 group-hover:translate-x-2"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    
    <?php if($type && !$generate): ?>
    <div class="max-w-3xl mx-auto">
        <form action="<?php echo e(route('admin.reports.index')); ?>" method="GET" class="space-y-8">
            <input type="hidden" name="category" value="<?php echo e($category); ?>">
            <input type="hidden" name="type" value="<?php echo e($type); ?>">
            <input type="hidden" name="generate" value="true">

            <div class="glass-card overflow-hidden">
                <div class="p-8 border-b border-gray-50 bg-gray-50/50 text-center">
                    <h1 class="text-2xl font-black text-gray-900 uppercase tracking-tight"><?php echo e(str_replace('_', ' ', $type)); ?></h1>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mt-1">Configure report parameters and filters</p>
                </div>

                <div class="p-10 space-y-10">
                    
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Time Period</label>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="relative group">
                                <i class="bi bi-calendar-event absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                                <input type="date" name="start_date" value="<?php echo e($startDate); ?>" 
                                    class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold text-gray-700 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all outline-none">
                            </div>
                            <div class="relative group">
                                <i class="bi bi-calendar-check absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                                <input type="date" name="end_date" value="<?php echo e($endDate); ?>" 
                                    class="w-full pl-12 pr-6 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold text-gray-700 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all outline-none">
                            </div>
                        </div>
                    </div>

                    
                    <?php if($category == 'custom'): ?>
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Included Columns</label>
                        <div class="grid grid-cols-2 gap-4 bg-gray-50/50 p-8 rounded-[32px] border border-gray-100 shadow-inner">
                            <?php if($type == 'custom_financial'): ?>
                                <?php $__currentLoopData = ['created_at' => 'Date Paid', 'resident_name' => 'Resident Name', 'unit' => 'Unit (Block/Lot)', 'amount' => 'Amount', 'status' => 'Status', 'payment_method' => 'Payment Method']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="flex items-center gap-3 p-3 bg-white border border-gray-100 rounded-xl cursor-pointer hover:border-emerald-500/30 transition-all group">
                                        <input type="checkbox" name="columns[]" value="<?php echo e($col); ?>" class="w-5 h-5 rounded-lg border-gray-200 text-emerald-600 focus:ring-emerald-500/20" checked>
                                        <span class="text-[11px] font-black text-gray-600 uppercase tracking-widest group-hover:text-emerald-700 transition-colors"><?php echo e($label); ?></span>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php elseif($type == 'custom_resident'): ?>
                                <?php $__currentLoopData = ['full_name' => 'Name', 'unit' => 'Unit (Block/Lot)', 'contact_number' => 'Contact', 'email' => 'Email', 'status' => 'Status', 'move_in_date' => 'Move In Date']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="flex items-center gap-3 p-3 bg-white border border-gray-100 rounded-xl cursor-pointer hover:border-emerald-500/30 transition-all group">
                                        <input type="checkbox" name="columns[]" value="<?php echo e($col); ?>" class="w-5 h-5 rounded-lg border-gray-200 text-emerald-600 focus:ring-emerald-500/20" checked>
                                        <span class="text-[11px] font-black text-gray-600 uppercase tracking-widest group-hover:text-emerald-700 transition-colors"><?php echo e($label); ?></span>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    
                    <?php if($type !== 'outstanding_balances' && $type !== 'paid_vs_unpaid' && $type !== 'financial_forecasting' && $type !== 'statement_financial_position'): ?>
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Filter by Status</label>
                        <div class="relative group/select">
                            <select name="status" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-black text-gray-700 focus:bg-white focus:border-emerald-500 outline-none appearance-none cursor-pointer transition-all">
                                <option value="all" <?php echo e($status == 'all' ? 'selected' : ''); ?>>ALL STATUSES</option>
                                <?php if($category == 'financial'): ?>
                                    <option value="approved" <?php echo e($status == 'approved' ? 'selected' : ''); ?>>PAID / APPROVED</option>
                                    <option value="pending" <?php echo e($status == 'pending' ? 'selected' : ''); ?>>PENDING</option>
                                    <option value="rejected" <?php echo e($status == 'rejected' ? 'selected' : ''); ?>>REJECTED</option>
                                <?php elseif($category == 'resident'): ?>
                                    <option value="active" <?php echo e($status == 'active' ? 'selected' : ''); ?>>ACTIVE</option>
                                    <option value="inactive" <?php echo e($status == 'inactive' ? 'selected' : ''); ?>>INACTIVE</option>
                                <?php else: ?>
                                    <option value="pending" <?php echo e($status == 'pending' ? 'selected' : ''); ?>>PENDING</option>
                                    <option value="approved" <?php echo e($status == 'approved' ? 'selected' : ''); ?>>APPROVED / IN PROGRESS</option>
                                    <option value="completed" <?php echo e($status == 'completed' ? 'selected' : ''); ?>>COMPLETED</option>
                                <?php endif; ?>
                            </select>
                            <i class="bi bi-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none group-hover/select:text-emerald-600 transition-colors"></i>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="pt-6">
                        <button type="submit" class="btn-premium w-full py-5 text-sm">
                            <i class="bi bi-gear-fill"></i>
                            Generate Professional Report
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php endif; ?>

    
    <?php if($generate): ?>
    <div class="space-y-8 animate-fade-in">
        
        <div class="glass-card p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 relative overflow-hidden group">
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl group-hover:bg-emerald-500/10 transition-all duration-700"></div>
            <div class="relative z-10">
                <h1 class="text-2xl font-black text-gray-900 uppercase tracking-tight"><?php echo e(str_replace('_', ' ', $type)); ?></h1>
                <div class="flex items-center gap-3 mt-2">
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase tracking-widest rounded-full border border-emerald-100/50 shadow-sm">
</toolcall_result>
                            <?php echo e(\Carbon\Carbon::parse($startDate)->format('M d, Y')); ?> - <?php echo e(\Carbon\Carbon::parse($endDate)->format('M d, Y')); ?>

                        </span>
                        <?php if($status !== 'all'): ?>
                        <span class="bg-gray-100 px-2 py-0.5 rounded border border-gray-200 capitalize">
                            Status: <?php echo e($status); ?>

                        </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    
                    <div class="bg-white p-1 rounded-lg border border-gray-200 flex items-center shadow-sm">
                        <button @click="showSummary = false" 
                            :class="!showSummary ? 'bg-gray-100 text-gray-900 font-bold shadow-sm' : 'text-gray-500 hover:bg-gray-50'"
                            class="px-3 py-1.5 rounded-md text-sm transition-all">
                            <i class="bi bi-table mr-1"></i> Detail View
                        </button>
                        <button @click="showSummary = true" 
                            :class="showSummary ? 'bg-gray-100 text-gray-900 font-bold shadow-sm' : 'text-gray-500 hover:bg-gray-50'"
                            class="px-3 py-1.5 rounded-md text-sm transition-all">
                            <i class="bi bi-pie-chart mr-1"></i> Board View
                        </button>
                    </div>

                    
                    <div class="h-8 w-px bg-gray-300 mx-1"></div>
                    
                    <a href="<?php echo e(route('admin.reports.exportPdf', request()->all())); ?>" class="bg-red-50 text-red-700 hover:bg-red-100 border border-red-200 px-4 py-2 rounded-lg text-sm font-bold transition-colors">
                        <i class="bi bi-file-pdf"></i> PDF
                    </a>
                    <a href="<?php echo e(route('admin.reports.exportExcel', request()->all())); ?>" class="bg-green-50 text-green-700 hover:bg-green-100 border border-green-200 px-4 py-2 rounded-lg text-sm font-bold transition-colors">
                        <i class="bi bi-file-earmark-excel"></i> Excel
                    </a>
                    <a href="<?php echo e(route('admin.reports.exportCsv', request()->all())); ?>" class="bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 px-4 py-2 rounded-lg text-sm font-bold transition-colors">
                        <i class="bi bi-file-text"></i> CSV
                    </a>
                </div>
            </div>

            
            <div x-show="showSummary" style="display: none;" class="space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php $__currentLoopData = $summary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                        <p class="text-sm font-bold text-gray-500 uppercase tracking-wide"><?php echo e($key); ?></p>
                        <p class="text-3xl font-extrabold text-gray-900 mt-2"><?php echo e($value); ?></p>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                
                <?php if(isset($chartData) && $chartData): ?>
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-8"><?php echo e($chartData['title']); ?></h3>
                    
                    <div class="relative h-64 flex items-end justify-between space-x-2 md:space-x-6 px-4">
                        <?php 
                            $maxValue = 1;
                            if (!empty($chartData['values'])) {
                                $localMax = max($chartData['values']);
                                $maxValue = $localMax > 0 ? $localMax : 1;
                            }
                        ?>
                        <?php $__currentLoopData = $chartData['values']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php 
                                $height = ($value / $maxValue) * 100; 
                                $label = $chartData['labels'][$index] ?? '';
                                $colors = ['bg-blue-500', 'bg-emerald-500', 'bg-purple-500', 'bg-orange-500', 'bg-pink-500'];
                                $color = $colors[$index % count($colors)];
                            ?>
                            <div class="flex-1 flex flex-col items-center group relative">
                                <div class="w-full <?php echo e($color); ?> rounded-t-lg hover:opacity-90 transition-all relative" style="height: <?php echo e($height); ?>%; min-height: 4px;">
                                    <div class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs font-bold py-1 px-2 rounded shadow-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10">
                                        <?php echo e(number_format($value)); ?>

                                    </div>
                                </div>
                                <div class="mt-3 text-xs font-medium text-gray-500 text-center w-full truncate" title="<?php echo e($label); ?>">
                                    <?php echo e($label); ?>

                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                        
                        <div class="absolute inset-0 pointer-events-none flex flex-col justify-between" style="z-index: 0;">
                            <div class="border-t border-gray-100 w-full h-0"></div>
                            <div class="border-t border-gray-100 w-full h-0"></div>
                            <div class="border-t border-gray-100 w-full h-0"></div>
                            <div class="border-t border-gray-100 w-full h-0"></div>
                            <div class="border-t border-gray-200 w-full h-0"></div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            
            <div x-show="!showSummary" class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <?php if($type === 'statement_financial_position'): ?>
                    <?php echo $__env->make('admin.reports.partials.statement_financial_position', ['results' => $results], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider"><?php echo e($col); ?></th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php $__empty_1 = true; $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <?php $__currentLoopData = $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cell): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">
                                        <?php echo e($cell); ?>

                                    </td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="<?php echo e(count($columns)); ?>" class="px-6 py-8 text-center text-gray-500 italic">
                                    No records found for the selected criteria.
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                        
                        <tfoot class="bg-gray-50 border-t border-gray-200">
                            <tr>
                                <td colspan="<?php echo e(count($columns)); ?>" class="px-6 py-4">
                                    <div class="flex gap-6 text-sm">
                                        <?php $__currentLoopData = $summary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div>
                                                <span class="font-bold text-gray-500"><?php echo e($key); ?>:</span>
                                                <span class="font-bold text-gray-900 ml-1"><?php echo e($value); ?></span>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\admin\reports\index.blade.php ENDPATH**/ ?>