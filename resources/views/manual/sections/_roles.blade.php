<div class="space-y-5">
    <div>
        <p class="text-xs uppercase tracking-[0.4em] text-[var(--text-muted)]">Sección 3 · Roles</p>
        <h2 class="text-2xl font-semibold text-[var(--text)]">Capacidades y flujos por rol</h2>
    </div>
    <p class="text-sm text-[var(--text-muted)]">
        Los cuatro roles disponibles (Super Administrador, Administrador Auxiliar, docente y estudiante) están descritos en esta sección. Cada rol aparece con lo que puede hacer, lo que debe evitar y los flujos típicos dentro de la plataforma.
    </p>
    <div class="grid gap-4 md:grid-cols-2">
        @foreach ($roleCards as $card)
            <article class="rounded-3xl border border-[var(--border)] bg-[var(--card)] p-5 shadow-sm">
                <div class="flex flex-wrap items-baseline justify-between gap-3">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-[var(--text-muted)]">Rol</p>
                        <h3 class="text-xl font-semibold text-[var(--text)]">{{ $card['title'] }}</h3>
                    </div>
                    <span class="rounded-full border border-[var(--border)] px-3 py-1 text-xs font-semibold uppercase tracking-[0.3em] text-[var(--text-muted)]">
                        {{ $card['slug'] }}
                    </span>
                </div>
                <p class="mt-2 text-sm text-[var(--text-muted)]">{{ $card['overview'] }}</p>
                <div class="mt-4 grid gap-4 md:grid-cols-2 text-sm text-[var(--text-muted)]">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-[var(--text-muted)]">Qué puede hacer</p>
                        <ul class="mt-2 space-y-2">
                            @foreach ($card['capabilities'] as $capability)
                                <li class="flex gap-2">
                                    <span class="text-[var(--accent)]">•</span>
                                    <span>{{ $capability }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-[var(--text-muted)]">Qué no puede hacer</p>
                        <ul class="mt-2 space-y-2">
                            @foreach ($card['limitations'] as $limitation)
                                <li class="flex gap-2">
                                    <span class="text-[var(--accent)]">•</span>
                                    <span>{{ $limitation }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="mt-4 rounded-2xl bg-[var(--border)]/40 p-4 text-sm text-[var(--text)]">
                    <p class="text-xs uppercase tracking-[0.3em] text-[var(--text-muted)]">Flujos típicos</p>
                    <ol class="mt-2 space-y-2 text-[var(--text-muted)]">
                        @foreach ($card['flows'] as $flow)
                            <li class="flex gap-3">
                                <span class="flex-shrink-0 rounded-full bg-[var(--text-muted)]/10 px-2 py-0.5 text-[0.65rem] font-semibold text-[var(--text)]">{{ $loop->iteration }}</span>
                                <span>{{ $flow }}</span>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </article>
        @endforeach
    </div>
</div>
