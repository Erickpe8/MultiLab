@props([
    'variant' => 'primary',
    'href' => null,
    'type' => 'button',
])

@php
    $variants = [
        'primary' => 'bg-[var(--primary)] text-white border border-transparent shadow-sm hover:bg-[var(--primary-600)] focus-visible:ring-[var(--primary)]',
        'secondary' => 'bg-[var(--surface)] text-[var(--primary)] border border-[var(--border)] hover:bg-[var(--surface)]/90 focus-visible:ring-[var(--primary)]',
        'ghost' => 'bg-transparent text-[var(--text)] border border-transparent hover:bg-[var(--surface)]/80 focus-visible:ring-[var(--primary)]',
        'danger' => 'bg-[var(--danger)] text-white border border-transparent shadow-sm hover:bg-[var(--danger)]/90 focus-visible:ring-[var(--danger)]',
    ];

    $variantClasses = $variants[$variant] ?? $variants['primary'];
    $baseClasses = 'font-primary inline-flex items-center justify-center gap-2 rounded-2xl px-4 py-2 text-sm font-semibold tracking-wide transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--background)]';
    $classes = trim("{$baseClasses} {$variantClasses}");
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
