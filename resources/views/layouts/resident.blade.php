<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Resident Portal')</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
/* Base */
body {
    font-family: 'Inter', sans-serif;
    background-color: #fdfdff;
    color: #1F2937;
}

/* Sidebar */
.sidebar {
    background: linear-gradient(180deg, #1D4ED8, #2563EB); /* Blue gradient for Resident */
    border-right: 1px solid #374151;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.sidebar-hidden { transform: translateX(-100%); }

.sidebar-title {
    background-color: #1D4ED8;
    color: #ffffff;
    padding: 1rem 1.5rem;
    font-size: 1.25rem;
    font-weight: 800;
    border-bottom: 2px solid #2563EB;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    text-align: center;
}

.sidebar-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    color: #ffffff;
    font-weight: 800;
    transition: all 0.3s ease;
}
.sidebar-link i {
    color: #BFDBFE;
    font-size: 1.2rem;
    transition: color 0.3s ease, transform 0.3s ease;
}
.sidebar-link:hover {
    background-color: #2563EB;
    color: #F3F4F6;
    transform: translateX(3px);
}
.sidebar-link:hover i {
    color: #F3F4F6;
    transform: scale(1.1);
}
.sidebar-link.active {
    background-color: #1E40AF;
    color: #F3F4F6;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}
.sidebar-link.active i { color: #F3F4F6; }

.sidebar-scroll {
    overflow-y: auto;
    max-height: calc(100vh - 4rem);
    padding-right: 0.25rem;
}
.sidebar:hover { box-shadow: 2px 0 10px rgba(0,0,0,0.05); }

/* Header */
header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: 4rem;
    background: linear-gradient(90deg, #1D4ED8, #2563EB);
    border-bottom: 1px solid #374151;
    z-index: 50;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 1.5rem;
    box-shadow: 0 3px 8px rgba(0,0,0,0.08);
}
header h1 {
    font-size: 1.6rem;
    font-weight: 600;
    color: #E5E7EB;
}

/* Buttons */
.btn-primary {
    @apply bg-blue-700 text-white font-medium rounded-md px-4 py-2 transition-all duration-200;
}
.btn-primary:hover { @apply bg-blue-800; }
.btn-secondary {
    @apply bg-gray-300 text-gray-800 font-medium rounded-md px-4 py-2 transition-all duration-200;
}
.btn-secondary:hover { @apply bg-gray-500 text-gray-100; }
.btn-danger {
    @apply bg-red-600 text-white font-medium rounded-md px-4 py-2 transition-all duration-200;
}
.btn-danger:hover { @apply bg-red-700; }

/* Content */
.content-wrapper { padding: 6rem 2rem 2rem; }
.content-wrapper .card {
    background-color: #F9FAFB; 
    border: 1px solid #E5E7EB;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}
.content-wrapper .card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}

/* Table */
.table th {
    background-color: #2563EB;
    color: #F3F4F6;
}
.table tr:hover { background-color: #E5E7EB; }

/* Scrollbar */
.sidebar-scroll::-webkit-scrollbar { width: 6px; }
.sidebar-scroll::-webkit-scrollbar-thumb { background-color: #4B5563; border-radius: 3px; }
.sidebar-scroll::-webkit-scrollbar-track { background: transparent; }

@media (min-width: 1024px) {
    header { left: 18rem; }
    .sidebar { transform: none !important; }
}
</style>
</head>
<body class="flex h-screen overflow-hidden">

{{-- Sidebar --}}
<aside id="sidebar" class="sidebar fixed lg:static top-0 left-0 h-full w-72 flex flex-col justify-between shadow-md z-40 sidebar-hidden lg:translate-x-0">
    <div>
        <div class="sidebar-title">Resident Portal</div>
        <nav class="mt-4 space-y-1 px-4 sidebar-scroll">
            @php
            $links = [
                ['Home', 'resident.home', 'bi-speedometer2'],
                ['My Profile', 'resident.profile.index', 'bi-person-circle'],
                ['Payments & Dues', 'resident.dues.index', 'bi-cash-stack'],
                ['Announcements', 'resident.announcements.index', 'bi-megaphone-fill'],
                ['My Requests', 'resident.requests.index', 'bi-envelope-fill'],
                ['Amenities', 'resident.amenities.index', 'bi-building'],
                ['My Reservations', 'resident.my-reservations.index', 'bi-calendar-event'],
            ];
            @endphp

            @foreach ($links as [$label, $route, $icon])
                <a href="{{ route($route) }}" class="sidebar-link flex items-center {{ request()->routeIs($route) ? 'active' : '' }}">
                    <i class="{{ $icon }}"></i>
                    <span>{{ $label }}</span>
                </a>
            @endforeach
        </nav>
    </div>

{{-- Logout --}}
<form action="{{ route('logout') }}" method="POST" class="px-4 mb-6 mt-auto">
    @csrf
    <button type="submit"
        class="w-full bg-blue-900 text-white font-semibold rounded-md px-4 py-2 hover:bg-blue-800 hover:text-white transition-all duration-200"
        onclick="return confirm('Are you sure you want to logout?');">
        <i class="bi bi-box-arrow-right mr-2"></i> Logout
    </button>
</form>

</aside>

{{-- Overlay for mobile --}}
<div id="overlay" class="fixed inset-0 bg-black bg-opacity-40 hidden lg:hidden z-30" onclick="toggleSidebar()"></div>

{{-- Main Content --}}
<main class="flex-1 flex flex-col overflow-y-auto transition-all duration-300">
    <header>
        <div class="flex items-center gap-3">
            <button id="menu-btn" class="lg:hidden text-gray-100 hover:text-gray-300 focus:outline-none" onclick="toggleSidebar()">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <h1>@yield('page-title')</h1>
        </div>
    </header>

    <section class="content-wrapper">
        <div class="max-w-8xl mx-auto">
            @yield('content')
        </div>
    </section>
</main>

<script>
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');
function toggleSidebar() {
    sidebar.classList.toggle('sidebar-hidden');
    overlay.classList.toggle('hidden');
}
</script>

</body>
</html>
