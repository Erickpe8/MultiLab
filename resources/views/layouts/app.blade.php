<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('head_start')
    {{ \Filament\Facades\Filament::renderHook('panels::head.start') }}

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/multilab.svg') }}">
    <link rel="alternate icon" href="{{ asset('images/multilab.svg') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">
    <meta name="theme-color" content="#1D4ED8">
    <title>
        @hasSection('title')
            @yield('title') | {{ config('app.name', 'Ingeniería de Software (FESC)') }}
        @else
            {{ config('app.name', 'Ingeniería de Software (FESC)') }}
        @endif
    </title>

    <!-- Tipografías -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <script>
        (function () {
            const route = "{{ route('profile.theme.update') }}";
            const allowedThemes = ['system', 'light', 'dark'];
            const prefersDark = typeof window.matchMedia === 'function'
                ? window.matchMedia('(prefers-color-scheme: dark)')
                : {
                    matches: false,
                    addEventListener: null,
                    addListener: null,
                };
            let currentTheme = 'system';

            const normalize = (value) => {
                if (typeof value !== 'string') {
                    return 'system';
                }
                const lower = value.toLowerCase();
                return allowedThemes.includes(lower) ? lower : 'system';
            };

            const resolvedApplied = (theme) => {
                const normalized = normalize(theme);
                if (normalized === 'dark') return 'dark';
                if (normalized === 'light') return 'light';
                return prefersDark.matches ? 'dark' : 'light';
            };

            const syncToggles = (applied) => {
                const sync = () => {
                    document.querySelectorAll('[data-theme-toggle]').forEach((toggle) => {
                        if (toggle instanceof HTMLInputElement) {
                            const shouldBeChecked = applied === 'dark';
                            if (toggle.checked !== shouldBeChecked) {
                                toggle.checked = shouldBeChecked;
                            }
                        }
                    });
                };

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', sync);
                } else {
                    sync();
                }
            };

            const dispatchChange = (theme, applied) => {
                window.dispatchEvent(new CustomEvent('theme:changed', {
                    detail: { theme, applied },
                }));
            };

            const applyTheme = (theme) => {
                const normalized = normalize(theme);
                const applied = resolvedApplied(normalized);
                currentTheme = normalized;
                document.documentElement.dataset.theme = normalized;
                document.documentElement.classList.toggle('dark', applied === 'dark');
                syncToggles(applied);
                try {
                    localStorage.setItem('theme', normalized);
                } catch (_error) {
                    // Silently ignore storage errors.
                }
                dispatchChange(normalized, applied);
                return { theme: normalized, applied };
            };

            const persistedMode = (value) => (value === 'db' ? 'db' : 'local');

            const persistTheme = async (themeValue) => {
                const normalized = normalize(themeValue);
                if (!route) {
                    return {
                        ok: false,
                        theme: normalized,
                        applied: resolvedApplied(normalized),
                        persisted: 'local',
                    };
                }

                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                };

                if (token) {
                    headers['X-CSRF-TOKEN'] = token;
                }

                let payload = {
                    ok: false,
                    theme: normalized,
                    applied: resolvedApplied(normalized),
                    persisted: 'local',
                };

                try {
                    const response = await fetch(route, {
                        method: 'PATCH',
                        headers,
                        credentials: 'same-origin',
                        body: JSON.stringify({ theme: normalized }),
                    });

                    const data = await response.json().catch(() => null);

                    if (response.ok && data?.ok) {
                        const serverTheme = normalize(data.theme ?? normalized);
                        const applied = typeof data.applied === 'string'
                            ? data.applied
                            : resolvedApplied(serverTheme);
                        payload = {
                            ok: true,
                            theme: serverTheme,
                            applied,
                            persisted: persistedMode(data.persisted),
                        };
                        applyTheme(serverTheme);
                    } else {
                        payload = {
                            ...payload,
                            error: data?.message ?? 'No se pudo guardar el tema.',
                        };
                    }
                } catch (error) {
                    payload = {
                        ...payload,
                        error: error?.message ?? 'Error de conexión.',
                    };
                }

                return payload;
            };

            const setTheme = (themeValue, options = { persist: true }) => {
                const result = applyTheme(themeValue);
                if (options.persist) {
                    persistTheme(result.theme);
                }
                return result;
            };

            const toggleTheme = () => {
                const applied = resolvedApplied(currentTheme);
                const next = applied === 'dark' ? 'light' : 'dark';
                return setTheme(next);
            };

            const handleSystemChange = () => {
                if (currentTheme === 'system') {
                    applyTheme('system');
                }
            };

            if (typeof prefersDark.addEventListener === 'function') {
                prefersDark.addEventListener('change', handleSystemChange);
            } else if (typeof prefersDark.addListener === 'function') {
                prefersDark.addListener(handleSystemChange);
            }

            document.addEventListener('change', (event) => {
                const target = event.target;
                if (!(target instanceof HTMLElement)) return;
                if (target.matches('[data-theme-toggle]') && target instanceof HTMLInputElement) {
                    setTheme(target.checked ? 'dark' : 'light');
                }
            });

            const stored = normalize(localStorage.getItem('theme'));

            window.theme = {
                normalize,
                apply: applyTheme,
                persist: persistTheme,
                set: setTheme,
                toggle: toggleTheme,
                current: () => currentTheme,
            };

            applyTheme(stored);
        })();
    </script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/user-management.js'])

    @livewireStyles
    @filamentStyles
    {{ \Filament\Facades\Filament::renderHook('panels::head.end') }}
    @stack('head_end')
</head>

<body class="font-primary antialiased bg-[var(--bg)]">
    {{ \Filament\Facades\Filament::renderHook('panels::body.start') }}
    @stack('body_start')
    <div class="min-h-screen flex flex-col bg-[var(--bg)]">
        @if (isset($header))
            <header class="border-b border-[var(--border)] bg-[var(--card)]">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <main class="flex-1 flex flex-col brand-content">
            <div class="flex-1 w-full max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>

        <!-- Footer -->
        @include('layouts.footer')
    </div>

    <!-- Componente de notificaciones -->
    <x-notify />
    @include('components.toast-bridge')

    <!-- Flowbite (opcional) -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

    @stack('scripts')

    @livewireScripts
    @filamentScripts
    @stack('body_end')
    {{ \Filament\Facades\Filament::renderHook('panels::body.end') }}
</body>

</html>
