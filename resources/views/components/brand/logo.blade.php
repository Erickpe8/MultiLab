@props([
    'variant' => 'horizontal',
])

@php
    $variants = [
        'horizontal' => 'images/brand-horizontal.png',
        'vertical' => 'images/brand-vertical.png',
        'icon' => 'images/brand-icon.png',
    ];
    $path = $variants[$variant] ?? $variants['horizontal'];
@endphp

<img
    src="{{ asset($path) }}"
    alt="Logotipo de Ingeniería de Software (FESC)"
    {{ $attributes->merge(['class' => 'inline-block']) }}
/>
