@props([
    'name',
    'size' => 'md',
    'variant' => 'muted',
    'solid' => false,
    'title' => null,
])

@php
    use BladeUI\Icons\Exceptions\SvgNotFound;
    use Illuminate\Support\Str;

    $sizeClasses = [
        'xs' => 'h-3.5 w-3.5',
        'sm' => 'h-4 w-4',
        'md' => 'h-5 w-5',
        'lg' => 'h-6 w-6',
        'xl' => 'h-7 w-7',
    ];

    $variantClasses = [
        'muted' => 'text-gray-500 dark:text-gray-400',
        'primary' => 'text-[var(--primary)]',
        'success' => 'text-emerald-600 dark:text-emerald-400',
        'warning' => 'text-amber-600 dark:text-amber-400',
        'danger' => 'text-red-600 dark:text-red-400',
        'info' => 'text-sky-600 dark:text-sky-400',
    ];

    $aliases = config('icons.aliases', []);
    $lookup = (string) Str::of($name ?? '')->trim()->lower();
    $resolved = $aliases[$lookup] ?? ($name ?? '');

    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
    $variantClass = $variantClasses[$variant] ?? $variantClasses['muted'];

    $attributes = $attributes->class([$sizeClass, $variantClass]);

    $resolvedTitle = $title ?? $attributes->get('title');

    if ($resolvedTitle) {
        $attributes = $attributes->merge(['title' => $resolvedTitle]);

        if (! $attributes->has('role')) {
            $attributes = $attributes->merge(['role' => 'img']);
        }
    } elseif (! $attributes->has('aria-hidden')) {
        $attributes = $attributes->merge(['aria-hidden' => 'true']);
    }

    $attributeArray = $attributes->getIterator()->getArrayCopy();
    $classAttribute = $attributeArray['class'] ?? '';
    unset($attributeArray['class']);

    $candidates = [];

    if ($solid && Str::startsWith($resolved, 'heroicon-o-')) {
        $candidates[] = Str::replaceFirst('heroicon-o-', 'heroicon-s-', $resolved);
    }

    if ($resolved) {
        $candidates[] = $resolved;
    }

    $candidates = array_values(array_unique(array_filter($candidates)));

    $renderedSvg = null;
    foreach ($candidates as $candidate) {
        try {
            $renderedSvg = svg($candidate, $classAttribute, $attributeArray);
            break;
        } catch (SvgNotFound) {
            // Continue to the next candidate until one works.
        }
    }
@endphp

@if ($renderedSvg)
    {!! $renderedSvg->toHtml() !!}
@else
    @php
        $fallbackAttributes = '';
        foreach ($attributeArray as $key => $value) {
            $fallbackAttributes .= ' '.$key.'="'.e($value).'"';
        }
    @endphp
    <svg viewBox="0 0 24 24"@if(trim($classAttribute)) class="{{ $classAttribute }}"@endif{!! $fallbackAttributes !!}>
        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" fill="none" />
        <text x="12" y="16" text-anchor="middle" fill="currentColor" font-size="12" font-weight="700">?</text>
    </svg>
@endif
