@php
    $accentColor = match($announcement->category) {
        'Maintenance' => '#E6B566',
        'Meeting'     => '#7DA2D6',
        'Event'       => '#7FB69A',
        'Security'    => '#8B8F9C',
        'Finance'     => '#8FAE9E',
        'Emergency'   => '#C97A7A',
        default       => '#94a3b8',
    };
    
    $icon = match($announcement->category) {
        'Maintenance' => 'bi-tools',
        'Meeting' => 'bi-people-fill',
        'Event' => 'bi-calendar-event-fill',
        'Security' => 'bi-shield-lock-fill',
        'Finance' => 'bi-cash-stack',
        'Emergency' => 'bi-exclamation-octagon-fill',
        default => 'bi-megaphone-fill',
    };

    $readersCount = $announcement->readers()->count();
    $totalResidents = \App\Models\Resident::where('status', 'active')->count();
    $isPinned = $announcement->is_pinned;
@endphp

<div @click="openModal({{ $announcement->id }}, '{{ addslashes($announcement->title) }}', '{{ addslashes(nl2br(e($announcement->content))) }}', '{{ $announcement->created_at->format('M d, Y • g:i A') }}', '{{ $announcement->category }}', '{{ $accentColor }}', '{{ $icon }}', '{{ $announcement->image }}')"
     class="glass-card group relative overflow-hidden transition-all duration-300 animate-fade-in cursor-pointer border border-gray-100 hover:border-emerald-500/20 shadow-sm hover:shadow-md">
    
    {{-- Left Accent Line --}}
    <div class="absolute left-0 top-0 bottom-0 w-[4px] group-hover:w-[6px] transition-all duration-300" 
         style="background-color: {{ $isPinned ? '#10B981' : $accentColor }}"></div>

    <div class="p-6 pl-8 flex flex-col md:flex-row md:items-center gap-6">
        
        {{-- Selection Checkbox (Visible only in selection mode) --}}
        <template x-if="selectionMode">
            <div class="shrink-0" @click.stop>
                <input type="checkbox" 
                       :value="{{ $announcement->id }}" 
                       x-model="selected"
                       class="w-5 h-5 rounded-lg border-gray-300 text-emerald-600 focus:ring-emerald-500/20 transition-all cursor-pointer">
            </div>
        </template>

        {{-- Icon & Category --}}
        <div class="shrink-0 flex flex-row md:flex-col items-center gap-3">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 shadow-sm border border-gray-100 group-hover:scale-105 transition-all duration-300"
                 style="background-color: {{ $accentColor }}10; color: {{ $accentColor }}">
                <i class="bi {{ $icon }} text-2xl"></i>
            </div>
        </div>

        {{-- Content --}}
        <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-2 mb-2">
                <span class="text-[10px] font-black uppercase tracking-[0.1em]" style="color: {{ $accentColor }}">
                    {{ $announcement->category }}
                </span>
                <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                    {{ $announcement->created_at->format('M d, Y') }}
                </span>
                
                @if($isPinned)
                    <span class="badge-standard bg-emerald-50 text-emerald-600 border border-emerald-100 ml-auto md:ml-0">
                        <i class="bi bi-pin-angle-fill mr-1"></i> Pinned
                    </span>
                @endif

                @if($announcement->priority && $announcement->priority !== 'fyi')
                    <span class="badge-standard 
                        {{ $announcement->priority === 'urgent' ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-amber-50 text-amber-600 border border-amber-100' }}">
                        {{ $announcement->priority }}
                    </span>
                @endif
            </div>

            <h4 class="text-lg font-bold text-gray-900 leading-tight group-hover:text-emerald-600 transition-colors duration-300 mb-1">
                {{ $announcement->title }}
            </h4>
            
            <p class="text-sm text-gray-600 leading-relaxed font-medium line-clamp-1 opacity-80 group-hover:opacity-100 transition-opacity">
                {{ $announcement->content }}
            </p>

            <div class="flex items-center gap-4 mt-3">
                <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    <i class="bi bi-eye-fill text-emerald-600/50"></i>
                    <span>{{ $readersCount }} / {{ $totalResidents }} Seen</span>
                </div>
                <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    <i class="bi bi-clock-history"></i>
                    <span>{{ $announcement->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 -translate-x-2 group-hover:translate-x-0" @click.stop>
            @if(isset($isTrashedView) && $isTrashedView)
                {{-- TRASHED VIEW ACTIONS --}}
                <form action="{{ route('admin.announcements.restore', $announcement) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" title="Restore" class="w-10 h-10 rounded-xl bg-gray-50 text-gray-400 hover:bg-emerald-50 hover:text-emerald-600 border border-gray-100 transition-all duration-200 flex items-center justify-center">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </form>

                <form action="{{ route('admin.announcements.forceDelete', $announcement) }}" method="POST" onsubmit="return confirm('PERMANENTLY DELETE? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" title="Delete Permanently" class="w-10 h-10 rounded-xl bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-600 border border-gray-100 transition-all duration-200 flex items-center justify-center">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </form>
            @elseif(isset($isArchivedView) && $isArchivedView)
                {{-- ARCHIVED VIEW ACTIONS --}}
                <form action="{{ route('admin.announcements.restore', $announcement) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" title="Restore to Active" class="w-10 h-10 rounded-xl bg-gray-50 text-gray-400 hover:bg-emerald-50 hover:text-emerald-600 border border-gray-100 transition-all duration-200 flex items-center justify-center">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </form>

                <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" onsubmit="return confirm('Move to trash?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" title="Move to Trash" class="w-10 h-10 rounded-xl bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-600 border border-gray-100 transition-all duration-200 flex items-center justify-center">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            @else
                {{-- ACTIVE VIEW ACTIONS --}}
                <form action="{{ route('admin.announcements.togglePin', $announcement) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-200 {{ $isPinned ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-gray-50 text-gray-400 hover:bg-emerald-50 hover:text-emerald-600 border border-gray-100' }}">
                        <i class="bi bi-pin-angle"></i>
                    </button>
                </form>
                
                <a href="{{ route('admin.announcements.edit', $announcement) }}"
                   class="w-10 h-10 rounded-xl bg-gray-50 text-gray-400 hover:bg-emerald-50 hover:text-emerald-600 border border-gray-100 transition-all duration-200 flex items-center justify-center">
                    <i class="bi bi-pencil"></i>
                </a>

                <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" onsubmit="return confirm('Move to trash?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-10 h-10 rounded-xl bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-600 border border-gray-100 transition-all duration-200 flex items-center justify-center">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

