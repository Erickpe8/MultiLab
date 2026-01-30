<x-layouts.guest-auth title="Iniciar sesión" subtitle="Accede a tu cuenta institucional">

    <style>
        :root[data-theme='dark'] input {
            background-color: var(--card);
        }
    </style>

    @php
        $inputClass = 'h-11 w-full rounded-xl border border-[var(--border)] bg-[var(--card)]/70 px-4 text-sm text-[var(--text)] placeholder:text-[color:var(--text-muted)] focus:outline-none focus:ring-2 focus:ring-[var(--primary)]/40 focus:border-[var(--primary)] transition';
        $buttonClass = 'w-full h-11 rounded-xl font-semibold text-white bg-[var(--primary)] hover:bg-[var(--primary-600)] disabled:opacity-60 disabled:cursor-not-allowed shadow-soft transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[var(--primary)]/50 focus-visible:ring-offset-[var(--bg)]';
    @endphp

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-semibold text-[var(--text)] mb-1">
                Correo institucional
            </label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                placeholder="correo@fesc.edu.co"
                class="{{ $inputClass }} @error('email') border-rose-500 focus:border-rose-500 focus:ring-rose-500/40 @enderror"
                aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}" />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-[var(--primary-600)]" />
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-[var(--text)] mb-1">
                Contraseña
            </label>
            <input id="password" name="password" type="password" required autocomplete="current-password"
                class="{{ $inputClass }} @error('password') border-rose-500 focus:border-rose-500 focus:ring-rose-500/40 @enderror"
                aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}" />
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-[var(--primary-600)]" />
        </div>

        <div class="flex items-center justify-between text-sm">
            <label for="remember_me" class="inline-flex items-center gap-2 text-[color:var(--text-muted)]">
                <input id="remember_me" name="remember" type="checkbox"
                    class="h-4 w-4 rounded border-[var(--border)] text-[var(--primary)] focus:ring-[var(--primary)]" />
                <span>Recuérdame</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-[var(--primary)] hover:underline">
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
        <a href="{{ route('register') }}" class="text-[var(--primary)] font-semibold hover:underline">
            Regístrate
        </a>
    </p>
</x-layouts.guest-auth>
