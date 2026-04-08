<?php $__env->startSection('title', 'Payments'); ?>
<?php $__env->startSection('page-title', 'Payments History'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8 animate-fade-in" x-data="paymentsBulkApproval()" x-cloak>

    
    
    
    <div class="glass-card p-8 relative overflow-hidden group">
        
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-brand-accent/5 rounded-full blur-3xl group-hover:bg-brand-accent/10 transition-all duration-700"></div>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                    Payments
                </h1>
                <p class="mt-2 text-gray-600 text-lg max-w-xl">
                    Track community collections, review transactions, and manage resident payments.
                </p>
            </div>
        </div>
    </div>

    
    
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="glass-card p-6 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative z-10 space-y-2">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Collected (<?php echo e(date('Y')); ?>)</p>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight">₱<?php echo e(number_format($totalCollectedYear, 2)); ?></h3>
                <div class="flex items-center gap-2 pt-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Approved</p>
                </div>
            </div>
        </div>

        
        <div class="glass-card p-6 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-orange-50 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative z-10 space-y-2">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Pending Approval</p>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight">₱<?php echo e(number_format($pendingAmount, 2)); ?></h3>
                <div class="flex items-center gap-2 pt-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                    <p class="text-[10px] font-black text-orange-600 uppercase tracking-widest"><?php echo e($pendingCount); ?> Transactions</p>
                </div>
            </div>
        </div>

        
        <div class="glass-card p-6 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative z-10 space-y-2">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Collected This Month</p>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight">₱<?php echo e(number_format($thisMonth, 2)); ?></h3>
                <div class="flex items-center gap-2 pt-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                    <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest"><?php echo e(date('F')); ?> Collection</p>
                </div>
            </div>
        </div>

        
        <div class="glass-card p-6 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-gray-50 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative z-10 space-y-2">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">MoM Growth</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-2xl font-black text-gray-900 tracking-tight"><?php echo e(number_format(abs($growth), 1)); ?>%</h3>
                    <span class="mb-1.5 text-xs font-black <?php echo e($direction === 'up' ? 'text-emerald-500' : ($direction === 'down' ? 'text-red-500' : 'text-gray-500')); ?>">
                        <?php if($direction !== 'neutral'): ?>
                            <i class="bi bi-arrow-<?php echo e($direction); ?>"></i>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </span>
                </div>
                <div class="flex items-center gap-2 pt-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">vs Last Month</p>
                </div>
            </div>
        </div>
    </div>

    
    
    
    <div class="glass-card p-4 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        
        
        <div class="flex-1 max-w-md">
            <form method="GET" action="<?php echo e(route('admin.payments.index')); ?>" class="relative group">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-600 transition-colors"></i>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                    placeholder="Search name, reference, or description..." 
                    class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all placeholder-gray-400">
                
                <?php $__currentLoopData = request()->except(['search', 'page']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <input type="hidden" name="<?php echo e($key); ?>" value="<?php echo e($value); ?>">
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </form>
        </div>

        
        <div class="flex flex-wrap items-center gap-3">
            <button type="button"
                    x-show="hasPendingRows && !selectionMode"
                    x-transition.opacity.duration.150ms
                    @click="enableSelectionMode()"
                    class="h-11 px-4 inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white text-[10px] font-black uppercase tracking-widest text-gray-600 transition-all hover:border-emerald-500/30 hover:bg-gray-50">
                <i class="bi bi-check2-square text-emerald-600"></i>
                Select
            </button>

            <div x-show="selectionMode"
                 x-transition.opacity.duration.150ms
                 class="flex items-center gap-2">
                <button type="button"
                        @click="clearSelection()"
                        class="h-11 px-4 inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white text-[10px] font-black uppercase tracking-widest text-gray-600 transition-all hover:border-gray-300 hover:bg-gray-50">
                    <i class="bi bi-x-lg"></i>
                    Cancel Selection
                </button>

                <button type="button"
                        x-show="hasPendingSelection"
                        x-transition.opacity.duration.150ms
                        @click="submitApproval()"
                        class="h-11 px-4 inline-flex items-center gap-2 rounded-xl bg-gray-900 text-[10px] font-black uppercase tracking-widest text-white transition-all hover:bg-emerald-600 disabled:cursor-not-allowed disabled:opacity-50"
                        :disabled="!hasPendingSelection">
                    <i class="bi bi-check2-all"></i>
                    Approve Selected
                </button>
            </div>
            
            
            <div class="relative group/filter">
                <button onclick="toggleDropdown('statusDropdown')" 
                    class="h-11 px-4 flex items-center gap-2 rounded-xl border border-gray-200 bg-white text-[10px] font-black uppercase tracking-widest text-gray-600 hover:border-emerald-500/30 hover:bg-gray-50 transition-all relative">
                    <i class="bi bi-funnel text-emerald-600"></i>
                    Status
                    <i class="bi bi-chevron-down text-[8px] opacity-50"></i>
                    <?php if(request('status')): ?>
                        <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-emerald-500 rounded-full border-2 border-white"></span>
                    <?php endif; ?>
                </button>
                <div id="statusDropdown" class="hidden absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-50 py-2">
                    <div class="px-4 py-2 text-[10px] font-black text-gray-400 uppercase tracking-wider border-b border-gray-50 mb-1">Filter Status</div>
                    <a href="<?php echo e(request()->fullUrlWithQuery(['status' => null])); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium transition-colors">All Statuses</a>
                    <a href="<?php echo e(request()->fullUrlWithQuery(['status' => 'pending'])); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium transition-colors">Pending</a>
                    <a href="<?php echo e(request()->fullUrlWithQuery(['status' => 'approved'])); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium transition-colors">Approved</a>
                    <a href="<?php echo e(request()->fullUrlWithQuery(['status' => 'rejected'])); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium transition-colors">Rejected</a>
                </div>
            </div>

            
            <div class="relative group/filter">
                <button onclick="toggleDropdown('methodDropdown')" 
                    class="h-11 px-4 flex items-center gap-2 rounded-xl border border-gray-200 bg-white text-[10px] font-black uppercase tracking-widest text-gray-600 hover:border-emerald-500/30 hover:bg-gray-50 transition-all relative">
                    <i class="bi bi-credit-card text-emerald-600"></i>
                    Method
                    <i class="bi bi-chevron-down text-[8px] opacity-50"></i>
                    <?php if(request('method')): ?>
                        <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-emerald-500 rounded-full border-2 border-white"></span>
                    <?php endif; ?>
                </button>
                <div id="methodDropdown" class="hidden absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-50 py-2">
                    <div class="px-4 py-2 text-[10px] font-black text-gray-400 uppercase tracking-wider border-b border-gray-50 mb-1">Filter Method</div>
                    <a href="<?php echo e(request()->fullUrlWithQuery(['method' => null])); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium transition-colors">All Methods</a>
                    <?php $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(request()->fullUrlWithQuery(['method' => $method])); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium transition-colors capitalize"><?php echo e(str_replace('_', ' ', $method)); ?></a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            
            <div class="flex items-center bg-gray-50 p-1 rounded-xl border border-gray-100">
                <button onclick="toggleView('list')" id="listViewBtn" class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-400 hover:text-emerald-600 transition-all">
                    <i class="bi bi-list-ul text-lg"></i>
                </button>
                <button onclick="toggleView('grid')" id="gridViewBtn" class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-400 hover:text-emerald-600 transition-all">
                    <i class="bi bi-grid-fill text-lg"></i>
                </button>
            </div>

            
            <?php if(request()->anyFilled(['search', 'status', 'method', 'date_filter', 'sort_option'])): ?>
                <a href="<?php echo e(route('admin.payments.index')); ?>" class="h-11 w-11 flex items-center justify-center rounded-xl border border-red-100 text-red-500 hover:bg-red-50 transition-all" title="Clear All Filters">
                    <i class="bi bi-x-lg"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>

    
    
    
    <div class="glass-card overflow-hidden">
        
        
        <div id="listView" class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50/50 border-b border-gray-100">
                    <tr>
                        <th class="p-5 w-14 text-center">
                            <input type="checkbox"
                                   x-show="selectionMode && hasPendingRows"
                                   x-model="selectAll"
                                    @change="toggleSelectAll($event.target.checked)"
                                   class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500/20">
                        </th>
                        <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Resident</th>
                        <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Reference</th>
                        <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Method</th>
                        <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Amount</th>
                        <th class="p-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Paid Date</th>
                        <th class="p-5 w-40 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                        <th class="p-5 w-32 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-emerald-50/30 transition-all duration-300 group border-l-4 border-transparent hover:border-emerald-500">
                        <td class="p-5 text-center align-middle">
                            <div x-show="selectionMode">
                                <?php if($payment->status === 'pending'): ?>
                                    <input type="checkbox"
                                           value="<?php echo e($payment->id); ?>"
                                           x-model="selectedItems"
                                           @change="syncSelectAll()"
                                           class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500/20">
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="p-5 align-middle">
                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 shrink-0 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-xs border border-emerald-100 shadow-sm">
                                    <?php echo e(strtoupper(substr($payment->resident?->first_name ?? 'R', 0, 1))); ?><?php echo e(strtoupper(substr($payment->resident?->last_name ?? 'S', 0, 1))); ?>

                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-gray-900 group-hover:text-emerald-700 transition-colors truncate"><?php echo e($payment->resident?->full_name ?? 'Unknown Resident'); ?></p>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-0.5">B<?php echo e($payment->resident?->block ?? '-'); ?> / L<?php echo e($payment->resident?->lot ?? '-'); ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="p-5 align-middle">
                            <span class="text-xs font-black text-gray-400 uppercase tracking-widest"><?php echo e($payment->reference_no ?? 'N/A'); ?></span>
                        </td>
                        <td class="p-5 text-center align-middle">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-gray-100 bg-gray-50 text-gray-600 capitalize">
                                <?php echo e(str_replace('_', ' ', $payment->payment_method)); ?>

                            </span>
                        </td>
                        <td class="p-5 text-right align-middle">
                            <span class="text-base font-black text-gray-900">₱<?php echo e(number_format($payment->amount, 2)); ?></span>
                        </td>
                        <td class="p-5 text-center align-middle">
                            <div class="space-y-0.5">
                                <p class="text-xs font-black text-gray-900"><?php echo e($payment->date_paid ? $payment->date_paid->format('M d, Y') : '-'); ?></p>
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest"><?php echo e($payment->date_paid ? $payment->date_paid->format('h:i A') : ''); ?></p>
                            </div>
                        </td>
                        <td class="p-5 text-center align-middle">
                            <div class="w-full flex justify-center">
                                <?php
                                    $statusColors = [
                                        'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                        'pending' => 'bg-amber-50 text-amber-700 border-amber-100',
                                        'rejected' => 'bg-red-50 text-red-700 border-red-100'
                                    ];
                                    $statusDots = [
                                        'approved' => 'bg-emerald-500',
                                        'pending' => 'bg-amber-500',
                                        'rejected' => 'bg-red-500'
                                    ];
                                ?>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border w-28 justify-center <?php echo e($statusColors[$payment->status] ?? 'bg-gray-50 text-gray-600 border-gray-100'); ?>">
                                    <span class="w-1.5 h-1.5 rounded-full <?php echo e($statusDots[$payment->status] ?? 'bg-gray-400'); ?>"></span>
                                    <?php echo e($payment->status); ?>

                                </span>
                            </div>
                        </td>
                        <td class="p-5 text-right align-middle">
                            <div class="flex items-center justify-end gap-2 h-9">
                                <a href="<?php echo e(route('admin.payments.review', $payment->id)); ?>" class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-900 text-white hover:bg-emerald-600 transition-all shadow-sm group-hover:shadow-md active:scale-95" title="Review Payment">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <div class="w-9 h-9">
                                    <?php if($payment->status === 'approved'): ?>
                                    <a href="<?php echo e(route('admin.payments.receipt', $payment->id)); ?>" target="_blank" class="w-9 h-9 flex items-center justify-center rounded-xl border border-gray-200 text-gray-400 hover:text-emerald-600 hover:border-emerald-600 transition-all bg-white shadow-sm group-hover:shadow-md active:scale-95" title="Print Receipt">
                                        <i class="bi bi-printer-fill"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="p-20 text-center">
                            <div class="w-20 h-20 rounded-3xl bg-gray-50 flex items-center justify-center mx-auto mb-6 text-gray-200">
                                <i class="bi bi-cash-stack text-4xl"></i>
                            </div>
                            <p class="text-gray-400 text-sm font-medium">No payments found matching your criteria.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <div id="gridView" class="hidden p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('admin.payments.partials.payment-card', ['payment' => $payment], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

    </div>

    
    <?php if($payments->hasPages()): ?>
    <div class="mt-8">
        <?php echo e($payments->links()); ?>

    </div>
    <?php endif; ?>

</div>

<form id="bulkPaymentApproveForm" action="<?php echo e(route('admin.payments.bulkAction')); ?>" method="POST" class="hidden">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="action" value="approve">
    <div id="bulkPaymentIds"></div>
</form>

<script>
    function paymentsBulkApproval() {
        return {
            selectionMode: false,
            selectedItems: [],
            pendingIds: <?php echo json_encode($payments->getCollection()->filter(fn ($p) => $p->status === 'pending')->pluck('id')->values(), 15, 512) ?>,
            selectAll: false,
            get hasPendingRows() {
                return this.pendingIds.length > 0;
            },
            get selectedCount() {
                return this.selectedItems.length;
            },
            get hasPendingSelection() {
                return this.selectedCount > 0;
            },
            enableSelectionMode() {
                this.selectionMode = true;
            },
            toggleSelectAll(checked) {
                this.selectedItems = checked ? [...this.pendingIds] : [];
            },
            syncSelectAll() {
                this.selectAll = this.pendingIds.length > 0 && this.selectedItems.length === this.pendingIds.length;
            },
            clearSelection() {
                this.selectedItems = [];
                this.selectAll = false;
                this.selectionMode = false;
            },
            submitApproval() {
                if (!this.hasPendingSelection) return;

                const confirmed = confirm(`Approve ${this.selectedCount} selected payment${this.selectedCount > 1 ? 's' : ''}?`);
                if (!confirmed) return;

                const idsContainer = document.getElementById('bulkPaymentIds');
                idsContainer.innerHTML = '';

                this.selectedItems.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = id;
                    idsContainer.appendChild(input);
                });

                document.getElementById('bulkPaymentApproveForm').submit();
            }
        }
    }

    function toggleDropdown(id) {
        const dropdown = document.getElementById(id);
        const allDropdowns = ['statusDropdown', 'methodDropdown'];
        allDropdowns.forEach(d => {
            if (d !== id) document.getElementById(d)?.classList.add('hidden');
        });
        dropdown.classList.toggle('hidden');
    }

    function toggleView(view) {
        const list = document.getElementById('listView');
        const grid = document.getElementById('gridView');
        const listBtn = document.getElementById('listViewBtn');
        const gridBtn = document.getElementById('gridViewBtn');

        if (view === 'list') {
            list.classList.remove('hidden');
            grid.classList.add('hidden');
            listBtn.classList.add('text-emerald-600', 'bg-white', 'shadow-sm');
            listBtn.classList.remove('text-gray-400');
            gridBtn.classList.remove('text-emerald-600', 'bg-white', 'shadow-sm');
            gridBtn.classList.add('text-gray-400');
        } else {
            list.classList.add('hidden');
            grid.classList.remove('hidden');
            gridBtn.classList.add('text-emerald-600', 'bg-white', 'shadow-sm');
            gridBtn.classList.remove('text-gray-400');
            listBtn.classList.remove('text-emerald-600', 'bg-white', 'shadow-sm');
            listBtn.classList.add('text-gray-400');
        }
    }

    // Close dropdowns on click outside
    window.onclick = function(event) {
        if (!event.target.closest('.group/filter')) {
            document.getElementById('statusDropdown')?.classList.add('hidden');
            document.getElementById('methodDropdown')?.classList.add('hidden');
        }
    }

    // Initialize View
    document.addEventListener('DOMContentLoaded', () => toggleView('list'));
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views/admin/payments/index.blade.php ENDPATH**/ ?>