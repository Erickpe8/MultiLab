@section('title', 'Login')

<x-guest-layout>
    @if (session('status'))
        <div class="mb-4 text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <div class="min-h-screen relative flex items-center justify-center bg-cover bg-center bg-no-repeat px-4 py"
        style="background-image: url('{{ asset('images/FESC.JPG') }}');">

        {{-- Overlay institucional (deja ver la imagen) --}}
        <div class="absolute inset-0 bg-white/50 dark:bg-black/45"></div>

        {{-- Contenedor principal (más compacto) --}}
        <div class="relative z-10 w-full max-w-md rounded-2xl border border-[var(--border)]
                    bg-white dark:bg-[var(--card)] shadow-2xl px-6 py-4 sm:px-8 sm:py-7">

            {{-- Logo y título (más pequeño) --}}
            <div class="text-center mb-4">
                <a href="{{ route('welcome') }}">
                    <img src="{{ asset('images/FESC-30.png') }}" alt="FESC"
                        class="h-14 mx-auto mb-2 hover:scale-105 transition-transform">
                </a>

                <h2 class="text-xl sm:text-2xl font-extrabold text-[var(--text)] leading-tight">
                    Iniciar sesión
                </h2>
                <p class="text-xs sm:text-sm text-[color:var(--text-muted)] mt-1">
                    Accede a <strong>MultiLab</strong> para gestionar reservas, préstamos y control del laboratorio
                </p>
            </div>

            {{-- Formulario compacto --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                {{-- Correo --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-[var(--text)] mb-1">
                        Correo
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="tucorreo@fesc.edu.co"
                        class="w-full rounded-xl border border-[var(--border)] px-4 py-2.5
                               bg-white dark:bg-[var(--bg)]
                               text-[var(--text)] placeholder:text-[color:var(--text-muted)]
                               focus:outline-none focus:ring-2 focus:ring-[var(--accent)]/35 focus:border-[var(--accent)]
                               transition">
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                {{-- Contraseña --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-[var(--text)] mb-1">
                        Contraseña
                    </label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="w-full rounded-xl border border-[var(--border)] px-4 py-2
                               bg-white dark:bg-[var(--bg)]
                               text-[var(--text)] placeholder:text-[color:var(--text-muted)]
                               focus:outline-none focus:ring-2 focus:ring-[var(--accent)]/35 focus:border-[var(--accent)]
                               transition">
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                {{-- Recordarme + Olvidaste (en una fila) --}}
                <div class="flex items-center justify-between gap-3">
                    <label for="remember_me" class="inline-flex items-center gap-2">
                        <input id="remember_me" type="checkbox"
                            class="h-4 w-4 rounded border-[var(--border)] text-[var(--accent)]
                                   focus:ring-[var(--accent)] focus:ring-offset-0"
                            name="remember">
                        <span class="text-sm text-[color:var(--text-muted)]">{{ __('Recordarme') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-sm text-[color:var(--text-muted)] hover:text-[var(--accent)] underline underline-offset-4">
                            ¿Olvidaste?
                        </a>
                    @endif
                </div>

                {{-- Botón --}}
                <div class="pt-1">
                    <button type="submit"
                        class="w-full py-2 rounded-xl font-semibold text-white bg-[var(--accent)]
                               hover:bg-[var(--primary)] transition shadow-md">
                        INICIAR SESIÓN
                    </button>
                </div>

                {{-- Registro (solo si existe la ruta) --}}
                @if (Route::has('register'))
                    <div class="text-center pt-1">
                        <a href="{{ route('register') }}"
                            class="text-sm text-[color:var(--text-muted)] hover:text-[var(--accent)] underline underline-offset-4">
                            ¿No tienes cuenta? Regístrate
                        </a>
                    </div>
                @endif

                {{-- Términos y Privacidad --}}
                    <div class="pt-6 border-t border-red-500/20">
                        <p class="text-sm text-[var(--text-muted)] leading-relaxed">
                            Al iniciar sesión aceptas los
                            <a href="{{ route('legal.terms') }}" target="_blank" rel="noopener noreferrer"
                               class="font-semibold text-red-600 hover:text-red-700 transition-colors underline underline-offset-4">
                                Términos y Condiciones
                            </a>
                            y la
                            <a href="{{ route('legal.privacy') }}" target="_blank" rel="noopener noreferrer"
                               class="font-semibold text-red-600 hover:text-red-700 transition-colors underline underline-offset-4">
                                Política de Privacidad
                            </a>
                            del sistema.
                        </p>
                    </div>
            </form>
        </div>
    </div>
</x-guest-layout>
