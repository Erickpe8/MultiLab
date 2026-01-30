@props(['status'])

@if ($status)
    <p
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 5000)"
        x-show="show"
        x-transition.opacity.duration.300ms
        {{ $attributes->merge(['class' => 'mt-2 text-sm leading-relaxed !font-bold !text-red-600 dark:!text-red-300']) }}
    >
        {{ $status }}
    </p>
@endif
