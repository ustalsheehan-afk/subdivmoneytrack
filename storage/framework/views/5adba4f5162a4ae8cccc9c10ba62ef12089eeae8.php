<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<title><?php echo $__env->yieldContent('title', 'Admin Panel'); ?></title>
<script src="<?php echo e(asset('js/ui-density.js')); ?>"></script>
<link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
/* ===== THEME COLORS (OFFICIAL) ===== */
:root {
    --bg-dark: #0D1F1C;
    --bg-darker: #081412;
    --brand-accent: #B6FF5C;
    --brand-accent-glow: rgba(182, 255, 92, 0.3);
    --text-main: #FFFFFF;
    --text-muted: #A0AEC0;
    
    /* Legacy mapping for compatibility if needed */
    --color-pale-sky: var(--brand-accent);
    --color-soft-denim: var(--text-muted);
    --color-steel-blue: var(--bg-dark);
    --color-deep-slate: var(--bg-darker);
}

/* Base */
body {
    font-family: 'Inter', sans-serif;
    background-color: #FFFFFF; 
    color: #1A202C;
    overflow: hidden; 
}

/* Scrollbar */
.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background-color: rgba(0, 0, 0, 0.1); border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: rgba(0, 0, 0, 0.2); }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }

/* Content Wrapper */
.content-wrapper {
    margin-top: 6rem; /* Header Height (h-24 = 6rem) */
    margin-left: 18rem; /* Sidebar Width (w-72 = 18rem) */
    height: calc(100vh - 6rem);
    overflow-y: auto;
    padding: 2rem;
    position: relative;
    width: calc(100% - 18rem);
    background-color: #FFFFFF; /* Explicitly set white background */
}

@media (max-width: 1024px) {
    .content-wrapper {
        margin-left: 0;
        width: 100%;
    }
}

/* Premium Cards (Updated for White BG) */
.glass-card {
    background: #FFFFFF;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    transition: all 0.3s ease;
}

.glass-card:hover {
    border-color: var(--brand-accent);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    transform: translateY(-2px);
}

/* Buttons */
.btn-premium {
    background-color: #081412;
    color: var(--brand-accent) !important;
    font-weight: 700;
    padding: 0.75rem 1.5rem;
    border-radius: 16px;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    border: 1px solid rgba(182, 255, 92, 0.2);
    text-transform: uppercase;
    font-size: 10px;
    letter-spacing: 0.1em;
}

.btn-premium:hover {
    background-color: #0D1F1C;
    box-shadow: 0 0 20px var(--brand-accent-glow);
    transform: translateY(-1px);
    color: var(--brand-accent) !important;
}

.btn-secondary {
    background-color: #FFFFFF;
    color: #4A5568 !important;
    font-weight: 700;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    border: 1px solid #E2E8F0;
    text-transform: uppercase;
    font-size: 10px;
    letter-spacing: 0.1em;
}

.btn-secondary:hover {
    background-color: #F7FAFC;
    border-color: #CBD5E0;
    color: #1A202C !important;
}

.btn-danger {
    background-color: #FFF5F5;
    color: #C53030 !important;
    font-weight: 700;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    border: 1px solid #FEB2B2;
    text-transform: uppercase;
    font-size: 10px;
    letter-spacing: 0.1em;
}

.btn-danger:hover {
    background-color: #FC8181;
    color: #FFFFFF !important;
    border-color: #F56565;
}

/* Typography */
h1, h2, h3, h4, h5, h6 { 
    color: #1A202C; 
    font-weight: 700; 
    letter-spacing: -0.025em; 
}

.text-brand { color: #2D3748; } /* Adjusted for white BG */
.text-muted-custom { color: #718096; }

/* Transitions */
.smooth-transition { transition: all 0.3s ease; }

/* Status Badges */
.badge-standard {
    padding: 4px 12px;
    border-radius: 9999px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* ===== SIDEBAR & HEADER COLORS (Keep Cinematic Dark) ===== */
aside#sidebar {
    background-color: #081412 !important;
    border-right-color: rgba(182, 255, 92, 0.1) !important;
}

header {
    background-color: #081412 !important;
    border-bottom-color: rgba(182, 255, 92, 0.1) !important;
}

aside#sidebar .bg-gradient-to-tr {
    background: linear-gradient(135deg, #B6FF5C, #8AC941) !important;
}

aside#sidebar h2 span {
    color: #B6FF5C !important;
}

aside#sidebar nav p {
    color: #B6FF5C !important;
    opacity: 0.8;
}

.admin-btn-primary {
    background-color: #B6FF5C !important;
    color: #081412 !important;
    box-shadow: 0 10px 25px rgba(182, 255, 92, 0.2) !important;
}

.admin-btn-primary:hover {
    background-color: #8AC941 !important;
    box-shadow: 0 14px 30px rgba(182, 255, 92, 0.3) !important;
}

/* Update sidebar link styles to use green accents */
.sidebar-link-active {
    background: rgba(182, 255, 92, 0.1) !important;
    color: #B6FF5C !important;
    border-left: 3px solid #B6FF5C !important;
}

/* Sidebar profile section */
aside#sidebar .p-4.border-t.border-gray-800 {
    background-color: #081412 !important;
}

aside#sidebar .bg-gradient-to-br.from-\[\#5b86b6\].to-\[\#3f6593\] {
    background: linear-gradient(135deg, #B6FF5C, #8AC941) !important;
}

</style>
</head>
<body class="flex h-screen w-full" x-data="systemNotifications" x-init="fetchNotifications(); setInterval(() => fetchNotifications(), 30000)">


<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-[#081412] border-r border-[#B6FF5C]/10 flex flex-col transition-transform duration-300 transform -translate-x-full lg:translate-x-0">
    
    
    <div class="h-24 flex items-center px-8 border-b border-gray-800">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#B6FF5C] to-[#8AC941] flex items-center justify-center shadow-lg shadow-green-900/20 ring-1 ring-white/10">
                <i class="bi bi-buildings-fill text-[#081412] text-lg"></i>
            </div>
            <div>
                <h2 class="text-white font-bold text-lg tracking-tight leading-none">Subdiv<span class="text-[#B6FF5C]">Management</span></h2>
                <p class="text-gray-400 text-[10px] font-semibold tracking-widest uppercase mt-1">System</p>
            </div>
        </div>
    </div>

    
    <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-8 custom-scrollbar">
        
        <div>
            <p class="px-4 text-[10px] font-bold text-[#B6FF5C] uppercase tracking-widest mb-3 opacity-60">Main</p>
            <div class="space-y-1">
                <?php
                    $mainLinks = [
                        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'bi-grid-1x2-fill', 'pattern' => 'admin.dashboard', 'permission' => 'dashboard.view'],
                        ['label' => 'Announcements', 'route' => 'admin.announcements.index', 'icon' => 'bi-megaphone-fill', 'pattern' => 'admin.announcements*', 'permission' => 'announcements.view'],
                    ];
                ?>
                <?php $__currentLoopData = $mainLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('layouts.partials.sidebar-link', ['link' => $link], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div>
            <p class="px-4 text-[10px] font-bold text-[#B6FF5C] uppercase tracking-widest mb-3 opacity-60">Communications</p>
            <div class="space-y-1">
                <?php
                    $commLinks = [
                        ['label' => 'Resident Support', 'route' => 'admin.messages.index', 'icon' => 'bi-chat-left-text-fill', 'pattern' => 'admin.messages.index*', 'badge' => 'support', 'permission' => 'support.view'],
                        ['label' => 'Notifications', 'route' => 'admin.messages.notifications.index', 'icon' => 'bi-bell-fill', 'pattern' => 'admin.messages.notifications*', 'permission' => 'notifications.view'],
                    ];
                ?>
                <?php $__currentLoopData = $commLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('layouts.partials.sidebar-link', ['link' => $link], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div>
            <p class="px-4 text-[10px] font-bold text-[#B6FF5C] uppercase tracking-widest mb-3 opacity-60">Resident Management</p>
            <div class="space-y-1">
                <?php
                    $residentMgmtLinks = [
                        ['label' => 'Residents', 'route' => 'admin.residents.index', 'icon' => 'bi-people-fill', 'pattern' => 'admin.residents*', 'permission' => 'residents.view'],
                        ['label' => 'Invitations', 'route' => 'admin.invitations.index', 'icon' => 'bi-envelope-paper-fill', 'pattern' => 'admin.invitations*', 'permission' => 'invitations.view'],
                        ['label' => 'Board Members', 'route' => 'admin.board.index', 'icon' => 'bi-people-fill', 'pattern' => 'admin.board*', 'permission' => 'board_members.view'],
                    ];
                ?>
                <?php $__currentLoopData = $residentMgmtLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('layouts.partials.sidebar-link', ['link' => $link], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div>
            <p class="px-4 text-[10px] font-bold text-[#B6FF5C] uppercase tracking-widest mb-3 opacity-60">Financial Management</p>
            <div class="space-y-1">
                <?php
                    $financialLinks = [
                        ['label' => 'Dues', 'route' => 'admin.dues.index', 'icon' => 'bi-receipt', 'pattern' => 'admin.dues*', 'permission' => 'dues.view'],
                        ['label' => 'Payments', 'route' => 'admin.payments.index', 'icon' => 'bi-credit-card-fill', 'pattern' => 'admin.payments*', 'permission' => 'payments.view'],
                        ['label' => 'Penalties', 'route' => 'admin.penalties.index', 'icon' => 'bi-exclamation-octagon-fill', 'pattern' => 'admin.penalties*', 'permission' => 'penalties.view'],
                    ];
                ?>
                <?php $__currentLoopData = $financialLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('layouts.partials.sidebar-link', ['link' => $link], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div>
            <p class="px-4 text-[10px] font-bold text-[#B6FF5C] uppercase tracking-widest mb-3 opacity-60">Operations</p>
            <div class="space-y-1">
                <?php
                    $opsLinks = [
                        ['label' => 'Requests', 'route' => 'admin.requests.index', 'icon' => 'bi-inbox-fill', 'pattern' => 'admin.requests*', 'permission' => 'requests.view'],
                        ['label' => 'Amenities', 'route' => 'admin.amenities.index', 'icon' => 'bi-building-fill', 'pattern' => 'admin.amenities*', 'permission' => 'amenities.view'],
                        ['label' => 'Reservations', 'route' => 'admin.amenity-reservations.index', 'icon' => 'bi-calendar-check-fill', 'pattern' => 'admin.amenity-reservations*', 'permission' => 'reservations.view'],
                    ];
                ?>
                <?php $__currentLoopData = $opsLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('layouts.partials.sidebar-link', ['link' => $link], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div>
            <p class="px-4 text-[10px] font-bold text-[#B6FF5C] uppercase tracking-widest mb-3 opacity-60">System Monitoring</p>
            <div class="space-y-1">
                <?php
                    $monitoringLinks = [
                        ['label' => 'Reports', 'route' => 'admin.system.reports.index', 'icon' => 'bi-bar-chart-fill', 'pattern' => 'admin.system.reports*', 'permission' => 'reports.view'],
                        ['label' => 'Activity Logs', 'route' => 'admin.system.activity-logs.index', 'icon' => 'bi-journal-text', 'pattern' => 'admin.system.activity-logs*', 'permission' => 'logs.view'],
                    ];
                ?>
                <?php $__currentLoopData = $monitoringLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('layouts.partials.sidebar-link', ['link' => $link], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div>
            <p class="px-4 text-[10px] font-bold text-[#B6FF5C] uppercase tracking-widest mb-3 opacity-60">Administration</p>
            <div class="space-y-1">
                <?php
                    $adminLinks = [
                        ['label' => 'Roles & Permissions', 'route' => 'admin.system.roles-permissions.index', 'icon' => 'bi-shield-lock', 'pattern' => 'admin.system.roles-permissions*', 'permission' => 'roles.view'],
                        ['label' => 'Accounts', 'route' => 'admin.accounts.index', 'icon' => 'bi-shield-lock-fill', 'pattern' => 'admin.accounts*', 'permission' => 'users.view'],
                    ];
                ?>
                <?php $__currentLoopData = $adminLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('layouts.partials.sidebar-link', ['link' => $link], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div class="pt-4 mt-4 border-t border-gray-800">
            <form action="<?php echo e(route('logout')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-full group flex items-center gap-3.5 px-4 py-3 rounded-xl text-red-400 hover:bg-red-500/10 hover:text-red-500 transition-all duration-200">
                    <i class="bi bi-box-arrow-right text-[1.2rem]"></i>
                    <span class="text-[14px] font-bold tracking-wide uppercase">Logout</span>
                </button>
            </form>
        </div>
    </nav>

    
    <?php if(auth()->guard('admin')->check()): ?>
    <div class="p-4 border-t border-gray-800 bg-[#081412]">
        <div class="flex items-center gap-3 p-3 rounded-2xl hover:bg-[#B6FF5C]/5 transition-all cursor-pointer group border border-transparent hover:border-gray-800">
            <div class="relative">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#B6FF5C] to-[#8AC941] flex items-center justify-center text-sm font-bold text-[#081412] shadow-md">
                    AD
                </div>
                <div class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-[#081412] rounded-full"></div>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-white truncate group-hover:text-[#B6FF5C] transition-colors">Administrator</p>
                <p class="text-[11px] text-[#A0AEC0] truncate">System Admin</p>
            </div>
            <form action="<?php echo e(route('logout')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" 
                    class="w-8 h-8 flex items-center justify-center rounded-lg text-[#A0AEC0] hover:text-white hover:bg-red-500/20 transition-all" 
                    title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
    <?php endif; ?>
</aside>


<div class="flex-1 flex flex-col min-w-0 bg-[#ffffff] relative">
    
    
    <header class="h-24 fixed top-0 right-0 left-0 lg:left-72 z-30 flex items-center justify-between px-8 lg:px-10 bg-[#081412] border-b border-gray-800 transition-all duration-300">
        
        
        <div class="flex flex-col justify-center animate-fade-in">
            <div class="flex items-center gap-3 mb-1">
                <button class="lg:hidden text-gray-400 hover:text-white transition-colors" onclick="toggleSidebar()">
                    <i class="bi bi-list text-2xl"></i>
                </button>
            </div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight drop-shadow-sm">
                <?php echo $__env->yieldContent('page-title'); ?>
            </h1>
        </div>

        
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-4">
                
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="relative p-2 rounded-xl hover:bg-gray-100 transition-colors">
                        <i class="bi bi-bell text-xl text-gray-500"></i>
                        <?php if($unreadCount > 0): ?>
                            <span class="absolute top-1.5 right-1.5 w-4 h-4 bg-red-500 text-white text-[10px] font-bold flex items-center justify-center rounded-full border-2 border-white">
                                <?php echo e($unreadCount > 9 ? '9+' : $unreadCount); ?>

                            </span>
                        <?php endif; ?>
                    </button>

                    
                    <div x-show="open" 
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden">
                        
                        <div class="p-4 border-b border-gray-50 flex items-center justify-between">
                            <h3 class="text-sm font-bold text-gray-900">Notifications</h3>
                            <?php if($unreadCount > 0): ?>
                                <form action="<?php echo e(route('admin.notifications.mark-all-read')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="text-[10px] font-bold text-emerald-600 hover:text-emerald-700 uppercase tracking-widest">Mark all as read</button>
                                </form>
                            <?php endif; ?>
                        </div>

                        <div class="max-h-96 overflow-y-auto custom-scrollbar">
                            <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <form action="<?php echo e(route('admin.messages.notifications.read', $notification->id)); ?>" method="POST" class="m-0">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="w-full text-left block p-4 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0 <?php echo e(!$notification->is_read ? 'bg-blue-50/30' : ''); ?>">
                                    <div class="flex gap-3">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 
                                            <?php if(($notification->category ?? '') == 'payment'): ?> bg-blue-50 text-blue-500
                                            <?php elseif(($notification->category ?? '') == 'billing'): ?> bg-indigo-50 text-indigo-500
                                            <?php elseif(($notification->category ?? '') == 'request'): ?> bg-emerald-50 text-emerald-500
                                            <?php elseif(($notification->category ?? '') == 'alert'): ?> bg-orange-50 text-orange-500
                                            <?php else: ?> bg-gray-50 text-gray-500 <?php endif; ?>">
                                            <i class="bi 
                                                <?php if(($notification->category ?? '') == 'payment'): ?> bi-cash-stack
                                                <?php elseif(($notification->category ?? '') == 'billing'): ?> bi-receipt
                                                <?php elseif(($notification->category ?? '') == 'request'): ?> bi-tools
                                                <?php elseif(($notification->category ?? '') == 'alert'): ?> bi-exclamation-triangle
                                                <?php else: ?> bi-bell <?php endif; ?> text-lg"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex justify-between items-start mb-0.5">
                                                <p class="text-sm font-bold text-gray-900 truncate"><?php echo e($notification->title); ?></p>
                                                <span class="text-[10px] text-gray-400 font-bold uppercase whitespace-nowrap ml-2"><?php echo e($notification->created_at->diffForHumans(null, true)); ?></span>
                                            </div>
                                            <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed"><?php echo e($notification->message); ?></p>
                                        </div>
                                    </div>
                                </button>
                            </form>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="p-10 text-center">
                                <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="bi bi-bell-slash text-gray-300 text-xl"></i>
                                </div>
                                <p class="text-sm text-gray-500 font-medium">No notifications yet</p>
                            </div>
                            <?php endif; ?>
                        </div>

                        <?php if($notifications->isNotEmpty()): ?>
                        <div class="p-3 bg-gray-50 border-t border-gray-100 text-center">
                            <a href="<?php echo e(route('admin.messages.notifications.index')); ?>" class="text-xs font-bold text-gray-500 hover:text-emerald-600 transition-colors uppercase tracking-widest">View all notifications</a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm shadow-sm">
                        <?php echo e(substr(Auth::user()->name, 0, 1)); ?>

                    </div>
                    <span class="hidden md:block text-sm font-bold text-gray-700 tracking-tight"><?php echo e(Auth::user()->name); ?></span>
                </div>
            </div>
        </div>
    </header>

    
    <main class="content-wrapper custom-scrollbar fade-in">
        <div class="max-w-7xl mx-auto">
            <?php if(session('success')): ?>
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                     class="mb-6 flex items-center p-4 bg-emerald-50 border border-emerald-100 rounded-2xl shadow-sm animate-in fade-in slide-in-from-top-4 duration-300">
                    <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-bold text-emerald-900"><?php echo e(session('success')); ?></p>
                    </div>
                    <button @click="show = false" class="ml-4 text-emerald-400 hover:text-emerald-600 transition-colors">
                        <i class="bi bi-x-lg text-xs"></i>
                    </button>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                     class="mb-6 flex items-center p-4 bg-red-50 border border-red-100 rounded-2xl shadow-sm animate-in fade-in slide-in-from-top-4 duration-300">
                    <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center text-red-600">
                        <i class="bi bi-exclamation-circle-fill"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-bold text-red-900"><?php echo e(session('error')); ?></p>
                    </div>
                    <button @click="show = false" class="ml-4 text-red-400 hover:text-red-600 transition-colors">
                        <i class="bi bi-x-lg text-xs"></i>
                    </button>
                </div>
            <?php endif; ?>

            <?php if(session('info')): ?>
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                     class="mb-6 flex items-center p-4 bg-blue-50 border border-blue-100 rounded-2xl shadow-sm animate-in fade-in slide-in-from-top-4 duration-300">
                    <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
                        <i class="bi bi-info-circle-fill"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-bold text-blue-900"><?php echo e(session('info')); ?></p>
                    </div>
                    <button @click="show = false" class="ml-4 text-blue-400 hover:text-blue-600 transition-colors">
                        <i class="bi bi-x-lg text-xs"></i>
                    </button>
                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>

</div>


<div id="overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity duration-300" onclick="toggleSidebar()"></div>


<div id="global-search-overlay" class="fixed inset-0 bg-[#000f22]/80 backdrop-blur-md hidden z-[60] flex items-start justify-center pt-24" onclick="closeGlobalSearch(event)">
    <div class="bg-[#1b3554] w-full max-w-2xl rounded-2xl shadow-2xl border border-gray-800 overflow-hidden transform transition-all scale-95 opacity-0" id="global-search-modal">
        <div class="relative">
            <i class="bi bi-search absolute left-5 top-5 text-gray-400 text-xl"></i>
            <input type="text" id="global-search-input" 
                class="w-full pl-14 pr-6 py-5 text-lg bg-transparent text-white placeholder-gray-500 focus:outline-none"
                placeholder="Search anything..."
                autocomplete="off">
            <div class="absolute right-5 top-5">
                 <span class="text-xs font-mono text-gray-500 border border-gray-700 rounded px-2 py-1">ESC</span>
            </div>
        </div>
        <div class="border-t border-gray-800 max-h-[60vh] overflow-y-auto p-2" id="global-search-results">
            <div class="p-4 text-center text-gray-500 text-sm">Start typing to search...</div>
        </div>
    </div>
</div>

<?php echo $__env->make('components.universal-drawer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


<div id="invitationModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeInvitationModal()"></div>
    <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 w-full max-w-md relative overflow-hidden transform transition-all scale-95 opacity-0" id="invitationModalContent">
        <div class="p-6">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                    <i class="bi bi-link-45deg text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Invitation Link Generated</h3>
                    <p class="text-sm text-gray-500">Copy the link below to send to the resident.</p>
                </div>
            </div>

            <div class="relative group">
                <input type="text" id="invitationLinkInput" readonly
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-4 pr-12 py-3 text-sm font-mono text-blue-600 focus:outline-none">
                <button onclick="copyInvitationLink()" 
                        class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center bg-white border border-gray-200 rounded-lg text-gray-500 hover:text-blue-600 hover:border-blue-200 transition shadow-sm">
                    <i class="bi bi-copy" id="copyIcon"></i>
                </button>
            </div>

            <p class="mt-4 text-[10px] text-gray-400 flex items-center gap-1">
                <i class="bi bi-info-circle"></i> This link will expire in 24 hours and can only be used once.
            </p>

            <div class="mt-8 flex justify-end">
                <button onclick="closeInvitationModal()" 
                        class="px-6 py-2.5 bg-gray-900 text-white text-sm font-bold rounded-xl hover:bg-black transition shadow-lg">
                    Done
                </button>
            </div>
        </div>
    </div>
</div>

<?php echo $__env->yieldPushContent('modals'); ?>

<script>
    // Invitation Modal Logic
    function openInvitationModal(link) {
        const modal = document.getElementById('invitationModal');
        const content = document.getElementById('invitationModalContent');
        const input = document.getElementById('invitationLinkInput');
        const copyIcon = document.getElementById('copyIcon');
        
        input.value = link;
        copyIcon.className = 'bi bi-copy';
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeInvitationModal() {
        const modal = document.getElementById('invitationModal');
        const content = document.getElementById('invitationModalContent');
        
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function copyInvitationLink() {
        const input = document.getElementById('invitationLinkInput');
        const copyIcon = document.getElementById('copyIcon');
        
        input.select();
        document.execCommand('copy');
        
        copyIcon.className = 'bi bi-check2 text-green-500';
        setTimeout(() => {
            copyIcon.className = 'bi bi-copy';
        }, 2000);
    }

    async function generateInvite(residentId, email) {
        if (!email) {
            alert('Resident must have an email address to generate an invitation.');
            return;
        }

        try {
            const response = await fetch('<?php echo e(route("admin.residents.invite")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ resident_id: residentId, email: email })
            });

            const data = await response.json();
            
            if (data.success) {
                openInvitationModal(data.link);
            } else {
                alert(data.message || 'Failed to generate invitation.');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while generating the invitation.');
        }
    }

    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    
    function toggleSidebar() { 
        sidebar.classList.toggle('-translate-x-full'); 
        overlay.classList.toggle('hidden'); 
    }

    // Search Logic
    const searchOverlay = document.getElementById('global-search-overlay');
    const searchModal = document.getElementById('global-search-modal');
    const searchInput = document.getElementById('global-search-input');

    function openGlobalSearch() {
        searchOverlay.classList.remove('hidden');
        setTimeout(()=>{ searchModal.classList.remove('scale-95','opacity-0'); searchModal.classList.add('scale-100','opacity-100'); },10);
        searchInput.focus();
    }
    
    function closeGlobalSearch(e) {
        if(e.target===searchOverlay || e.key==='Escape'){
            searchModal.classList.remove('scale-100','opacity-100'); searchModal.classList.add('scale-95','opacity-0');
            setTimeout(()=>{ searchOverlay.classList.add('hidden'); },300);
        }
    }

    document.addEventListener('keydown',e=>{
        if((e.ctrlKey||e.metaKey)&&e.key==='k'){ e.preventDefault(); openGlobalSearch(); }
        if(e.key==='Escape'&&!searchOverlay.classList.contains('hidden')) closeGlobalSearch({key:'Escape'});
    });
</script>

<?php echo $__env->yieldPushContent('scripts'); ?>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('systemNotifications', () => ({
            counts: {
                requests: { count: 0, priority: 'normal' },
                payments: { count: 0, priority: 'normal' },
                dues: { count: 0, priority: 'normal' },
                reservations: { count: 0, priority: 'normal' },
                support: { count: 0, priority: 'normal' }
            },
            async fetchNotifications() {
                try {
                    const response = await fetch('<?php echo e(route("admin.system-notifications")); ?>');
                    if (!response.ok) throw new Error('Network response was not ok');
                    this.counts = await response.json();
                } catch (error) {
                    console.error('Failed to fetch notifications:', error);
                }
            },
            formatCount(count) {
                return count > 9 ? '9+' : count;
            }
        }));
    });
</script>

</body>
</html>
<?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views/layouts/admin.blade.php ENDPATH**/ ?>