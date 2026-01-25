@props([
    'title' => null,
    'subtitle' => null,
])

@php
    $pageTitle = $title ? "{$title} | " . config('app.name', 'MultiLab') : config('app.name', 'MultiLab');
    $path = request()->getPathInfo();
    $backgroundImage = str_contains($path, 'register')
        ? 'images/Bodega2.png'
        : 'images/Bodega1.png';
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

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

<body class="font-sans antialiased bg-[var(--bg)] text-[var(--text)] min-h-screen min-w-full">
    <div class="fixed inset-0 -z-10">
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
            style="background-image: url('{{ asset($backgroundImage) }}');">
        </div>
        <div class="absolute inset-0 bg-white/50 dark:bg-black/45"></div>
    </div>

    <main class="min-h-screen flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-lg space-y-6">
            <div
                class="relative overflow-hidden rounded-3xl border border-[var(--border)] bg-[var(--card)] shadow-soft p-6 sm:p-8">
                <header class="space-y-2 text-center mb-6">
                    <a href="{{ route('welcome') }}" class="inline-flex flex-col items-center gap-2">
                        <img src="{{ asset('images/FESC-30.png') }}" alt="FESC logo" class="h-12 w-auto" />
                        <span class="text-sm font-semibold tracking-wide text-[#8E1616]">MultiLab</span>
                    </a>
                    <h1 class="text-2xl font-extrabold tracking-tight text-[var(--text)]">
                        {{ $title ?? config('app.name', 'MultiLab') }}
                    </h1>
                    @if ($subtitle)
                        <p class="text-sm text-[var(--text-muted)]">
                            {{ $subtitle }}
                        </p>
                    @endif
                </header>

                <div class="space-y-5">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </main>

    <x-notify />

    @if (session('notify'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const data = @json(session('notify'));
                window.showNotification?.(data.message ?? 'Operación realizada', data.type ?? 'info');
            });
        </script>
    @endif

    @if (session('status'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                window.showNotification?.(@json(session('status')), 'success');
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const firstError = @json($errors->first());
                if (firstError) window.showNotification?.(firstError, 'error');
            });
        </script>
    @endif
</body>

</html>
