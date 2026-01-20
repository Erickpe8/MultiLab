<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'MultiLab'))</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <script>
        (function () {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = saved || (prefersDark ? 'dark' : 'light');

            document.documentElement.dataset.theme = theme;
            document.documentElement.classList.toggle('dark', theme === 'dark');
            document.documentElement.style.colorScheme = theme;
        })();
    </script>

    <script src="{{ asset('js/theme-toggle.js') }}" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-screen overflow-hidden font-sans antialiased bg-[var(--bg)] text-[var(--text)]">
    <div class="h-screen overflow-hidden">
        {{ $slot }}
    </div>

    <x-notify />

    @if (session('notify'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const data = @json(session('notify'));
                window.showNotification?.(data.message ?? 'Operación realizada', data.type ?? 'info');
            });
        </script>
    @endif
</body>

</html>
