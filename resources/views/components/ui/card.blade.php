@props([
    'shadow' => 'shadow-xl',
])

@php
    $baseClasses = 'font-primary relative rounded-3xl border border-[var(--border)] bg-[var(--surface)] shadow-black/10';
    $classes = trim("{$baseClasses} {$shadow}");
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
