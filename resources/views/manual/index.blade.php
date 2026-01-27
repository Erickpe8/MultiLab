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
    <div class="max-w-6xl mx-auto space-y-8 py-6 overflow-x-hidden">
        <header class="space-y-6">
            <div class="text-xs uppercase tracking-[0.4em] text-[var(--text-muted)]">Manual de Usuario</div>
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <h1 class="flex-1 text-3xl font-semibold text-[var(--text)]">Manual de Usuario MultiLab</h1>
                <button
                    type="button"
                    onclick="window.print()"
                    class="manual-print-hide mt-1 inline-flex shrink-0 items-center justify-center gap-2 h-10 w-fit rounded-full border border-[var(--border)] bg-[var(--card)] px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-[var(--text-muted)] transition hover:border-[var(--accent)]/70 hover:text-[var(--text)] dark:bg-[color-mix(in oklab, var(--card), rgba(255,255,255,0.08))]"
                >
                    Imprimir / PDF
                </button>
            </div>
            <div class="max-w-3xl text-sm leading-relaxed text-[var(--text-muted)] space-y-4">
                <p class="text-[var(--accent)]">
                    Esta guía reúne los datos clave de operación para el laboratorio, con el objetivo de centralizar la información necesaria para el uso cotidiano del sistema, sin introducir módulos nuevos ni alterar la estructura existente.
                </p>

                <p>
                    El contenido describe de forma clara los roles reales del sistema, el funcionamiento del Panel principal (dashboard), la gestión de usuarios, las reservas de espacios, el manejo de materiales y los procesos de préstamo asociados.
                    A lo largo del manual se hace énfasis en ejemplos prácticos y pasos concretos que permiten comprender los límites y responsabilidades de cada rol dentro del flujo operativo.
                </p>

                <p>
                    Asimismo, se detallan los estados críticos de los procesos —Pendiente, Aprobada, En uso y Finalizada— y se explica qué ocurre cuando los materiales son reportados en mantenimiento o cuando existen préstamos vencidos.
                    El manual incluye orientaciones específicas para el registro de nuevos materiales, garantizando que estos procedimientos se realicen sin afectar la estructura actual del sistema.
                </p>

                <p>
                    Se establece la relación entre reservas e inventario mediante el uso conjunto de equipos y la gestión del Aula B202, y se explican los mensajes que el sistema presenta al usuario junto con su significado operativo.
                    Además, se ofrecen recomendaciones de uso diario, buenas prácticas y puntos de control que ayudan a mantener la consistencia y el orden en la operación del laboratorio.
                </p>

                <p>
                    El contenido está orientado a la operación real del laboratorio universitario y se mantiene alineado con la evolución del sistema, respetando las migraciones vigentes.
                    Se invita a consultar esta guía antes de tomar decisiones críticas o comunicar excepciones, ya que el Panel principal permite visualizar alertas y solicitudes de forma inmediata.
                </p>

                <p>
                    Finalmente, se describen situaciones habituales relacionadas con reservas y préstamos, se aclaran las condiciones de devolución y mantenimiento, y se resalta la importancia de coordinar inventario y reservas para evitar duplicados.
                    También se enfatiza la trazabilidad como elemento clave para auditorías futuras, se recomienda revisar el Panel principal antes de responder solicitudes y se recuerda que las acciones concretas deben ejecutarse desde los módulos especializados.
                    En caso de dudas menores, se sugiere consultar el apartado de preguntas frecuentes (FAQ).
                </p>
            </div>

        </header>
        <nav class="grid w-full grid-cols-2 gap-3 rounded-3xl border border-[var(--border)] bg-[var(--card)] p-3 shadow-sm sm:grid-cols-3 lg:grid-cols-6">
            @foreach ($tocSections ?? [] as $section)
                <a
                    href="#{{ $section['id'] }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-transparent bg-[var(--bg)]/50 px-3 py-2 text-[0.65rem] font-semibold uppercase tracking-[0.3em] text-[var(--text-muted)] transition hover:border-[var(--accent)]/70 hover:text-[var(--text)]"
                >
                    <span class="text-[var(--accent)]">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <span class="truncate">{{ $section['label'] }}</span>
                </a>
            @endforeach
        </nav>
        <div class="space-y-6">
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
</x-app-layout>
