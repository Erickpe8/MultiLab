<x-guest-layout>

    {{-- Notificaciones globales --}}
    <x-notify />

    {{-- Mostrar mensaje de estado (p. ej. “Se envió el enlace de recuperación”) --}}
    @if (session('status'))
        <script>
            window.notify.show("{{ session('status') }}", "success");
        </script>
    @endif

    {{-- Mostrar errores globales del login (creds incorrectas, usuario inactivo, etc.) --}}
    @if ($errors->any())
        <script>
            window.notify.show("{{ $errors->first() }}", "error");
        </script>
    @endif

    <div class="min-h-screen flex items-center justify-center bg-cover bg-center bg-no-repeat"
        style="background-image: url('{{ asset('images/FESC.JPG') }}');">

        <!-- Overlay institucional -->
        <div class="absolute inset-0 bg-[#1D1616]/70"></div>

        <!-- Contenedor principal -->
        <div
            class="relative z-10 w-full max-w-md bg-white/90 backdrop-blur-md rounded-2xl shadow-2xl p-8 border border-[#D84040]">

            <!-- Logo y título -->
            <div class="text-center mb-6">
                <a href="{{ route('welcome') }}">
                    <img src="{{ asset('images/fesc-30.png') }}" alt="FESC"
                        class="h-20 mx-auto mb-3 hover:scale-105 transition-transform">
                </a>
                <h2 class="text-2xl font-bold text-[#8E1616]">Iniciar sesión</h2>
                <p class="text-sm text-gray-700 mt-1">Accede a tu cuenta para continuar</p>
            </div>

            <!-- Formulario de login -->
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Correo -->
                <div class="flex flex-col">
                    <label for="email" class="text-[#1D1616] font-semibold mb-1">Correo institucional</label>

                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-800
                                  focus:border-[#D84040] focus:ring focus:ring-[#D84040]/40 transition duration-200">

                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Contraseña -->
                <div class="flex flex-col">
                    <label for="password" class="text-[#1D1616] font-semibold mb-1">Contraseña</label>

                    <input id="password" type="password" name="password" required autocomplete="current-password" class="w-full rounded-lg border border-gray-300 px-4 py-2 text-gray-800
                                  focus:border-[#D84040] focus:ring focus:ring-[#D84040]/40 transition duration-200">

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Recordarme -->
                <div class="flex items-center">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="rounded border-gray-300 text-[#D84040] shadow-sm focus:ring-[#D84040]">
                        <span class="ms-2 text-sm text-gray-600">Recordarme</span>
                    </label>
                </div>

                <!-- Botón de inicio de sesión -->
                <div class="pt-2">
                    <button type="submit" class="w-full py-3 rounded-lg font-semibold text-white bg-[#8E1616]
                               hover:bg-[#D84040] transition duration-200 shadow-md">
                        INICIAR SESIÓN
                    </button>
                </div>

                <!-- Enlace a recuperar contraseña -->
                @if (Route::has('password.request'))
                    <div class="text-center mt-3">
                        <a href="{{ route('password.request') }}"
                            class="text-sm text-gray-700 hover:text-[#D84040] underline">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                @endif

                <!-- Enlace a registro -->
                <div class="text-center mt-3">
                    <a href="{{ route('register') }}" class="text-sm text-gray-700 hover:text-[#D84040] underline">
                        ¿No tienes cuenta? Regístrate
                    </a>
                </div>

            </form>
        </div>
    </div>

</x-guest-layout>
