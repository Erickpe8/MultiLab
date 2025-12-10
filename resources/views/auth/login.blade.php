<x-guest-layout>
    <!-- Background con overlay -->
    <div class="min-h-screen flex items-center justify-center relative bg-cover bg-center bg-no-repeat"
        style="background-image: url('{{ asset('images/FESC.JPG') }}');">

        <div class="absolute inset-0 bg-[#1D1616]/70 backdrop-blur-sm"></div>

        <!-- Contenedor principal -->
        <div class="relative z-10 w-full max-w-md p-8 rounded-2xl shadow-2xl border border-[#D84040]/40
            bg-white/95 backdrop-blur-md animate-fadeIn">

            <!-- Logo y título -->
            <div class="text-center mb-6">
                <a href="{{ route('welcome') }}">
                    <img src="{{ asset('images/fesc-30.png') }}"
                        class="h-20 mx-auto mb-4 transition-transform hover:scale-105">
                </a>

                <h2 class="text-2xl font-bold text-[#8E1616]">
                    Iniciar sesión
                </h2>
                <p class="text-sm text-gray-700 mt-1">
                    Accede a tu cuenta para continuar
                </p>
            </div>

            <!-- Mensaje de estado -->
            @if (session('status'))
                <div class="mb-4 text-sm font-medium text-green-600 text-center">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Formulario -->
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Correo -->
                <div>
                    <label for="email" class="font-semibold text-[#1D1616]">Correo institucional</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full mt-1 rounded-lg px-4 py-2 border border-gray-300 text-gray-800
                               focus:ring-2 focus:ring-[#D84040]/50 focus:border-[#D84040] transition">
                    <x-input-error :messages="$errors->get('email')" class="text-red-600 mt-1" />
                </div>

                <!-- Contraseña -->
                <div>
                    <label for="password" class="font-semibold text-[#1D1616]">Contraseña</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password" class="w-full mt-1 rounded-lg px-4 py-2 border border-gray-300 text-gray-800
                               focus:ring-2 focus:ring-[#D84040]/50 focus:border-[#D84040] transition">
                    <x-input-error :messages="$errors->get('password')" class="text-red-600 mt-1" />
                </div>

                <!-- Recordarme -->
                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember"
                        class="rounded border-gray-300 text-[#8E1616] focus:ring-[#D84040]">
                    <label for="remember_me" class="ml-2 text-sm text-gray-700">
                        Recordarme
                    </label>
                </div>

                <!-- Botón -->
                <div class="pt-2">
                    <button type="submit" class="w-full py-3 rounded-lg font-semibold text-white bg-[#8E1616]
                               hover:bg-[#D84040] transition shadow-md">
                        INICIAR SESIÓN
                    </button>
                </div>

                <!-- Extras -->
                <div class="text-center mt-4 space-y-2">

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-sm text-gray-700 hover:text-[#D84040] underline">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif

                    <br>

                    <a href="{{ route('register') }}" class="text-sm text-gray-700 hover:text-[#D84040] underline">
                        ¿No tienes cuenta? Regístrate
                    </a>
                </div>
            </form>
        </div>
    </div>

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
            animation: fadeIn 0.4s ease-out;
        }
    </style>
</x-guest-layout>
