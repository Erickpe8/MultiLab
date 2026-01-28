<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <script>
        (function () {
            try {
                const stored = localStorage.getItem('theme');
                const theme = stored || 'light';
                if (!stored) {
                    localStorage.setItem('theme', 'light');
                }
                document.documentElement.dataset.theme = theme;
                document.documentElement.classList.toggle('dark', theme === 'dark');
                document.documentElement.style.colorScheme = theme;
            } catch (error) {
                document.documentElement.dataset.theme = 'light';
                document.documentElement.classList.remove('dark');
                document.documentElement.style.colorScheme = 'light';
            }
        })();
    </script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Ingeniería de Software (FESC)'))</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('images/multilab.svg') }}">
    <link rel="alternate icon" href="{{ asset('images/multilab.svg') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <script src="{{ asset('js/theme-toggle.js') }}" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-screen overflow-hidden font-primary antialiased bg-[var(--bg)] text-[var(--text)]">
    <div class="h-screen overflow-hidden">
        {{ $slot }}
    </div>

    <x-notify />
    @include('components.toast-bridge')
</body>

</html>
