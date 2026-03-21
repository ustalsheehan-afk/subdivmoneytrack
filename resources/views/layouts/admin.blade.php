<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Admin Panel')</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
/* ===== THEME COLORS (OFFICIAL) ===== */
:root {
    --color-pale-sky: #c0e6fd;
    --color-soft-denim: #334155; /* Dark Grayish Blue */
    --color-steel-blue: #1e3a8a; /* Dark Blue */
    --color-deep-slate: #1e293b; /* Dark Slate */
    --color-midnight: #1b3554;
    --color-obsidian: #000f22;
}

/* Base */
body {
    font-family: 'Inter', sans-serif;
    background-color: #ffffff; /* Darker than Obsidian for content contrast */
    color: var(--color-soft-denim);
    overflow: hidden; /* Prevent body scroll, handle in containers */
}

/* Scrollbar */
.custom-scrollbar::-webkit-scrollbar { width: 5px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background-color: rgba(128,170,211,0.2); border-radius: 10px; }
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
    background-color: #ffffff; /* Explicitly set white background */
}

@media (max-width: 1024px) {
    .content-wrapper {
        margin-left: 0;
        width: 100%;
    }
}

/* Glass Cards */
.card, .glass-card {
    background-color: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 1rem;
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    padding: 1.5rem;
    color: #000000;
}

.admin-form-card {
    background-color: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 1rem;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
    padding: 2rem;
    max-width: 48rem;
    margin-left: auto;
    margin-right: auto;
}

.admin-form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: #111827;
    margin-bottom: 0.35rem;
}

.admin-form-input,
.admin-form-select,
.admin-form-textarea {
    width: 100%;
    border-radius: 0.75rem;
    border: 1px solid #d1d5db;
    padding: 0.625rem 0.75rem;
    font-size: 0.875rem;
    color: #111827;
    background-color: #f9fafb;
    outline: none;
    transition: border-color 0.15s ease, box-shadow 0.15s ease, background-color 0.15s ease;
}

.admin-form-input:focus,
.admin-form-select:focus,
.admin-form-textarea:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 1px rgba(59, 130, 246, 0.4);
    background-color: #ffffff;
}

.admin-btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.75rem;
    padding: 0.625rem 1.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    background-color: #1d4ed8;
    color: #ffffff;
    box-shadow: 0 10px 25px rgba(37, 99, 235, 0.25);
    transition: background-color 0.15s ease, transform 0.1s ease, box-shadow 0.15s ease;
}

.admin-btn-primary:hover {
    background-color: #1e40af;
    transform: translateY(-1px);
    box-shadow: 0 14px 30px rgba(37, 99, 235, 0.3);
}

.admin-btn-secondary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.75rem;
    padding: 0.625rem 1.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    background-color: #e5e7eb;
    color: #111827;
    transition: background-color 0.15s ease, color 0.15s ease;
}

.admin-btn-secondary:hover {
    background-color: #d1d5db;
    color: #020617;
}

/* Headings */
h1, h2, h3, h4, h5, h6 { color: var(--color-pale-sky); font-weight: 700; letter-spacing: -0.025em; }

/* Buttons */
.btn-primary { 
    background: linear-gradient(135deg, var(--color-steel-blue), var(--color-deep-slate));
    color: var(--color-pale-sky);
    font-weight: 600;
    padding: 0.6rem 1.2rem;
    border-radius: 0.75rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 15px rgba(91, 134, 182, 0.2);
}
.btn-primary:hover { 
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(91, 134, 182, 0.3);
    filter: brightness(110%);
}

/* Table Overrides */
table thead { background-color: rgba(91,134,182,0.15); color: #c0e6fd; }
table tbody tr { color: #80aad3; border-bottom: 1px solid rgba(128,170,211,0.15); }
table tbody tr:hover { background-color: rgba(192,230,253,0.05); }

/* Animations */
.fade-in { animation: fadeIn 0.4s ease-out forwards; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

</style>
</head>
<body class="flex h-screen w-full">

{{-- SIDEBAR --}}
<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-[#000f22] border-r border-[#80aad3]/10 flex flex-col transition-transform duration-300 transform -translate-x-full lg:translate-x-0">
    
    {{-- Brand --}}
    <div class="h-24 flex items-center px-8 border-b border-gray-800">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#5b86b6] to-[#3f6593] flex items-center justify-center shadow-lg shadow-blue-900/20 ring-1 ring-white/10">
                <i class="bi bi-buildings-fill text-white text-lg"></i>
            </div>
            <div>
                <h2 class="text-white font-bold text-lg tracking-tight leading-none">Subdiv<span class="text-[#5b86b6]">Management</span></h2>
                <p class="text-gray-400 text-[10px] font-semibold tracking-widest uppercase mt-1">System</p>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-8 custom-scrollbar">
        {{-- MAIN --}}
        <div>
            <p class="px-4 text-[10px] font-bold text-[#80aad3] uppercase tracking-widest mb-3 opacity-60">Main</p>
            <div class="space-y-1">
                @php
                    $mainLinks = [
                        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'bi-grid-1x2-fill', 'pattern' => 'admin.dashboard'],
                        ['label' => 'Announcements', 'route' => 'admin.announcements.index', 'icon' => 'bi-megaphone-fill', 'pattern' => 'admin.announcements*'],
                    ];
                @endphp
                @foreach ($mainLinks as $link)
                    @include('layouts.partials.sidebar-link', ['link' => $link])
                @endforeach
            </div>
        </div>

        {{-- MANAGEMENT --}}
        <div>
            <p class="px-4 text-[10px] font-bold text-[#80aad3] uppercase tracking-widest mb-3 opacity-60">Management</p>
            <div class="space-y-1">
                @php
                    $mgmtLinks = [
                        ['label' => 'Residents', 'route' => 'admin.residents.index', 'icon' => 'bi-people-fill', 'pattern' => 'admin.residents*'],
                        ['label' => 'Invitations', 'route' => 'admin.invitations.index', 'icon' => 'bi-envelope-paper-fill', 'pattern' => 'admin.invitations*'],
                        ['label' => 'Dues', 'route' => 'admin.dues.index', 'icon' => 'bi-receipt', 'pattern' => 'admin.dues*'],
                        ['label' => 'Payments', 'route' => 'admin.payments.index', 'icon' => 'bi-credit-card-fill', 'pattern' => 'admin.payments*'],
                        ['label' => 'Penalties', 'route' => 'admin.penalties.index', 'icon' => 'bi-exclamation-octagon-fill', 'pattern' => 'admin.penalties*'],
                        ['label' => 'Requests', 'route' => 'admin.requests.index', 'icon' => 'bi-inbox-fill', 'pattern' => 'admin.requests*'],
                        ['label' => 'Support', 'route' => 'admin.support.index', 'icon' => 'bi-chat-left-text-fill', 'pattern' => 'admin.support*'],
                        ['label' => 'Amenities', 'route' => 'admin.amenities.index', 'icon' => 'bi-building-fill', 'pattern' => 'admin.amenities*'],
                        ['label' => 'Reservations', 'route' => 'admin.amenity-reservations.index', 'icon' => 'bi-calendar-check-fill', 'pattern' => 'admin.amenity-reservations*'],
                        ['label' => 'Board Members', 'route' => 'admin.board.index', 'icon' => 'bi-people-fill', 'pattern' => 'admin.board*'],
                    ];
                @endphp
                @foreach ($mgmtLinks as $link)
                    @include('layouts.partials.sidebar-link', ['link' => $link])
                @endforeach
            </div>
        </div>

        {{-- SYSTEM --}}
        <div>
            <p class="px-4 text-[10px] font-bold text-[#80aad3] uppercase tracking-widest mb-3 opacity-60">System</p>
            <div class="space-y-1">
                @php
                    $sysLinks = [
                        ['label' => 'Reports', 'route' => 'admin.reports.index', 'icon' => 'bi-bar-chart-fill', 'pattern' => 'admin.reports*'],
                        ['label' => 'Accounts', 'route' => 'admin.accounts.index', 'icon' => 'bi-shield-lock-fill', 'pattern' => 'admin.accounts*'],
                    ];
                @endphp
                @foreach ($sysLinks as $link)
                    @include('layouts.partials.sidebar-link', ['link' => $link])
                @endforeach
            </div>
        </div>
    </nav>

    {{-- User Profile --}}
    @auth('admin')
    <div class="p-4 border-t border-gray-800 bg-[#000f22]">
        <div class="flex items-center gap-3 p-3 rounded-2xl hover:bg-[#c0e6fd]/5 transition-all cursor-pointer group border border-transparent hover:border-gray-800">
            <div class="relative">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#5b86b6] to-[#3f6593] flex items-center justify-center text-sm font-bold text-white shadow-md">
                    AD
                </div>
                <div class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-[#000f22] rounded-full"></div>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-white truncate group-hover:text-[#c0e6fd] transition-colors">Administrator</p>
                <p class="text-[11px] text-[#80aad3] truncate">System Admin</p>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" 
                    class="w-8 h-8 flex items-center justify-center rounded-lg text-[#80aad3] hover:text-white hover:bg-red-500/20 transition-all" 
                    title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
    @endauth
</aside>

{{-- MAIN CONTENT AREA --}}
<div class="flex-1 flex flex-col min-w-0 bg-[#ffffff] relative">
    
    {{-- COORDINATED HEADER --}}
    <header class="h-24 fixed top-0 right-0 left-0 lg:left-72 z-30 flex items-center justify-between px-8 lg:px-10 bg-[#000f22] border-b border-gray-800 transition-all duration-300">
        
        {{-- Page Title (Big & Bold) --}}
        <div class="flex flex-col justify-center animate-fade-in">
            <div class="flex items-center gap-3 mb-1">
                <button class="lg:hidden text-gray-400 hover:text-white transition-colors" onclick="toggleSidebar()">
                    <i class="bi bi-list text-2xl"></i>
                </button>
            </div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight drop-shadow-sm">
                @yield('page-title')
            </h1>
        </div>

        {{-- Right Actions --}}
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-4">
                {{-- Admin Notifications --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="relative p-2 rounded-xl hover:bg-gray-100 transition-colors">
                        <i class="bi bi-bell text-xl text-gray-500"></i>
                        @if($unreadCount > 0)
                            <span class="absolute top-1.5 right-1.5 w-4 h-4 bg-red-500 text-white text-[10px] font-bold flex items-center justify-center rounded-full border-2 border-white">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                        @endif
                    </button>

                    {{-- Notification Dropdown --}}
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
                            @if($unreadCount > 0)
                                <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-[10px] font-bold text-blue-600 hover:text-blue-700 uppercase tracking-widest">Mark all as read</button>
                                </form>
                            @endif
                        </div>

                        <div class="max-h-96 overflow-y-auto custom-scrollbar">
                            @forelse($notifications as $notification)
                            <a href="{{ $notification->link ?? '#' }}" 
                               class="block p-4 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0 {{ !$notification->is_read ? 'bg-blue-50/30' : '' }}">
                                <div class="flex gap-3">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 
                                        @if($notification->type == 'payment') bg-blue-50 text-blue-500
                                        @elseif($notification->type == 'request') bg-emerald-50 text-emerald-500
                                        @elseif($notification->type == 'alert') bg-orange-50 text-orange-500
                                        @else bg-gray-50 text-gray-500 @endif">
                                        <i class="bi 
                                            @if($notification->type == 'payment') bi-cash-stack
                                            @elseif($notification->type == 'request') bi-tools
                                            @elseif($notification->type == 'alert') bi-exclamation-triangle
                                            @else bi-bell @endif text-lg"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-start mb-0.5">
                                            <p class="text-sm font-bold text-gray-900 truncate">{{ $notification->title }}</p>
                                            <span class="text-[10px] text-gray-400 font-bold uppercase whitespace-nowrap ml-2">{{ $notification->created_at->diffForHumans(null, true) }}</span>
                                        </div>
                                        <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed">{{ $notification->message }}</p>
                                    </div>
                                </div>
                            </a>
                            @empty
                            <div class="p-10 text-center">
                                <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="bi bi-bell-slash text-gray-300 text-xl"></i>
                                </div>
                                <p class="text-sm text-gray-500 font-medium">No notifications yet</p>
                            </div>
                            @endforelse
                        </div>

                        @if($notifications->isNotEmpty())
                        <div class="p-3 bg-gray-50 border-t border-gray-100 text-center">
                            <a href="#" class="text-xs font-bold text-gray-500 hover:text-blue-600 transition-colors uppercase tracking-widest">View all notifications</a>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm shadow-sm">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <span class="hidden md:block text-sm font-bold text-gray-700 tracking-tight">{{ Auth::user()->name }}</span>
                </div>
            </div>
        </div>
    </header>

    {{-- SCROLLABLE CONTENT --}}
    <main class="content-wrapper custom-scrollbar fade-in">
        <div class="max-w-7xl mx-auto">
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                     class="mb-6 flex items-center p-4 bg-emerald-50 border border-emerald-100 rounded-2xl shadow-sm animate-in fade-in slide-in-from-top-4 duration-300">
                    <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-bold text-emerald-900">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="ml-4 text-emerald-400 hover:text-emerald-600 transition-colors">
                        <i class="bi bi-x-lg text-xs"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                     class="mb-6 flex items-center p-4 bg-red-50 border border-red-100 rounded-2xl shadow-sm animate-in fade-in slide-in-from-top-4 duration-300">
                    <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center text-red-600">
                        <i class="bi bi-exclamation-circle-fill"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-bold text-red-900">{{ session('error') }}</p>
                    </div>
                    <button @click="show = false" class="ml-4 text-red-400 hover:text-red-600 transition-colors">
                        <i class="bi bi-x-lg text-xs"></i>
                    </button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

</div>

{{-- Overlay --}}
<div id="overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity duration-300" onclick="toggleSidebar()"></div>

{{-- Global Search Overlay --}}
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

@include('components.universal-drawer')

{{-- Invitation Modal --}}
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

@stack('modals')

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
            const response = await fetch('{{ route("admin.residents.invite") }}', {
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

@stack('scripts')

</body>
</html>
