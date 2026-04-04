<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $__env->yieldContent('title', 'Resident Portal'); ?></title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
[x-cloak] { display: none !important; }
html { scroll-behavior: smooth; }

:root {
    --bg-dark: #0D1F1C;
    --bg-darker: #081412;
    --brand-accent: #B6FF5C;
    --brand-accent-glow: rgba(182, 255, 92, 0.3);
    --text-muted: #A0AEC0;
}

body {
    font-family: 'Inter', sans-serif;
    font-size: 14px;
    font-weight: 500;
    color: #1A202C;
    background: #ffffff;
    overflow-x: hidden;
}

.meta {
    font-size: 12px;
    font-weight: 700;
    color: #718096;
}

.section-title {
    font-size: 12px;
    font-weight: 800;
    color: #1A202C;
}

.custom-scrollbar::-webkit-scrollbar { width: 5px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background-color: rgba(0,0,0,0.1); border-radius: 9999px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }

.fade-in { animation: fadeIn .5s ease-out forwards; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .glass-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        transition: all 0.3s ease;
    }
    
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

    .btn-premium {
        background-color: var(--bg-darker);
        color: var(--brand-accent) !important;
        font-weight: 800;
        padding: 0.75rem 1.25rem;
        border-radius: 16px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: 1px solid rgba(182, 255, 92, 0.2);
        text-transform: uppercase;
        font-size: 10px;
        letter-spacing: 0.1em;
        line-height: 1;
        white-space: nowrap;
    }

    .btn-premium:hover {
        background-color: var(--bg-dark);
        box-shadow: 0 0 20px var(--brand-accent-glow);
        transform: translateY(-1px);
        color: var(--brand-accent) !important;
    }

    .btn-secondary {
        background-color: #FFFFFF;
        color: #4A5568 !important;
        font-weight: 800;
        padding: 0.75rem 1.25rem;
        border-radius: 12px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: 1px solid #E2E8F0;
        text-transform: uppercase;
        font-size: 10px;
        letter-spacing: 0.1em;
        line-height: 1;
        white-space: nowrap;
    }

    .btn-secondary:hover {
        background-color: #F7FAFC;
        border-color: #CBD5E0;
        transform: translateY(-1px);
    }

    .btn-danger {
        background-color: #FEF2F2;
        color: #B91C1C !important;
        font-weight: 800;
        padding: 0.75rem 1.25rem;
        border-radius: 12px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: 1px solid #FEE2E2;
        text-transform: uppercase;
        font-size: 10px;
        letter-spacing: 0.1em;
        line-height: 1;
        white-space: nowrap;
    }

    .btn-danger:hover {
        background-color: #FEE2E2;
        transform: translateY(-1px);
    }

    .input {
        width: 100%;
        padding: 0.75rem 1rem;
        background: #F7FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 700;
        color: #1A202C;
        outline: none;
        transition: all 0.2s ease;
    }

    .input:focus {
        background: #FFFFFF;
        border-color: rgba(182, 255, 92, 0.8);
        box-shadow: 0 0 0 4px rgba(182, 255, 92, 0.12);
    }

    .select {
        width: 100%;
        padding: 0.75rem 2.25rem 0.75rem 1rem;
        background: #F7FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #4A5568;
        outline: none;
        transition: all 0.2s ease;
        appearance: none;
    }

    .select:focus {
        background: #FFFFFF;
        border-color: rgba(182, 255, 92, 0.8);
        box-shadow: 0 0 0 4px rgba(182, 255, 92, 0.12);
    }
</style>
</head>

<body class="flex h-screen w-full bg-white overflow-hidden" x-data="systemNotifications" x-init="fetchNotifications(); setInterval(() => fetchNotifications(), 30000)">

<!-- SIDEBAR -->
<aside id="resident-sidebar"
    class="fixed inset-y-0 left-0 z-50 w-72 bg-[var(--bg-darker)] text-white/80
           border-r border-[rgba(182,255,92,0.10)] flex flex-col
           transition-transform duration-300 lg:translate-x-0
           -translate-x-full">

    <!-- SIDEBAR HEADER -->
    <div class="h-16 px-6 border-b border-[rgba(182,255,92,0.10)] flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-[#B6FF5C] flex items-center justify-center
                    shadow-[0_0_15px_rgba(182,255,92,0.3)]">
            <i class="bi bi-houses-fill text-black"></i>
        </div>
        <span class="text-lg font-black tracking-tight text-white uppercase">Vistabellas</span>
    </div>

    <!-- SIDEBAR CONTENT -->
    <div class="flex-1 overflow-y-auto custom-scrollbar p-6 space-y-10">
        
        <!-- MAIN -->
        <div>
            <p class="px-4 text-[10px] font-bold text-[#B6FF5C] uppercase tracking-widest mb-3 opacity-60">Main</p>

            <a href="<?php echo e(route('resident.home')); ?>"
               class="group relative flex items-center gap-3.5 px-4 py-3 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('resident.home') ? 'bg-[#B6FF5C]/10 text-[#B6FF5C] font-bold' : 'text-white hover:bg-[#B6FF5C]/5 hover:text-white'); ?>">
                <?php if(request()->routeIs('resident.home')): ?>
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-[#B6FF5C] rounded-r-full shadow-[0_0_12px_rgba(182,255,92,0.4)]"></div>
                <?php endif; ?>
                <i class="bi bi-speedometer2 text-[1.2rem] <?php echo e(request()->routeIs('resident.home') ? 'text-[#B6FF5C]' : 'text-white/40 group-hover:text-white group-hover:scale-110 transition-all duration-300'); ?> ml-1"></i>
                <span class="text-[14px]">Home</span>
            </a>
        </div>

        <!-- COMMUNITY -->
        <div>
            <p class="px-4 text-[10px] font-bold text-[#B6FF5C] uppercase tracking-widest mb-3 opacity-60">Community</p>

            <a href="<?php echo e(route('resident.announcements.index')); ?>"
               class="group relative flex items-center gap-3.5 px-4 py-3 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('resident.announcements.*') ? 'bg-[#B6FF5C]/10 text-[#B6FF5C] font-bold' : 'text-white hover:bg-[#B6FF5C]/5 hover:text-white'); ?>">
                <?php if(request()->routeIs('resident.announcements.*')): ?>
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-[#B6FF5C] rounded-r-full shadow-[0_0_12px_rgba(182,255,92,0.4)]"></div>
                <?php endif; ?>
                <i class="bi bi-megaphone-fill text-[1.2rem] <?php echo e(request()->routeIs('resident.announcements.*') ? 'text-[#B6FF5C]' : 'text-white/40 group-hover:text-white group-hover:scale-110 transition-all duration-300'); ?> ml-1"></i>
                <span class="text-[14px]">Announcements</span>
            </a>

            <a href="<?php echo e(route('resident.about.board')); ?>"
               class="group relative flex items-center gap-3.5 px-4 py-3 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('resident.about.board') ? 'bg-[#B6FF5C]/10 text-[#B6FF5C] font-bold' : 'text-white hover:bg-[#B6FF5C]/5 hover:text-white'); ?>">
                <?php if(request()->routeIs('resident.about.board')): ?>
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-[#B6FF5C] rounded-r-full shadow-[0_0_12px_rgba(182,255,92,0.4)]"></div>
                <?php endif; ?>
                <i class="bi bi-people-fill text-[1.2rem] <?php echo e(request()->routeIs('resident.about.board') ? 'text-[#B6FF5C]' : 'text-white/40 group-hover:text-white group-hover:scale-110 transition-all duration-300'); ?> ml-1"></i>
                <span class="text-[14px]">Board Members</span>
            </a>

            <a href="<?php echo e(route('resident.amenities.index')); ?>"
               class="group relative flex items-center gap-3.5 px-4 py-3 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('resident.amenities.*') ? 'bg-[#B6FF5C]/10 text-[#B6FF5C] font-bold' : 'text-white hover:bg-[#B6FF5C]/5 hover:text-white'); ?>">
                <?php if(request()->routeIs('resident.amenities.*')): ?>
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-[#B6FF5C] rounded-r-full shadow-[0_0_12px_rgba(182,255,92,0.4)]"></div>
                <?php endif; ?>
                <i class="bi bi-building-check text-[1.2rem] <?php echo e(request()->routeIs('resident.amenities.*') ? 'text-[#B6FF5C]' : 'text-white/40 group-hover:text-white group-hover:scale-110 transition-all duration-300'); ?> ml-1"></i>
                <span class="text-[14px]">Amenities</span>
            </a>

            <a href="<?php echo e(route('resident.my-reservations.index')); ?>"
               class="group relative flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-300 <?php echo e(request()->routeIs('resident.my-reservations.*') ? 'bg-[#B6FF5C]/10 text-[#B6FF5C] font-bold shadow-[inset_0_0_12px_rgba(182,255,92,0.05)]' : 'text-white hover:bg-[#B6FF5C]/5 hover:text-white'); ?>">
                <div class="flex items-center gap-3.5">
                    <?php if(request()->routeIs('resident.my-reservations.*')): ?>
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-[#B6FF5C] rounded-r-full shadow-[0_0_12px_rgba(182,255,92,0.6)]"></div>
                    <?php endif; ?>
                    <i class="bi bi-calendar-check text-[1.2rem] <?php echo e(request()->routeIs('resident.my-reservations.*') ? 'text-[#B6FF5C]' : 'text-white/40 group-hover:text-white group-hover:scale-110 transition-all duration-300'); ?> ml-1"></i>
                    <span class="text-[14px]">My Reservations</span>
                </div>
                <template x-if="counts.reservations && counts.reservations.count > 0">
                    <span x-text="formatCount(counts.reservations.count)" 
                          :class="{
                              'bg-[#B6FF5C] text-[#0B1F1A]': counts.reservations.priority === 'normal',
                              'bg-amber-400 text-amber-950': counts.reservations.priority === 'warning',
                              'bg-red-500 text-white animate-pulse shadow-[0_0_10px_rgba(239,68,68,0.3)]': counts.reservations.priority === 'critical'
                          }"
                          class="flex items-center justify-center h-[18px] px-2 rounded-full text-[11px] font-black tracking-tighter transition-all duration-300 hover:brightness-110">
                    </span>
                </template>
            </a>
        </div>

        <!-- PAYMENTS -->
        <div>
            <p class="px-4 text-[10px] font-bold text-[#B6FF5C] uppercase tracking-widest mb-3 opacity-60">Payments</p>

            <a href="<?php echo e(route('resident.payments.index')); ?>"
               class="group relative flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-300 <?php echo e(request()->routeIs('resident.payments.*') ? 'bg-[#B6FF5C]/10 text-[#B6FF5C] font-bold shadow-[inset_0_0_12px_rgba(182,255,92,0.05)]' : 'text-white hover:bg-[#B6FF5C]/5 hover:text-white'); ?>">
                <div class="flex items-center gap-3.5">
                    <?php if(request()->routeIs('resident.payments.*')): ?>
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-[#B6FF5C] rounded-r-full shadow-[0_0_12px_rgba(182,255,92,0.6)]"></div>
                    <?php endif; ?>
                    <i class="bi bi-credit-card-2-front text-[1.2rem] <?php echo e(request()->routeIs('resident.payments.*') ? 'text-[#B6FF5C]' : 'text-gray-400 group-hover:text-white group-hover:scale-110 transition-all duration-300'); ?> ml-1"></i>
                    <span class="text-[14px]">Payments &amp; Dues</span>
                </div>
                <template x-if="counts.payments && counts.payments.count > 0">
                    <span x-text="formatCount(counts.payments.count)" 
                          :class="{
                              'bg-[#B6FF5C] text-[#0B1F1A]': counts.payments.priority === 'normal',
                              'bg-amber-400 text-amber-950': counts.payments.priority === 'warning',
                              'bg-red-500 text-white animate-pulse shadow-[0_0_10px_rgba(239,68,68,0.3)]': counts.payments.priority === 'critical'
                          }"
                          class="flex items-center justify-center h-[18px] px-2 rounded-full text-[11px] font-black tracking-tighter transition-all duration-300 hover:brightness-110">
                    </span>
                </template>
            </a>
        </div>

        <!-- SERVICES -->
        <div>
            <p class="px-4 text-[10px] font-bold text-[#B6FF5C] uppercase tracking-widest mb-3 opacity-60">Services</p>

            <a href="<?php echo e(route('resident.requests.index')); ?>"
                   class="group relative flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-300 <?php echo e(request()->routeIs('resident.requests.*') ? 'bg-[#B6FF5C]/10 text-[#B6FF5C] font-bold shadow-[inset_0_0_12px_rgba(182,255,92,0.05)]' : 'text-white hover:bg-[#B6FF5C]/5 hover:text-white'); ?>">
                <div class="flex items-center gap-3.5">
                    <?php if(request()->routeIs('resident.requests.*')): ?>
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-[#B6FF5C] rounded-r-full shadow-[0_0_12px_rgba(182,255,92,0.6)]"></div>
                    <?php endif; ?>
                    <i class="bi bi-inbox-fill text-[1.2rem] <?php echo e(request()->routeIs('resident.requests.*') ? 'text-[#B6FF5C]' : 'text-gray-400 group-hover:text-white group-hover:scale-110 transition-all duration-300'); ?> ml-1"></i>
                    <span class="text-[14px]">My Requests</span>
                </div>
                <template x-if="counts.requests && counts.requests.count > 0">
                    <span x-text="formatCount(counts.requests.count)" 
                          :class="{
                              'bg-[#B6FF5C] text-[#0B1F1A]': counts.requests.priority === 'normal',
                              'bg-amber-400 text-amber-950': counts.requests.priority === 'warning',
                              'bg-red-500 text-white animate-pulse shadow-[0_0_10px_rgba(239,68,68,0.3)]': counts.requests.priority === 'critical'
                          }"
                          class="flex items-center justify-center h-[18px] px-2 rounded-full text-[11px] font-black tracking-tighter transition-all duration-300 hover:brightness-110">
                    </span>
                </template>
            </a>

            <a href="<?php echo e(route('resident.messages.index')); ?>"
                   class="group relative flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-300 <?php echo e(request()->routeIs('resident.messages.*') ? 'bg-[#B6FF5C]/10 text-[#B6FF5C] font-bold shadow-[inset_0_0_12px_rgba(182,255,92,0.05)]' : 'text-white hover:bg-[#B6FF5C]/5 hover:text-white'); ?>">
                <div class="flex items-center gap-3.5">
                    <?php if(request()->routeIs('resident.messages.*')): ?>
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-[#B6FF5C] rounded-r-full shadow-[0_0_12px_rgba(182,255,92,0.6)]"></div>
                    <?php endif; ?>
                    <i class="bi bi-chat-dots-fill text-[1.2rem] <?php echo e(request()->routeIs('resident.messages.*') ? 'text-[#B6FF5C]' : 'text-white/40 group-hover:text-white group-hover:scale-110 transition-all duration-300'); ?> ml-1"></i>
                    <span class="text-[14px]">Messages</span>
                </div>
                <template x-if="counts.messages && counts.messages.count > 0">
                    <span x-text="formatCount(counts.messages.count)" 
                          class="flex items-center justify-center h-[18px] px-2 rounded-full bg-emerald-500 text-[#0B1F1A] text-[11px] font-black tracking-tighter transition-all duration-300 hover:brightness-110 shadow-[0_0_10px_rgba(16,185,129,0.2)]">
                    </span>
                </template>
            </a>
        </div>

        <!-- ACCOUNT -->
        <div>
            <p class="px-4 text-[10px] font-bold text-[#B6FF5C] uppercase tracking-widest mb-3 opacity-60">Account</p>

            <a href="<?php echo e(route('resident.profile.index')); ?>"
               class="group relative flex items-center gap-3.5 px-4 py-3 rounded-lg transition-all duration-200 <?php echo e(request()->routeIs('resident.profile.*') ? 'bg-[#B6FF5C]/10 text-[#B6FF5C] font-bold' : 'text-gray-300 hover:bg-[#B6FF5C]/5 hover:text-white'); ?>">
                <?php if(request()->routeIs('resident.profile.*')): ?>
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-[#B6FF5C] rounded-r-full shadow-[0_0_12px_rgba(182,255,92,0.4)]"></div>
                <?php endif; ?>
                <i class="bi bi-person-badge-fill text-[1.2rem] <?php echo e(request()->routeIs('resident.profile.*') ? 'text-[#B6FF5C]' : 'text-gray-400 group-hover:text-white'); ?> ml-1"></i>
                <span class="text-[14px]">My Profile</span>
            </a>
        </div>

        <!-- LOGOUT -->
        <div class="pt-4 mt-4 border-t border-white/10">
            <form action="<?php echo e(route('logout')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-full group flex items-center gap-3.5 px-4 py-3 rounded-lg text-red-400 hover:bg-red-500/10 hover:text-red-500 transition-all duration-200">
                    <i class="bi bi-box-arrow-right text-[1.2rem] text-red-400 group-hover:text-red-500 ml-1"></i>
                    <span class="text-[14px] font-bold tracking-wide uppercase">Logout</span>
                </button>
            </form>
        </div>

    </nav>

    <!-- SIDEBAR FOOTER -->
    <div class="p-4 border-t border-white/10 bg-[var(--bg-darker)]">
        <div class="flex items-center gap-3 px-3 py-3 rounded-2xl bg-[var(--bg-dark)] border border-[rgba(182,255,92,0.10)]">
            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#B6FF5C] to-[#8AC941]
                        flex items-center justify-center text-xs font-bold text-black">
                <?php echo e(strtoupper(substr(Auth::user()?->resident?->first_name ?? Auth::user()?->name ?? 'R', 0, 1))); ?>

            </div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-semibold text-white truncate">
                    <?php echo e(Auth::user()?->resident?->first_name ?? Auth::user()?->name); ?>

                </div>
                <div class="text-[11px] text-[#B6FF5C] truncate">Resident</div>
            </div>
            <form action="<?php echo e(route('logout')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button class="w-8 h-8 flex items-center justify-center rounded-lg text-[#A0AEC0] hover:text-white hover:bg-red-500/30 transition">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- MAIN CONTENT -->
<div class="flex-1 flex flex-col min-w-0 bg-white relative">

    <header class="h-16 fixed top-0 right-0 left-0 lg:left-72 z-30
                   flex items-center justify-between px-4 sm:px-6 lg:px-10
                   bg-white text-[#000f22] border-b border-gray-200">

        <div class="flex items-center gap-4">
            <button class="lg:hidden" onclick="toggleResidentSidebar()">
                <i class="bi bi-list text-2xl"></i>
            </button>
            <h1 class="text-lg sm:text-xl font-extrabold tracking-tight">
                <?php echo $__env->yieldContent('page-title', 'Resident Portal'); ?>
            </h1>
        </div>

        <div class="hidden md:flex items-center gap-6 text-sm relative" x-data="{ open: false }">
            
            <div class="relative">
                <button @click="open = !open" @click.away="open = false" class="relative p-2 rounded-full hover:bg-gray-100 transition-colors">
                    <i class="bi bi-bell text-xl text-[#4B5563]"></i>
                    <?php
                        $resident = Auth::user()?->resident;
                        $unreadCount = $resident
                            ? $resident->notifications()
                                ->where('role', \App\Models\Notification::ROLE_RESIDENT)
                                ->where('is_read', false)
                                ->count()
                            : 0;
                    ?>
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
                        <h3 class="font-bold text-[#1F2937]">Notifications</h3>
                        <?php if($unreadCount > 0): ?>
                        <form action="<?php echo e(route('resident.notifications.mark-all-read')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="text-xs text-[#385780] hover:underline font-semibold">Mark all as read</button>
                        </form>
                        <?php endif; ?>
                    </div>

                    <div class="max-h-[400px] overflow-y-auto custom-scrollbar">
                        <?php
                            $notifications = $resident
                                ? app(\App\Services\NotificationService::class)->getResidentDropdownNotifications($resident, 10)
                                : collect();
                        ?>
                        
                        <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <a href="<?php echo e($notification->link ?? '#'); ?>" 
                           class="block p-4 hover:bg-[#F5F7FA] transition-colors border-b border-gray-50 last:border-0 <?php echo e(!$notification->is_read ? 'bg-[#F9FAFB]' : ''); ?>">
                            <div class="flex gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 
                                    <?php if(($notification->category ?? '') == 'payment'): ?> bg-blue-50 text-blue-500
                                    <?php elseif(($notification->category ?? '') == 'billing'): ?> bg-indigo-50 text-indigo-500
                                    <?php elseif(($notification->category ?? '') == 'reminder'): ?> bg-amber-50 text-amber-500
                                    <?php elseif(($notification->category ?? '') == 'request'): ?> bg-emerald-50 text-emerald-500
                                    <?php elseif(($notification->category ?? '') == 'alert'): ?> bg-orange-50 text-orange-500
                                    <?php else: ?> bg-gray-50 text-gray-500 <?php endif; ?>">
                                    <i class="bi 
                                        <?php if(($notification->category ?? '') == 'payment'): ?> bi-cash-stack
                                        <?php elseif(($notification->category ?? '') == 'billing'): ?> bi-receipt
                                        <?php elseif(($notification->category ?? '') == 'reminder'): ?> bi-exclamation-circle
                                        <?php elseif(($notification->category ?? '') == 'request'): ?> bi-tools
                                        <?php elseif(($notification->category ?? '') == 'alert'): ?> bi-exclamation-triangle
                                        <?php else: ?> bi-bell <?php endif; ?> text-lg"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start mb-0.5">
                                        <p class="text-sm font-bold text-[#1F2937] truncate"><?php echo e($notification->title); ?></p>
                                        <span class="text-[11px] text-[#6B7280]"><?php echo e($notification->created_at->diffForHumans(null, true)); ?></span>
                                    </div>
                                    <p class="text-xs text-[#4B5563] line-clamp-2 leading-relaxed"><?php echo e($notification->message); ?></p>
                                </div>
                            </div>
                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="p-10 text-center">
                            <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="bi bi-bell-slash text-gray-300 text-xl"></i>
                            </div>
                            <p class="text-sm text-[#6B7280]">No notifications yet</p>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if($notifications->isNotEmpty()): ?>
                    <div class="p-3 bg-gray-50 border-t border-gray-100 text-center">
                        <a href="#" class="text-xs font-semibold text-[#4B5563] hover:text-[#1F2937]">View all notifications</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <i class="bi bi-person-circle text-lg text-[#4B5563]"></i>
                <span class="font-semibold text-[#1F2937]"><?php echo e(Auth::user()?->resident?->first_name ?? Auth::user()?->name); ?></span>
            </div>
        </div>
    </header>

    <main class="flex-1 mt-16 lg:ml-72 fade-in min-h-screen overflow-y-auto custom-scrollbar bg-[#F8F9FB]">
        
        <?php if(session('success')): ?>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10 pt-6">
                <div class="bg-green-50 border border-green-100 p-4 rounded-xl shadow-sm flex items-start">
                <i class="bi bi-check-circle-fill text-green-500 mr-3 mt-0.5"></i>
                <div>
                    <h3 class="text-sm font-medium text-green-800">Success</h3>
                    <p class="text-sm text-green-700 mt-1"><?php echo e(session('success')); ?></p>
                </div>
                <button onclick="this.parentElement.remove()" class="ml-auto text-green-500 hover:text-green-700">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10 pt-6">
                <div class="bg-red-50 border border-red-100 p-4 rounded-xl shadow-sm flex items-start">
                <i class="bi bi-exclamation-circle-fill text-red-500 mr-3 mt-0.5"></i>
                <div>
                    <h3 class="text-sm font-medium text-red-800">Error</h3>
                    <p class="text-sm text-red-700 mt-1"><?php echo e(session('error')); ?></p>
                </div>
                <button onclick="this.parentElement.remove()" class="ml-auto text-red-500 hover:text-red-700">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10 pt-6">
                <div class="bg-red-50 border border-red-100 p-4 rounded-xl shadow-sm flex items-start">
                <i class="bi bi-exclamation-circle-fill text-red-500 mr-3 mt-0.5"></i>
                <div>
                    <h3 class="text-sm font-medium text-red-800">There were some problems with your input</h3>
                    <ul class="list-disc list-inside text-sm text-red-700 mt-1">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
                <button onclick="this.parentElement.remove()" class="ml-auto text-red-500 hover:text-red-700">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            </div>
        <?php endif; ?>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-10 py-6">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>

</div>

<div id="resident-overlay"
     class="fixed inset-0 bg-black/60 z-40 hidden lg:hidden"
     onclick="toggleResidentSidebar()"></div>

<?php echo $__env->yieldPushContent('modals'); ?>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('systemNotifications', () => ({
            counts: {
                requests: { count: 0, priority: 'normal' },
                payments: { count: 0, priority: 'normal' },
                dues: { count: 0, priority: 'normal' },
                reservations: { count: 0, priority: 'normal' },
                messages: { count: 0, priority: 'normal' }
            },
            async fetchNotifications() {
                try {
                    const response = await fetch('<?php echo e(route("resident.system-notifications")); ?>');
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

const residentSidebar = document.getElementById('resident-sidebar');
const residentOverlay = document.getElementById('resident-overlay');

function toggleResidentSidebar() {
    residentSidebar.classList.toggle('-translate-x-full');
    residentOverlay.classList.toggle('hidden');
}
</script>

</body>
</html>
<?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views/resident/layouts/app.blade.php ENDPATH**/ ?>