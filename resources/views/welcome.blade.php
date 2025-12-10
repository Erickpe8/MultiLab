<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Multilab - FESC</title>

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

        .overlay {
            background-color: rgba(29, 22, 22, 0.75);
            backdrop-filter: blur(10px);
        }
    </style>
</head>

<body class="antialiased font-sans text-gray-100">
    <div class="overlay min-h-screen flex flex-col justify-between">

        <!-- Header -->
        <header class="flex flex-wrap justify-between items-center px-6 py-3 bg-[#1D1616]/70 backdrop-blur-md">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/multilab-logo.png') }}" alt="Multilab Logo"
                    class="h-12 w-auto">
                <h1 class="text-2xl font-bold text-[#D84040] tracking-wide">
                    Multilab FESC
                </h1>
            </div>

            <nav class="flex gap-3 mt-3 sm:mt-0">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="px-4 py-2 rounded-lg bg-[#D84040] hover:bg-[#8E1616] transition font-semibold">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-4 py-2 rounded-lg bg-[#D84040] hover:bg-[#8E1616] transition font-semibold">
                            Iniciar Sesión
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="px-4 py-2 rounded-lg border border-[#D84040] text-[#EEEEEE]
                                       hover:bg-[#8E1616] hover:text-white transition font-semibold">
                                Registro Estudiantes
                            </a>
                        @endif
                    @endauth
                @endif
            </nav>
        </header>

        <!-- Contenido central -->
        <main class="flex flex-col items-center justify-center flex-1 px-4 py-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-6xl w-full items-center">

                <!-- Carrusel -->
                <div id="auto-carousel"
                    class="relative w-full h-64 md:h-72 overflow-hidden rounded-xl shadow-lg mx-auto">

                    <div class="relative w-full h-full">
                        <div class="carousel-item absolute inset-0 opacity-100 transition duration-1000">
                            <img src="{{ asset('images/multilab-1.png') }}"
                                class="w-full h-full object-cover rounded-xl">
                        </div>

                        <div class="carousel-item absolute inset-0 opacity-0 transition duration-1000">
                            <img src="{{ asset('images/multilab-2.png') }}"
                                class="w-full h-full object-cover rounded-xl">
                        </div>

                        <div class="carousel-item absolute inset-0 opacity-0 transition duration-1000">
                            <img src="{{ asset('images/multilab-3.png') }}"
                                class="w-full h-full object-cover rounded-xl">
                        </div>
                    </div>
                </div>

                <!-- Caja de información Multilab -->
                <div class="relative bg-[#1D1616]/80 backdrop-blur-md text-center p-8 rounded-xl shadow-lg border border-[#8E1616]/50">

                    <h3 class="text-2xl font-bold text-[#D84040] mb-3">¿Qué es Multilab?</h3>

                    <p class="text-[#EEEEEE] text-sm md:text-base leading-relaxed mb-6">
                        Multilab es el Sistema de Gestión de Inventarios y Préstamos Tecnológicos
                        de los laboratorios FESC. Te permite solicitar equipos, consultar disponibilidad,
                        gestionar devoluciones y mantener un historial claro de tus movimientos.
                    </p>

                    <a href="{{ route('login') }}"
                        class="inline-flex items-center justify-center gap-2 px-10 py-3 text-base font-semibold rounded-lg
                               border border-[#D84040] text-[#EEEEEE] hover:bg-[#8E1616] hover:text-white transition">

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M12 12a9 9 0 110-18 9 9 0 010 18z" />
                        </svg>

                        Más información
                    </a>
                </div>

            </div>
        </main>

        <!-- Footer -->
        <footer class="text-center py-4 bg-[#1D1616]/70 text-[#EEEEEE] text-xs sm:text-sm">
            <p>&copy; {{ date('Y') }} Fundación de Estudios Superiores Comfanorte — FESC</p>
            <p>Ingenieria de Software · Multilab</p>
        </footer>
    </div>

    <!-- Carrusel automático -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const items = document.querySelectorAll('#auto-carousel .carousel-item');
            let index = 0;

            setInterval(() => {
                items[index].classList.remove('opacity-100');
                items[index].classList.add('opacity-0');

                index = (index + 1) % items.length;

                items[index].classList.remove('opacity-0');
                items[index].classList.add('opacity-100');
            }, 3500);
        });
    </script>

</body>

</html>
