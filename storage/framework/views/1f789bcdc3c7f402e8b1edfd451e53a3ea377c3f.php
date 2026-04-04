<?php $__env->startSection('title', 'Batch Details'); ?>
<?php $__env->startSection('page-title', 'Billing Statement Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8 animate-fade-in" x-data="paymentWorkflow()">

    
    
    
    <div class="glass-card p-8 relative overflow-hidden group">
        
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-brand-accent/5 rounded-full blur-3xl group-hover:bg-brand-accent/10 transition-all duration-700"></div>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div class="flex items-center gap-6">
                <a href="<?php echo e(route('admin.dues.index')); ?>" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white border border-gray-100 text-gray-400 hover:text-emerald-600 hover:border-emerald-100 hover:shadow-sm transition-all shadow-sm">
                    <i class="bi bi-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                        <?php echo e($batch->title ?? 'Untitled Statement'); ?>

                    </h1>
                    <p class="mt-2 text-gray-600 text-lg flex items-center gap-2">
                        <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-black uppercase tracking-widest border border-emerald-100">
                            <?php echo e(str_replace('_', ' ', $batch->type ?? 'N/A')); ?>

                        </span>
                        <span class="text-gray-400">•</span>
                        <span class="font-bold text-gray-500 uppercase tracking-widest text-sm">Due <?php echo e($batch->due_date ? $batch->due_date->format('M d, Y') : 'N/A'); ?></span>
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button class="btn-secondary">
                    <i class="bi bi-download"></i>
                    Export CSV
                </button>
                <button class="btn-premium">
                    <i class="bi bi-bell"></i>
                    Bulk Reminders
                </button>
            </div>
        </div>
    </div>

    
    
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="glass-card p-6 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative z-10 space-y-4">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Collection Progress</p>
                <div class="flex items-end justify-between">
                    <h3 class="text-3xl font-black text-gray-900 tracking-tight"><?php echo e(number_format($batch->progress ?? 0, 1)); ?>%</h3>
                    <p class="text-[11px] font-bold text-gray-500 tabular-nums">₱<?php echo e(number_format($batch->collected_amount ?? 0, 0)); ?> / ₱<?php echo e(number_format($batch->total_expected ?? 0, 0)); ?></p>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                    <div class="bg-emerald-500 h-full rounded-full transition-all duration-1000 shadow-[0_0_10px_rgba(16,185,129,0.3)]" style="width: <?php echo e($batch->progress ?? 0); ?>%"></div>
                </div>
            </div>
        </div>

        
        <div class="glass-card p-6 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative z-10 space-y-4">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Resident Status</p>
                <div class="flex items-center justify-between">
                    <div class="text-center px-4">
                        <div class="text-2xl font-black text-emerald-600"><?php echo e($batch->residentDues->where('status', 'paid')->count()); ?></div>
                        <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest mt-1">Paid</div>
                    </div>
                    <div class="text-center px-4 border-x border-gray-100">
                        <div class="text-2xl font-black text-amber-500"><?php echo e($batch->residentDues->where('status', 'partial')->count()); ?></div>
                        <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest mt-1">Partial</div>
                    </div>
                    <div class="text-center px-4">
                        <div class="text-2xl font-black text-red-500"><?php echo e($batch->residentDues->where('status', 'unpaid')->count()); ?></div>
                        <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest mt-1">Unpaid</div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="glass-card bg-gray-900 p-6 relative overflow-hidden group border-none">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl"></div>
            <div class="relative z-10 space-y-4">
                <p class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Pending Collection</p>
                <h3 class="text-3xl font-black text-white tracking-tight">₱<?php echo e(number_format(($batch->total_expected ?? 0) - ($batch->collected_amount ?? 0), 2)); ?></h3>
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <p class="text-[11px] font-medium text-gray-400">Targeting <?php echo e($batch->residentDues->count()); ?> residents</p>
                </div>
            </div>
        </div>
    </div>

    
    
    
    <div class="glass-card p-4 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        
        
        <div class="flex-1 max-w-md">
            <div class="relative group">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-600 transition-colors"></i>
                <input type="text" id="residentSearch" 
                    placeholder="Search resident by name or email..." 
                    class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all placeholder-gray-400">
            </div>
        </div>

        
        <div class="flex flex-wrap items-center gap-3">
            
            <div class="relative group">
                <select id="statusFilter" 
                        class="appearance-none pl-4 pr-10 py-2.5 bg-white border border-gray-200 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-700 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all cursor-pointer hover:border-gray-300">
                    <option value="">All Statuses</option>
                    <option value="paid">Paid</option>
                    <option value="partial">Partial</option>
                    <option value="unpaid">Unpaid</option>
                </select>
                <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none group-hover:text-gray-600 transition-colors"></i>
            </div>
        </div>
    </div>

    
    
    
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50/50 border-b border-gray-100">
                    <tr>
                        <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Resident</th>
                        <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Property</th>
                        <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Amount Due</th>
                        <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Paid</th>
                        <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Balance</th>
                        <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                        <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50" id="residentTableBody">
                    <?php $__empty_1 = true; $__currentLoopData = $batch->residentDues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $due): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $statusInfo = $due->status_info;
                    ?>
                    <tr class="hover:bg-emerald-50/30 transition-all duration-300 group border-l-4 border-transparent hover:border-emerald-500" 
                        data-status="<?php echo e($due->dynamic_status); ?>" 
                        data-name="<?php echo e(strtolower(($due->resident?->first_name ?? '') . ' ' . ($due->resident?->last_name ?? ''))); ?>">
                        
                        <td class="p-5">
                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-xs border border-emerald-100 shadow-sm group-hover:scale-105 transition-transform duration-500">
                                    <?php echo e(strtoupper(substr($due->resident?->first_name ?? 'R', 0, 1))); ?><?php echo e(strtoupper(substr($due->resident?->last_name ?? 'S', 0, 1))); ?>

                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 group-hover:text-emerald-700 transition-colors"><?php echo e(($due->resident?->first_name ?? 'Unknown') . ' ' . ($due->resident?->last_name ?? 'Resident')); ?></p>
                                    <p class="text-[11px] text-gray-500 font-medium tracking-wide"><?php echo e($due->resident?->email ?? 'No Email'); ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="p-5">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-gray-700">Block <?php echo e($due->resident?->block ?? '-'); ?></span>
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Lot <?php echo e($due->resident?->lot ?? '-'); ?></span>
                            </div>
                        </td>
                        <td class="p-5">
                            <div class="text-sm font-black text-gray-900 tabular-nums">₱<?php echo e(number_format($due->amount, 2)); ?></div>
                        </td>
                        <td class="p-5">
                            <div class="text-sm font-bold text-emerald-600 tabular-nums">₱<?php echo e(number_format($due->total_paid, 2)); ?></div>
                        </td>
                        <td class="p-5">
                            <div class="text-sm font-black text-red-600 tabular-nums">₱<?php echo e(number_format($due->balance, 2)); ?></div>
                        </td>
                        <td class="p-5 text-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-<?php echo e($statusInfo['color']); ?>-100 bg-<?php echo e($statusInfo['color']); ?>-50 text-<?php echo e($statusInfo['color']); ?>-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-<?php echo e($statusInfo['color']); ?>-500"></span>
                                <?php echo e($statusInfo['label']); ?>

                            </span>
                        </td>
                        <td class="p-5 text-right">
                            <?php if($due->balance > 0): ?>
                                <button @click="openPaymentModal({
                                    id: <?php echo e($due->id); ?>, 
                                    name: <?php echo e(json_encode(($due->resident?->first_name ?? '') . ' ' . ($due->resident?->last_name ?? ''))); ?>, 
                                    balance: <?php echo e($due->balance); ?>,
                                    title: <?php echo e(json_encode($batch->title ?? 'Untitled Statement')); ?>,
                                    type: <?php echo e(json_encode(str_replace('_', ' ', $batch->type ?? 'N/A'))); ?>

                                })" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-sm active:scale-95">
                                    Record Payment
                                </button>
                            <?php else: ?>
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center ml-auto border border-emerald-100 shadow-sm" title="Fully Paid">
                                    <i class="bi bi-check2-all text-xl"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="p-20 text-center">
                            <div class="w-20 h-20 rounded-3xl bg-gray-50 flex items-center justify-center mx-auto mb-6 text-gray-200">
                                <i class="bi bi-people text-4xl"></i>
                            </div>
                            <p class="text-gray-400 text-sm font-medium">No residents found for this billing statement.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <template x-teleport="body">
        <div id="paymentModal" x-show="showPaymentModal" 
             class="fixed inset-0 z-[9999] flex items-center justify-center p-4 overflow-y-auto bg-black/40 backdrop-blur-[2px]" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @keydown.escape.window="closePaymentModal()"
             x-cloak>
            
            
            <div class="absolute inset-0 cursor-default" @click="closePaymentModal()"></div>

            <div class="relative w-full max-w-md my-auto pointer-events-auto">
                
                <div x-show="step === 1" class="bg-white rounded-[32px] shadow-2xl overflow-hidden border border-gray-100">
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-xl font-black text-gray-900 tracking-tight">Record Payment</h3>
                            <button type="button" @click="closePaymentModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-50 text-gray-400 hover:text-gray-600 transition-all">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>

                        <div class="bg-emerald-50/50 p-5 rounded-2xl mb-8 border border-emerald-100/50">
                            <div class="text-[9px] font-black text-emerald-500 uppercase tracking-[0.2em] mb-1.5">Resident</div>
                            <div x-text="paymentData.name" class="text-base font-black text-[#0D1F1C] tracking-tight">-</div>
                        </div>

                        <div class="space-y-6">
                            <div class="space-y-2.5">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Amount to Pay</label>
                                <div class="relative group">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold transition-colors group-focus-within:text-emerald-500">₱</span>
                                    <input type="number" x-model="paymentData.amount" step="0.01" class="w-full pl-10 pr-4 py-3.5 rounded-2xl border border-gray-100 bg-gray-50 text-sm font-bold focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 outline-none transition-all" required>
                                </div>
                                <p class="text-[10px] text-emerald-600 font-black uppercase tracking-widest ml-1">Balance: ₱<span x-text="paymentData.balance.toLocaleString(undefined, {minimumFractionDigits: 2})">0.00</span></p>
                            </div>

                            <div class="space-y-2.5">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Payment Method</label>
                                <div class="relative group">
                                    <select x-model="paymentData.method" class="w-full px-4 py-3.5 rounded-2xl border border-gray-100 bg-gray-50 text-sm font-bold focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 outline-none transition-all appearance-none cursor-pointer" required>
                                        <option value="cash">Cash</option>
                                        <option value="gcash">GCash</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                        <option value="check">Check</option>
                                    </select>
                                    <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs group-focus-within:text-emerald-500 transition-colors"></i>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-10">
                            <button type="button" @click="closePaymentModal()" class="py-4 rounded-2xl border border-gray-100 text-gray-500 text-[10px] font-black uppercase tracking-widest hover:bg-gray-50 transition-all active:scale-95">Cancel</button>
                            <button type="button" @click="step = 2" class="py-4 rounded-2xl bg-[#0D1F1C] text-white text-[10px] font-black uppercase tracking-widest hover:bg-[#1a2e2a] transition-all shadow-lg shadow-emerald-500/10 active:scale-95">Review Payment</button>
                        </div>
                    </div>
                </div>

                
                <div x-show="step === 2" class="bg-white rounded-[32px] shadow-2xl overflow-hidden border border-gray-100" x-cloak>
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-xl font-black text-gray-900 tracking-tight">Review Settlement</h3>
                            <button type="button" @click="closePaymentModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-50 text-gray-400 hover:text-gray-600 transition-all">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>

                        <div class="space-y-1">
                            <div class="flex justify-between items-center py-4 border-b border-gray-50 group">
                                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Resident</span>
                                <span x-text="paymentData.name" class="text-sm font-black text-gray-900"></span>
                            </div>
                            <div class="flex justify-between items-start py-4 border-b border-gray-50">
                                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest mt-1">Billing</span>
                                <div class="text-right">
                                    <p x-text="paymentData.title" class="text-sm font-black text-gray-900"></p>
                                    <p x-text="paymentData.type" class="text-[9px] font-black text-emerald-500 uppercase tracking-widest mt-0.5"></p>
                                </div>
                            </div>
                            <div class="flex justify-between items-center py-4 border-b border-gray-50">
                                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Amount to Pay</span>
                                <span class="text-xl font-black text-gray-900">₱<span x-text="parseFloat(paymentData.amount).toLocaleString(undefined, {minimumFractionDigits: 2})"></span></span>
                            </div>
                            <div class="flex justify-between items-center py-4 border-b border-gray-50">
                                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Remaining Balance</span>
                                <span :class="(paymentData.balance - parseFloat(paymentData.amount)) <= 0 ? 'text-emerald-600' : 'text-red-600'" class="text-xl font-black">
                                    ₱<span x-text="Math.max(0, paymentData.balance - parseFloat(paymentData.amount)).toLocaleString(undefined, {minimumFractionDigits: 2})"></span>
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-4">
                                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Method</span>
                                <span x-text="paymentData.method" class="px-3 py-1.5 bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase tracking-widest rounded-full border border-emerald-100 capitalize"></span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-10">
                            <button type="button" @click="step = 1" class="py-4 rounded-2xl border border-gray-100 text-gray-500 text-[10px] font-black uppercase tracking-widest hover:bg-gray-50 transition-all flex items-center justify-center gap-2 active:scale-95">
                                <i class="bi bi-arrow-left"></i>
                                Back
                            </button>
                            <button type="button" @click="confirmPayment()" :disabled="loading" class="py-4 rounded-2xl bg-[#081412] text-[#B6FF5C] text-[10px] font-black uppercase tracking-widest hover:bg-[#1a2e2a] transition-all shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed active:scale-95">
                                <template x-if="!loading">
                                    <span>Confirm & Record</span>
                                </template>
                                <template x-if="loading">
                                    <span class="flex items-center gap-2">
                                        <svg class="animate-spin h-3 w-3 text-[#B6FF5C]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        Processing...
                                    </span>
                                </template>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            
            <div x-show="step === 3" class="relative w-full max-w-2xl my-auto pointer-events-auto" x-cloak>
                <div class="bg-white rounded-[32px] shadow-2xl overflow-hidden border border-gray-100">
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-xl font-black text-gray-900 tracking-tight">Official Receipt</h3>
                            <button type="button" @click="closePaymentModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-50 text-gray-400 hover:text-gray-600 transition-all">
                                <i class="bi bi-x-lg text-sm"></i>
                            </button>
                        </div>

                        <div class="border border-gray-100 rounded-[24px] p-8 bg-gray-50/50 mb-8 max-h-[60vh] overflow-y-auto custom-scrollbar relative">
                            <div class="absolute top-0 right-0 p-6 opacity-[0.03] pointer-events-none">
                                <i class="bi bi-patch-check-fill text-[120px] text-emerald-600"></i>
                            </div>
                            
                            <div class="space-y-10 relative z-10">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="text-2xl font-black text-[#081412] tracking-tighter">VISTABELLA</h4>
                                        <p class="text-[9px] font-black text-emerald-500 uppercase tracking-[0.3em] mt-1">Official Receipt</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Receipt No.</p>
                                        <p class="text-lg font-black text-gray-900 tabular-nums" x-text="receiptData.id ? '#' + receiptData.id.toString().padStart(8, '0') : 'Generating...'"></p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-12 text-sm">
                                    <div>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Resident</p>
                                        <p x-text="paymentData.name" class="font-black text-[#081412] tracking-tight text-base"></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Date Processed</p>
                                        <p x-text="new Date().toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })" class="font-black text-[#081412] tracking-tight text-base"></p>
                                    </div>
                                </div>

                                <div class="border-t border-dashed border-gray-200 pt-8">
                                    <div class="flex justify-between items-center mb-6">
                                        <div class="space-y-1">
                                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Particulars</p>
                                            <p class="text-sm font-black text-gray-700" x-text="paymentData.title"></p>
                                        </div>
                                        <span class="text-base font-black text-[#081412] tabular-nums" x-text="'₱' + parseFloat(paymentData.amount).toLocaleString(undefined, {minimumFractionDigits: 2})"></span>
                                    </div>
                                    <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                                        <span class="text-[10px] font-black text-gray-900 uppercase tracking-[0.2em]">Total Amount Paid</span>
                                        <span class="text-3xl font-black text-emerald-600 tabular-nums" x-text="'₱' + parseFloat(paymentData.amount).toLocaleString(undefined, {minimumFractionDigits: 2})"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap justify-center gap-4">
                            <button type="button" @click="printReceipt()" class="px-8 py-4 rounded-2xl bg-[#081412] text-white text-[10px] font-black uppercase tracking-widest hover:bg-[#1a2e2a] transition-all flex items-center gap-3 shadow-lg shadow-emerald-500/10 active:scale-95">
                                <i class="bi bi-printer-fill text-sm"></i>
                                Print Receipt
                            </button>
                            <button type="button" @click="downloadReceipt()" class="px-8 py-4 rounded-2xl bg-white border border-gray-200 text-gray-700 text-[10px] font-black uppercase tracking-widest hover:bg-gray-50 transition-all flex items-center gap-3 active:scale-95">
                                <i class="bi bi-download text-sm"></i>
                                Download PDF
                            </button>
                            <button type="button" @click="closePaymentModal()" class="px-8 py-4 rounded-2xl bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest hover:bg-emerald-100 transition-all active:scale-95">
                                Close Window
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

<style>
    [x-cloak] { display: none !important; }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
</style>

<script>
    function paymentWorkflow() {
        return {
            showPaymentModal: false,
            step: 1,
            loading: false,
            paymentData: {
                id: null,
                name: '',
                balance: 0,
                amount: 0,
                method: 'cash',
                title: '',
                type: ''
            },
            receiptData: {
                id: 0
            },
            init() {
                this.$watch('showPaymentModal', value => {
                    if (value) {
                        document.body.style.overflow = 'hidden';
                    } else {
                        document.body.style.overflow = 'auto';
                    }
                });
            },
            openPaymentModal(data) {
                this.paymentData = {
                    id: data.id,
                    name: data.name,
                    balance: data.balance,
                    amount: data.balance,
                    method: 'cash',
                    title: data.title,
                    type: data.type
                };
                this.step = 1;
                this.showPaymentModal = true;
            },
            closePaymentModal() {
                this.showPaymentModal = false;
                if (this.step === 3) {
                    window.location.reload();
                }
            },
            async confirmPayment() {
                this.loading = true;
                try {
                    const response = await fetch(`<?php echo e(url('admin/dues')); ?>/${this.paymentData.id}/pay`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                        },
                        body: JSON.stringify({
                            amount: this.paymentData.amount,
                            method: this.paymentData.method
                        })
                    });
                    
                    const result = await response.json();
                    if (result.success) {
                        this.receiptData.id = result.payment_id;
                        this.step = 3;
                    } else {
                        alert(result.message || 'Payment failed');
                    }
                } catch (error) {
                    console.error('Payment confirmation error:', error);
                    alert('An error occurred. Please try again.');
                } finally {
                    this.loading = false;
                }
            },
            printReceipt() {
                window.open(`<?php echo e(url('admin/payments')); ?>/${this.receiptData.id}/receipt`, '_blank');
            },
            downloadReceipt() {
                window.open(`<?php echo e(url('admin/payments')); ?>/${this.receiptData.id}/receipt`, '_blank');
            }
        }
    }

    // Client-side filtering (existing)
    document.getElementById('residentSearch').addEventListener('input', filterTable);
    document.getElementById('statusFilter').addEventListener('change', filterTable);

    function filterTable() {
        const searchTerm = document.getElementById('residentSearch').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const rows = document.querySelectorAll('#residentTableBody tr[data-status]');

        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            const status = row.getAttribute('data-status');
            
            const matchesSearch = name.includes(searchTerm);
            const matchesStatus = statusFilter === '' || status === statusFilter;

            if (matchesSearch && matchesStatus) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        });
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views/admin/dues/show.blade.php ENDPATH**/ ?>