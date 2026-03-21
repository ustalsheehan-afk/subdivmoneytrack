@php
    $isActive = request()->routeIs($link['pattern']);
    
    // Official Palette Active State
    // Active: bg-[rgba(91,134,182,0.15)] text-[#c0e6fd]
    // Inactive: text-[#80aad3] hover:bg-[rgba(192,230,253,0.08)] hover:text-[#c0e6fd]
    
    
    $containerClass = $isActive 
        ? 'bg-[#5b86b6]/15 text-white font-semibold' 
        : 'text-gray-300 font-medium hover:bg-[#c0e6fd]/10 hover:text-white';

    $iconClass = $isActive
        ? 'text-white' 
        : 'text-gray-400 group-hover:text-white';
@endphp

<a href="{{ route($link['route']) }}" 
   class="group relative flex items-center gap-3.5 px-4 py-3 rounded-lg transition-all duration-200 {{ $containerClass }}">
    
    {{-- Active Indicator Line (Left) using #5b86b6 --}}
    @if($isActive)
        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-7 bg-[#5b86b6] rounded-r-full shadow-[0_0_8px_rgba(91,134,182,0.6)]"></div>
    @endif

    <i class="{{ $link['icon'] }} text-[1.2rem] {{ $iconClass }} transition-colors duration-200 ml-1"></i>
    <span class="tracking-wide text-[14px]">{{ $link['label'] }}</span>
</a>