<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('head_start')
    {{ \Filament\Facades\Filament::renderHook('panels::head.start') }}

    <!-- Título y Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/ICONFESC.png?v=2') }}">
    <link rel="shortcut icon" href="{{ asset('images/ICONFESC.png?v=2') }}" type="image/png">
    <title>
        @hasSection('title')
            @yield('title') | {{ config('app.name', 'MultiLab') }}
        @else
            {{ config('app.name', 'MultiLab') }}
        @endif
    </title>

    <!-- Tipografías -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

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

<body class="font-sans antialiased bg-[var(--bg)]" x-data="{ sidebarOpen: false }">
{{ \Filament\Facades\Filament::renderHook('panels::body.start') }}
@stack('body_start')
<div class="min-h-screen flex bg-[var(--bg)]">
    <div class="flex flex-1">
        <!-- Sidebar fijo en escritorio / off-canvas en móvil -->
        @include('layouts.navigation')

        <!-- Overlay móvil -->
        <div class="fixed inset-0 bg-black/40 z-30 lg:hidden" x-show="sidebarOpen" x-transition.opacity
             @click="sidebarOpen=false" style="display: none;"></div>

        <!-- Contenido -->
        <div class="flex-1 flex flex-col min-h-screen lg:ml-64">
            <!-- Topbar móvil -->
            <div class="lg:hidden sticky top-0 z-20 bg-[var(--card)] border-b border-[var(--border)]">
                <div class="h-14 px-4 flex items-center justify-between">
                    <button @click="sidebarOpen = true"
                            class="p-2 rounded-md text-[var(--text)] hover:text-[var(--accent)] hover:bg-[var(--border)]/20 transition-colors"
                            aria-label="Abrir menú">
                        <x-ui.icon name="menu" size="lg" />
                    </button>
                    <div class="text-sm text-[var(--text)] truncate">
                        MultiLab
                    </div>
                    <div class="w-10"></div>
                </div>
            </div>

            @if (isset($header))
                <header class="bg-[var(--card)] border-b border-[var(--border)]">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main class="flex-1 flex flex-col">
                <div class="flex-1 w-full max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
                    {{ $slot }}
                </div>
            </main>

            <!-- Footer -->
            @include('layouts.footer')
        </div>
    </div>
</div>

<!-- Componente de notificaciones -->
<x-notify />

<!-- Flowbite (opcional) -->
<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

<!-- Sistema de notificaciones global -->
<script>
    // Función global para mostrar notificaciones
    window.showNotification = function (message, type = 'info') {
        const notify = document.getElementById('notify');
        const notifyMessage = document.getElementById('notify-message');
        const notifyIcon = document.getElementById('notify-icon');
        const notifyCard = document.getElementById('notify-card');
        const notifyIconWrap = document.getElementById('notify-icon-wrap');

        if (!notify || !notifyMessage || !notifyCard || !notifyIconWrap || !notifyIcon) return;

        // Configurar colores e iconos según el tipo
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

        // ✅ FIX: Nada de className.replace (revienta con SVG).
        // Limpiar clases de borde (en el card)
        notifyCard.classList.remove('border-green-500', 'border-red-500', 'border-yellow-500', 'border-blue-500');
        notifyCard.classList.add(config.border);

        // Aplicar clases al wrapper del ícono
        notifyIconWrap.className = 'inline-flex items-center justify-center w-9 h-9 rounded-full';
        config.iconBg.split(' ').forEach(cls => notifyIconWrap.classList.add(cls));

        // Aplicar clases al SVG ícono (classList funciona con SVG)
        notifyIcon.className = 'w-10 h-10';
        config.iconColor.split(' ').forEach(cls => notifyIcon.classList.add(cls));

        // Cambiar el ícono (paths dentro del svg)
        notifyIcon.innerHTML = config.icon;

        // Mostrar mensaje
        notifyMessage.textContent = message;
        notify.classList.remove('hidden', '-translate-y-2', 'opacity-0');

        // Auto-cerrar después de 5 segundos
        clearTimeout(window.__notifyTimer);
        window.__notifyTimer = setTimeout(() => {
            notify.classList.add('-translate-y-2', 'opacity-0');
            setTimeout(() => notify.classList.add('hidden'), 300);
        }, 5000);
    };

    // Cerrar al hacer clic en el botón X
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('notify-close')?.addEventListener('click', () => {
            const notify = document.getElementById('notify');
            notify.classList.add('-translate-y-2', 'opacity-0');
            setTimeout(() => notify.classList.add('hidden'), 300);
        });
    });
</script>

{{-- Notificación desde sesión --}}
@if (session('notify'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const data = @json(session('notify'));
            showNotification(data.message ?? 'Operación realizada', data.type ?? 'info');
        });
    </script>
@endif

@stack('scripts')

@livewireScripts
@filamentScripts
@stack('body_end')
{{ \Filament\Facades\Filament::renderHook('panels::body.end') }}
</body>

</html>
