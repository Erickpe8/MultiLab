@props(['messages'])

@if ($messages)
    <ul
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 5000)"
        x-show="show"
        x-transition.opacity.duration.300ms
        {{ $attributes->merge(['class' => 'mt-2 space-y-1 text-sm leading-relaxed !font-bold !text-red-600 dark:!text-red-300']) }}
    >
        @foreach ((array) $messages as $message)
            <li class="!font-bold !text-red-600 dark:!text-red-300">{{ $message }}</li>
        @endforeach
    </ul>
@endif
