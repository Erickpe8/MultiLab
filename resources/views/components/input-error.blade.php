@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'mt-2 space-y-1 text-sm leading-relaxed !font-bold !text-rose-600 dark:!text-rose-300']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
