<x-guest-layout>

    <div class="min-h-screen flex items-center justify-center relative bg-cover bg-center bg-no-repeat"
        style="background-image: url('{{ asset('images/FESC.JPG') }}');">

        <!-- Overlay -->
        <div class="absolute inset-0 bg-[#1D1616]/70 backdrop-blur-sm"></div>

        <!-- CONTENEDOR PRINCIPAL MEJORADO -->
        <div class="relative z-10 w-full max-w-2xl mx-auto rounded-2xl bg-white/95
                    shadow-2xl border border-[#D84040]/40 backdrop-blur-lg p-10 animate-fadeIn">

            <!-- LOGO + TÍTULO -->
            <div class="text-center mb-8">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('images/fesc-30.png') }}" alt="FESC"
                        class="h-20 mx-auto mb-4 transition-transform hover:scale-105">
                </a>

                <h2 class="text-3xl font-bold text-[#8E1616]">Registro de Estudiantes</h2>
                <p class="text-gray-700 mt-1 text-sm">Completa tus datos para crear una cuenta</p>
            </div>

            <!-- FORMULARIO -->
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- GRID DE 2 COLUMNAS -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <!-- Primer nombre -->
                    <div>
                        <label class="text-[#1D1616] font-semibold">Primer nombre</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" required class="w-full mt-1 rounded-lg border border-gray-300 px-4 py-2 text-gray-800
                               focus:ring-2 focus:ring-[#D84040]/40 focus:border-[#D84040] transition">
                        <x-input-error :messages="$errors->get('first_name')" class="mt-1" />
                    </div>

                    <!-- Segundo nombre -->
                    <div>
                        <label class="text-[#1D1616] font-semibold">Segundo nombre (opcional)</label>
                        <input type="text" name="middle_name" value="{{ old('middle_name') }}" class="w-full mt-1 rounded-lg border border-gray-300 px-4 py-2 text-gray-800
                               focus:ring-2 focus:ring-[#D84040]/40 focus:border-[#D84040] transition">
                    </div>

                    <!-- Primer apellido -->
                    <div>
                        <label class="text-[#1D1616] font-semibold">Primer apellido</label>
                        <input type="text" name="first_surname" value="{{ old('first_surname') }}" required class="w-full mt-1 rounded-lg border border-gray-300 px-4 py-2 text-gray-800
                               focus:ring-2 focus:ring-[#D84040]/40 focus:border-[#D84040] transition">
                        <x-input-error :messages="$errors->get('first_surname')" class="mt-1" />
                    </div>

                    <!-- Segundo apellido -->
                    <div>
                        <label class="text-[#1D1616] font-semibold">Segundo apellido (opcional)</label>
                        <input type="text" name="second_surname" value="{{ old('second_surname') }}" class="w-full mt-1 rounded-lg border border-gray-300 px-4 py-2 text-gray-800
                               focus:ring-2 focus:ring-[#D84040]/40 focus:border-[#D84040] transition">
                    </div>

                </div> <!-- END GRID -->


                <!-- Correo -->
                <div>
                    <label class="text-[#1D1616] font-semibold">Correo institucional</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full mt-1 rounded-lg border border-gray-300 px-4 py-2 text-gray-800
                           focus:ring-2 focus:ring-[#D84040]/40 focus:border-[#D84040] transition">
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <!-- Contraseña -->
                <div>
                    <label class="text-[#1D1616] font-semibold">Contraseña</label>
                    <input type="password" name="password" required class="w-full mt-1 rounded-lg border border-gray-300 px-4 py-2 text-gray-800
                           focus:ring-2 focus:ring-[#D84040]/40 focus:border-[#D84040] transition">
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <!-- Confirmar contraseña -->
                <div>
                    <label class="text-[#1D1616] font-semibold">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" required class="w-full mt-1 rounded-lg border border-gray-300 px-4 py-2 text-gray-800
                           focus:ring-2 focus:ring-[#D84040]/40 focus:border-[#D84040] transition">
                </div>

                <!-- BOTÓN -->
                <div class="pt-3">
                    <button type="submit" class="w-full py-3 rounded-lg font-semibold text-white bg-[#8E1616]
                                   hover:bg-[#D84040] transition shadow-md">
                        CREAR CUENTA
                    </button>
                </div>

                <!-- LOGIN -->
                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-[#D84040] underline">
                        ¿Ya tienes cuenta? Inicia sesión
                    </a>
                </div>

            </form>
        </div>
    </div>

    <!-- Animación -->
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn .4s ease-out;
        }
    </style>

</x-guest-layout>
