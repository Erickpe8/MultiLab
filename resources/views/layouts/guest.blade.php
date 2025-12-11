<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Título y Favicon -->
    <title>{{ config('app.name', 'MultiLab') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/multilab.svg') }}" />

    <!-- Tipografías -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased bg-gray-100">

    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-md">
            {{ $slot }}
        </div>
    </div>

    <!-- Notificaciones -->
    <x-notify />

    @if (session('notify'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const data = @json(session('notify'));
                showNotification(data.message ?? 'Operación realizada', data.type ?? 'info');
            });
        </script>
    @endif

    @if (session('status'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                showNotification(@json(session('status')), 'success');
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const firstError = @json($errors->first());
                if (firstError) showNotification(firstError, 'error');
            });
        </script>
    @endif

</body>

</html>
