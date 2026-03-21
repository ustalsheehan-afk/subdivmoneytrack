@props(['id', 'width' => 'max-w-md'])

{{-- Overlay --}}
<div id="{{ $id }}Overlay"
     onclick="close{{ ucfirst($id) }}()"
     class="x-drawer-overlay fixed inset-0 bg-black/50 hidden opacity-0 transition-opacity duration-300 z-[9998] backdrop-blur-sm">
</div>

{{-- Drawer --}}
<div id="{{ $id }}"
     class="x-drawer-component fixed top-0 right-0 h-full w-full {{ $width }} bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-out z-[9999] flex flex-col overflow-hidden">

    {{-- Drawer Content Wrapper (IMPORTANT) --}}
    <div class="flex-1 overflow-hidden">
        {{ $slot }}
    </div>

</div>

<script>
    window.open{{ ucfirst($id) }} = function () {
        const overlay = document.getElementById('{{ $id }}Overlay');
        const drawer = document.getElementById('{{ $id }}');

        if (!overlay || !drawer) return;

        // Close any other open drawers (GLOBAL FIX)
        document.querySelectorAll('.x-drawer-overlay').forEach(el => el.classList.add('hidden', 'opacity-0'));
        document.querySelectorAll('.x-drawer-component').forEach(el => {
            if (el.classList.contains('translate-x-0')) {
                el.classList.add('translate-x-full');
                el.classList.remove('translate-x-0');
            }
        });

        // Lock background scroll
        document.body.classList.add('overflow-hidden');

        overlay.classList.remove('hidden');
        requestAnimationFrame(() => {
            overlay.classList.remove('opacity-0');
            drawer.classList.remove('translate-x-full');
            drawer.classList.add('translate-x-0');
        });
    }

    window.close{{ ucfirst($id) }} = function () {
        const overlay = document.getElementById('{{ $id }}Overlay');
        const drawer = document.getElementById('{{ $id }}');

        if (!overlay || !drawer) return;

        drawer.classList.add('translate-x-full');
        drawer.classList.remove('translate-x-0');
        overlay.classList.add('opacity-0');

        setTimeout(() => {
            overlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 300);
    }
</script>
