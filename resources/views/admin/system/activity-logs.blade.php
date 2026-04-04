@extends('layouts.admin')

@section('title', 'Activity Logs & Monitoring')
@section('page-title', 'Activity Logs & Monitoring')

@section('content')
<div class="space-y-8 animate-fade-in" x-data="activityLogs()">
    <div class="glass-card p-8 relative overflow-hidden">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900">Activity Logs & Monitoring</h1>
                <p class="mt-1 text-gray-600 text-sm">Auditability, monitoring, and administrator accountability</p>
            </div>
            <div class="flex items-center gap-4">
                <button class="w-11 h-11 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-emerald-600 hover:border-emerald-200 transition">
                    <i class="bi bi-bell-fill"></i>
                </button>
                <div class="flex items-center gap-3 px-4 py-2 rounded-2xl bg-white border border-gray-200">
                    <div class="w-8 h-8 rounded-full bg-gray-900 text-[#B6FF5C] flex items-center justify-center text-xs font-black">SA</div>
                    <div class="leading-tight">
                        <div class="text-sm font-black text-gray-900">Super Admin</div>
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Administrator</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @php
        $todayCount = \App\Models\ActivityLog::whereDate('created_at', now())->count();
        $failedLogins = \App\Models\ActivityLog::where('action', 'failed_login')->whereBetween('created_at', [now()->subDay(), now()])->count();
        $activeAdmins = \App\Models\ActivityLog::whereBetween('created_at', [now()->subDay(), now()])->where('causer_type', \App\Models\User::class)->distinct('causer_id')->count('causer_id');
        $topModule = \App\Models\ActivityLog::selectRaw('module, COUNT(*) as c')->whereBetween('created_at', [now()->subDay(), now()])->groupBy('module')->orderByDesc('c')->first();
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="glass-card p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100"><i class="bi bi-graph-up"></i></div>
            <div>
                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Actions Today</div>
                <div class="text-3xl font-black text-gray-900">{{ number_format($todayCount) }}</div>
                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Last 24 hours</div>
            </div>
        </div>
        <div class="glass-card p-6 flex items-center gap-4 {{ $failedLogins > 0 ? 'border border-red-100' : '' }}">
            <div class="w-12 h-12 rounded-2xl {{ $failedLogins > 0 ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-gray-50 text-gray-400 border border-gray-100' }} flex items-center justify-center"><i class="bi bi-exclamation-octagon"></i></div>
            <div>
                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Failed Login Attempts</div>
                <div class="text-3xl font-black {{ $failedLogins > 0 ? 'text-red-600' : 'text-gray-900' }}">{{ number_format($failedLogins) }}</div>
                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Last 24 hours</div>
            </div>
        </div>
        <div class="glass-card p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Active Admin Users</div>
                <div class="text-3xl font-black text-gray-900">{{ number_format($activeAdmins) }}</div>
                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Last 24 hours</div>
            </div>
        </div>
        <div class="glass-card p-6 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100"><i class="bi bi-sliders"></i></div>
            <div>
                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Most Modified Module</div>
                <div class="text-xl font-black text-gray-900">{{ $topModule->module ?? 'N/A' }}</div>
                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Last 24 hours</div>
            </div>
        </div>
    </div>
    <div class="glass-card p-4">
        <div class="flex flex-col lg:flex-row lg:items-center gap-4">
            <div class="relative flex-1">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input x-model="search" type="text" placeholder="Search by user, action, or ID" class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-700 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 outline-none">
            </div>
            <div class="flex items-center gap-2">
                <div class="relative">
                    <button @click="toggle('action')" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-600 hover:bg-gray-50">All Actions <i class="bi bi-chevron-down ml-1"></i></button>
                </div>
                <div class="relative">
                    <button @click="toggle('module')" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-600 hover:bg-gray-50">All Modules <i class="bi bi-chevron-down ml-1"></i></button>
                </div>
                <div class="relative">
                    <button @click="toggle('role')" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-600 hover:bg-gray-50">All Users <i class="bi bi-chevron-down ml-1"></i></button>
                </div>
                <div class="relative">
                    <button @click="toggle('range')" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-600 hover:bg-gray-50">Last 7 Days <i class="bi bi-chevron-down ml-1"></i></button>
                </div>
                <a href="{{ route('admin.system.activity-logs.index') }}" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-600 hover:bg-gray-50">Clear Filters</a>
                @can('export_logs')
                    <a href="{{ route('admin.system.activity-logs.export', request()->query()) }}" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-700">Export Logs</a>
                @endcan
            </div>
        </div>
    </div>
    <div class="glass-card overflow-hidden">
        <div class="overflow-auto">
            <table class="min-w-full text-sm">
                <thead class="sticky top-0 bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Time</th>
                        <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">User</th>
                        <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Action</th>
                        <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Module</th>
                        <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Description</th>
                        <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">IP Address</th>
                        <th class="px-6 py-3 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($logs as $log)
                        @php
                            $actionMap = [
                                'created' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'border' => 'border-emerald-100', 'icon' => 'bi-check-circle'],
                                'updated' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-100', 'icon' => 'bi-pencil-fill'],
                                'payment' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-600', 'border' => 'border-yellow-100', 'icon' => 'bi-cash-stack'],
                                'deleted' => ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'border' => 'border-red-100', 'icon' => 'bi-trash-fill'],
                                'warning' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-600', 'border' => 'border-orange-100', 'icon' => 'bi-exclamation-triangle-fill'],
                            ];
                            $conf = $actionMap[$log->action] ?? $actionMap['updated'];
                            $userName = $log->causer->name ?? ($log->causer->full_name ?? 'System');
                            $userRole = method_exists($log->causer ?? null, 'role') ? ($log->causer->role ?? 'user') : 'user';
                            $ip = $log->metadata['ip'] ?? '—';
                        @endphp
                        <tr x-show="matchesSearch('{{ strtolower($userName.' '.$log->action.' '.$log->id) }}')">
                            <td class="px-6 py-4 text-gray-500">{{ $log->created_at->format('M d, Y • h:i A') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gray-900 text-[#B6FF5C] flex items-center justify-center text-xs font-black">{{ strtoupper(substr($userName,0,1)) }}</div>
                                    <div class="leading-tight">
                                        <div class="text-sm font-black text-gray-900">{{ strtoupper($userName) }}</div>
                                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $userRole }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $conf['bg'] }} {{ $conf['text'] }} {{ $conf['border'] }}"><i class="bi {{ $conf['icon'] }}"></i> {{ ucfirst($log->action) }}</span>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-900">{{ ucfirst($log->module) }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $log->description }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $ip }}</td>
                            <td class="px-6 py-4 text-right">
                                <button @click="openDetails({{ json_encode(['id'=>$log->id,'user'=>$userName,'role'=>$userRole,'action'=>$log->action,'module'=>$log->module,'description'=>$log->description,'ip'=>$ip,'time'=>$log->created_at->format('M d, Y • h:i A'),'metadata'=>$log->metadata]) }})" class="px-4 py-2 bg-gray-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-black">View Details</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-24 text-center text-gray-400 font-bold">No logs found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $logs->links() }}</div>
    </div>
    <div x-show="drawerOpen" x-cloak class="fixed inset-0 z-[100]">
        <div class="absolute inset-0 bg-black/50" @click="drawerOpen=false"></div>
        <div class="absolute right-0 top-0 bottom-0 w-full max-w-2xl bg-white shadow-2xl border-l border-gray-100 flex flex-col">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gray-900 text-[#B6FF5C] flex items-center justify-center text-sm font-black" x-text="detailsAvatar"></div>
                    <div>
                        <div class="text-lg font-black text-gray-900" x-text="details.user"></div>
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest" x-text="details.role"></div>
                    </div>
                </div>
                <button @click="drawerOpen=false" class="w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-400 hover:text-gray-600 hover:bg-gray-50"><i class="bi bi-x-lg text-xs"></i></button>
            </div>
            <div class="p-6 space-y-6 overflow-y-auto">
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Action</div>
                        <div class="text-sm font-black text-gray-900" x-text="capitalize(details.action)"></div>
                    </div>
                    <div class="space-y-1">
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Module</div>
                        <div class="text-sm font-black text-gray-900" x-text="capitalize(details.module)"></div>
                    </div>
                    <div class="space-y-1">
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">IP Address</div>
                        <div class="text-sm font-black text-gray-900" x-text="details.ip"></div>
                    </div>
                    <div class="space-y-1">
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Timestamp</div>
                        <div class="text-sm font-black text-gray-900" x-text="details.time"></div>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Description</div>
                    <div class="text-sm text-gray-700" x-text="details.description"></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-show="details.metadata && (details.metadata.before || details.metadata.after)">
                    <div class="space-y-2">
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Before</div>
                        <pre class="p-4 bg-gray-50 border border-gray-100 rounded-xl text-xs overflow-x-auto" x-text="formatJSON(details.metadata?.before)"></pre>
                    </div>
                    <div class="space-y-2">
                        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">After</div>
                        <pre class="p-4 bg-gray-50 border border-gray-100 rounded-xl text-xs overflow-x-auto" x-text="formatJSON(details.metadata?.after)"></pre>
                    </div>
                </div>
                <div class="space-y-2" x-show="details.metadata && !(details.metadata.before || details.metadata.after)">
                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Metadata</div>
                    <pre class="p-4 bg-gray-50 border border-gray-100 rounded-xl text-xs overflow-x-auto" x-text="formatJSON(details.metadata)"></pre>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
function activityLogs() {
    return {
        search: '',
        drawerOpen: false,
        details: {},
        get detailsAvatar() { return (this.details.user || 'U')[0]?.toUpperCase() || 'U' },
        matchesSearch(s) { return s.includes(this.search.toLowerCase()) },
        openDetails(d) { this.details = d; this.drawerOpen = true },
        formatJSON(obj) { try { return JSON.stringify(obj ?? {}, null, 2) } catch(e) { return '—' } },
        capitalize(s) { return (s||'').charAt(0).toUpperCase()+ (s||'').slice(1) },
        toggle() {}
    }
}
</script>
@endpush
@endsection
