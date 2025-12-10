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
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        {{-- Desktop: toggle sidebar pin/unpin --}}
        <button type="button" data-sidebar-toggle-desktop
            class="hidden lg:inline-flex items-center justify-center p-2 rounded-md text-multilab-dark dark:text-multilab-gray hover:bg-multilab-light dark:hover:bg-multilab-darkblue/30"
            aria-label="Toggle sidebar">
            <!-- Pin icon -->
            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 2a1 1 0 000 2h1v6h2V4h1a1 1 0 100-2H6z" />
                <path d="M4 11a1 1 0 011-1h10a1 1 0 010 2H5a1 1 0 01-1-1z" />
                <path d="M7 15a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1z" />
            </svg>
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
        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M10 2a.75.75 0 01.75.75V4a.75.75 0 11-1.5 0V2.75A.75.75 0 0110 2zM10 16a.75.75 0 01.75.75V18a.75.75 0 11-1.5 0v-1.25A.75.75 0 0110 16zM4.22 4.22a.75.75 0 011.06 0l.884.884a.75.75 0 11-1.06 1.06l-.884-.884a.75.75 0 010-1.06zM14.836 14.836a.75.75 0 011.06 0l.884.884a.75.75 0 11-1.06 1.06l-.884-.884a.75.75 0 010-1.06zM2 10a.75.75 0 01.75-.75H4a.75.75 0 110 1.5H2.75A.75.75 0 012 10zM16 10a.75.75 0 01.75-.75H18a.75.75 0 110 1.5h-1.25A.75.75 0 0116 10zM4.22 15.78a.75.75 0 010-1.06l.884-.884a.75.75 0 011.06 1.06l-.884.884a.75.75 0 01-1.06 0zM14.836 5.164a.75.75 0 010-1.06l.884-.884a.75.75 0 111.06 1.06l-.884.884a.75.75 0 01-1.06 0z" />
        </svg>
    </button>
</header>
