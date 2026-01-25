@props(['title' => null])

@php
    use Illuminate\Support\Str;
@endphp

{{--
Dashboard header component.
Displays navigation controls for the sidebar on mobile and desktop,
a page title and a theme toggle button. Uses simple SVG icons
to avoid heavy JavaScript frameworks.
--}}
<header class="flex items-center justify-between px-4 py-3 border-b border-[var(--border)] bg-[var(--surface)] text-[var(--text)]">
    <div>
        <h1 class="text-lg font-semibold text-brand">
            {{ $title ?? '' }}
        </h1>
    </div>

    <div class="flex items-center gap-3">
        <x-ui.button variant="ghost" type="button" onclick="document.documentElement.classList.toggle('dark')"
            class="p-2 text-current rounded-md"
            aria-label="Toggle theme">
            <x-ui.icon name="sol" size="lg" />
        </x-ui.button>

        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button class="inline-flex items-center gap-2 px-3 py-2 rounded-full border border-[var(--border)] bg-[var(--card)] text-sm font-medium transition hover:border-[var(--accent)]">
                    <span>{{ auth()->user()?->name ? Str::of(auth()->user()->name)->explode(' ')[0] : 'Cuenta' }}</span>
                    <x-ui.icon name="expandir" size="sm" class="text-[var(--accent)]" />
                </button>
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2 text-[var(--text)] hover:text-[var(--accent)]">
                    <x-ui.icon name="perfil" size="sm" class="text-[var(--accent)]" />
                    Perfil
                </x-dropdown-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')" class="flex items-center gap-2 text-[var(--text)] hover:text-[var(--accent)]"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        <x-ui.icon name="logout" size="sm" class="text-[var(--accent)]" />
                        Cerrar sesión
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</header>
