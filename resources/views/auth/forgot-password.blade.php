@section('title', 'Recuperar contraseña')

<x-guest-layout>
    <div class="min-h-screen relative flex items-center justify-center bg-cover bg-center bg-no-repeat px-4 py-4"
        style="background-image: url('{{ asset('images/FESC.JPG') }}');">

        {{-- Overlay institucional (deja ver la imagen) --}}
        <div class="absolute inset-0 bg-white/50 dark:bg-black/45"></div>

        {{-- Contenedor principal (compacto, sin blur) --}}
        <div class="relative z-10 w-full max-w-md rounded-2xl border border-[var(--border)]
                    bg-white dark:bg-[var(--card)] shadow-2xl px-6 py-6 sm:px-8 sm:py-7">

            {{-- Header --}}
            <div class="text-center mb-4">
                <a href="{{ route('welcome') }}">
                    <img src="{{ asset('images/fesc-30.png') }}" alt="FESC"
                        class="h-14 mx-auto mb-2 hover:scale-105 transition-transform">
                </a>

                <h2 class="text-xl sm:text-2xl font-extrabold text-[var(--text)] leading-tight">
                    Recuperación de contraseña
                </h2>
                <p class="text-xs sm:text-sm text-[color:var(--text-muted)] mt-1">
                    Ingrese su correo para recibir el enlace
                </p>
            </div>

            {{-- Notificación de sesión --}}
            @if (session('status'))
                <div class="mb-3 rounded-xl border border-green-200/70 bg-green-50 px-4 py-2 text-sm text-green-700
                                dark:border-green-400/25 dark:bg-green-500/10 dark:text-green-300">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Formulario --}}
            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                {{-- Correo --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-[var(--text)] mb-1">
                        Correo institucional
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full rounded-xl border border-[var(--border)] px-4 py-2.5
                               bg-white dark:bg-[var(--bg)]
                               text-[var(--text)] placeholder:text-[color:var(--text-muted)]
                               focus:outline-none focus:ring-2 focus:ring-[var(--accent)]/35 focus:border-[var(--accent)]
                               transition">
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                {{-- Botón --}}
                <div class="pt-1">
                    <button type="submit" class="w-full py-2.5 rounded-xl font-semibold text-white bg-[var(--accent)]
                               hover:bg-[var(--primary)] transition shadow-md">
                        ENVIAR ENLACE
                    </button>
                </div>

                {{-- Link a login --}}
                <div class="text-center pt-1">
                    <a href="{{ route('login') }}"
                        class="text-sm text-[color:var(--text-muted)] hover:text-[var(--accent)] underline underline-offset-4">
                        ¿Ya tienes cuenta? Inicia sesión
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
