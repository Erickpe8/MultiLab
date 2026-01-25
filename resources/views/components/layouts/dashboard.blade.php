{{-- resources/views/components/layouts/dashboard.blade.php --}}
@props([
    'title'   => null,
    'active'  => null,
    'accent'  => 'blue',
    'modules' => [],
])

<div class="min-h-[100svh] flex flex-col bg-[var(--bg)] text-[var(--text)] overflow-x-hidden">
    {{-- Header --}}
    <x-dashboard.header :title="$title" />

    {{-- Contenido --}}
    <main class="flex-1 w-full min-w-0 px-4 py-6 sm:px-6 sm:py-8 brand-content"
          style="padding-bottom: env(safe-area-inset-bottom);">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    @include('layouts.footer')

    {{-- Modal equipo desarrollador --}}
    <x-dashboard.team-dialog />
</div>

{{-- ▸ ASSETS --}}
@once
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endonce

@stack('styles')
@stack('scripts')

<x-notify />
@include('components.toast-bridge')
