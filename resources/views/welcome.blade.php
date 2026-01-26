<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('images/multilab.svg') }}">
    <link rel="alternate icon" href="{{ asset('images/multilab.svg') }}">

    <script>
        (function() {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = saved || (prefersDark ? 'dark' : 'light');

            document.documentElement.dataset.theme = theme;
            document.documentElement.classList.toggle('dark', theme === 'dark');
            document.documentElement.style.colorScheme = theme;
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased font-primary text-[var(--text)] overflow-x-hidden">
    <div class="relative min-h-[100svh] bg-[var(--bg)]">
        <div class="bg-overlay"></div>
        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(37,99,235,0.15)_1px,transparent_0)] bg-[size:24px_24px] opacity-60 pointer-events-none">
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
        <header class="sticky top-0 z-30 border-b border-[var(--border)] bg-[var(--card)]/80 backdrop-blur-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">
                <a href="{{ route('welcome') }}" class="flex items-center gap-3">
                    <x-brand.logo variant="horizontal" class="h-10 w-auto" />
                    <span class="text-lg sm:text-xl font-semibold tracking-wide text-[var(--primary)]">
                        {{ config('app.name') }}
                    </span>
                </a>
                <div class="flex items-center gap-3">
                    @if (Route::has('login'))
                        <x-ui.button variant="ghost" href="{{ route('login') }}"
                            class="px-4 py-2 text-xs font-semibold uppercase tracking-[0.3em] border border-blue-500/40 text-[var(--primary)]">
                            Iniciar sesión
                        </x-ui.button>
                    @endif
                    @if (Route::has('register'))
                        <x-ui.button variant="ghost" href="{{ route('register') }}"
                            class="px-4 py-2 text-xs font-semibold uppercase tracking-[0.3em] border border-[var(--border)] text-[var(--text)]">
                            Registrarse
                        </x-ui.button>
                    @endif
                </div>
            </div>
        </header>

        {{-- MAIN --}}
        <main class="flex-1 min-h-[calc(100vh-140px)] pt-6 pb-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

                <!-- WELCOME_REDESIGN_V1 -->
                <section
                    class="relative rounded-[32px] border border-[var(--border)] bg-white/75 backdrop-blur-sm shadow-sm overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-b from-white/90 via-white/70 to-white/80"></div>
                    <div class="relative z-10 grid gap-8 lg:grid-cols-2 items-start px-6 sm:px-10 py-10">
                        <div class="space-y-6">
                            <p class="text-xs uppercase tracking-[0.5em] text-[var(--text-muted)]">Ingeniería de
                                Software · FESC</p>
                            <h1
                                class="text-3xl sm:text-4xl lg:text-5xl font-semibold leading-tight text-[var(--primary)]">
                                MultiLab impulsa la operación ágil del Laboratorio B202 y la bodega
                            </h1>
                            <p class="text-base leading-relaxed text-slate-600 max-w-2xl">
                                Controla accesos, gestiona reservas, supervisa préstamos y devoluciones, y garantiza
                                trazabilidad y reportes claros para cada turno.
                            </p>
                            <div class="flex flex-wrap items-center gap-3">
                                <a href="{{ route('manual.index') }}"
                                    class="inline-flex items-center gap-2 rounded-full bg-[var(--primary)] px-6 py-3 text-xs font-semibold uppercase tracking-[0.3em] text-white transition hover:bg-[var(--primary-600)]">
                                    Explorar manual
                                </a>
                                @if (Route::has('login'))
                                    <x-ui.button variant="ghost" href="{{ route('login') }}"
                                        class="px-6 py-3 text-xs font-semibold uppercase tracking-[0.3em] border border-blue-500/40 text-[var(--primary)]">
                                        Iniciar sesión
                                    </x-ui.button>
                                @endif
                                @if (Route::has('register'))
                                    <x-ui.button variant="ghost" href="{{ route('register') }}"
                                        class="px-6 py-3 text-xs font-semibold uppercase tracking-[0.3em] border border-[var(--border)] text-[var(--text)]">
                                        Registrarse
                                    </x-ui.button>
                                @endif
                            </div>
                        </div>
                        <div
                            class="flex flex-col rounded-2xl border border-dashed border-[var(--border)] bg-white/80 px-6 py-8 text-sm text-[var(--text-muted)]">
                            <div class="text-xs uppercase tracking-[0.4em] text-[var(--accent)]">Operación</div>
                            <p class="text-base text-slate-700 leading-relaxed">
                                Centraliza reservas, monitorea inventario y entrega reportes claros para cada
                                responsable del laboratorio.
                                <br>
                                Trazabilidad por turno y responsable para asegurar cumplimiento institucional.
                            </p>
                            <div class="relative rounded-2xl overflow-hidden mt-6 lg:mt-0">
                                <img src="{{ asset('images/Bodega2.png') }}" alt="Operación"
                                    class="h-32 w-full object-cover object-center opacity-90" />
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="space-y-6 pb-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <article
                            class="flex flex-col rounded-3xl border border-[var(--border)] bg-white/90 shadow-sm shadow-black/5 transition hover:-translate-y-1 hover:shadow-md">
                            <div class="relative w-full aspect-[16/7] overflow-hidden rounded-t-3xl">
                                <img src="{{ asset('images/Bodega1.png') }}" alt="Laboratorio B202"
                                    class="w-full h-full object-cover object-center" />
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/20 to-transparent">
                                </div>
                            </div>
                            <div class="flex flex-col flex-1 p-8 space-y-4">
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
                                <p class="text-sm leading-relaxed text-slate-600">
                                    Centraliza el control de accesos, reservas, préstamos y devoluciones del Laboratorio
                                    B202 y la bodega, asignando responsables por turno y manteniendo trazabilidad
                                    operativa con registros y alertas para respaldo institucional.
                                </p>
                            </div>
                        </article>

                        <article
                            class="flex flex-col rounded-3xl border border-[var(--border)] bg-white/90 shadow-sm shadow-black/5 transition hover:-translate-y-1 hover:shadow-md">
                            <div class="relative aspect-[16/7] w-full overflow-hidden rounded-t-3xl">
                                <img src="{{ asset('images/Bodega3.png') }}" alt="Manual de usuario"
                                    class="h-full w-full object-cover object-center" />
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/20 to-transparent">
                                </div>
                            </div>
                            <div class="flex flex-col flex-1 p-8 space-y-4">
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
                                <p class="text-sm leading-relaxed text-slate-600">
                                    Ofrece guías operativas por rol para docentes, administrativos y equipos técnicos,
                                    con pasos claros, responsabilidades definidas y criterios de uso que facilitan la
                                    ejecución adecuada de cada proceso del laboratorio.
                                </p>
                                <div class="mt-auto flex items-center justify-start gap-4">
                                    <x-ui.button variant="primary" href="{{ route('manual.index') }}"
                                        class="inline-flex items-center gap-2 rounded-full px-5 py-3 text-xs font-semibold uppercase tracking-[0.3em]">
                                        Ver manual
                                        <x-ui.icon name="siguiente" size="xs" class="text-white" />
                                    </x-ui.button>
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
