<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon MultiLab -->
    <link rel="icon" type="image/png" href="{{ asset('images/multilab.svg') }}">
    <link rel="shortcut icon" href="{{ asset('images/multilab.svg') }}" type="image/png">

    <title>{{ config('app.name', 'MultiLab') }}</title>

    <!-- Tipografías -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tema oscuro/claro -->
    <script>
        (function () {
            const saved = localStorage.getItem('theme') || 'light';
            document.documentElement.dataset.theme = saved;
            document.documentElement.classList.toggle('dark', saved === 'dark');
        })();
    </script>

    <!-- Estilos y JS de Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-[var(--bg)]" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex flex-col">

        <div class="flex flex-1">

            <!-- SIDEBAR MULTILAB -->
            @include('layouts.navigation')

            <!-- OVERLAY MÓVIL -->
            <div class="fixed inset-0 bg-black/40 z-30 lg:hidden" x-show="sidebarOpen" x-transition.opacity
                @click="sidebarOpen=false" style="display:none;">
            </div>

            <!-- CONTENIDO PRINCIPAL -->
            <div class="flex-1 min-w-0 w-full lg:ml-64 flex flex-col">

                <!-- TOPBAR (para móvil) -->
                <div class="lg:hidden sticky top-0 z-20 bg-[var(--card)] border-b border-[var(--border)]">
                    <div class="h-14 px-4 flex items-center justify-between">
                        <button @click="sidebarOpen = true"
                            class="p-2 rounded-md text-[var(--text)] hover:text-[var(--accent)] hover:bg-[var(--border)]/20 transition">
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

                <!-- HEADER SUPERIOR (opcional) -->
                @if (isset($header))
                    <header class="bg-[var(--card)] border-b border-[var(--border)]">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- CONTENIDO -->
                <main class="flex-1">
                    {{ $slot }}
                </main>

                <!-- FOOTER -->
                @include('layouts.footer')

            </div>
        </div>
    </div>

    <!-- NOTIFICACIONES -->
    <x-notify />

    <!-- FLOWBITE (opcional) -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

    <!-- SISTEMA DE NOTIFICACIONES GLOBAL -->
    <script>
        window.showNotification = function (message, type = 'info') {
            const notify = document.getElementById('notify');
            const notifyMessage = document.getElementById('notify-message');
            const notifyIcon = document.getElementById('notify-icon');
            const notifyCard = document.getElementById('notify-card');
            const notifyIconWrap = document.getElementById('notify-icon-wrap');

            const configs = {
                success: {
                    border: 'border-green-500',
                    iconBg: 'bg-green-100 dark:bg-green-900/30',
                    iconColor: 'text-green-600 dark:text-green-400',
                    icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7" />`
                },
                error: {
                    border: 'border-red-500',
                    iconBg: 'bg-red-100 dark:bg-red-900/30',
                    iconColor: 'text-red-600 dark:text-red-400',
                    icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />`
                },
                warning: {
                    border: 'border-yellow-500',
                    iconBg: 'bg-yellow-100 dark:bg-yellow-900/30',
                    iconColor: 'text-yellow-600 dark:text-yellow-400',
                    icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01M10.29 3.86l-7.4 12.84A2 2 0 004.53 20h14.94a2 2 0 001.74-3.3L13.8 3.86a2 2 0 00-3.5 0z" />`
                },
                info: {
                    border: 'border-blue-500',
                    iconBg: 'bg-blue-100 dark:bg-blue-900/30',
                    iconColor: 'text-blue-600 dark:text-blue-400',
                    icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />`
                }
            };

            const config = configs[type] || configs.info;

            notifyMessage.textContent = message;
            notifyIcon.innerHTML = config.icon;

            notifyCard.classList.remove('hidden', 'opacity-0', '-translate-y-2');

            setTimeout(() => {
                notifyCard.classList.add('-translate-y-2', 'opacity-0');
                setTimeout(() => notifyCard.classList.add('hidden'), 300);
            }, 5000);
        };

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('notify-close')?.addEventListener('click', () => {
                const notify = document.getElementById('notify-card');
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

</body>

</html>
