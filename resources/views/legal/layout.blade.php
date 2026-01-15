@php
    $layoutComponent = auth()->check() ? 'app-layout' : 'guest-layout';
@endphp

<x-dynamic-component :component="$layoutComponent">
    <x-slot name="header">
        @yield('legal-header')
    </x-slot>

    @yield('legal-content')
</x-dynamic-component>
