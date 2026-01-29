<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <script>
        (function () {
            try {
                const stored = localStorage.getItem('theme');
                const theme = stored || 'light';
                if (!stored) {
                    localStorage.setItem('theme', 'light');
                }
                document.documentElement.dataset.theme = theme;
                document.documentElement.classList.toggle('dark', theme === 'dark');
                document.documentElement.style.colorScheme = theme;
            } catch (error) {
                document.documentElement.dataset.theme = 'light';
                document.documentElement.classList.remove('dark');
                document.documentElement.style.colorScheme = 'light';
            }
        })();
    </script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('images/multilab.svg') }}">
    <link rel="alternate icon" href="{{ asset('images/multilab.svg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased font-primary text-[var(--text)] overflow-x-hidden bg-[var(--bg)]">
    <div class="relative min-h-[100svh] bg-[var(--bg)]">
        <div class="bg-overlay opacity-20"></div>
        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(37,99,235,0.15)_1px,transparent_0)] bg-[size:24px_24px] opacity-20 pointer-events-none">
        </div>

        @php
            $modules = [
                [
                    'code' => 'M1',
                    'name' => 'Control de acceso a PCs',
                    'objective' =>
                        'Registra quién utiliza cada estación del laboratorio, con trazabilidad y observaciones asociadas para respaldar el control operativo.',
                ],
                [
                    'code' => 'M2',
                    'name' => 'Reservas del aula y recursos',
                    'objective' =>
                        'Centraliza las reservas para evitar choques de disponibilidad y mejorar la planificación de uso del laboratorio.',
                ],
                [
                    'code' => 'M3',
                    'name' => 'Préstamos y devoluciones',
                    'objective' =>
                        'Administra el préstamo de herramientas y materiales con estados claros, responsables y registro de entrega/devolución.',
                ],
                [
                    'code' => 'M4',
                    'name' => 'Inventario del laboratorio y bodega',
                    'objective' =>
                        'Mantiene un registro actualizado de equipos, herramientas y materiales, con historial de movimientos y control de condiciones.',
                ],
                [
                    'code' => 'M5',
                    'name' => 'Históricos y observaciones',
                    'objective' =>
                        'Genera históricos que respaldan la gestión del laboratorio: uso, novedades, incidencias y seguimiento por periodos.',
                ],
            ];
        @endphp

        {{-- HEADER --}}
        <header
            class="sticky top-0 z-50 h-16 lg:h-20 border-b border-slate-200/70 dark:border-white/10 bg-white/90 dark:bg-slate-950/70 backdrop-blur">
            <div class="max-w-6xl mx-auto flex h-full items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
                <a href="{{ route('welcome') }}" class="flex items-center gap-3">
                    <x-brand.logo variant="horizontal" class="h-10 w-auto" />
                    <span class="text-lg sm:text-xl font-semibold tracking-wide text-[var(--primary)]">
                        {{ config('app.name') }}
                    </span>
                </a>
                <div class="flex items-center gap-3">
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}"
                            class="inline-flex h-11 items-center justify-center rounded-full px-6 text-xs font-semibold uppercase tracking-[0.3em] text-[var(--primary)] transition ring-1 ring-blue-300/60 hover:ring-blue-400 dark:ring-blue-300/40 dark:hover:ring-blue-300/70">
                            Iniciar sesión
                        </a>
                    @endif
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="inline-flex h-11 items-center justify-center rounded-full bg-blue-600 px-6 text-xs font-semibold uppercase tracking-[0.3em] text-white transition hover:bg-blue-700">
                            Registrarse
                        </a>
                    @endif
                </div>
            </div>
        </header>

        {{-- MAIN --}}
        <main class="flex-1 min-h-[calc(100vh-140px)] pt-20 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-14 py-10 lg:py-14">

                <!-- WELCOME_REDESIGN_V1 -->
                <section
                    class="relative rounded-[32px] bg-white/80 dark:bg-white/5 ring-1 ring-slate-200/70 dark:ring-white/10 shadow-[0_10px_30px_-20px_rgba(0,0,0,0.35)] overflow-hidden">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(255,255,255,0.35),transparent_45%)] opacity-20 pointer-events-none"></div>
                    <div class="relative z-10 grid gap-6 lg:grid-cols-2 items-start px-6 sm:px-10 py-8">
                        <div class="space-y-5">
                            <p class="text-xs uppercase tracking-[0.25em] text-[var(--text-muted)]">Ingeniería de
                                Software · FESC</p>
                            <h1
                                class="text-3xl sm:text-4xl lg:text-5xl font-semibold leading-tight tracking-normal text-[var(--primary)]">
                                MultiLab impulsa la operación ágil del Laboratorio B202 y la bodega
                            </h1>
                            <p class="text-base leading-relaxed text-slate-600 dark:text-slate-300 max-w-2xl">
                                Controla accesos, gestiona reservas, supervisa préstamos y devoluciones, y garantiza
                                trazabilidad y reportes claros para cada turno.
                            </p>
                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('manual.index') }}"
                                    class="inline-flex h-11 items-center justify-center rounded-full bg-blue-600 px-6 text-xs font-semibold uppercase tracking-[0.3em] text-white transition hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500 dark:bg-blue-500 dark:hover:bg-blue-400">
                                    Explorar manual
                                </a>
                                @if (Route::has('login'))
                                    <x-ui.button variant="ghost" href="{{ route('login') }}"
                                        class="h-11 px-6 text-xs font-semibold uppercase tracking-[0.3em] rounded-full border border-blue-500/40 text-[var(--primary)] transition ring-1 ring-blue-300/60 hover:ring-blue-400 dark:border-blue-400 dark:text-blue-400 dark:ring-blue-300/40 dark:hover:ring-blue-300/70">
                                        Iniciar sesión
                                    </x-ui.button>
                                @endif
                                @if (Route::has('register'))
                                    <x-ui.button variant="ghost" href="{{ route('register') }}"
                                        class="h-11 px-6 text-xs font-semibold uppercase tracking-[0.3em] rounded-full border border-[var(--border)] text-[var(--text)] transition ring-1 ring-slate-200/70 hover:ring-slate-300 dark:ring-white/10">
                                        Registrarse
                                    </x-ui.button>
                                @endif
                            </div>
                        </div>
                        <div
                            class="flex flex-col rounded-3xl bg-white/80 dark:bg-white/5 ring-1 ring-slate-200/70 dark:ring-white/10 px-6 py-8 text-sm text-slate-600 dark:text-slate-300 shadow-[0_10px_30px_-20px_rgba(0,0,0,0.35)]">
                            <div class="text-xs uppercase tracking-[0.4em] text-[var(--accent)]">Operación</div>
                            <p class="text-base leading-relaxed text-slate-600 dark:text-slate-300">
                                Centraliza reservas, monitorea inventario y entrega reportes claros para cada
                                responsable del laboratorio.
                                <br>
                                Trazabilidad por turno y responsable para asegurar cumplimiento institucional.
                            </p>
                            <div class="relative mt-6 w-full overflow-hidden rounded-2xl bg-slate-900/80 aspect-[16/9]">
                                <img src="{{ asset('images/Bodega2.png') }}" alt="Operación"
                                    class="w-full h-full object-cover object-center" />
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/0 to-transparent"></div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="space-y-6 pb-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <article
                            class="flex flex-col rounded-3xl bg-white/80 dark:bg-white/5 ring-1 ring-slate-200/70 dark:ring-white/10 shadow-[0_10px_30px_-20px_rgba(0,0,0,0.35)] transition hover:-translate-y-0.5 hover:shadow-[0_10px_30px_-20px_rgba(15,23,42,0.35)]">
                            <div class="relative w-full aspect-[16/7] overflow-hidden rounded-t-3xl">
                                <img src="{{ asset('images/Bodega1.png') }}" alt="Laboratorio B202"
                                    class="w-full h-full object-cover object-center" />
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/20 to-transparent">
                                </div>
                            </div>
                            <div class="flex flex-col flex-1 p-8 space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="space-y-1">
                                        <p class="text-[0.65rem] uppercase tracking-[0.25em] text-[var(--text-muted)]">
                                            ¿Qué hace {{ config('app.name') }}?</p>
                                        <h2
                                            class="text-2xl font-semibold text-[var(--primary)] leading-tight uppercase tracking-[0.3em]">
                                            Operación</h2>
                                    </div>
                                    <x-ui.icon name="cog" size="lg" class="text-[var(--primary)]" />
                                </div>
                                <p class="text-2xl font-semibold text-[var(--primary)] leading-tight">Visibilidad total
                                    del laboratorio y la bodega</p>
                                <p class="text-sm leading-relaxed text-slate-600 dark:text-slate-300">
                                    Centraliza el control de accesos, reservas, préstamos y devoluciones del Laboratorio
                                    B202 y la bodega, asignando responsables por turno y manteniendo trazabilidad
                                    operativa con registros y alertas para respaldo institucional.
                                </p>
                            </div>
                        </article>

                        <article
                            class="flex flex-col rounded-3xl bg-white/80 dark:bg-white/5 ring-1 ring-slate-200/70 dark:ring-white/10 shadow-[0_10px_30px_-20px_rgba(0,0,0,0.35)] transition hover:-translate-y-0.5 hover:shadow-[0_10px_30px_-20px_rgba(15,23,42,0.35)]">
                            <div class="relative aspect-[16/7] w-full overflow-hidden rounded-t-3xl">
                                <img src="{{ asset('images/Bodega3.png') }}" alt="Manual de usuario"
                                    class="h-full w-full object-cover object-center" />
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/20 to-transparent">
                                </div>
                            </div>
                            <div class="flex flex-col flex-1 p-8 space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="space-y-1">
                                        <p class="text-[0.65rem] uppercase tracking-[0.25em] text-[var(--text-muted)]">
                                            Manual de Usuario</p>
                                        <h2
                                            class="text-2xl font-semibold text-[var(--primary)] leading-tight uppercase tracking-[0.3em]">
                                            Documentación</h2>
                                    </div>
                                    <x-ui.icon name="book" size="lg" class="text-[var(--primary)]" />
                                </div>
                                <p class="text-2xl font-semibold text-[var(--primary)] leading-tight">Guías rápidas por
                                    rol</p>
                                <p class="text-sm leading-relaxed text-slate-600 dark:text-slate-300">
                                    Ofrece guías operativas por rol para docentes, administrativos y equipos técnicos,
                                    con pasos claros, responsabilidades definidas y criterios de uso que facilitan la
                                    ejecución adecuada de cada proceso del laboratorio.
                                </p>
                                <div class="mt-auto flex items-center justify-start gap-4">
                                <a href="{{ route('manual.index') }}"
                                    class="inline-flex h-11 items-center justify-center gap-2 rounded-full bg-blue-600 px-6 text-xs font-semibold uppercase tracking-[0.3em] text-white transition hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500 dark:bg-blue-500 dark:hover:bg-blue-400">
                                    Ver manual
                                    <x-ui.icon name="siguiente" size="xs" class="text-current" />
                                </a>
                                </div>
                            </div>
                        </article>
                    </div>
                </section>
            </div>
        </main>

        {{-- FOOTER --}}
        <footer class="shrink-0">
            @include('layouts.footer')
        </footer>
    </div>

    <x-notify />
    @include('components.toast-bridge')
</body>

</html>
