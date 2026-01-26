@props([
    'href' => '#',
    'label',
    'icon',
    'active' => false,
])

@php
    $stateClasses = $active
        ? 'bg-[var(--primary)]/10 text-[var(--primary)] font-medium'
        : 'hover:bg-[var(--border)]/10 text-[var(--text)]/70';
@endphp

<a href="{{ $href }}"
   class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-all duration-200 group {{ $stateClasses }}"
   @if($active) aria-current="page" @endif>
    <x-ui.icon name="{{ $icon }}" size="sm" class="text-current" />
    <span>{{ $label }}</span>
    {{ $slot }}
</a>
