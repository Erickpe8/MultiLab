@props([
    'title' => null,
    'subtitle' => null,
])

@php
    $pageTitle = $title ? "{$title} | " . config('app.name', 'Ingeniería de Software (FESC)') : config('app.name', 'Ingeniería de Software (FESC)');
    $path = request()->getPathInfo();
    $backgroundImage = str_contains($path, 'register')
        ? 'images/Bodega2.png'
        : 'images/Bodega1.png';
@endphp

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

    <title>{{ $pageTitle }}</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('images/multilab.svg') }}">
    <link rel="alternate icon" href="{{ asset('images/multilab.svg') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        [x-cloak] {
            display: none !important;
        }

        html,
        body,
        #app {
            min-height: 100%;
        }
    </style>

    <script src="{{ asset('js/theme-toggle.js') }}" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-primary antialiased bg-[var(--bg)] text-[var(--text)] min-h-screen min-w-full">
    <div class="fixed inset-0 -z-10">
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
            style="background-image: url('{{ asset($backgroundImage) }}');">
        </div>
        <div class="absolute inset-0 bg-white/50 dark:bg-black/45"></div>
    </div>

    <div class="min-h-screen flex flex-col">
        <div class="flex-1 flex items-center justify-center px-4 py-10">
            <div class="w-full max-w-md space-y-5">
            <x-ui.card class="relative overflow-hidden p-6 md:p-8">
                <header class="space-y-2 text-center mb-6 pt-4">
                    <a href="{{ route('welcome') }}" class="inline-flex flex-col items-center gap-2">
                        <x-brand.logo variant="vertical" class="h-12 w-auto" />
                        <span class="text-sm font-semibold tracking-wide text-[var(--primary)]">{{ config('app.name') }}</span>
                    </a>
                    <h1 class="text-2xl font-extrabold tracking-tight text-[var(--text)]">
                        {{ $title ?? config('app.name', 'Ingeniería de Software (FESC)') }}
                    </h1>
                    @if($subtitle)
                        <p class="text-sm text-[color:var(--text-muted)] max-w-[30ch] mx-auto">
                            {{ $subtitle }}
                        </p>
                    @endif
                </header>

                <div class="space-y-5">
                    {{ $slot }}
                </div>
            </x-ui.card>
        </div>
    </div>
</div>

    <x-notify />
    @include('components.toast-bridge')

    @if (session('notify'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const data = @json(session('notify'));
                showNotification(data.message ?? 'Operación realizada', data.type ?? 'info');
            });
        </script>
    @endif
</body>

</html>
