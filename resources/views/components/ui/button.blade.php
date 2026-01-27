@props([
    'variant' => 'primary',
    'href' => null,
    'type' => 'button',
])

@php
    $variants = [
        'primary' => 'bg-[var(--primary)] text-white border border-transparent shadow-sm hover:bg-[var(--primary-600)] focus-visible:ring-[var(--primary)]',
        'secondary' => 'bg-[var(--surface)] text-[var(--primary)] border border-[var(--border)] hover:bg-[var(--surface)]/90 focus-visible:ring-[var(--primary)]',
        'ghost' => 'bg-transparent text-[var(--primary)] border border-transparent hover:text-[var(--primary-600)] hover:bg-[var(--primary-soft)] focus-visible:ring-[var(--primary)]',
        'danger' => 'bg-[var(--danger)] text-white border border-transparent shadow-sm hover:bg-[var(--danger)]/90 focus-visible:ring-[var(--danger)]',
    ];

    $variantClasses = $variants[$variant] ?? $variants['primary'];
    $baseClasses = 'font-primary inline-flex items-center justify-center gap-2 rounded-2xl px-4 py-2 text-sm font-semibold tracking-wide transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--background)]';

    $isDisabled = $attributes->has('disabled');
    $disabledClasses = $isDisabled ? 'opacity-50 pointer-events-none cursor-not-allowed' : '';
    $classes = trim("{$baseClasses} {$variantClasses} {$disabledClasses}");
    $linkAttributes = $attributes->except('disabled');
@endphp

@if ($href)
    <a href="{{ $isDisabled ? '#' : $href }}"
        {{ $linkAttributes->merge(['class' => $classes, 'role' => 'button']) }}
        @if($isDisabled) aria-disabled="true" tabindex="-1" @endif>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
