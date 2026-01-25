@extends('layouts.manual')

@section('title', 'Manual de Usuario')

@section('content')
    <section class="space-y-10">
        <div class="rounded-3xl border border-[var(--border)] bg-[var(--card)] shadow-soft p-8 space-y-4">
            <div class="space-y-2">
                <p class="text-xs font-semibold uppercase tracking-wide text-[var(--text-muted)]">
                    Manual de Usuario
                </p>
                <h1 class="text-3xl font-extrabold text-[var(--text)]">
                    Guías por roles para usar MultiLab
                </h1>
                <p class="text-sm text-[color:var(--text-muted)] max-w-3xl">
                    Encuentra el flujo de trabajo diario, acciones clave y estados recurrentes para cada perfil
                    operativo. Esta guía es pública y está pensada como referencia antes de iniciar ciclos de
                    préstamo, aprobación o devoluciones.
                </p>
            </div>
        </div>

        <div class="rounded-2xl border border-[var(--border)] bg-[var(--card)] shadow-soft p-6 space-y-4">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-[var(--text)]">Índice rápido</h2>
                    <p class="text-sm text-[color:var(--text-muted)]">
                        Salta rápidamente a la sección del rol que necesitas.
                    </p>
                </div>
                <span class="text-xs font-semibold tracking-wide uppercase text-[var(--accent)]">
                    Todos los roles
                </span>
            </div>

            <nav class="flex flex-wrap gap-3">
                @foreach ($rolesManual as $section)
                    <a href="#{{ $section['id'] }}"
                        class="inline-flex items-center rounded-xl border border-[var(--border)] px-4 py-2 text-sm font-medium text-[var(--text)] bg-[var(--card)] hover:border-[var(--accent)] hover:text-[var(--accent)] transition">
                        {{ $section['title'] }}
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="space-y-8">
            @foreach ($rolesManual as $section)
                <article id="{{ $section['id'] }}"
                    class="rounded-3xl border border-[var(--border)] bg-[var(--card)] shadow-soft p-8 space-y-6">
                    <header class="space-y-2">
                        <p class="text-xs font-semibold uppercase tracking-wide text-[var(--text-muted)]">Rol en
                            MultiLab</p>
                        <h3 class="text-2xl font-bold text-[var(--text)]">{{ $section['title'] }}</h3>
                        <p class="text-sm text-[color:var(--text-muted)]">
                            {{ $section['description'] }}
                        </p>
                    </header>

                    <div class="flex flex-wrap gap-2">
                        @foreach ($section['states'] as $state)
                            <span class="text-xs font-semibold uppercase tracking-wide px-3 py-1 rounded-full border border-transparent {{ $state['class'] }}">
                                {{ $state['label'] }}
                            </span>
                        @endforeach
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <h4 class="text-sm font-semibold text-[var(--text)]">Flujo típico</h4>
                            <ol class="mt-4 space-y-3 text-sm text-[color:var(--text-muted)]">
                                @foreach ($section['steps'] as $index => $step)
                                    <li class="flex gap-3">
                                        <span
                                            class="flex-shrink-0 rounded-full border border-[var(--border)] px-2 py-1 text-[var(--text)] text-[0.65rem] font-semibold">
                                            {{ $index + 1 }}
                                        </span>
                                        <p>{{ $step }}</p>
                                    </li>
                                @endforeach
                            </ol>
                        </div>

                        <div>
                            <h4 class="text-sm font-semibold text-[var(--text)]">Acciones clave</h4>
                            <ul class="mt-4 space-y-3 text-sm text-[color:var(--text-muted)]">
                                @foreach ($section['actions'] as $action)
                                    <li class="flex gap-3">
                                        <span class="mt-1 h-2 w-2 rounded-full bg-[var(--accent)]"></span>
                                        <span>{{ $action }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-dashed border-[var(--border)] bg-[var(--card)]/60 p-5 text-sm text-[color:var(--text-muted)]">
                        <p class="font-semibold text-[var(--text)]">Tips útiles</p>
                        <ul class="mt-3 space-y-2">
                            @foreach ($section['tips'] as $tip)
                                <li class="flex gap-2">
                                    <span class="text-[var(--accent)]">•</span>
                                    <span>{{ $tip }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endsection
