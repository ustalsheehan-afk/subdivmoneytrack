@extends('layouts.admin')

@section('title', 'Activity Logs')
@section('page-title', 'Activity Logs')

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
                    Activity Logs
                </h1>
                <p class="mt-2 text-gray-600 text-lg max-w-xl">
                    System audit trail and administrative transparency records.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 shadow-sm border border-emerald-100">
                    <i class="bi bi-journal-text text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== --}}
    {{-- TOOLBAR / FILTER SECTION --}}
    {{-- ===================== --}}
    <div class="glass-card p-4">
        <form action="{{ route('admin.system.activity-logs.index') }}" method="GET" class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="flex flex-wrap items-center gap-4 flex-1">
                {{-- Module Filter --}}
                <div class="relative group min-w-[200px]">
                    <i class="bi bi-filter absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                    <select name="module" onchange="this.form.submit()" 
                        class="w-full pl-11 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-600 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all appearance-none cursor-pointer">
                        <option value="">All Modules</option>
                        <option value="dues" {{ request('module') == 'dues' ? 'selected' : '' }}>Dues</option>
                        <option value="payments" {{ request('module') == 'payments' ? 'selected' : '' }}>Payments</option>
                        <option value="reservations" {{ request('module') == 'reservations' ? 'selected' : '' }}>Reservations</option>
                        <option value="requests" {{ request('module') == 'requests' ? 'selected' : '' }}>Requests</option>
                        <option value="messages" {{ request('module') == 'messages' ? 'selected' : '' }}>Messages</option>
                    </select>
                    <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[8px] opacity-50 pointer-events-none"></i>
                </div>

                {{-- Action Filter --}}
                <div class="relative group min-w-[200px]">
                    <i class="bi bi-lightning absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-500 transition-colors"></i>
                    <select name="action" onchange="this.form.submit()" 
                        class="w-full pl-11 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-600 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all appearance-none cursor-pointer">
                        <option value="">All Actions</option>
                        <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                        <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated</option>
                        <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                        <option value="approved" {{ request('action') == 'approved' ? 'selected' : '' }}>Approved</option>
                    </select>
                    <i class="bi bi-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-[8px] opacity-50 pointer-events-none"></i>
                </div>
            </div>

            {{-- Reset Button --}}
            @if(request('module') || request('action'))
                <a href="{{ route('admin.system.activity-logs.index') }}" class="btn-secondary px-6">
                    <i class="bi bi-arrow-counterclockwise"></i>
                    Reset Filters
                </a>
            @endif
        </form>
    </div>

    {{-- ===================== --}}
    {{-- ACTIVITY TIMELINE --}}
    {{-- ===================== --}}
    <div class="glass-card overflow-hidden">
        <div class="divide-y divide-gray-50">
            @forelse($logs as $log)
                <div class="p-8 flex items-start gap-6 hover:bg-emerald-50/20 transition-all group border-l-4 border-transparent hover:border-emerald-500">
                    <div class="relative shrink-0">
                        <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-emerald-50 group-hover:text-emerald-600 transition-all duration-500 border border-gray-100 shadow-sm">
                            <i class="bi 
                                @if($log->module == 'dues') bi-receipt
                                @elseif($log->module == 'payments') bi-cash-stack
                                @elseif($log->module == 'reservations') bi-calendar-check
                                @elseif($log->module == 'requests') bi-tools
                                @elseif($log->module == 'messages') bi-chat-left-dots
                                @else bi-journal-text @endif text-2xl"></i>
                        </div>
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 mb-2">
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-black text-gray-900 uppercase tracking-tight group-hover:text-emerald-700 transition-colors">
                                    {{ $log->causer->name ?? ($log->causer->full_name ?? 'System') }}
                                </span>
                                <span class="px-3 py-1 bg-emerald-50 text-[9px] font-black text-emerald-600 rounded-full uppercase tracking-widest border border-emerald-100/50">
                                    {{ $log->action }}
                                </span>
                            </div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                                <i class="bi bi-clock text-emerald-500"></i>
                                {{ $log->created_at->format('M d, Y • h:i A') }}
                            </span>
                        </div>
                        <p class="text-base text-gray-600 font-medium leading-relaxed">{{ $log->description }}</p>
                        
                        @if($log->metadata)
                            <div class="mt-4 p-4 bg-gray-900 rounded-2xl border border-white/10 overflow-hidden shadow-inner group/meta">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[9px] font-black text-emerald-400 uppercase tracking-widest">Metadata Payload</span>
                                    <i class="bi bi-braces text-emerald-500/50"></i>
                                </div>
                                <pre class="text-[11px] text-gray-300 font-mono overflow-x-auto custom-scrollbar-dark">{{ json_encode($log->metadata, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-24 text-center">
                    <div class="w-24 h-24 bg-gray-50 rounded-[32px] flex items-center justify-center mx-auto mb-6 text-gray-200 shadow-inner">
                        <i class="bi bi-journals text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 tracking-tight mb-2 uppercase">No Logs Found</h3>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">The activity log is currently empty.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-8">
        {{ $logs->links() }}
    </div>
</div>

<style>
.custom-scrollbar-dark::-webkit-scrollbar { width: 4px; height: 4px; }
.custom-scrollbar-dark::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); border-radius: 10px; }
.custom-scrollbar-dark::-webkit-scrollbar-thumb { background: rgba(182,255,92,0.2); border-radius: 10px; }
.custom-scrollbar-dark::-webkit-scrollbar-thumb:hover { background: rgba(182,255,92,0.4); }
</style>

    <div class="mt-8">
        {{ $logs->links() }}
    </div>
</div>
@endsection
