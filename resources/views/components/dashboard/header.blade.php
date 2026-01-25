@props(['title' => null])

{{--
Dashboard header component.
Displays navigation controls for the sidebar on mobile and desktop,
a page title and a theme toggle button. Uses simple SVG icons
to avoid heavy JavaScript frameworks.
--}}
<header
    class="flex items-center justify-between px-4 py-3 border-b border-[var(--border)] bg-[var(--surface)] text-[var(--text)]">
    <div class="flex items-center space-x-2">
        {{-- Mobile: open sidebar --}}
        <x-ui.button variant="ghost" type="button" data-sidebar-open
            class="lg:hidden p-2 text-current rounded-md"
            aria-label="Open sidebar">
            <!-- Hamburger icon -->
            <x-ui.icon name="menu" size="lg" />
        </x-ui.button>
        {{-- Desktop: toggle sidebar pin/unpin --}}
        <x-ui.button variant="ghost" type="button" data-sidebar-toggle-desktop
            class="hidden lg:inline-flex p-2 text-current rounded-md"
            aria-label="Toggle sidebar">
            <!-- Pin icon -->
            <x-ui.icon name="pin" size="lg" />
        {{-- Page title --}}
        <h1 class="text-lg font-semibold text-brand">
            {{ $title ?? '' }}
        </h1>
    </div>
    {{-- Theme toggle button --}}
        <x-ui.button variant="ghost" type="button" onclick="document.documentElement.classList.toggle('dark')"
            class="p-2 text-current rounded-md"
            aria-label="Toggle theme">
        <!-- Sun/Moon icon -->
        <x-ui.icon name="sol" size="lg" />
</header>
