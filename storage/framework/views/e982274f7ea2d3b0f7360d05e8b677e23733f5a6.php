<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $__env->yieldContent('title', 'Resident Portal'); ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
/* Base */
body {
    font-family: 'Inter', sans-serif;
    background-color: #FFFFFF;
    color: #1F2937;
}

/* Sidebar */
.sidebar {
    background: #081412; /* Keep dark sidebar for consistency */
    border-right: 1px solid rgba(182, 255, 92, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.sidebar-hidden { transform: translateX(-100%); }

.sidebar-title {
    background-color: #081412;
    color: #ffffff;
    padding: 1rem 1.5rem;
    font-size: 1.25rem;
    font-weight: 800;
    border-bottom: 1px solid rgba(182, 255, 92, 0.1);
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
    color: #A0AEC0;
    font-weight: 600;
    transition: all 0.3s ease;
}
.sidebar-link i {
    color: #A0AEC0;
    font-size: 1.2rem;
    transition: color 0.3s ease, transform 0.3s ease;
}
.sidebar-link:hover {
    background-color: rgba(182, 255, 92, 0.05);
    color: #B6FF5C;
    transform: translateX(3px);
}
.sidebar-link:hover i {
    color: #B6FF5C;
    transform: scale(1.1);
}
.sidebar-link.active {
    background-color: rgba(182, 255, 92, 0.1);
    color: #B6FF5C;
    font-weight: 700;
    border-left: 3px solid #B6FF5C;
}
.sidebar-link.active i { color: #B6FF5C; }

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
    background: #081412;
    border-bottom: 1px solid rgba(182, 255, 92, 0.1);
    z-index: 50;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 1.5rem;
    box-shadow: 0 3px 8px rgba(0,0,0,0.08);
}
header h1 {
    font-size: 1.6rem;
    font-weight: 700;
    color: #FFFFFF;
}

/* Buttons */
.btn-primary {
    @apply bg-[#081412] text-[#B6FF5C] font-bold rounded-xl px-6 py-2.5 transition-all duration-300 border border-[#B6FF5C]/20 shadow-lg hover:shadow-[#B6FF5C]/10 hover:-translate-y-0.5;
}
.btn-secondary {
    @apply bg-gray-100 text-gray-700 font-bold rounded-xl px-6 py-2.5 transition-all duration-300 hover:bg-gray-200;
}
.btn-danger {
    @apply bg-red-500 text-white font-bold rounded-xl px-6 py-2.5 transition-all duration-300 hover:bg-red-600 shadow-lg shadow-red-500/20;
}

/* Content */
.content-wrapper { padding: 6rem 2rem 2rem; background-color: #FFFFFF; }
.content-wrapper .card {
    background-color: #FFFFFF; 
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}
.content-wrapper .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

/* Table */
.table th {
    background-color: #F8FAFC;
    color: #475569;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 0.05em;
}
.table tr:hover { background-color: #F1F5F9; }

/* Scrollbar */
.sidebar-scroll::-webkit-scrollbar { width: 6px; }
.sidebar-scroll::-webkit-scrollbar-thumb { background-color: rgba(255, 255, 255, 0.1); border-radius: 3px; }
.sidebar-scroll::-webkit-scrollbar-track { background: transparent; }

@media (min-width: 1024px) {
    header { left: 18rem; }
    .sidebar { transform: none !important; }
}
</style>
</head>
<body class="flex h-screen overflow-hidden">


<aside id="sidebar" class="sidebar fixed lg:static top-0 left-0 h-full w-72 flex flex-col justify-between shadow-md z-40 sidebar-hidden lg:translate-x-0">
    <div>
        <div class="sidebar-title">Resident Portal</div>
        <nav class="mt-4 space-y-1 px-4 sidebar-scroll">
            <?php
            $links = [
                ['Home', 'resident.home', 'bi-speedometer2'],
                ['My Profile', 'resident.profile.index', 'bi-person-circle'],
                ['Payments & Dues', 'resident.dues.index', 'bi-cash-stack'],
                ['Announcements', 'resident.announcements.index', 'bi-megaphone-fill'],
                ['My Requests', 'resident.requests.index', 'bi-envelope-fill'],
                ['Amenities', 'resident.amenities.index', 'bi-building'],
                ['My Reservations', 'resident.my-reservations.index', 'bi-calendar-event'],
                ['Messages', 'resident.messages.index', 'bi-chat-left-text-fill'],
            ];
            ?>

            <?php $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $route, $icon]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route($route)); ?>" class="sidebar-link flex items-center <?php echo e(request()->routeIs($route) ? 'active' : ''); ?>">
                    <i class="<?php echo e($icon); ?>"></i>
                    <span><?php echo e($label); ?></span>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </nav>
    </div>


<form action="<?php echo e(route('logout')); ?>" method="POST" class="px-4 mb-6 mt-auto">
    <?php echo csrf_field(); ?>
    <button type="submit"
        class="w-full bg-blue-900 text-white font-semibold rounded-md px-4 py-2 hover:bg-blue-800 hover:text-white transition-all duration-200"
        onclick="return confirm('Are you sure you want to logout?');">
        <i class="bi bi-box-arrow-right mr-2"></i> Logout
    </button>
</form>

</aside>


<div id="overlay" class="fixed inset-0 bg-black bg-opacity-40 hidden lg:hidden z-30" onclick="toggleSidebar()"></div>


<main class="flex-1 flex flex-col overflow-y-auto transition-all duration-300">
    <header>
        <div class="flex items-center gap-3">
            <button id="menu-btn" class="lg:hidden text-gray-100 hover:text-gray-300 focus:outline-none" onclick="toggleSidebar()">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <h1><?php echo $__env->yieldContent('page-title'); ?></h1>
        </div>
    </header>

    <section class="content-wrapper">
        <div class="max-w-8xl mx-auto">
            <?php echo $__env->yieldContent('content'); ?>
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
<?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views\layouts\resident.blade.php ENDPATH**/ ?>