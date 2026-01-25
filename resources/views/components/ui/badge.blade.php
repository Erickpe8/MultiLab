@props([
    'variant' => 'info',
])

@php
    $variants = [
        'success' => 'bg-[var(--success)]/20 text-[var(--success)] border border-[var(--success)]/30',
        'warning' => 'bg-[var(--warning)]/20 text-[var(--warning)] border border-[var(--warning)]/30',
        'danger' => 'bg-[var(--danger)]/20 text-[var(--danger)] border border-[var(--danger)]/30',
        'muted' => 'bg-[var(--border)]/15 text-[var(--text-secondary)] border border-[var(--border)]/40',
        'info' => 'bg-[var(--primary-soft)] text-[var(--primary)] border border-[var(--primary)]/30',
    ];

    $variantClasses = $variants[$variant] ?? $variants['info'];
    $baseClasses = 'font-primary inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-semibold tracking-[0.2em] uppercase rounded-full';
    $classes = trim("{$baseClasses} {$variantClasses}");
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
