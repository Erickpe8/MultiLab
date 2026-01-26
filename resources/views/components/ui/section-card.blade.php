@props(['title', 'subtitle' => null])

<div class="rounded-2xl bg-[var(--card)] border border-[color:var(--border)] shadow-sm card-title-blue">
    <div class="p-6 md:p-8 space-y-6">
        <header class="space-y-1">
            <h3 class="text-xl font-bold theme-text">
                {{ $title }}
            </h3>
            @if ($subtitle)
                <p class="text-sm text-[color:var(--text-muted)]">
                    {{ $subtitle }}
                </p>
            @endif
        </header>
        <div>
            {{ $slot }}
        </div>
    </div>
</div>
