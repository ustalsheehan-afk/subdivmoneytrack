@php
    $allowed = !isset($link['permission']) || (auth()->check() && auth()->user()->can($link['permission']));
    $isActive = request()->routeIs($link['pattern']);
    
    $containerClass = $isActive 
        ? 'bg-[#B6FF5C]/10 text-[#B6FF5C] font-bold shadow-[inset_0_0_12px_rgba(182,255,92,0.05)]' 
        : 'text-[#A0AEC0] font-medium hover:bg-[#B6FF5C]/5 hover:text-white transition-all duration-300';

    $iconClass = $isActive
        ? 'text-[#B6FF5C]' 
        : 'text-[#A0AEC0] group-hover:text-white group-hover:scale-110 transition-all duration-300';

    // Map label to notification key
    $notifKey = match($link['label']) {
        'Requests' => 'requests',
        'Payments' => 'payments',
        'Dues' => 'dues',
        'Reservations' => 'reservations',
        'Resident Support' => 'messages',
        'Notifications' => 'system_notifications',
        default => null
    };
@endphp

@if($allowed)
<a href="{{ route($link['route']) }}" 
   class="group relative flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-300 {{ $containerClass }}">
    
    <div class="flex items-center gap-3.5">
        {{-- Active Indicator Line (Left) --}}
        @if($isActive)
            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-[#B6FF5C] rounded-r-full shadow-[0_0_12px_rgba(182,255,92,0.6)]"></div>
        @endif

        <i class="{{ $link['icon'] }} text-[1.2rem] {{ $iconClass }} ml-1"></i>
        <span class="tracking-wide text-[14px]">{{ $link['label'] }}</span>
    </div>

    @if($notifKey)
        <template x-if="counts['{{ $notifKey }}'] && counts['{{ $notifKey }}'].count > 0">
            <span x-text="formatCount(counts['{{ $notifKey }}'].count)" 
                  :class="{
                      'bg-[#B6FF5C] text-[#0B1F1A]': counts['{{ $notifKey }}'].priority === 'normal',
                      'bg-amber-400 text-amber-950': counts['{{ $notifKey }}'].priority === 'warning',
                      'bg-red-500 text-white animate-pulse shadow-[0_0_10px_rgba(239,68,68,0.3)]': counts['{{ $notifKey }}'].priority === 'critical'
                  }"
                  class="flex items-center justify-center h-[18px] px-2 rounded-full text-[11px] font-black tracking-tighter transition-all duration-300 hover:brightness-110">
            </span>
        </template>
    @endif
</a>
@endif
