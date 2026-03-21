<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Resident Portal')</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
<style>
[x-cloak] { display: none !important; }
html { scroll-behavior: smooth; }

body {
    font-family: "Helvetica Neue", Arial, sans-serif;
    font-size: 15px;
    font-weight: 500;
    color: #4B5563;
    background: #ffffff;
    overflow-x: hidden;
}

.title, .section-title {
    font-size: 16px;
    font-weight: 600;
    color: #1F2937;
}

.meta {
    font-size: 13px;
    font-weight: 500;
    color: #6B7280;
}

.custom-scrollbar::-webkit-scrollbar { width: 5px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background-color: rgba(148,163,184,0.4); border-radius: 9999px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }

.fade-in { animation: fadeIn .5s ease-out forwards; }
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
</head>

<body class="flex h-screen w-full">

<!-- SIDEBAR -->
<aside id="resident-sidebar"
    class="fixed inset-y-0 left-0 z-50 w-72 bg-[#000f22] text-[#c0e6fd]
           border-r border-[#80aad3]/10 flex flex-col
           transition-transform duration-300 transform
           -translate-x-full lg:translate-x-0">

    <!-- TOP -->
    <div class="h-16 flex items-center px-6 border-b border-gray-800">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#5b86b6] to-[#3f6593]
                        flex items-center justify-center shadow-lg ring-1 ring-white/10">
                <i class="bi bi-house-door-fill text-white text-base"></i>
            </div>
            <div class="leading-tight">
                <div class="text-sm font-semibold text-white">Resident Portal</div>
                <div class="text-[10px] text-[#80aad3] uppercase tracking-[0.18em]">Subdivision</div>
            </div>
        </div>
    </div>

    <!-- NAV -->
    <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-8 custom-scrollbar">

        <!-- MAIN -->
        <div>
            <p class="px-4 text-[10px] font-bold text-[#80aad3] uppercase tracking-widest mb-3 opacity-60">Main</p>

            <a href="{{ route('resident.home') }}"
               class="group relative flex items-center gap-3.5 px-4 py-3 rounded-lg transition-all duration-200 text-gray-300 hover:bg-[#c0e6fd]/10 hover:text-white">
                <i class="bi bi-speedometer2 text-[1.2rem] text-gray-400 group-hover:text-white ml-1"></i>
                <span class="text-[14px]">Home</span>
            </a>
        </div>

        <!-- COMMUNITY -->
        <div>
            <p class="px-4 text-[10px] font-bold text-[#80aad3] uppercase tracking-widest mb-3 opacity-60">Community</p>

            <a href="{{ route('resident.announcements.index') }}"
               class="group flex items-center gap-3.5 px-4 py-3 rounded-lg text-gray-300 hover:bg-[#c0e6fd]/10 hover:text-white">
                <i class="bi bi-megaphone-fill text-[1.2rem] text-gray-400 group-hover:text-white ml-1"></i>
                <span class="text-[14px]">Announcements</span>
            </a>

           {{-- 
<a href="{{ route('resident.announcements.index', ['category' => 'Event']) }}"
   class="group flex items-center gap-3.5 px-4 py-3 rounded-lg text-gray-300 hover:bg-[#c0e6fd]/10 hover:text-white">
    <i class="bi bi-calendar-event text-[1.2rem] text-gray-400 group-hover:text-white ml-1"></i>
    <span class="text-[14px]">Community Highlights</span>
</a>
--}}

            <a href="{{ route('resident.about.board') }}"
               class="group flex items-center gap-3.5 px-4 py-3 rounded-lg text-gray-300 hover:bg-[#c0e6fd]/10 hover:text-white">
                <i class="bi bi-people-fill text-[1.2rem] text-gray-400 group-hover:text-white ml-1"></i>
                <span class="text-[14px]">Board Members</span>
            </a>

            <a href="{{ route('resident.amenities.index') }}"
               class="group flex items-center gap-3.5 px-4 py-3 rounded-lg text-gray-300 hover:bg-[#c0e6fd]/10 hover:text-white">
                <i class="bi bi-building-check text-[1.2rem] text-gray-400 group-hover:text-white ml-1"></i>
                <span class="text-[14px]">Amenities</span>
            </a>

            <a href="{{ route('resident.my-reservations.index') }}"
               class="group flex items-center gap-3.5 px-4 py-3 rounded-lg text-gray-300 hover:bg-[#c0e6fd]/10 hover:text-white">
                <i class="bi bi-calendar-check text-[1.2rem] text-gray-400 group-hover:text-white ml-1"></i>
                <span class="text-[14px]">My Reservations</span>
            </a>
        </div>

        <!-- PAYMENTS -->
        <div>
            <p class="px-4 text-[10px] font-bold text-[#80aad3] uppercase tracking-widest mb-3 opacity-60">Payments</p>

            <a href="{{ route('resident.payments.index') }}"
               class="group flex items-center gap-3.5 px-4 py-3 rounded-lg text-gray-300 hover:bg-[#c0e6fd]/10 hover:text-white">
                <i class="bi bi-credit-card-2-front text-[1.2rem] text-gray-400 group-hover:text-white ml-1"></i>
                <span class="text-[14px]">Payments &amp; Dues</span>
            </a>
        </div>

        <!-- SERVICES -->
        <div>
            <p class="px-4 text-[10px] font-bold text-[#80aad3] uppercase tracking-widest mb-3 opacity-60">Services</p>

            <a href="{{ route('resident.requests.index') }}"
                   class="group flex items-center gap-3.5 px-4 py-3 rounded-lg text-gray-300 hover:bg-[#c0e6fd]/10 hover:text-white">
                <i class="bi bi-inbox-fill text-[1.2rem] text-gray-400 group-hover:text-white ml-1"></i>
                <span class="text-[14px]">My Requests</span>
            </a>

            <a href="{{ route('resident.support.index') }}"
                   class="group flex items-center gap-3.5 px-4 py-3 rounded-lg text-gray-300 hover:bg-[#c0e6fd]/10 hover:text-white">
                <i class="bi bi-chat-left-text-fill text-[1.2rem] text-gray-400 group-hover:text-white ml-1"></i>
                <span class="text-[14px]">Support & Messages</span>
            </a>
        </div>

        <!-- ACCOUNT -->
        <div>
            <p class="px-4 text-[10px] font-bold text-[#80aad3] uppercase tracking-widest mb-3 opacity-60">Account</p>

            <a href="{{ route('resident.profile.index') }}"
               class="group flex items-center gap-3.5 px-4 py-3 rounded-lg text-gray-300 hover:bg-[#c0e6fd]/10 hover:text-white">
                <i class="bi bi-person-badge-fill text-[1.2rem] text-gray-400 group-hover:text-white ml-1"></i>
                <span class="text-[14px]">My Profile</span>
            </a>
        </div>

    </nav>

    <!-- SIDEBAR FOOTER -->
    <div class="p-4 border-t border-gray-800 bg-[#000f22]">
        <div class="flex items-center gap-3 px-3 py-3 rounded-2xl bg-[#1b3554]">
            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#5b86b6] to-[#3f6593]
                        flex items-center justify-center text-xs font-bold text-white">
                {{ strtoupper(substr(optional(Auth::user()->resident)->first_name ?? Auth::user()->name ?? 'R', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-semibold text-white truncate">
                    {{ optional(Auth::user()->resident)->first_name ?? Auth::user()->name }}
                </div>
                <div class="text-[11px] text-[#80aad3] truncate">Resident</div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-8 h-8 flex items-center justify-center rounded-lg text-[#80aad3] hover:text-white hover:bg-red-500/30 transition">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- MAIN CONTENT -->
<div class="flex-1 flex flex-col min-w-0 bg-white relative">

    <header class="h-16 fixed top-0 right-0 left-0 lg:left-72 z-30
                   flex items-center justify-between px-6 lg:px-10
                   bg-white text-[#000f22] border-b border-gray-200">

        <div class="flex items-center gap-4">
            <button class="lg:hidden" onclick="toggleResidentSidebar()">
                <i class="bi bi-list text-2xl"></i>
            </button>
            <h1 class="text-xl font-extrabold">
                @yield('page-title', 'Resident Portal')
            </h1>
        </div>

        <div class="hidden md:flex items-center gap-6 text-sm relative" x-data="{ open: false }">
            {{-- Notification Bell --}}
            <div class="relative">
                <button @click="open = !open" @click.away="open = false" class="relative p-2 rounded-full hover:bg-gray-100 transition-colors">
                    <i class="bi bi-bell text-xl text-[#4B5563]"></i>
                    @php
                        $unreadCount = optional(Auth::user()->resident)->notifications()->where('is_read', false)->count() ?? 0;
                    @endphp
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
                        <h3 class="font-bold text-[#1F2937]">Notifications</h3>
                        @if($unreadCount > 0)
                        <form action="{{ route('resident.notifications.mark-all-read') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs text-[#385780] hover:underline font-semibold">Mark all as read</button>
                        </form>
                        @endif
                    </div>

                    <div class="max-h-[400px] overflow-y-auto custom-scrollbar">
                        @php
                            $notifications = optional(Auth::user()->resident)->notifications()->latest()->take(10)->get() ?? collect();
                        @endphp
                        
                        @forelse($notifications as $notification)
                        <a href="{{ $notification->link ?? '#' }}" 
                           class="block p-4 hover:bg-[#F5F7FA] transition-colors border-b border-gray-50 last:border-0 {{ !$notification->is_read ? 'bg-[#F9FAFB]' : '' }}">
                            <div class="flex gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 
                                    @if(($notification->type ?? '') == 'payment') bg-blue-50 text-blue-500
                                    @elseif(($notification->type ?? '') == 'request') bg-emerald-50 text-emerald-500
                                    @elseif(($notification->type ?? '') == 'alert') bg-orange-50 text-orange-500
                                    @else bg-gray-50 text-gray-500 @endif">
                                    <i class="bi 
                                        @if(($notification->type ?? '') == 'payment') bi-cash-stack
                                        @elseif(($notification->type ?? '') == 'request') bi-tools
                                        @elseif(($notification->type ?? '') == 'alert') bi-exclamation-triangle
                                        @else bi-bell @endif text-lg"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start mb-0.5">
                                        <p class="text-sm font-bold text-[#1F2937] truncate">{{ $notification->title }}</p>
                                        <span class="text-[11px] text-[#6B7280]">{{ $notification->created_at->diffForHumans(null, true) }}</span>
                                    </div>
                                    <p class="text-xs text-[#4B5563] line-clamp-2 leading-relaxed">{{ $notification->message }}</p>
                                </div>
                            </div>
                        </a>
                        @empty
                        <div class="p-10 text-center">
                            <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="bi bi-bell-slash text-gray-300 text-xl"></i>
                            </div>
                            <p class="text-sm text-[#6B7280]">No notifications yet</p>
                        </div>
                        @endforelse
                    </div>

                    @if($notifications->isNotEmpty())
                    <div class="p-3 bg-gray-50 border-t border-gray-100 text-center">
                        <a href="#" class="text-xs font-semibold text-[#4B5563] hover:text-[#1F2937]">View all notifications</a>
                    </div>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-3">
                <i class="bi bi-person-circle text-lg text-[#4B5563]"></i>
                <span class="font-semibold text-[#1F2937]">{{ optional(Auth::user()->resident)->first_name ?? Auth::user()->name }}</span>
            </div>
        </div>
    </header>

    <main class="flex-1 mt-16 lg:ml-72 fade-in min-h-screen">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mx-6 mt-6 lg:mx-10 bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm flex items-start">
                <i class="bi bi-check-circle-fill text-green-500 mr-3 mt-0.5"></i>
                <div>
                    <h3 class="text-sm font-medium text-green-800">Success</h3>
                    <p class="text-sm text-green-700 mt-1">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="ml-auto text-green-500 hover:text-green-700">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="mx-6 mt-6 lg:mx-10 bg-red-50 border-l-4 border-red-500 p-4 rounded-md shadow-sm flex items-start">
                <i class="bi bi-exclamation-circle-fill text-red-500 mr-3 mt-0.5"></i>
                <div>
                    <h3 class="text-sm font-medium text-red-800">Error</h3>
                    <p class="text-sm text-red-700 mt-1">{{ session('error') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="ml-auto text-red-500 hover:text-red-700">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="mx-6 mt-6 lg:mx-10 bg-red-50 border-l-4 border-red-500 p-4 rounded-md shadow-sm flex items-start">
                <i class="bi bi-exclamation-circle-fill text-red-500 mr-3 mt-0.5"></i>
                <div>
                    <h3 class="text-sm font-medium text-red-800">There were some problems with your input</h3>
                    <ul class="list-disc list-inside text-sm text-red-700 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button onclick="this.parentElement.remove()" class="ml-auto text-red-500 hover:text-red-700">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        @endif

        @yield('content')
    </main>

</div>

<div id="resident-overlay"
     class="fixed inset-0 bg-black/60 z-40 hidden lg:hidden"
     onclick="toggleResidentSidebar()"></div>

@stack('modals')

<script>
const residentSidebar = document.getElementById('resident-sidebar');
const residentOverlay = document.getElementById('resident-overlay');

function toggleResidentSidebar() {
    residentSidebar.classList.toggle('-translate-x-full');
    residentOverlay.classList.toggle('hidden');
}
</script>

</body>
</html>