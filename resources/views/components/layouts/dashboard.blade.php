{{-- resources/views/components/layouts/dashboard.blade.php --}}
@props([
    'title'   => null,
    'active'  => null,
    'accent'  => 'blue',
    'modules' => [],
])

<div
    class="min-h-screen flex bg-multilab-light dark:bg-multilab-dark
           text-multilab-dark dark:text-multilab-gray"
>
    {{-- ▸ SIDEBAR --}}
    <div
        id="sidebarWrapper"
        class="fixed inset-y-0 left-0 z-40 w-64 transform transition-transform
               -translate-x-full lg:translate-x-0 lg:static"
    >
        <x-dashboard.sidebar
            :active="$active"
            :accent="$accent"
            :modules="$modules"
        />
    </div>

    {{-- ▸ OVERLAY (móvil) --}}
    <div
        id="sidebarOverlay"
        class="fixed inset-0 z-30 bg-black bg-opacity-50 hidden lg:hidden"
    ></div>

    {{-- ▸ CONTENIDO PRINCIPAL --}}
    <div class="flex-1 flex flex-col min-w-0">
        {{-- Header --}}
        <x-dashboard.header :title="$title" />

        {{-- Contenido dinámico --}}
        <main class="flex-1 p-4 overflow-y-auto">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <x-dashboard.footer />

        {{-- Modal equipo desarrollador --}}
        <x-dashboard.team-dialog />
    </div>
</div>

{{-- ▸ ASSETS --}}
@once
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endonce

@stack('styles')
@stack('scripts')

{{-- ▸ SCRIPT DEL SIDEBAR (JS VANILLA) --}}
@push('scripts')
<script>
(function () {
    const sidebar = document.getElementById('sidebarWrapper');
    const overlay = document.getElementById('sidebarOverlay');

    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        if (overlay) overlay.classList.remove('hidden');
    }

    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        if (overlay) overlay.classList.add('hidden');
    }

    function toggleDesktop() {
        sidebar.classList.toggle('-translate-x-full');
    }

    // Botones de control
    document.addEventListener('click', (e) => {
        if (e.target.closest('[data-sidebar-open]')) openSidebar();
        if (e.target.closest('[data-sidebar-close]')) closeSidebar();
        if (e.target.closest('[data-sidebar-toggle-desktop]')) toggleDesktop();
    });

    // Clic en overlay → cerrar
    if (overlay) overlay.addEventListener('click', closeSidebar);

    // Sincronizar estado según tamaño
    const sync = () => {
        if (window.innerWidth >= 1024) {
            sidebar.classList.remove('-translate-x-full');
            if (overlay) overlay.classList.add('hidden');
        } else {
            sidebar.classList.add('-translate-x-full');
        }
    };

    window.addEventListener('resize', sync);
    sync();
})();
</script>
@endpush
