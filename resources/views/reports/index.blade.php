@section('title', 'Métricas e Informes')
<x-app-layout>

    <div class="max-w-7xl mx-auto space-y-10 py-6" data-reports-page>
        <header class="space-y-2">
            <p class="text-xs uppercase tracking-[0.4em] text-[var(--text-muted)]">Solo superadmin</p>
            <h1 class="text-3xl font-semibold text-[var(--text)]">Métricas e Informes</h1>
            <p class="text-sm text-[var(--text-muted)]">
                Indicadores operativos y trazabilidad del laboratorio.
            </p>
        </header>

        <section class="space-y-4">
            <div class="flex items-center justify-between gap-4">
                <div class="min-w-0 space-y-1">
                    <p class="text-xs uppercase tracking-[0.4em] text-[var(--text-muted)]">Resumen</p>
                    <h2 class="text-xl font-semibold text-[var(--text)]">Estado actual</h2>
                </div>
                <span class="text-xs text-[var(--text-muted)] whitespace-nowrap" data-summary-updated>Actualizando…</span>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4" data-reports-summary>
                @for ($i = 0; $i < 4; $i++)
                    <article class="rounded-2xl border border-[var(--border)] bg-[var(--card)] p-4 shadow-sm">
                        <div class="h-6 w-2/3 rounded-lg bg-[var(--border)]/50 animate-pulse"></div>
                        <div class="mt-6 h-6 w-1/2 rounded-lg bg-[var(--border)]/50 animate-pulse"></div>
                        <div class="mt-3 h-3 w-3/4 rounded-lg bg-[var(--border)]/40 animate-pulse"></div>
                    </article>
                @endfor
            </div>

            <p class="text-sm text-amber-500 hidden" data-summary-error></p>
        </section>

        <section class="space-y-4">
            <div class="flex items-center justify-between gap-4">
                <div class="min-w-0 space-y-1">
                    <p class="text-xs uppercase tracking-[0.4em] text-[var(--text-muted)]">Actividad</p>
                    <h2 class="text-xl font-semibold text-[var(--text)]">Últimos 14 días</h2>
                </div>
                <span class="text-xs text-[var(--text-muted)] whitespace-nowrap" data-activity-updated>Actualizando…</span>
            </div>

            <div class="mt-2 w-full max-w-3xl mx-auto flex flex-wrap items-center justify-center gap-3 text-xs font-medium rounded-full border border-[var(--border)] bg-[var(--card)] px-3 py-1" data-activity-legend>
                {{-- JS injects legend chips --}}
            </div>

            <div class="rounded-3xl border border-[var(--border)] bg-[var(--card)] p-4" data-activity-chart-wrapper>
                <div class="relative h-72" data-reports-chart>
                    <div class="absolute inset-0 flex items-center justify-center text-xs text-[var(--text-muted)]"
                         data-activity-loading>
                        Cargando gráfica…
                    </div>
                </div>
                <p class="mt-3 text-sm text-[var(--text-muted)] hidden" data-activity-empty>
                    Sin actividad registrada en los últimos 14 días.
                </p>
                <p class="mt-3 text-sm text-red-500 hidden" data-activity-error></p>
            </div>
        </section>

        <section class="space-y-4">
            <div class="flex items-center justify-between gap-4">
                <div class="min-w-0 space-y-1">
                    <p class="text-xs uppercase tracking-[0.4em] text-[var(--text-muted)]">Inventario y alertas</p>
                    <h2 class="text-xl font-semibold text-[var(--text)]">Materiales + préstamos</h2>
                </div>
                <span class="text-xs text-[var(--text-muted)] whitespace-nowrap" data-inventory-updated>Actualizando…</span>
            </div>

            <div class="grid gap-4 lg:grid-cols-3">
                <article class="rounded-3xl border border-[var(--border)] bg-[var(--card)] p-4 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-[var(--text)]">Bajo stock</p>
                            <p class="text-xs text-[var(--text-muted)]">Materiales próximos al mínimo</p>
                        </div>
                        <span class="text-xs text-[var(--text-muted)]" data-inventory-count="low-stock">0</span>
                    </div>
                    <ul class="space-y-3 text-sm text-[var(--text-muted)]" data-inventory-list="low-stock">
                        @for ($i = 0; $i < 2; $i++)
                            <li>
                                <div class="h-3 w-full rounded-lg bg-[var(--border)]/50 animate-pulse"></div>
                                <div class="mt-1 h-2 w-5/6 rounded-lg bg-[var(--border)]/30 animate-pulse"></div>
                            </li>
                        @endfor
                    </ul>
                    <p class="text-xs text-[var(--text-muted)] hidden" data-inventory-empty data-inventory-target="low-stock">
                        Sin materiales en alerta.
                    </p>
                </article>

                <article class="rounded-3xl border border-[var(--border)] bg-[var(--card)] p-4 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-[var(--text)]">Préstamos vencidos</p>
                            <p class="text-xs text-[var(--text-muted)]">Requieren gestión inmediata</p>
                        </div>
                        <span class="text-xs text-[var(--text-muted)]" data-inventory-count="overdue">0</span>
                    </div>
                    <ul class="space-y-3 text-sm text-[var(--text-muted)]" data-inventory-list="overdue">
                        @for ($i = 0; $i < 2; $i++)
                            <li>
                                <div class="h-3 w-full rounded-lg bg-[var(--border)]/50 animate-pulse"></div>
                                <div class="mt-1 h-2 w-2/3 rounded-lg bg-[var(--border)]/30 animate-pulse"></div>
                            </li>
                        @endfor
                    </ul>
                    <p class="text-xs text-[var(--text-muted)] hidden" data-inventory-empty data-inventory-target="overdue">
                        No hay préstamos vencidos.
                    </p>
                </article>

                <article class="rounded-3xl border border-[var(--border)] bg-[var(--card)] p-4 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-[var(--text)]">Top materiales</p>
                            <p class="text-xs text-[var(--text-muted)]">Más solicitados en 30 días</p>
                        </div>
                        <span class="text-xs text-[var(--text-muted)]" data-inventory-count="top-materials">0</span>
                    </div>
                    <ul class="space-y-3 text-sm text-[var(--text-muted)]" data-inventory-list="top-materials">
                        @for ($i = 0; $i < 2; $i++)
                            <li>
                                <div class="h-3 w-full rounded-lg bg-[var(--border)]/50 animate-pulse"></div>
                                <div class="mt-1 h-2 w-2/3 rounded-lg bg-[var(--border)]/30 animate-pulse"></div>
                            </li>
                        @endfor
                    </ul>
                    <p class="text-xs text-[var(--text-muted)] hidden"
                       data-inventory-empty data-inventory-target="top-materials">
                        Sin movimientos recientes.
                    </p>
                </article>
            </div>

            <p class="text-sm text-red-500 hidden" data-inventory-error></p>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @vite('resources/js/reports/index.js')
</x-app-layout>
