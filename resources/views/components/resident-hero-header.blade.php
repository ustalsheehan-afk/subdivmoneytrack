@props([
    'label' => null,
    'icon' => null,
    'title',
    'description' => null,
    'tabs' => [],
    'actions' => null,
])

<div class="relative overflow-hidden bg-[#081412] rounded-[24px] p-8 shadow-2xl group animate-fade-in mb-10">
    {{-- Subtle gradient glow in background --}}
    <div class="absolute -right-20 -top-20 w-80 h-80 bg-brand-accent/10 rounded-full blur-3xl group-hover:bg-brand-accent/20 transition-all duration-1000"></div>
    <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl"></div>
    
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 relative z-10">
        <div class="flex-1 space-y-3">
            @if($label)
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-emerald-500/10 border border-emerald-500/20">
                @if($icon) <i class="bi {{ $icon }} text-emerald-400 text-[10px]"></i> @endif
                <span class="text-[9px] font-black text-emerald-400 uppercase tracking-[0.2em]">{{ $label }}</span>
            </div>
            @endif
            
            <div class="flex items-center gap-4">
                <h2 class="text-3xl md:text-4xl font-black text-white tracking-tight leading-none">{{ $title }}</h2>
                @if($actions)
                    <div class="hidden md:flex items-center gap-3">
                        {{ $actions }}
                    </div>
                @endif
            </div>

            <p class="text-[13px] font-medium text-white/70 max-w-2xl leading-relaxed">
                {{ $description }}
            </p>

            @if($actions)
                <div class="flex md:hidden items-center gap-3 pt-2">
                    {{ $actions }}
                </div>
            @endif
        </div>
        
        @if(count($tabs) > 0)
        <div class="flex items-center gap-2 overflow-x-auto pb-2 lg:pb-0 custom-scrollbar scrollbar-hide">
            @foreach($tabs as $tab)
                @php
                    $href = $tab['href'] ?? null;
                    $click = $tab['click'] ?? null;
                    $active = $tab['active'] ?? false;
                    $activeCondition = $tab['active_condition'] ?? null;
                @endphp
                
                @if($href)
                    <a href="{{ $href }}" 
                       class="px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all border shrink-0 flex items-center gap-2.5
                        {{ $active 
                            ? 'bg-white text-black border-white shadow-xl' 
                            : 'bg-white/5 text-white/60 border-white/10 hover:text-white hover:bg-white/10 hover:border-white/20' }}">
                        @if(isset($tab['icon'])) <i class="bi {{ $tab['icon'] }}"></i> @endif
                        {{ $tab['label'] }}
                    </a>
                @else
                    <button @click="{{ $click }}" 
                       class="px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all border shrink-0 flex items-center gap-2.5"
                       :class="{{ $activeCondition }} 
                            ? 'bg-white text-black border-white shadow-xl' 
                            : 'bg-white/5 text-white/60 border-white/10 hover:text-white hover:bg-white/10 hover:border-white/20'">
                        @if(isset($tab['icon'])) <i class="bi {{ $tab['icon'] }}"></i> @endif
                        {{ $tab['label'] }}
                    </button>
                @endif
            @endforeach
        </div>
        @endif
    </div>
</div>
