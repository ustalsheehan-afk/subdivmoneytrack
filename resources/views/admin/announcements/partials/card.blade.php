{{-- Color Accent Bar --}}
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
@endphp

<div @click="openModal({{ $announcement->id }}, '{{ addslashes($announcement->title) }}', '{{ addslashes(nl2br(e($announcement->content))) }}', '{{ $announcement->created_at->format('M d, Y • g:i A') }}', '{{ $announcement->category }}', '{{ $accentColor }}', '{{ $icon }}', '{{ $announcement->image }}')"
     class="group relative bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:border-blue-200 transition-all duration-300 overflow-hidden flex flex-col h-full animate-fade-in cursor-pointer">
    
    <div class="absolute left-0 top-0 bottom-0 w-[6px] group-hover:w-[10px] transition-all duration-300" style="background-color: {{ $accentColor }}"></div>

    <div class="p-6 pl-8 flex-1 flex flex-col">
        {{-- Top Meta Section --}}
        <div class="flex items-start justify-between mb-5">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 shadow-sm group-hover:scale-110 transition-transform duration-300"
                     style="background-color: {{ $accentColor }}20; color: {{ $accentColor }}">
                    <i class="bi {{ $icon }} text-xl"></i>
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-black uppercase tracking-widest" style="color: {{ $accentColor }}">
                            {{ $announcement->category }}
                        </span>
                        @if($announcement->is_pinned)
                            <span class="px-2 py-0.5 rounded-full bg-orange-50 text-orange-600 text-[9px] font-black uppercase tracking-tighter border border-orange-100 shadow-sm flex items-center gap-1">
                                <i class="bi bi-pin-angle-fill"></i> Pinned
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 text-[11px] text-gray-400 font-bold uppercase tracking-wider">
                        <span>{{ $announcement->created_at->format('M d, Y') }}</span>
                        <span>•</span>
                        <span class="text-blue-500 flex items-center gap-1">
                            <i class="bi bi-eye-fill"></i> {{ $readersCount }} / {{ $totalResidents }} Seen
                        </span>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <form action="{{ route('admin.announcements.togglePin', $announcement) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" title="{{ $announcement->is_pinned ? 'Unpin' : 'Pin' }}" 
                        class="w-9 h-9 rounded-xl flex items-center justify-center transition-all duration-200 {{ $announcement->is_pinned ? 'bg-orange-50 text-orange-600 border border-orange-100' : 'bg-gray-50 text-gray-400 hover:bg-orange-50 hover:text-orange-600' }}">
                        <i class="bi bi-pin-angle text-sm"></i>
                    </button>
                </form>
                
                <a href="{{ route('admin.announcements.edit', $announcement) }}" title="Edit"
                    class="w-9 h-9 rounded-xl bg-gray-50 text-gray-400 hover:bg-blue-50 hover:text-blue-600 transition-all duration-200 flex items-center justify-center">
                    <i class="bi bi-pencil text-sm"></i>
                </a>

                <button type="button" onclick="confirmDelete({{ $announcement->id }})" title="Delete"
                    class="w-9 h-9 rounded-xl bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-600 transition-all duration-200 flex items-center justify-center">
                    <i class="bi bi-trash text-sm"></i>
                </button>
            </div>
        </div>

        {{-- Content Section --}}
        <div class="flex-1 space-y-4">
            <h4 class="text-gray-900 font-black text-xl leading-tight group-hover:text-blue-600 transition-colors duration-300">
                {{ $announcement->title }}
            </h4>
            
            <p class="text-sm text-gray-500 leading-relaxed font-medium line-clamp-3">
                {{ Str::limit($announcement->content, 180) }}
            </p>

            @if($announcement->image)
                <div class="relative w-full h-44 rounded-2xl overflow-hidden border border-gray-100 shadow-sm mt-4 group/img">
                    <img src="{{ Storage::url($announcement->image) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover/img:scale-110" alt="{{ $announcement->title }}">
                    <div class="absolute inset-0 bg-black/5 group-hover/img:bg-transparent transition-colors"></div>
                </div>
            @endif
        </div>

        {{-- Bottom Meta --}}
        <div class="mt-6 pt-5 border-t border-gray-50 flex items-center justify-between text-[11px] font-bold text-gray-400 uppercase tracking-widest">
            <div class="flex items-center gap-2">
                <i class="bi bi-clock-history"></i>
                <span>{{ $announcement->created_at->diffForHumans() }}</span>
            </div>
            
            @if($announcement->priority && $announcement->priority !== 'fyi')
                <span class="px-2.5 py-1 rounded-lg {{ $announcement->priority === 'urgent' ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-amber-50 text-amber-600 border border-amber-100' }} shadow-sm">
                    {{ $announcement->priority }}
                </span>
            @endif
        </div>
    </div>
</div>
