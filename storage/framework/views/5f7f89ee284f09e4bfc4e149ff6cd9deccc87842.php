<!-- 
    UNIVERSAL DRAWER COMPONENT
    --------------------------------------------------
    This component acts as a single, global drawer for the entire application.
    It uses vanilla JavaScript to handle state and dynamic content injection.
    
    Usage:
    1. Include this component in your main layout (e.g., admin.blade.php).
    2. Define your drawer content templates using <template id="drawer-{module}">.
    3. Call UniversalDrawer.open('module-name', { data }) from anywhere.
-->

<!-- DRAWER BACKDROP & CONTAINER -->
<div id="universal-drawer" class="fixed inset-0 z-[100] hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
    
    <!-- Backdrop (Click to close) -->
    <div id="drawer-backdrop" 
         class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity opacity-0"
         onclick="UniversalDrawer.close()"></div>

    <!-- Panel -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
            
            <!-- Sliding Content -->
            <div id="drawer-panel" 
                 class="pointer-events-auto w-screen max-w-md transform transition ease-in-out duration-300 translate-x-full bg-white shadow-2xl flex flex-col h-full">
                
                <!-- HEADER -->
                <div class="flex items-center justify-between px-6 py-3 border-b border-gray-100 bg-white z-10">
                    <h2 id="drawer-title" class="text-lg font-bold text-gray-800">
                        <!-- Dynamic Title -->
                    </h2>
                    <div class="flex items-center gap-2">
                        <!-- Header Actions Slot -->
                        <div id="drawer-header-actions" class="flex items-center gap-2 empty:hidden"></div>

                        <button type="button" onclick="UniversalDrawer.close()" class="rounded-full p-2 text-gray-400 hover:text-gray-500 hover:bg-gray-100 transition focus:outline-none">
                            <span class="sr-only">Close panel</span>
                            <i class="bi bi-x-lg text-lg"></i>
                        </button>
                    </div>
                </div>

                <!-- BODY (Scrollable) -->
                <div id="drawer-body" class="flex-1 overflow-y-auto p-6 custom-scrollbar relative">
                    <!-- Dynamic Content Injected Here -->
                </div>

                <!-- FOOTER (Optional Actions) -->
                <div id="drawer-footer" class="border-t border-gray-100 px-6 py-4 bg-gray-50 flex items-center justify-end gap-3 hidden">
                    <button type="button" onclick="UniversalDrawer.close()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none">
                        Cancel
                    </button>
                    <button type="button" id="drawer-submit-btn" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none shadow-sm shadow-blue-500/30">
                        Save Changes
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- 
    ==================================================
    MODULE TEMPLATES
    Define the form structures for each module here.
    ==================================================
-->

<!-- 1. RESIDENTS MODULE TEMPLATE -->
<template id="template-drawer-resident">
    <form id="form-resident" class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
            <input type="text" name="name" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm p-2.5 bg-gray-50" placeholder="e.g. Juan Dela Cruz">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
            <input type="email" name="email" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm p-2.5 bg-gray-50" placeholder="juan@example.com">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Block</label>
                <input type="text" name="block" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm p-2.5 bg-gray-50">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Lot</label>
                <input type="text" name="lot" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm p-2.5 bg-gray-50">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
            <input type="tel" name="phone" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm p-2.5 bg-gray-50" placeholder="0912 345 6789">
        </div>
    </form>
</template>

<!-- 2. ANNOUNCEMENTS MODULE TEMPLATE (Create/Edit/View) -->
<template id="template-drawer-announcement">
    <div class="h-full flex flex-col">
        
        <!-- View Mode -->
        <div id="announcement-view-mode" class="space-y-6">
            <div class="space-y-2">
                <span data-field="category" class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700 uppercase tracking-wide">
                    Category
                </span>
                <h2 data-field="title" class="text-2xl font-bold text-gray-900 leading-tight">
                    Announcement Title
                </h2>
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <i class="bi bi-calendar-event"></i>
                    <span data-field="date_posted">Jan 01, 2026</span>
                </div>
            </div>

            <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed whitespace-pre-wrap" data-field="content">
                Announcement content goes here...
            </div>
            
            <!-- Pin Info (Optional) -->
            <div id="announcement-pin-info" class="hidden p-4 bg-yellow-50 rounded-xl border border-yellow-100 flex items-start gap-3">
                <i class="bi bi-pin-angle-fill text-yellow-600 mt-0.5"></i>
                <div>
                    <p class="text-sm font-bold text-yellow-800">Pinned Announcement</p>
                    <p class="text-xs text-yellow-700 mt-1">Expires on <span data-field="pin_expires_at"></span></p>
                </div>
            </div>

            <!-- Banner Image (Bottom) -->
            <div class="rounded-xl overflow-hidden border border-gray-100 hidden group cursor-pointer" data-show-if="image" onclick="window.open(this.querySelector('img').src, '_blank')">
                <img src="" data-field="image" class="w-full h-auto object-cover max-h-64 group-hover:opacity-95 transition">
                <div class="bg-gray-50 p-2 text-center text-xs text-gray-500 border-t border-gray-100">
                    <i class="bi bi-zoom-in"></i> Click to view full image
                </div>
            </div>
        </div>

        <!-- Create/Edit Form Mode (Hidden by default, toggled via JS if needed) -->
        <form id="form-announcement" class="hidden space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input type="text" name="title" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm p-2.5 bg-gray-50" placeholder="Announcement Title">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select name="category" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm p-2.5 bg-gray-50">
                    <option value="General">General</option>
                    <option value="Event">Event</option>
                    <option value="Maintenance">Maintenance</option>
                    <option value="Meeting">Meeting</option>
                    <option value="Security">Security</option>
                    <option value="Finance">Finance</option>
                    <option value="Emergency">Emergency</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                <textarea name="content" rows="10" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm p-2.5 bg-gray-50" placeholder="Write your announcement details here..."></textarea>
            </div>
        </form>

    </div>
</template>

<!-- 3. PAYMENTS MODULE TEMPLATE -->
<template id="template-drawer-payment">
    <div class="h-full flex flex-col bg-white">
        
        <!-- Header Actions Slot -->
        <div data-slot="header-actions" class="flex items-center">
             <a href="#" target="_blank" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-100 transition text-gray-500 hidden" title="Download Receipt" data-field="receipt_url" id="btn-receipt">
                <i class="bi bi-printer"></i>
            </a>
             <a href="#" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-100 transition text-gray-500" title="Edit" data-field="edit_url">
                <i class="bi bi-pencil"></i>
            </a>
        </div>

        <div class="space-y-6">
            
            <!-- Resident Info -->
            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100 mt-2">
                <img src="" class="w-12 h-12 rounded-full object-cover ring-2 ring-white bg-gray-200" data-field="resident_photo" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=Resident&background=random'">
                <div class="flex-1">
                    <h3 class="font-bold text-gray-900 text-sm" data-field="resident_name">Resident Name</h3>
                    <p class="text-xs text-gray-500" data-field="resident_property">Block - Lot -</p>
                </div>
                <a href="#" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1" data-field="resident_profile_url">
                    View <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <!-- Amount Card -->
            <div class="bg-white rounded-2xl p-4 border border-gray-200 text-center shadow-sm">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-1">Amount Paid</p>
                <p class="text-2xl font-bold text-emerald-600 font-mono" data-field="amount">₱0.00</p>
                <div class="mt-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold border capitalize tracking-wide" data-field="status_badge_class" data-class="status_badge_class">
                        <span class="w-1.5 h-1.5 rounded-full" data-field="status_dot_class" data-class="status_dot_class"></span>
                        <span data-field="status_text">Paid</span>
                    </span>
                </div>
            </div>

            <!-- Details -->
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Transaction Details</p>
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Date Paid</p>
                            <p class="text-sm font-bold text-gray-900" data-field="date">Jan 01, 2026</p>
                            <p class="text-[10px] text-gray-400 font-medium mt-0.5" data-field="time">12:00 AM</p>
                        </div>
                         <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Method</p>
                            <p class="text-sm font-bold text-gray-900 capitalize" data-field="method">Cash</p>
                        </div>
                    </div>
                     <div class="pt-4 border-t border-gray-100">
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Transaction ID</p>
                        <p class="text-xs font-mono text-gray-600" data-field="transaction_id">#000000</p>
                    </div>
                    <div class="pt-4 border-t border-gray-100" data-show-if="reference_no">
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">GCash Reference No.</p>
                        <p class="text-xs font-mono text-blue-600 font-bold" data-field="reference_no">-</p>
                    </div>
                </div>
                </div>
            </div>

            <!-- Proof -->
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Proof of Payment</p>
                <div class="rounded-xl overflow-hidden border border-gray-200 bg-gray-50 flex flex-col shadow-sm">
                    <!-- Image Area -->
                    <div class="relative min-h-[150px] flex items-center justify-center bg-gray-100">
                        <img src="" data-field="proof_image" 
                             class="w-full h-auto object-contain hidden max-h-[300px]" 
                             id="proof-img"
                             onerror="this.classList.add('hidden'); document.getElementById('no-proof-placeholder').classList.remove('hidden');">
                        
                        <!-- No Image Placeholder -->
                        <div id="no-proof-placeholder" class="text-center py-8 text-gray-400">
                            <i class="bi bi-image-alt text-2xl mb-2 block"></i>
                            <span class="text-xs">No proof uploaded or image broken</span>
                        </div>
                    </div>

                    <!-- Action Bar -->
                    <a href="#" target="_blank" data-field="proof_url" id="proof-link"
                       class="block w-full py-3 bg-white border-t border-gray-100 text-center text-sm font-bold text-blue-600 hover:bg-blue-50 transition hidden">
                        <i class="bi bi-box-arrow-up-right mr-2"></i> View Full Image
                    </a>
                </div>

                <!-- View Receipt Button (Approved Only) -->
                <div class="mt-4 hidden" id="receipt-action-container" data-show-if="receipt_url">
                    <a href="" data-field="receipt_url" target="_blank"
                       class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-blue-50 text-blue-700 border border-blue-100 rounded-xl font-bold text-sm hover:bg-blue-100 transition-colors">
                        <i class="bi bi-printer"></i>
                        View & Print Receipt
                    </a>
                </div>
            </div>

            <!-- Pending Actions Footer -->
            <div id="payment-pending-actions" class="hidden" data-show-if="is_pending" data-slot="footer">
                <div class="grid grid-cols-2 gap-3 w-full">
                    <form action="#" method="POST" data-field="reject_url" class="w-full">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-full py-2.5 bg-red-50 text-red-600 font-bold rounded-xl hover:bg-red-100 transition flex items-center justify-center gap-2">
                            <i class="bi bi-x-circle"></i> Reject
                        </button>
                    </form>
                    <form action="#" method="POST" data-field="verify_url" class="w-full">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-full py-2.5 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition shadow-lg shadow-emerald-200 flex items-center justify-center gap-2">
                            <i class="bi bi-check-circle"></i> Approve
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</template>

<!-- 4. SERVICE REQUEST MODULE TEMPLATE -->
<template id="template-drawer-request">
    <div class="h-full flex flex-col bg-white">
        
        <!-- Header Actions Slot -->
        <div data-slot="header-actions" class="flex items-center">
             <a href="#" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-100 transition text-gray-500" title="View Full Page" data-field="view_url">
                <i class="bi bi-box-arrow-up-right"></i>
            </a>
        </div>

        <div class="space-y-6">
            
            <!-- Resident Info -->
            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100 mt-2">
                <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 text-lg font-bold" data-field="resident_initials">
                    ??
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-gray-900 text-sm" data-field="resident_name">Resident Name</h3>
                    <p class="text-xs text-gray-500" data-field="resident_property">Block - Lot -</p>
                    <p class="text-xs text-gray-500" data-field="resident_contact">0912 345 6789</p>
                </div>
            </div>

            <!-- Status & Priority Cards -->
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 rounded-xl border border-gray-100 bg-white shadow-sm">
                    <p class="text-xs font-medium text-gray-500 uppercase mb-1">Status</p>
                    <span class="px-2.5 py-1 rounded-full text-sm font-semibold border capitalize" data-field="status_text" data-class="status_class">
                        Pending
                    </span>
                </div>
                <div class="p-4 rounded-xl border border-gray-100 bg-white shadow-sm">
                    <p class="text-xs font-medium text-gray-500 uppercase mb-1">Priority</p>
                    <p class="text-lg font-bold capitalize" data-field="priority_text" data-class="priority_class">High</p>
                </div>
            </div>

            <!-- Details -->
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Request Information</p>
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm space-y-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Type</p>
                        <p class="text-sm font-bold text-gray-900 capitalize" data-field="type">Maintenance</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Date Requested</p>
                        <p class="text-sm font-bold text-gray-900" data-field="date">Jan 01, 2026</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Description</p>
                        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap bg-gray-50 p-3 rounded-lg border border-gray-100" data-field="description">
                            No description provided.
                        </p>
                    </div>

                    <!-- Photo Section -->
                    <div data-show-if="photo_url" class="hidden">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Attached Photo</p>
                        <div class="rounded-xl overflow-hidden border border-gray-200 bg-gray-50 group cursor-pointer shadow-sm" onclick="window.open(this.querySelector('img').src, '_blank')">
                            <img src="" data-field="photo_url" class="w-full h-auto object-contain max-h-[300px] group-hover:opacity-95 transition">
                            <div class="bg-white p-2 text-center text-[10px] text-gray-500 border-t border-gray-100">
                                <i class="bi bi-zoom-in mr-1"></i> Click to view full image
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Update Status Form -->
            <div class="pt-4 border-t border-gray-100">
                <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-3">Update Status</h4>
                <form action="#" method="POST" class="space-y-3" data-field="update_url">
                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                    <select name="status" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                        <option value="pending">Pending</option>
                        <option value="in progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="rejected">Rejected</option>
                    </select>
                    <button type="submit" class="w-full bg-blue-600 text-white font-medium py-2.5 rounded-xl hover:bg-blue-700 transition shadow-sm shadow-blue-200">
                        Update Status
                    </button>
                </form>
            </div>

        </div>
    </div>
</template>

<!-- 5. PENALTY MODULE TEMPLATE -->
<template id="template-drawer-penalty">
    <div class="h-full flex flex-col bg-white">
        
        <!-- Header Actions Slot -->
        <div data-slot="header-actions" class="flex items-center">
             <a href="#" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-100 transition text-gray-500 hidden" title="Edit Penalty" data-field="edit_url">
                <i class="bi bi-pencil"></i>
            </a>
        </div>

        <div class="space-y-6">
            
            <!-- Resident Info -->
            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100 mt-2">
                <img src="" class="w-12 h-12 rounded-full object-cover ring-2 ring-white bg-gray-200" data-field="resident_photo" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=Resident&background=random'">
                <div class="flex-1">
                    <h3 class="font-bold text-gray-900 text-sm" data-field="resident_name">Resident Name</h3>
                    <p class="text-xs text-gray-500" data-field="resident_property">Block - Lot -</p>
                </div>
                 <a href="#" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1" data-field="resident_profile_url">
                    View <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <!-- Amount Card -->
            <div class="bg-white rounded-2xl p-4 border border-gray-200 text-center shadow-sm">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-1">Penalty Amount</p>
                <p class="text-2xl font-bold text-red-600 font-mono" data-field="amount">₱0.00</p>
                <div class="mt-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold border capitalize tracking-wide" data-field="status_badge_class" data-class="status_badge_class">
                        <span class="w-1.5 h-1.5 rounded-full" data-field="status_dot_class" data-class="status_dot_class"></span>
                        <span data-field="status">Unpaid</span>
                    </span>
                </div>
            </div>

            <!-- Details -->
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Penalty Information</p>
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm space-y-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Type</p>
                        <p class="text-sm font-bold text-gray-900 capitalize" data-field="type">Violation</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Date Issued</p>
                        <p class="text-sm font-bold text-gray-900" data-field="date_issued">Jan 01, 2026</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Reason / Description</p>
                        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap bg-gray-50 p-3 rounded-lg border border-gray-100" data-field="reason">
                            No description provided.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Payment Reference (if paid) -->
            <div data-show-if="has_payment" class="hidden">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Payment Reference</p>
                <div class="bg-emerald-50 rounded-2xl p-4 border border-emerald-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-emerald-700 uppercase mb-1">OR Number</p>
                        <p class="text-sm font-bold text-emerald-900" data-field="payment_or">#000000</p>
                    </div>
                    <div class="text-right">
                         <p class="text-xs font-semibold text-emerald-700 uppercase mb-1">Date Paid</p>
                        <p class="text-sm font-bold text-emerald-900" data-field="payment_date">-</p>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div data-slot="footer" class="w-full">
                 <div class="grid grid-cols-1 gap-3 w-full">
                    <!-- Delete Form -->
                    <form action="#" method="POST" data-field="delete_url" class="w-full" onsubmit="return confirm('Are you sure you want to delete this penalty?');">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="w-full py-3 bg-white border border-red-200 text-red-600 font-bold rounded-xl hover:bg-red-50 transition flex items-center justify-center gap-2">
                            <i class="bi bi-trash"></i> Delete Penalty
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</template>

<!-- 5. BATCH/DUE MODULE TEMPLATE -->
<template id="template-drawer-batch">
    <div class="h-full flex flex-col bg-white">
        
        <!-- HEADER (Already handled by Universal Drawer, but we can add sub-header if needed) -->
        <!-- We use the standard body area for the content -->
        
        <div class="space-y-6">
            
            <!-- Section: Financial Overview -->
            <div>
                 <div class="flex items-center justify-between mb-2">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Financial Overview</p>
                    <span data-field="type" class="text-xs font-bold text-blue-600 uppercase tracking-wide bg-blue-50 px-2 py-0.5 rounded-full">
                        Monthly HOA
                    </span>
                 </div>
                <div class="grid grid-cols-2 gap-3">
                    <!-- Amount Per Resident -->
                    <div class="p-4 rounded-xl bg-white border border-gray-100 shadow-sm text-center">
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-1">Fee Per Resident</p>
                        <p class="text-lg font-bold text-gray-900" data-field="amount">₱0.00</p>
                    </div>
                    <!-- Total Collected -->
                    <div class="p-4 rounded-xl bg-blue-50 border border-blue-100 text-center">
                        <p class="text-xs text-blue-600 font-medium uppercase tracking-wide mb-1">Total Collected</p>
                        <p class="text-lg font-bold text-blue-700" data-field="total_collected">₱0.00</p>
                    </div>
                </div>
            </div>

            <!-- Section: Collection Progress -->
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <div class="flex justify-between items-end mb-3">
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        Collection Progress
                    </h3>
                    <span class="text-lg font-bold text-blue-600" data-field="progress_text">0%</span>
                </div>
                
                <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden mb-4">
                    <div data-field="progress_style" class="bg-blue-600 h-full rounded-full transition-all duration-700 ease-out relative" style="width: 0%">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 text-sm border-t border-gray-100 pt-4">
                    <div>
                        <p class="text-gray-500 text-xs mb-0.5 uppercase tracking-wide">Paid Residents</p>
                        <p class="font-bold text-gray-900 text-lg" data-field="paid_count">0</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-500 text-xs mb-0.5 uppercase tracking-wide">Target Amount</p>
                        <p class="font-bold text-gray-900 text-lg" data-field="total_expected">₱0.00</p>
                    </div>
                </div>
            </div>

            <!-- Section: Details -->
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Batch Information</p>
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm space-y-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Description</p>
                        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap" data-field="description">No description provided.</p>
                    </div>
                    <div class="pt-4 border-t border-gray-100 grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Total Residents</p>
                            <p class="text-sm font-bold text-gray-900" data-field="total_residents">0</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Created Date</p>
                            <p class="text-sm font-bold text-gray-900" data-field="created_at">-</p>
                        </div>
                         <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Billing Start</p>
                            <p class="text-sm font-bold text-gray-900" data-field="billing_start">-</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Billing End</p>
                            <p class="text-sm font-bold text-gray-900" data-field="billing_end">-</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FOOTER ACTION -->
            <div class="pt-4">
                <a href="#" data-field="edit_url" class="w-full py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2 group active:scale-[0.98]">
                    <i class="bi bi-pencil-square group-hover:scale-110 transition-transform"></i>
                    <span>Edit Batch</span>
                </a>
            </div>

            <!-- Delete Action -->
            <div class="pt-3">
                <form action="#" method="POST" data-field="delete_url" class="w-full" onsubmit="return confirm('Are you sure you want to delete this batch? This action cannot be undone and will remove all resident dues in this batch.');">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="w-full py-3 bg-white border border-red-200 text-red-600 font-bold rounded-xl hover:bg-red-50 transition flex items-center justify-center gap-2">
                        <i class="bi bi-trash"></i> Delete Batch
                    </button>
                </form>
            </div>

        </div>
    </div>
</template>

<!-- 6. PENALTY MODULE TEMPLATE -->
<template id="template-drawer-penalty">
    <div class="h-full flex flex-col bg-white">
        
        <!-- Header Actions Slot -->
        <div data-slot="header-actions" class="flex items-center">
             <a href="#" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-100 transition text-gray-500" title="Edit" data-field="edit_url">
                <i class="bi bi-pencil"></i>
            </a>
        </div>

        <div class="space-y-6">
            
            <!-- Resident Info -->
            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100 mt-2">
                <img src="" class="w-12 h-12 rounded-full object-cover ring-2 ring-white bg-gray-200" data-field="resident_photo" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=Resident&background=random'">
                <div class="flex-1">
                    <h3 class="font-bold text-gray-900 text-sm" data-field="resident_name">Resident Name</h3>
                    <p class="text-xs text-gray-500" data-field="resident_property">Block - Lot -</p>
                </div>
                <a href="#" class="text-xs font-bold text-[#800020] hover:text-[#600018] flex items-center gap-1" data-field="resident_profile_url">
                    View <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <!-- Amount Card -->
            <div class="bg-white rounded-2xl p-4 border border-gray-200 text-center shadow-sm">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-1">Penalty Amount</p>
                <p class="text-2xl font-bold text-[#800020] font-mono" data-field="amount">₱0.00</p>
                <div class="mt-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold border capitalize tracking-wide" data-field="status_badge_class" data-class="status_badge_class">
                        <span class="w-1.5 h-1.5 rounded-full" data-field="status_dot_class" data-class="status_dot_class"></span>
                        <span data-field="status">Paid</span>
                    </span>
                </div>
            </div>

            <!-- Details -->
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Penalty Details</p>
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm space-y-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Violation Type</p>
                        <p class="text-sm font-bold text-gray-900 capitalize" data-field="type">General</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Reason / Description</p>
                        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap bg-gray-50 p-3 rounded-lg border border-gray-100" data-field="reason">
                            No description provided.
                        </p>
                    </div>
                     <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Date Issued</p>
                        <p class="text-sm font-bold text-gray-900" data-field="date_issued">-</p>
                    </div>
                </div>
            </div>
            
            <!-- Payment Info (if paid) -->
            <div data-show-if="has_payment" class="hidden">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Payment Reference</p>
                <div class="bg-emerald-50 rounded-2xl p-4 border border-emerald-100 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-emerald-700 uppercase mb-1">OR Number</p>
                        <p class="text-sm font-bold text-emerald-900" data-field="payment_or">#000000</p>
                    </div>
                    <div class="text-right">
                         <p class="text-xs font-semibold text-emerald-700 uppercase mb-1">Date Paid</p>
                        <p class="text-sm font-bold text-emerald-900" data-field="payment_date">-</p>
                    </div>
                </div>
            </div>

            <!-- Delete Action Footer -->
            <div data-slot="footer">
                <form action="#" method="POST" data-field="delete_url" class="w-full" onsubmit="return confirm('Are you sure you want to delete this penalty?');">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="w-full py-3 bg-white border border-red-200 text-red-600 font-bold rounded-xl hover:bg-red-50 transition flex items-center justify-center gap-2">
                        <i class="bi bi-trash"></i> Delete Penalty
                    </button>
                </form>
            </div>

        </div>
    </div>
</template>

<!-- JS LOGIC -->
<script>
    const UniversalDrawer = {
        // Elements
        el: document.getElementById('universal-drawer'),
        backdrop: document.getElementById('drawer-backdrop'),
        panel: document.getElementById('drawer-panel'),
        title: document.getElementById('drawer-title'),
        body: document.getElementById('drawer-body'),
        footer: document.getElementById('drawer-footer'),
        headerActions: document.getElementById('drawer-header-actions'),
        submitBtn: document.getElementById('drawer-submit-btn'),

        // Config
        config: {
            modules: {
                'resident': {
                    title: 'Manage Resident',
                    template: 'template-drawer-resident',
                    hasFooter: true,
                    submitText: 'Save Resident'
                },
                'announcement': {
                    title: 'Announcement Details',
                    template: 'template-drawer-announcement',
                    hasFooter: false, // For view mode
                    submitText: 'Post Announcement'
                },
                'payment': {
                    title: 'Payment Details',
                    template: 'template-drawer-payment',
                    hasFooter: false, // View only
                },
                'penalty': {
                    title: 'Penalty Details',
                    template: 'template-drawer-penalty',
                    hasFooter: false, // Uses custom footer
                },
                'request': {
                    title: 'Service Request',
                    template: 'template-drawer-request',
                    hasFooter: false,
                    submitText: 'Update Status'
                },
                'batch': {
                    title: 'Batch Information',
                    template: 'template-drawer-batch',
                    hasFooter: false
                },
                'html': {
                    title: 'Details',
                    template: null, // Dynamic HTML
                    hasFooter: false
                }
            }
        },

        // --- OPEN DRAWER ---
        async open(moduleName, data = null, onSubmit = null) {
            const module = this.config.modules[moduleName];
            if (!module) {
                console.error(`UniversalDrawer: Module '${moduleName}' not found.`);
                return;
            }

            // 0. Fetch Data if URL provided
            if (typeof data === 'string') {
                try {
                    const response = await fetch(data);
                    if (!response.ok) throw new Error('Failed to load data');
                    data = await response.json();
                } catch (error) {
                    console.error('UniversalDrawer: Error fetching data', error);
                    alert('Failed to load data. Please try again.');
                    return;
                }
            }

            // 1. Setup Content
            this.title.textContent = data && data.id ? `Edit ${module.title}` : module.title;
            if(data && data.customTitle) this.title.textContent = data.customTitle;

            // Setup Width (Optional)
            this.panel.classList.remove('max-w-md', 'max-w-lg', 'max-w-xl', 'max-w-2xl');
            this.panel.classList.add(data && data.width ? data.width : 'max-w-md');

            // 1.1 Handle Raw HTML Content (New Feature)
            if (data && data.htmlContent) {
                this.body.innerHTML = data.htmlContent;
            } 
            // 1.2 Clone Template (Standard Mode)
            else if (module.template) {
                const template = document.getElementById(module.template);
                if (template) {
                    this.body.innerHTML = '';
                    this.body.appendChild(template.content.cloneNode(true));
                } else {
                    this.body.innerHTML = '<p class="text-red-500">Template not found.</p>';
                }
            } else {
                // No template and no HTML?
                 this.body.innerHTML = '';
            }

            // 1.3 Handle Custom Footer Slot
            const customFooter = this.body.querySelector('[data-slot="footer"]');
            if (customFooter) {
                // Check visibility condition on the wrapper itself before unwrapping
                const showIf = customFooter.getAttribute('data-show-if');
                let shouldShow = true;
                if (showIf && data) {
                    shouldShow = !!data[showIf];
                }

                // Clear default footer
                this.footer.innerHTML = '';
                
                if (shouldShow) {
                    this.footer.classList.remove('hidden');
                    // Move custom footer content
                    while (customFooter.firstChild) {
                        this.footer.appendChild(customFooter.firstChild);
                    }
                } else {
                    this.footer.classList.add('hidden');
                }
                
                // Remove the placeholder from body
                customFooter.remove();
                
                // Add default styling if not present
                if (!this.footer.classList.contains('flex')) {
                    this.footer.classList.add('flex', 'items-center', 'justify-end', 'gap-3');
                }
            } else {
                // Restore Default Footer Structure if needed
                if (module.hasFooter) {
                    this.footer.innerHTML = `
                        <button type="button" onclick="UniversalDrawer.close()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none">
                            Cancel
                        </button>
                        <button type="button" id="drawer-submit-btn" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none shadow-sm shadow-blue-500/30">
                            Save Changes
                        </button>
                    `;
                    this.submitBtn = document.getElementById('drawer-submit-btn'); // Re-bind
                    this.footer.classList.remove('hidden');
                } else {
                    this.footer.classList.add('hidden');
                }
            }

            // 1.2 Handle Custom Header Actions Slot
            const headerActions = this.body.querySelector('[data-slot="header-actions"]');
            const headerContainer = document.getElementById('drawer-header-actions');
            
            // Clear previous header actions
            if (headerContainer) headerContainer.innerHTML = '';
            
            if (headerActions && headerContainer) {
                // Move content
                while (headerActions.firstChild) {
                    headerContainer.appendChild(headerActions.firstChild);
                }
                // Remove placeholder
                headerActions.remove();
            }

            // 2. Populate Data (if provided)
            if (data) {
                this.populateData(data);
            }

            // 3. Setup Default Footer (Only if no custom footer and hasFooter is true)
            if (!customFooter && module.hasFooter) {
                this.submitBtn.textContent = module.submitText;
                // Bind submit event
                this.submitBtn.onclick = () => {
                    if (onSubmit) onSubmit(this.getFormData());
                };
            }

            // 4. Show Animation
            this.el.classList.remove('hidden');
            // Small delay to allow display:block to apply before opacity transition
            setTimeout(() => {
                this.backdrop.classList.remove('opacity-0');
                this.panel.classList.remove('translate-x-full');
            }, 10);
        },

        // --- CLOSE DRAWER ---
        close() {
            this.backdrop.classList.add('opacity-0');
            this.panel.classList.add('translate-x-full');
            
            // Wait for transition to finish before hiding
            setTimeout(() => {
                this.el.classList.add('hidden');
                this.body.innerHTML = ''; // Clean up
                // Clear Header Actions
                const headerContainer = document.getElementById('drawer-header-actions');
                if (headerContainer) headerContainer.innerHTML = '';
                
                // Reset width to default
                this.panel.classList.remove('max-w-lg', 'max-w-xl', 'max-w-2xl');
                this.panel.classList.add('max-w-md');
            }, 300);
        },

        // --- HELPER: POPULATE FIELDS ---
        populateData(data) {
            // 1. Inputs/Selects/Textareas
            const inputs = this.body.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (data[input.name] !== undefined) {
                    input.value = data[input.name];
                }
            });

            // 2. Text Elements & Attributes (data-field="key")
            // Search in body, footer, and header actions
            const scope = [this.body, this.footer, this.headerActions];
            
            scope.forEach(container => {
                if (!container) return;
                const elements = container.querySelectorAll('[data-field]');
                elements.forEach(el => {
                    const key = el.getAttribute('data-field');
                    if (data[key] !== undefined) {
                        // Handle Images
                        if (el.tagName === 'IMG') {
                            if (data[key]) {
                                el.src = data[key];
                                el.classList.remove('hidden');
                                // Find any placeholder in the same container or parent
                                const placeholder = el.parentElement.querySelector('[id*="placeholder"], [id*="no-proof"]');
                                if (placeholder) placeholder.classList.add('hidden');
                            } else {
                                el.classList.add('hidden');
                                const placeholder = el.parentElement.querySelector('[id*="placeholder"], [id*="no-proof"]');
                                if (placeholder) placeholder.classList.remove('hidden');
                            }
                        } 
                        // Handle Links (href)
                        else if (el.tagName === 'A') {
                            if (data[key]) {
                                el.href = data[key];
                                el.classList.remove('hidden');
                            } else {
                                el.classList.add('hidden');
                            }
                        }
                        // Handle Form Actions
                        else if (el.tagName === 'FORM') {
                            el.action = data[key];
                        }
                        // Handle Styles (e.g., progress bar width)
                        else if (el.hasAttribute('style')) {
                            const val = data[key];
                            if (typeof val === 'string') {
                                const m = val.match(/width\s*:\s*([^;]+)/i);
                                if (m && m[1]) {
                                    el.style.width = m[1].trim();
                                }
                            }
                        }
                        // Handle Classes
                        else if (el.hasAttribute('data-class')) {
                            // Safe regex to remove color utility classes (e.g. bg-red-50, text-blue-700)
                            // but preserve layout classes like text-xs, border, etc.
                            el.className = el.className.replace(/\b(bg-[a-z]+-\d+|text-[a-z]+-\d+|border-[a-z]+-\d+)\b/g, '').trim();
                            
                            // If element has other base classes, keep them
                            const baseClasses = el.className;
                            el.className = `${baseClasses} ${data[key]}`.trim();
                        }
                        // Default: Text Content
                        else {
                            el.textContent = data[key];
                        }
                    }
                });

                // 3. Visibility Toggles (data-show-if="key")
                const toggles = container.querySelectorAll('[data-show-if]');
                toggles.forEach(el => {
                    const key = el.getAttribute('data-show-if');
                    // Check if it's a "custom footer slot" wrapper that we might have unwrapped?
                    // No, because we moved the *children*.
                    // But if the wrapper itself had data-show-if, we might have lost it if we didn't handle it.
                    // However, in our template, the wrapper div itself has data-show-if.
                    // When we moved "while(customFooter.firstChild)", we didn't move the wrapper.
                    // So we need to handle visibility of the footer based on the wrapper's condition?
                    
                    // Actually, for the footer case, we should probably toggle the footer container visibility.
                    // But let's stick to the current logic: 
                    // If the user puts data-show-if on the *children* of the footer slot, it works.
                    // If they put it on the slot itself, it's lost.
                    
                    // In my template update, I put data-show-if on the wrapper `div id="payment-pending-actions"`.
                    // This wrapper has `data-slot="footer"`.
                    // So when I select `[data-slot="footer"]`, it is that wrapper.
                    // I move its children. The wrapper is removed.
                    // So the data-show-if on the wrapper is LOST.
                    // However, we handled this in step 1.1 by checking `data-show-if` on the wrapper before unwrapping.
                    
                    if (data[key]) {
                        el.classList.remove('hidden');
                    } else {
                        el.classList.add('hidden');
                    }
                });
            });
        },

        // --- HELPER: GET FORM DATA ---
        getFormData() {
            const form = this.body.querySelector('form');
            if (!form) return {};
            const formData = new FormData(form);
            return Object.fromEntries(formData.entries());
        }
    };
</script>
<?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\components\universal-drawer.blade.php ENDPATH**/ ?>