<x-layouts.guest-auth title="Recuperar contraseña" subtitle="Envía tu correo institucional para recibir el enlace">
    @php
        $inputClass = 'h-11 w-full rounded-xl border border-[var(--border)] bg-[var(--card)]/70 px-4 text-sm text-[var(--text)] placeholder:text-[color:var(--text-muted)] focus:outline-none focus:ring-2 focus:ring-[var(--primary)]/40 focus:border-[var(--primary)] transition';
        $buttonClass = 'w-full h-11 rounded-xl font-semibold text-white bg-[var(--primary)] hover:bg-[var(--primary-600)] disabled:opacity-60 disabled:cursor-not-allowed shadow-soft transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[var(--primary)]/50 focus-visible:ring-offset-[var(--bg)]';
    @endphp

    <x-auth-session-status class="text-sm text-center text-[var(--primary)]" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-semibold text-[var(--text)] mb-1">
                Correo institucional
            </label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                placeholder="correo@fesc.edu.co" class="{{ $inputClass }}" />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-[var(--primary-600)]" />
        </div>

        <button type="submit" class="{{ $buttonClass }}">
            ENVIAR ENLACE
        </button>
    </form>

    @if (Route::has('login'))
        <p class="text-sm text-center text-[color:var(--text-muted)]">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}" class="text-[var(--primary)] font-semibold hover:underline">
                Inicia sesión
            </a>
        </p>
    @endif
</x-layouts.guest-auth>
