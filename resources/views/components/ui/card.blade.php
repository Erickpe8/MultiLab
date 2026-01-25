@props([
    'shadow' => 'shadow-xl',
])

@php
    $baseClasses = 'font-primary relative rounded-3xl border border-[var(--border)] bg-[var(--surface)] shadow-black/5 transition duration-200';
    $hoverClasses = 'hover:border-[var(--primary)]/40 focus-within:border-[var(--primary)]/50 focus-within:ring-2 focus-within:ring-[var(--primary-soft)]';
    $classes = trim("{$baseClasses} {$hoverClasses} {$shadow}");
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
