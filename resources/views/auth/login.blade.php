<x-layouts.guest-auth title="Iniciar sesión" subtitle="Accede a tu cuenta institucional">

    <style>
        :root[data-theme='dark'] input {
            background-color: var(--card);
        }
    </style>

    @php
        $inputClass = 'h-11 w-full rounded-xl border border-[var(--border)] bg-[var(--card)]/70 px-4 text-sm text-[var(--text)] placeholder:text-[color:var(--text-muted)] focus:outline-none focus:ring-2 focus:ring-[#D84040]/40 focus:border-[#D84040] transition';
        $buttonClass = 'w-full h-11 rounded-xl font-semibold text-white bg-[#8E1616] hover:bg-[#D84040] disabled:opacity-60 disabled:cursor-not-allowed shadow-soft transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[#D84040]/50 focus-visible:ring-offset-[var(--bg)]';
    @endphp

    <x-auth-session-status class="text-sm text-center text-[#8E1616]" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-semibold text-[var(--text)] mb-1">
                Correo institucional
            </label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                placeholder="correo@fesc.edu.co"
                class="{{ $inputClass }}" />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-[#D84040]" />
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-[var(--text)] mb-1">
                Contraseña
            </label>
            <input id="password" name="password" type="password" required autocomplete="current-password"
                class="{{ $inputClass }}" />
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-[#D84040]" />
        </div>

        <div class="flex items-center justify-between text-sm">
            <label for="remember_me" class="inline-flex items-center gap-2 text-[color:var(--text-muted)]">
                <input id="remember_me" name="remember" type="checkbox"
                    class="h-4 w-4 rounded border-[var(--border)] text-[#D84040] focus:ring-[#D84040]" />
                <span>Recuérdame</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-[#8E1616] hover:underline">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <button type="submit" class="{{ $buttonClass }}">
            INICIAR SESIÓN
        </button>
    </form>

    <p class="text-sm text-center text-[color:var(--text-muted)]">
        ¿No tienes cuenta?
        <a href="{{ route('register') }}" class="text-[#8E1616] font-semibold hover:underline">
            Regístrate
        </a>
    </p>
</x-layouts.guest-auth>
