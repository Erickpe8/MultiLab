@php
    $homeCardBase = 'group block min-h-full rounded-2xl border border-[var(--border)] bg-white dark:bg-[var(--card)] p-6 shadow-sm text-[var(--text)] transition duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:border-[var(--primary)] focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--primary)] focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--card)]';
    $cardKicker = 'text-xs font-semibold uppercase tracking-[0.35em] text-[var(--text-muted)]';
    $cardTitleClass = 'mt-4 text-xl font-semibold leading-7 text-[var(--text)]';
    $cardBodyClass = 'mt-2 text-sm leading-6 text-[var(--text-muted)]';
    $cardCtaClass = 'mt-5 inline-flex items-center gap-2 text-sm font-semibold text-[var(--primary)] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[var(--primary)] focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--card)]';
    $iconWrapperClass = 'flex h-12 w-12 items-center justify-center rounded-2xl border border-[var(--border)] bg-white/60 text-[var(--primary)] dark:bg-white/5';
@endphp

<div class="space-y-8">
    <section class="rounded-3xl border border-[var(--border)] bg-[var(--card)] p-6 shadow-sm backdrop-blur-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.4em] text-[var(--text-muted)]">Bienvenido</p>
                <h2 class="text-2xl font-semibold text-[var(--text)]">
                    ¡Hola, {{ auth()->user()->name }}!
                </h2>
                <p class="mt-1 text-sm text-[var(--text-muted)]">
                    Accede a las secciones principales desde este panel personalizado.
                </p>
            </div>

            <div
                class="inline-flex items-center gap-2 rounded-full border border-[var(--border)] bg-[var(--border)]/20 px-4 py-2 text-xs font-semibold uppercase tracking-[0.3em] text-[var(--accent)]">
                <x-ui.icon name="heroicon-o-shield-check" size="sm" class="text-[var(--primary)]" />
                Cuenta activa
            </div>
        </div>
    </section>

    @foreach ($this->getSections() as $section)
        @if ($section['visibleCards']->isNotEmpty())
            <section class="space-y-4">
                <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.4em] text-[var(--text-muted)]">{{ $section['tag'] }}</p>
                        <h2 class="text-xl font-semibold text-[var(--text)]">{{ $section['title'] }}</h2>
                        @if (!empty($section['subtitle']))
                            <p class="text-sm text-[var(--text-muted)]">{{ $section['subtitle'] }}</p>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($section['visibleCards'] as $card)
                        <a href="{{ $card['href'] }}"
                           aria-disabled="{{ $card['hasRoute'] ? 'false' : 'true' }}"
                           class="{{ $homeCardBase }} {{ $card['hasRoute'] ? '' : 'cursor-not-allowed opacity-70' }}">
                            <div class="flex items-center justify-between gap-4">
                                <div class="{{ $iconWrapperClass }}">
                                    {{-- OJO: aquí NO uses :name en Blade si no estás seguro; usa name con {{ }} --}}
                                    <x-ui.icon name="{{ $card['icon'] }}" size="lg" />
                                </div>

                                <span class="{{ $cardKicker }}">
                                        {{ $card['badge'] ?? 'Acceso' }}
                                    </span>
                            </div>

                            <h3 class="{{ $cardTitleClass }}">{{ $card['title'] }}</h3>

                            <p class="{{ $cardBodyClass }}">
                                {{ $card['description'] }}
                            </p>

                            <div class="mt-4 flex flex-col gap-2">
                                <span
                                    class="{{ $cardCtaClass }} {{ $card['hasRoute'] ? 'group-hover:text-[var(--primary-600)] group-hover:underline' : '' }}">
                                        {{ $card['cta'] ?? 'Ir al módulo' }}
                                        <x-ui.icon name="siguiente" size="sm" class="text-[var(--primary)]" />
                                    </span>
                                @unless ($card['hasRoute'])
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold uppercase tracking-[0.35em] text-[var(--text-muted)]">
                                            <x-ui.icon name="advertencia" size="xs" class="text-yellow-500" />
                                            Próximamente
                                        </span>
                                @endunless
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    @endforeach
</div>
