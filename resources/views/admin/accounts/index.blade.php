@extends('layouts.admin')

@section('title', 'Accounts Management')
@section('page-title', 'Accounts Management')

@section('content')

<div class="space-y-8 animate-fade-in">

    {{-- ===================== --}}
    {{-- HEADER SECTION --}}
    {{-- ===================== --}}
    <div class="glass-card p-8 relative overflow-hidden group">
        {{-- Subtle gradient glow in background --}}
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-brand-accent/5 rounded-full blur-3xl group-hover:bg-brand-accent/10 transition-all duration-700"></div>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                    Accounts Management
                </h1>
                <p class="mt-2 text-gray-600 text-lg max-w-xl">
                    Manage system users, roles, and security credentials.
                </p>
            </div>

            <div class="flex items-center gap-3">
                @can('manage_users')
                    <a href="{{ route('admin.accounts.create') }}" class="btn-premium">
                        <i class="bi bi-person-plus-fill"></i>
                        Create Account
                    </a>
                @endcan
            </div>
        </div>
    </div>

    {{-- ===================== --}}
    {{-- SUMMARY STATS --}}
    {{-- ===================== --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Total Accounts --}}
        <div class="glass-card p-8 flex items-center gap-6 group hover:shadow-xl transition-all duration-300">
            <div class="w-16 h-16 rounded-[24px] bg-gray-50 flex items-center justify-center text-gray-400 group-hover:scale-110 group-hover:bg-emerald-50 group-hover:text-emerald-500 transition-all duration-500 border border-gray-100 shadow-sm">
                <i class="bi bi-people-fill text-2xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Total Accounts</p>
                <h3 class="text-3xl font-black text-gray-900 tracking-tight tabular-nums">{{ $accounts->count() }}</h3>
            </div>
        </div>
        
        {{-- Active Users --}}
        <div class="glass-card p-8 flex items-center gap-6 group hover:shadow-xl transition-all duration-300">
            <div class="w-16 h-16 rounded-[24px] bg-emerald-50 flex items-center justify-center text-emerald-500 group-hover:scale-110 transition-all duration-500 border border-emerald-100/50 shadow-sm">
                <i class="bi bi-shield-check text-2xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.2em] mb-1">Active Users</p>
                <h3 class="text-3xl font-black text-gray-900 tracking-tight tabular-nums">{{ $accounts->where('active', true)->count() }}</h3>
            </div>
        </div>

        {{-- System Users --}}
        <div class="glass-card p-8 flex items-center gap-6 group hover:shadow-xl transition-all duration-300">
            <div class="w-16 h-16 rounded-[24px] bg-gray-900 flex items-center justify-center text-brand-accent group-hover:scale-110 transition-all duration-500 border border-white/10 shadow-lg">
                <i class="bi bi-person-badge-fill text-2xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">System Users</p>
                <h3 class="text-3xl font-black text-gray-900 tracking-tight tabular-nums">{{ $accounts->where('role', 'admin')->count() }}</h3>
            </div>
        </div>
    </div>

    {{-- ===================== --}}
    {{-- TOOLBAR SECTION --}}
    {{-- ===================== --}}
    <div class="glass-card p-4 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        {{-- Search Bar --}}
        <div class="flex-1 max-w-md">
            <div class="relative group">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-600 transition-colors"></i>
                <input type="text" id="accountSearch" onkeyup="filterAccounts(this)" 
                    placeholder="Search name or email..." 
                    class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all placeholder-gray-400">
            </div>
        </div>

        {{-- Filters & Toggles --}}
        <div class="flex flex-wrap items-center gap-3">
            {{-- Status Filter --}}
            <div class="relative group/filter">
                <select id="statusFilter" onchange="filterByStatus(this.value)"
                    class="h-11 px-4 flex items-center gap-2 rounded-xl border border-gray-200 bg-white text-[10px] font-black uppercase tracking-widest text-gray-600 hover:border-emerald-500/30 hover:bg-gray-50 transition-all outline-none appearance-none cursor-pointer pr-10">
                    <option value="all">All Status</option>
                    <option value="active">Active Only</option>
                    <option value="inactive">Inactive Only</option>
                </select>
                <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[8px] opacity-50 pointer-events-none"></i>
            </div>

            {{-- Role Filter --}}
            <div class="relative group/filter">
                <select id="roleFilter" onchange="filterByRole(this.value)"
                    class="h-11 px-4 flex items-center gap-2 rounded-xl border border-gray-200 bg-white text-[10px] font-black uppercase tracking-widest text-gray-600 hover:border-emerald-500/30 hover:bg-gray-50 transition-all outline-none appearance-none cursor-pointer pr-10">
                    <option value="all">All Roles</option>
                    <option value="super_admin">Super Admin</option>
                    <option value="admin">Admin</option>
                    <option value="staff">Staff</option>
                    <option value="auditor">Auditor</option>
                    <option value="resident">Resident</option>
                </select>
                <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[8px] opacity-50 pointer-events-none"></i>
            </div>
        </div>
    </div>

    {{-- ===================== --}}
    {{-- SUCCESS MESSAGE --}}
    {{-- ===================== --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 p-6 rounded-[24px] flex items-center gap-4 animate-zoom-in">
            <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-200">
                <i class="bi bi-check-lg text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-emerald-900 uppercase tracking-widest">Success</p>
                <p class="text-sm font-bold text-emerald-600">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- ===================== --}}
    {{-- ACCOUNTS TABLE --}}
    {{-- ===================== --}}
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest w-[5%]">#</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest w-[35%]">User Details</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest w-[20%]">System Role</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center w-[20%]">Status</th>
                        <th class="px-8 py-5 w-[10%]"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50" id="accountsTableBody">
                    @forelse($accounts as $account)
                        <tr class="account-row group hover:bg-emerald-50/30 transition-all duration-300 border-l-4 border-transparent hover:border-emerald-500" 
                            data-name="{{ strtolower($account->name) }}" 
                            data-email="{{ strtolower($account->email) }}"
                            data-status="{{ $account->active ? 'active' : 'inactive' }}"
                            data-role="{{ $account->role === 'resident' ? 'resident' : ($account->rbacRole->name ?? 'admin') }}">
                            
                            {{-- # --}}
                            <td class="px-8 py-6 text-[10px] font-black text-gray-400 tabular-nums">
                                {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </td>

                            {{-- User Details --}}
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-[16px] bg-gray-50 flex items-center justify-center text-sm font-black text-gray-600 border border-gray-100 shadow-sm group-hover:scale-110 group-hover:bg-emerald-50 group-hover:text-emerald-600 transition-all duration-500">
                                        {{ substr($account->name, 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-black text-gray-900 group-hover:text-emerald-700 transition-colors truncate">{{ $account->name }}</p>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tight truncate">{{ $account->email }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Role --}}
                            <td class="px-8 py-6">
                                @if($account->role === 'resident')
                                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-[9px] font-black uppercase tracking-widest bg-gray-50 text-gray-600 border border-gray-100">
                                        <i class="bi bi-person-fill"></i>
                                        Resident
                                    </span>
                                @else
                                    @php
                                        $roleName = $account->rbacRole->name ?? 'admin';
                                        $roleStyle = match($roleName) {
                                            'super_admin' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                            'admin' => 'bg-blue-50 text-blue-700 border-blue-100',
                                            'staff' => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                                            'auditor' => 'bg-gray-50 text-gray-600 border-gray-100',
                                            default => 'bg-gray-50 text-gray-600 border-gray-100',
                                        };
                                        $roleIcon = match($roleName) {
                                            'super_admin' => 'bi-shield-check',
                                            'admin' => 'bi-shield-lock-fill',
                                            'staff' => 'bi-person-workspace',
                                            'auditor' => 'bi-clipboard-check',
                                            default => 'bi-shield-lock',
                                        };
                                    @endphp
                                    <span title="User Role determines access level" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $roleStyle }}">
                                        <i class="bi {{ $roleIcon }}"></i>
                                        {{ strtoupper(str_replace('_',' ', $roleName)) }}
                                    </span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="px-8 py-6 text-center">
                                @if($account->active)
                                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-[9px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-[9px] font-black uppercase tracking-widest bg-gray-50 text-gray-400 border border-gray-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-8 py-6 text-right">
                                <div x-data="{open:false}" class="relative inline-block text-left">
                                    <button @click="open = !open" @click.outside="open = false"
                                            class="w-10 h-10 rounded-xl flex items-center justify-center bg-white border border-gray-100 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 hover:border-emerald-100 transition-all shadow-sm">
                                        <i class="bi bi-three-dots-vertical text-lg"></i>
                                    </button>

                                    {{-- Dropdown Menu --}}
                                    <div x-show="open" x-transition 
                                         class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden origin-top-right p-1">
                                        
                                        <div class="px-4 py-3 border-b border-gray-50 bg-gray-50/50 mb-1">
                                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Account Controls</p>
                                        </div>

                                        <div class="space-y-1">
                                            {{-- Reset Password --}}
                                            <form action="{{ route('admin.accounts.reset', $account->id) }}" method="POST"
                                                  onsubmit="return confirm('Are you sure you want to reset the password for {{ $account->name }}?');">
                                                @csrf
                                                <button type="submit" class="w-full text-left flex items-center gap-3 px-4 py-3 text-[10px] font-black uppercase tracking-widest text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition-all">
                                                    <i class="bi bi-key-fill text-emerald-500"></i>
                                                    Reset Password
                                                </button>
                                            </form>

                                            {{-- Toggle Status --}}
                                            <form action="{{ route('admin.accounts.toggle', $account->id) }}" method="POST"
                                                  onsubmit="return confirm('Are you sure you want to change the status of this account?');">
                                                @csrf
                                                @method('PUT')
                                                @if($account->active)
                                                    <button type="submit" class="w-full text-left flex items-center gap-3 px-4 py-3 text-[10px] font-black uppercase tracking-widest text-red-500 hover:bg-red-50 rounded-xl transition-all">
                                                        <i class="bi bi-person-x-fill"></i>
                                                        Deactivate
                                                    </button>
                                                @else
                                                    <button type="submit" class="w-full text-left flex items-center gap-3 px-4 py-3 text-[10px] font-black uppercase tracking-widest text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all">
                                                        <i class="bi bi-person-check-fill"></i>
                                                        Activate
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
                            <td colspan="5" class="px-8 py-24 text-center">
                                <div class="flex flex-col items-center justify-center animate-zoom-in">
                                    <div class="w-24 h-24 bg-gray-50 rounded-[32px] flex items-center justify-center mb-6 text-gray-200 shadow-inner">
                                        <i class="bi bi-person-x text-5xl"></i>
                                    </div>
                                    <h3 class="text-2xl font-black text-gray-900 tracking-tight uppercase">No accounts found</h3>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mt-3 max-w-xs mx-auto">Try adjusting your search or filters to find what you're looking for</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function filterAccounts(input) {
    const filter = input.value.toLowerCase();
    const rows = document.querySelectorAll('.account-row');
    
    rows.forEach(row => {
        const name = row.getAttribute('data-name');
        const email = row.getAttribute('data-email');
        if (name.includes(filter) || email.includes(filter)) {
            row.classList.remove('hidden');
        } else {
            row.classList.add('hidden');
        }
    });
}

function filterByStatus(status) {
    const rows = document.querySelectorAll('.account-row');
    rows.forEach(row => {
        if (status === 'all' || row.getAttribute('data-status') === status) {
            row.classList.remove('hidden');
        } else {
            row.classList.add('hidden');
        }
    });
}

function filterByRole(role) {
    const rows = document.querySelectorAll('.account-row');
    rows.forEach(row => {
        if (role === 'all' || row.getAttribute('data-role') === role) {
            row.classList.remove('hidden');
        } else {
            row.classList.add('hidden');
        }
    });
}
</script>

<style>
    @keyframes zoomIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-zoom-in {
        animation: zoomIn 0.2s ease-out forwards;
    }
</style>

@endsection
