@section('title', 'Manual de Usuario MultiLab')

@push('head_end')
    <style>
        @media print {
            body {
                background-color: #ffffff;
            }
            .manual-toc,
            .manual-print-hide {
                display: none !important;
            }
            .manual-section {
                page-break-inside: avoid;
            }
        }
    </style>
@endpush

<x-app-layout>
    <div class="max-w-6xl mx-auto space-y-8 py-6">
        <header class="space-y-6">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-3">
                    <p class="text-xs uppercase tracking-[0.4em] text-[var(--text-muted)]">Manual de Usuario</p>
                    <h1 class="text-3xl font-semibold text-[var(--text)]">Manual de Usuario MultiLab</h1>
                    <div class="space-y-1 text-sm text-[var(--text-muted)]">
                        <p>Esta guía centraliza la operación diaria del laboratorio sin inventar módulos nuevos.</p>
                        <p>Describe roles reales y procesos del Panel principal (dashboard), gestión de usuarios, reservas, materiales y préstamos.</p>
                        <p>Incluye ejemplos y pasos para que cada rol entienda sus límites.</p>
                        <p>Explica estados concretos como Pendiente, Aprobada, En uso y Finalizada.</p>
                        <p>Precisa qué ocurre cuando los materiales se reportan de mantenimiento o vencidos.</p>
                        <p>Ofrece pasos para registrar materiales nuevos sin alterar la estructura.</p>
                        <p>Conecta reservas con inventario al relacionar equipos de Aula B202.</p>
                        <p>Detalla los mensajes más comunes que verás en pantalla.</p>
                        <p>Incluye recomendaciones del día a día y buenas prácticas.</p>
                        <p>Se actualiza según lo haga el sistema, respetando las migraciones actuales.</p>
                        <p>Está pensado para la operación real en el laboratorio universitario.</p>
                        <p>Utiliza este contenido antes de tomar decisiones críticas o comunicar excepciones.</p>
                    </div>
                </div>
                <button
                    type="button"
                    onclick="window.print()"
                    class="manual-print-hide inline-flex items-center justify-center rounded-full border border-[var(--border)] bg-[var(--card)] px-4 py-2 text-xs font-semibold uppercase tracking-[0.3em] text-[var(--text-muted)] transition hover:border-[var(--accent)] hover:text-[var(--text)]"
                >
                    Imprimir / PDF
                </button>
            </div>
            @php
                $roleLabels = [
                    'superadmin' => 'Super Administrador',
                    'aux_admin' => 'Administrador Auxiliar',
                    'docente' => 'Docente',
                    'estudiante' => 'Estudiante',
                ];
            @endphp
            <div class="flex flex-wrap gap-2 text-xs font-semibold uppercase tracking-[0.35em] text-[var(--text-muted)]">
                @foreach (($roleChips ?? []) as $chip)
                    <span class="rounded-full border border-[var(--border)] px-3 py-1 text-[var(--text-muted)]">
                        {{ $roleLabels[$chip] ?? ucfirst(str_replace('_', ' ', $chip)) }}
                    </span>
                @endforeach
            </div>
            <div class="lg:hidden">
                <details class="rounded-3xl border border-[var(--border)] bg-[var(--card)] p-4 text-sm shadow-sm">
                    <summary class="cursor-pointer font-semibold text-[var(--text)]">Índice rápido</summary>
                    <nav class="mt-3 space-y-2">
                        @foreach ($tocSections ?? [] as $section)
                            <a
                                href="#{{ $section['id'] }}"
                                class="flex items-center gap-3 rounded-2xl px-3 py-2 text-xs font-semibold uppercase tracking-[0.3em] text-[var(--text-muted)] transition hover:bg-[var(--accent)]/10 hover:text-[var(--text)]"
                            >
                                <span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <span>{{ $section['label'] }}</span>
                            </a>
                        @endforeach
                    </nav>
                </details>
            </div>
        </header>
        <div class="lg:flex lg:gap-6">
            <aside class="manual-toc hidden lg:block w-64 shrink-0 rounded-3xl border border-[var(--border)] bg-[var(--card)] p-4 shadow-sm sticky top-24">
                <p class="text-xs uppercase tracking-[0.3em] text-[var(--text-muted)]">Índice</p>
                <nav class="mt-4 space-y-2">
                    @foreach ($tocSections ?? [] as $section)
                        <a
                            href="#{{ $section['id'] }}"
                            class="flex items-center gap-3 rounded-2xl px-3 py-2 text-xs font-semibold uppercase tracking-[0.3em] text-[var(--text-muted)] transition hover:bg-[var(--accent)]/10 hover:text-[var(--text)]"
                        >
                            <span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <span>{{ $section['label'] }}</span>
                        </a>
                    @endforeach
                </nav>
            </aside>
            <div class="flex-1 space-y-6">
                <section id="intro" class="manual-section scroll-mt-28 space-y-6">
                    <article class="rounded-3xl border border-[var(--border)] bg-[var(--card)] p-6 shadow-sm">
                        @include('manual.sections._intro')
                    </article>
                </section>
                <section id="access" class="manual-section scroll-mt-28 space-y-6">
                    <article class="rounded-3xl border border-[var(--border)] bg-[var(--card)] p-6 shadow-sm">
                        @include('manual.sections._access')
                    </article>
                </section>
                <section id="roles" class="manual-section scroll-mt-28 space-y-6">
                    <article class="rounded-3xl border border-[var(--border)] bg-[var(--card)] p-6 shadow-sm">
                        @include('manual.sections._roles')
                    </article>
                </section>
                <section id="dashboard" class="manual-section scroll-mt-28 space-y-6">
                    <article class="rounded-3xl border border-[var(--border)] bg-[var(--card)] p-6 shadow-sm">
                        @include('manual.sections._dashboard')
                    </article>
                </section>
                <section id="users" class="manual-section scroll-mt-28 space-y-6">
                    <article class="rounded-3xl border border-[var(--border)] bg-[var(--card)] p-6 shadow-sm">
                        @include('manual.sections._users')
                    </article>
                </section>
                <section id="reservations" class="manual-section scroll-mt-28 space-y-6">
                    <article class="rounded-3xl border border-[var(--border)] bg-[var(--card)] p-6 shadow-sm">
                        @include('manual.sections._reservations')
                    </article>
                </section>
                <section id="materials" class="manual-section scroll-mt-28 space-y-6">
                    <article class="rounded-3xl border border-[var(--border)] bg-[var(--card)] p-6 shadow-sm">
                        @include('manual.sections._materials')
                    </article>
                </section>
                <section id="loans" class="manual-section scroll-mt-28 space-y-6">
                    <article class="rounded-3xl border border-[var(--border)] bg-[var(--card)] p-6 shadow-sm">
                        @include('manual.sections._loans')
                    </article>
                </section>
                <section id="audit" class="manual-section scroll-mt-28 space-y-6">
                    <article class="rounded-3xl border border-[var(--border)] bg-[var(--card)] p-6 shadow-sm">
                        @include('manual.sections._audit')
                    </article>
                </section>
                <section id="logout" class="manual-section scroll-mt-28 space-y-6">
                    <article class="rounded-3xl border border-[var(--border)] bg-[var(--card)] p-6 shadow-sm">
                        @include('manual.sections._logout')
                    </article>
                </section>
                <section id="recommendations" class="manual-section scroll-mt-28 space-y-6">
                    <article class="rounded-3xl border border-[var(--border)] bg-[var(--card)] p-6 shadow-sm">
                        @include('manual.sections._recommendations')
                    </article>
                </section>
                <section id="faq" class="manual-section scroll-mt-28 space-y-6">
                    <article class="rounded-3xl border border-[var(--border)] bg-[var(--card)] p-6 shadow-sm">
                        @include('manual.sections._faq')
                    </article>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
