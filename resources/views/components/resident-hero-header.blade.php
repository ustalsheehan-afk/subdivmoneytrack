@props([
    'label' => null,
    'icon' => null,
    'title',
    'description' => null,
    'tabs' => [],
    'actions' => null,
])

<div class="relative overflow-hidden bg-[#081412] rounded-2xl p-6 sm:p-8 shadow-2xl group animate-fade-in">
    {{-- Subtle gradient glow in background --}}
    <div class="absolute -right-20 -top-20 w-80 h-80 bg-[rgba(182,255,92,0.10)] rounded-full blur-3xl group-hover:bg-[rgba(182,255,92,0.20)] transition-all duration-1000"></div>
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
                <h2 class="text-2xl md:text-3xl font-black text-white tracking-tight leading-none">{{ $title }}</h2>
            </div>

            <p class="text-[13px] font-medium text-white/70 max-w-2xl leading-relaxed">
                {{ $description }}
            </p>

        </div>
        
        @if($actions || count($tabs) > 0)
        <div class="w-full lg:w-auto lg:min-w-[260px] flex flex-col gap-3">
            @if($actions)
                <div class="flex w-full justify-start lg:justify-end">
                    {{ $actions }}
                </div>
            @endif

            @if(count($tabs) > 0)
            <div class="relative">
                <select
                    class="w-full px-4 py-3 pr-10 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all border bg-white/5 text-white/80 border-white/10 hover:bg-white/10 hover:border-white/20 focus:bg-white/10 focus:border-white/30 outline-none appearance-none"
                    @change="
                        const opt = $event.target.selectedOptions[0];
                        if (opt?.dataset?.href) { window.location = opt.dataset.href; return; }
                        if (opt?.dataset?.click) { eval(opt.dataset.click); return; }
                    "
                >
                    @foreach($tabs as $tab)
                        @php
                            $href = $tab['href'] ?? null;
                            $click = $tab['click'] ?? null;
                            $active = $tab['active'] ?? false;
                            $activeCondition = $tab['active_condition'] ?? null;
                        @endphp
                        <option
                            value="{{ $href ?? ($tab['id'] ?? '') }}"
                            {{ $href && $active ? 'selected' : '' }}
                            @if($href) data-href="{{ $href }}" @endif
                            @if($click) data-click="{{ $click }}" @endif
                            @if(!$href && $activeCondition) :selected="{{ $activeCondition }}" @endif
                            class="text-black"
                        >
                            {{ $tab['label'] }}
                        </option>
                    @endforeach
                </select>
                <i class="bi bi-funnel-fill absolute right-4 top-1/2 -translate-y-1/2 text-xs text-white/60 pointer-events-none"></i>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>
