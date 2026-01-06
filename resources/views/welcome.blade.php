<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MultiLab - FESC</title>

    <script>
        /**
         * Configura el tema inicial en el documento según preferencias guardadas o sistema.
         * Entradas: Ninguna.
         * Salidas: void (sin retorno).
         */
        (function () {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = saved || (prefersDark ? 'dark' : 'light');

            document.documentElement.dataset.theme = theme;
            document.documentElement.classList.toggle('dark', theme === 'dark');
            document.documentElement.style.colorScheme = theme;
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>

    <style>
        body {
            background-image: url('{{ asset('images/FESC.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .bg-overlay {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            background:
                radial-gradient(1200px 600px at 20% 15%, rgba(216, 64, 64, 0.16), transparent 60%),
                radial-gradient(900px 520px at 80% 70%, rgba(142, 22, 22, 0.14), transparent 55%),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.70), rgba(255, 255, 255, 0.58));
        }

        :root.dark .bg-overlay {
            background:
                radial-gradient(1200px 600px at 20% 15%, rgba(216, 64, 64, 0.18), transparent 60%),
                radial-gradient(900px 520px at 80% 70%, rgba(142, 22, 22, 0.16), transparent 55%),
                linear-gradient(to bottom, rgba(0, 0, 0, 0.62), rgba(0, 0, 0, 0.54));
        }
    </style>
</head>

<body class="antialiased font-sans text-[var(--text)] overflow-hidden">
    <div class="bg-overlay"></div>

    @php
        // MultiLab: foco en operación diaria del laboratorio (B201) y bodega de materiales.
        $modules = [
            [
                "code" => "M1",
                "name" => "Control de acceso a PCs",
                "objective" => "Registra quién utiliza cada estación del laboratorio, con trazabilidad y observaciones asociadas para respaldar el control operativo."
            ],
            [
                "code" => "M2",
                "name" => "Reservas del aula y recursos",
                "objective" => "Centraliza las reservas para evitar choques de disponibilidad y mejorar la planificación de uso del laboratorio."
            ],
            [
                "code" => "M3",
                "name" => "Préstamos y devoluciones",
                "objective" => "Administra el préstamo de herramientas y materiales con estados claros, responsables y registro de entrega/devolución."
            ],
            [
                "code" => "M4",
                "name" => "Inventario del laboratorio y bodega",
                "objective" => "Mantiene un registro actualizado de equipos, herramientas y materiales, con historial de movimientos y control de condiciones."
            ],
            [
                "code" => "M5",
                "name" => "Históricos y observaciones",
                "objective" => "Genera históricos que respaldan la gestión del laboratorio: uso, novedades, incidencias y seguimiento por periodos."
            ],
        ];
    @endphp

    <div class="relative z-10 h-[100svh] flex flex-col">

        {{-- HEADER --}}
        <header class="shrink-0 border-b border-[var(--border)]
                       bg-white/70 dark:bg-[#0f1115]/80 backdrop-blur-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 flex flex-wrap items-center justify-between gap-3">
                <a href="{{ route('welcome') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/FESC-30.png') }}" alt="Logo FESC" class="h-10 sm:h-11 w-auto" />
                    <span class="text-xl sm:text-2xl font-extrabold tracking-wide text-[var(--accent)]">
                        MultiLab
                    </span>
                </a>

                <nav class="flex items-center gap-2 sm:gap-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="px-4 py-2 rounded-xl bg-[var(--accent)] hover:bg-[var(--primary)]
                                       text-sm font-semibold text-white shadow-sm transition">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="px-4 py-2 rounded-xl bg-[var(--accent)] hover:bg-[var(--primary)]
                                       text-sm font-semibold text-white shadow-sm transition">
                                Iniciar sesión
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="px-4 py-2 rounded-xl border border-[var(--border)]
                                           bg-white/60 dark:bg-[var(--bg)]/35
                                           text-sm font-semibold text-[var(--text)]
                                           hover:border-[var(--accent)] hover:text-[var(--accent)]
                                           transition">
                                    Registrarse
                                </a>
                            @endif
                        @endauth

                        <x-theme-toggle id="theme-toggle-welcome" size="md" />
                    @endif
                </nav>
            </div>
        </header>

        {{-- MAIN --}}
        <main class="flex-1 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 h-full py-4 sm:py-5 lg:py-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 lg:gap-8 h-full min-h-0 items-stretch">

                    <section class="h-full min-h-0 rounded-2xl border border-[var(--border)]
                                    bg-white dark:bg-[#0f1115] shadow-xl
                                    overflow-hidden flex flex-col">
                        <div class="px-5 sm:px-6 py-4 border-b border-[var(--border)]">
                            <h2 class="text-base sm:text-lg font-extrabold text-[var(--text)]">
                                ¿Qué hace MultiLab?
                            </h2>
                            <p class="text-sm text-[color:var(--text-muted)] mt-1">
                                Funcionalidades clave para la operación del Laboratorio B201 y la bodega.
                            </p>
                        </div>

                        <div class="flex-1 min-h-0">
                            <div id="pillar-carousel" class="relative w-full h-full overflow-hidden">

                                <div class="absolute inset-0 pointer-events-none">
                                    <div class="absolute -top-16 -right-16 w-56 h-56 rounded-full blur-3xl opacity-35"
                                        style="background: color-mix(in oklab, var(--accent) 26%, transparent);"></div>
                                    <div class="absolute -bottom-20 -left-20 w-64 h-64 rounded-full blur-3xl opacity-25"
                                        style="background: color-mix(in oklab, var(--primary) 22%, transparent);"></div>
                                </div>

                                <div class="relative h-full">
                                    @foreach ($modules as $i => $m)
                                        <div class="pillar-item absolute inset-0 transition-opacity duration-500 ease-out
                                                    {{ $i === 0 ? 'opacity-100' : 'opacity-0 hidden' }}">

                                            <div class="h-full px-6 sm:px-8 py-3 sm:py-4 flex items-center justify-center text-center">
                                                <div class="w-full max-w-2xl">
                                                    <h3 class="text-[28px] sm:text-3xl lg:text-4xl font-extrabold text-[var(--text)] leading-tight break-words">
                                                        {{ $m['name'] }}
                                                    </h3>

                                                    <p class="mt-4 text-sm sm:text-base lg:text-[17px] leading-relaxed
                                                              text-[color:var(--text-muted)] max-w-[62ch] mx-auto break-words">
                                                        {{ $m['objective'] }}
                                                    </p>
                                                </div>
                                            </div>

                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                    </section>

                    <section class="h-full min-h-0 relative rounded-2xl border border-[var(--border)]
                                    bg-white dark:bg-[#0f1115] shadow-xl
                                    overflow-hidden flex flex-col">
                        <div class="absolute inset-0 pointer-events-none">
                            <div class="absolute -top-20 -right-20 w-64 h-64 rounded-full blur-3xl opacity-40"
                                style="background: color-mix(in oklab, var(--accent) 30%, transparent);"></div>
                            <div class="absolute -bottom-24 -left-24 w-72 h-72 rounded-full blur-3xl opacity-30"
                                style="background: color-mix(in oklab, var(--primary) 25%, transparent);"></div>
                        </div>

                        <div class="relative flex-1 min-h-0 px-6 sm:px-8 py-6 sm:py-7 text-center
                                    flex flex-col items-center justify-center">
                            <h3 class="text-3xl sm:text-4xl font-extrabold text-[var(--accent)]">
                                Actualizaciones
                            </h3>

                            <p class="mt-4 text-sm sm:text-base lg:text-[17px] leading-relaxed text-[color:var(--text-muted)] max-w-xl mx-auto">
                                Revisa las mejoras más recientes de <strong>MultiLab</strong> enfocadas en control de recursos,
                                estabilidad del sistema y trazabilidad de uso dentro del laboratorio.
                            </p>

                            <div class="mt-7 flex justify-center">
                                <a href="#"
                                    class="inline-flex items-center justify-center gap-2 px-8 py-3 text-sm font-semibold rounded-xl
                                           bg-[var(--accent)] text-white hover:bg-[var(--primary)]
                                           shadow-sm transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Ver más
                                </a>
                            </div>

                            <div class="mt-6 text-xs text-[color:var(--text-muted)]">
                                Última revisión: {{ now()->format('d/m/Y') }}
                            </div>
                        </div>
                    </section>

                </div>
            </div>
        </main>

        {{-- FOOTER --}}
        <footer class="shrink-0">
            @include('layouts.footer')
        </footer>
    </div>

    <script>
        /**
         * Inicializa el carrusel cuando la página está lista.
         * Entradas: Ninguna.
         * Salidas: void (sin retorno).
         */
        document.addEventListener('DOMContentLoaded', () => {
            const items = Array.from(document.querySelectorAll('#pillar-carousel .pillar-item'));
            if (!items.length) return;

            let index = 0;
            const total = items.length;

            items.forEach((el, i) => {
                if (i === 0) {
                    el.classList.remove('hidden');
                    el.classList.add('opacity-100');
                    el.classList.remove('opacity-0');
                } else {
                    el.classList.add('hidden');
                    el.classList.add('opacity-0');
                    el.classList.remove('opacity-100');
                }
            });

            /**
             * Cambia al slide indicado aplicando las clases de transición.
             * Entradas: nextIndex (number) índice de la diapositiva siguiente.
             * Salidas: void (sin retorno).
             */
            function showSlide(nextIndex) {
                const current = items[index];
                const next = items[nextIndex];

                current.classList.remove('opacity-100');
                current.classList.add('opacity-0');

                next.classList.remove('hidden');
                next.classList.add('opacity-0');

                requestAnimationFrame(() => {
                    next.classList.remove('opacity-0');
                    next.classList.add('opacity-100');
                });

                window.setTimeout(() => {
                    current.classList.add('hidden');
                }, 520);

                index = nextIndex;
            }

            setInterval(() => {
                showSlide((index + 1) % total);
            }, 3000);
        });
    </script>
</body>

</html>
