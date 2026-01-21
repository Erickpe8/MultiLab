@php
    $livewire ??= null;
@endphp

@push('styles')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css'])

    <script>
        window.themeConfig = window.themeConfig || {};
        window.themeConfig.persistUrl = "{{ route('profile.theme.update') }}";
        @if (! filament()->hasDarkMode())
            window.themeConfig.forceTheme = 'light';
        @elseif (filament()->hasDarkModeForced())
            window.themeConfig.forceTheme = 'dark';
        @endif
    </script>
    <script src="{{ asset('js/theme-toggle.js') }}" defer></script>
@endpush

<x-filament-panels::layout.base :livewire="$livewire">
    <div class="min-h-screen flex flex-col bg-[var(--bg)] font-sans antialiased" x-data="{ sidebarOpen: false }">
        <div class="flex flex-1">
            @include('layouts.navigation')

            <div class="fixed inset-0 bg-black/40 z-30 lg:hidden" x-show="sidebarOpen" x-transition.opacity
                @click="sidebarOpen=false" style="display: none;"></div>

            <div class="flex-1 min-w-0 w-full @if(request()->routeIs('dashboard')) lg:ml-64 @elseif(request()->routeIs('filament.dashboard.resources.*')) lg:ml-8 lg:mr-8 @endif flex flex-col">
                {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::TOPBAR_BEFORE, scopes: $livewire?->getRenderHookScopes()) }}

                <div class="lg:hidden sticky top-0 z-20 bg-[var(--card)] border-b border-[var(--border)]">
                    <div class="h-14 px-4 flex items-center justify-between">
                        <button @click="sidebarOpen = true"
                            class="p-2 rounded-md text-[var(--text)] hover:text-[var(--accent)] hover:bg-[var(--border)]/20 transition-colors"
                            aria-label="Abrir menú">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <div class="text-sm text-[var(--text)] truncate">
                            MultiLab
                        </div>
                        <div class="w-10"></div>
                    </div>
                </div>

                {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::TOPBAR_AFTER, scopes: $livewire?->getRenderHookScopes()) }}

                @if (isset($header))
                    <header class="bg-[var(--card)] border-b border-[var(--border)]">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::CONTENT_BEFORE, scopes: $livewire?->getRenderHookScopes()) }}

                <main class="flex-1">
                    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::CONTENT_START, scopes: $livewire?->getRenderHookScopes()) }}

                    {{ $slot }}

                    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::CONTENT_END, scopes: $livewire?->getRenderHookScopes()) }}
                </main>

                {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::CONTENT_AFTER, scopes: $livewire?->getRenderHookScopes()) }}

                {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::FOOTER, scopes: $livewire?->getRenderHookScopes()) }}

                @include('layouts.footer')
            </div>
        </div>
    </div>

    <x-notify />

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

    <script>
        window.showNotification = function (message, type = 'info') {
            const notify = document.getElementById('notify');
            const notifyMessage = document.getElementById('notify-message');
            const notifyIcon = document.getElementById('notify-icon');
            const notifyCard = document.getElementById('notify-card');
            const notifyIconWrap = document.getElementById('notify-icon-wrap');

            if (!notify || !notifyMessage || !notifyCard || !notifyIconWrap || !notifyIcon) return;

            const configs = {
                success: {
                    border: 'border-green-500',
                    iconBg: 'bg-green-100 dark:bg-green-900/30',
                    iconColor: 'text-green-600 dark:text-green-400',
                    icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />`
                },
                error: {
                    border: 'border-red-500',
                    iconBg: 'bg-red-100 dark:bg-red-900/30',
                    iconColor: 'text-red-600 dark:text-red-400',
                    icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />`
                },
                warning: {
                    border: 'border-yellow-500',
                    iconBg: 'bg-yellow-100 dark:bg-yellow-900/30',
                    iconColor: 'text-yellow-600 dark:text-yellow-400',
                    icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86l-7.4 12.84A2 2 0 004.53 20h14.94a2 2 0 001.74-3.3L13.8 3.86a2 2 0 00-3.5 0z" />`
                },
                info: {
                    border: 'border-blue-500',
                    iconBg: 'bg-blue-100 dark:bg-blue-900/30',
                    iconColor: 'text-blue-600 dark:text-blue-400',
                    icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />`
                }
            };

            const config = configs[type] || configs.info;

            notifyCard.classList.remove('border-green-500', 'border-red-500', 'border-yellow-500', 'border-blue-500');
            notifyCard.classList.add(config.border);

            notifyIconWrap.className = 'inline-flex items-center justify-center w-9 h-9 rounded-full';
            config.iconBg.split(' ').forEach(cls => notifyIconWrap.classList.add(cls));

            notifyIcon.className = 'w-10 h-10';
            config.iconColor.split(' ').forEach(cls => notifyIcon.classList.add(cls));

            notifyIcon.innerHTML = config.icon;

            notifyMessage.textContent = message;
            notify.classList.remove('hidden', '-translate-y-2', 'opacity-0');

            clearTimeout(window.__notifyTimer);
            window.__notifyTimer = setTimeout(() => {
                notify.classList.add('-translate-y-2', 'opacity-0');
                setTimeout(() => notify.classList.add('hidden'), 300);
            }, 5000);
        };

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('notify-close')?.addEventListener('click', () => {
                const notify = document.getElementById('notify');
                notify.classList.add('-translate-y-2', 'opacity-0');
                setTimeout(() => notify.classList.add('hidden'), 300);
            });
        });
    </script>

    @if (session('notify'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const data = @json(session('notify'));
                showNotification(data.message ?? 'Operación realizada', data.type ?? 'info');
            });
        </script>
    @endif
</x-filament-panels::layout.base>
