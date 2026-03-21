@extends('layouts.admin')

@section('title', 'Accounts Management')
@section('page-title', 'Accounts Management')

@section('content')

{{-- Header Stats/Summary --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
            <i class="bi bi-people-fill text-xl"></i>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Accounts</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ $accounts->count() }}</h3>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center text-green-600">
            <i class="bi bi-shield-check text-xl"></i>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Active Users</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ $accounts->where('active', true)->count() }}</h3>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
            <i class="bi bi-person-badge-fill text-xl"></i>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Admins</p>
            <h3 class="text-2xl font-bold text-gray-900">{{ $accounts->where('role', 'admin')->count() }}</h3>
        </div>
    </div>
</div>

{{-- Toolbar --}}
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    {{-- Search & Filter --}}
    <div class="flex flex-1 gap-3 max-w-2xl">
        <div class="relative flex-1">
            <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" placeholder="Search accounts..." class="w-full pl-11 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
        </div>
        <form method="GET" action="{{ route('admin.accounts.index') }}">
            <select name="status" onchange="this.form.submit()"
                class="h-full border border-gray-200 bg-white rounded-xl px-4 py-2.5 text-sm font-medium text-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 cursor-pointer hover:bg-gray-50 transition-colors">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </form>
    </div>

    {{-- Create Button --}}
    <a href="{{ route('admin.accounts.create') }}"
       class="inline-flex items-center justify-center gap-2 bg-gray-900 hover:bg-black text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
        <i class="bi bi-plus-lg"></i>
        <span>Create Account</span>
    </a>
</div>

{{-- Success Message --}}
@if(session('success'))
    <div class="bg-green-50 text-green-700 border border-green-200 p-4 rounded-xl mb-6 flex items-center gap-3">
        <i class="bi bi-check-circle-fill text-green-500"></i>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
@endif

{{-- Table Card --}}
<div class="bg-white shadow-sm rounded-2xl border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider w-[5%]">#</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider w-[35%]">User Details</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider w-[15%]">Role</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-center w-[15%]">Status</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right w-[10%]">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($accounts as $account)
                    <tr class="hover:bg-gray-50/80 transition-colors group">
                        
                        {{-- # --}}
                        <td class="px-6 py-4 text-sm text-gray-500 font-mono">
                            {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </td>

                        {{-- User Details --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-sm font-bold text-gray-600 border border-gray-100 shadow-sm">
                                    {{ substr($account->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">{{ $account->name }}</p>
                                    <p class="text-xs text-gray-500 font-medium">{{ $account->email }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Role --}}
                        <td class="px-6 py-4">
                            @if($account->role === 'admin')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-600 border border-purple-100">
                                    <i class="bi bi-shield-lock-fill"></i>
                                    Admin
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200">
                                    <i class="bi bi-person-fill"></i>
                                    Resident
                                </span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-4 text-center">
                            @if($account->active)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500 border border-gray-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                    Inactive
                                </span>
                            @endif
                        </td>

                        {{-- Actions (Kebab Menu) --}}
                        <td class="px-6 py-4 text-right">
                            <div class="relative inline-block text-left">
                                <button onclick="toggleActionMenu('menu-{{ $account->id }}')" 
                                        class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-all focus:outline-none focus:ring-2 focus:ring-gray-200">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>

                                {{-- Dropdown Menu --}}
                                <div id="menu-{{ $account->id }}" 
                                     class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden origin-top-right transform transition-all">
                                    
                                    <div class="px-4 py-3 border-b border-gray-50 bg-gray-50/50">
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Actions</p>
                                    </div>

                                    <div class="p-1">
                                        {{-- Reset Password --}}
                                        <form action="{{ route('admin.accounts.reset', $account->id) }}" method="POST"
                                              onsubmit="return confirm('Are you sure you want to reset the password for {{ $account->name }}?');">
                                            @csrf
                                            <button type="submit" class="w-full text-left flex items-center gap-3 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-900 rounded-lg transition-colors">
                                                <i class="bi bi-key text-yellow-500"></i>
                                                Reset Password
                                            </button>
                                        </form>

                                        {{-- Toggle Status --}}
                                        <form action="{{ route('admin.accounts.toggle', $account->id) }}" method="POST"
                                              onsubmit="return confirm('Are you sure you want to change the status of this account?');">
                                            @csrf
                                            @method('PUT')
                                            @if($account->active)
                                                <button type="submit" class="w-full text-left flex items-center gap-3 px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                                    <i class="bi bi-person-x"></i>
                                                    Deactivate Account
                                                </button>
                                            @else
                                                <button type="submit" class="w-full text-left flex items-center gap-3 px-3 py-2 text-sm font-medium text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                                                    <i class="bi bi-person-check"></i>
                                                    Activate Account
                                                </button>
                                            @endif
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="bi bi-person-x text-2xl text-gray-300"></i>
                                </div>
                                <p class="text-gray-500 font-medium">No accounts found</p>
                                <p class="text-xs text-gray-400 mt-1">Try adjusting your search or filters</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleActionMenu(menuId) {
        const menu = document.getElementById(menuId);
        const isHidden = menu.classList.contains('hidden');
        
        // Close all other menus first
        document.querySelectorAll('[id^="menu-"]').forEach(el => {
            el.classList.add('hidden');
        });

        // Toggle current menu
        if (isHidden) {
            menu.classList.remove('hidden');
        }
    }

    // Close menus when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.relative')) {
            document.querySelectorAll('[id^="menu-"]').forEach(el => {
                el.classList.add('hidden');
            });
        }
    });
</script>

@endsection
