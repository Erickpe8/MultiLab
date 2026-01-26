@props(['title' => null])

{{--
Dashboard header component.
Displays navigation controls for the sidebar on mobile and desktop,
a page title and a theme toggle button. Uses simple SVG icons
to avoid heavy JavaScript frameworks.
--}}
<header
    class="flex items-center justify-between px-4 py-3 border-b bg-white border-multilab-gray dark:bg-multilab-dark dark:border-multilab-darkblue">
    <div class="flex items-center space-x-2">
        {{-- Mobile: open sidebar --}}
        <button type="button" data-sidebar-open
                class="lg:hidden inline-flex items-center justify-center p-2 rounded-md text-multilab-dark dark:text-multilab-gray hover:bg-multilab-light dark:hover:bg-multilab-darkblue/30"
                aria-label="Open sidebar">
            <!-- Hamburger icon -->
            <x-ui.icon name="menu" size="lg" />
        </button>
        {{-- Desktop: toggle sidebar pin/unpin --}}
        <button type="button" data-sidebar-toggle-desktop
                class="hidden lg:inline-flex items-center justify-center p-2 rounded-md text-multilab-dark dark:text-multilab-gray hover:bg-multilab-light dark:hover:bg-multilab-darkblue/30"
                aria-label="Toggle sidebar">
            <!-- Pin icon -->
            <x-ui.icon name="pin" size="lg" />
        </button>
        {{-- Page title --}}
        <h1 class="text-lg font-semibold text-multilab-blue dark:text-multilab-gray">
            {{ $title ?? '' }}
        </h1>
    </div>
    {{-- Theme toggle button --}}
    <button type="button" onclick="document.documentElement.classList.toggle('dark')"
            class="inline-flex items-center justify-center p-2 rounded-md text-multilab-dark dark:text-multilab-gray hover:bg-multilab-light dark:hover:bg-multilab-darkblue/30"
            aria-label="Toggle theme">
        <!-- Sun/Moon icon -->
        <x-ui.icon name="sol" size="lg" />
    </button>
</header>
