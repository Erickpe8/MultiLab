<x-layouts.guest-auth title="Confirmar contraseña" subtitle="Reingresa tu contraseña para continuar">
    @php
        $inputClass = 'h-11 w-full rounded-xl border border-[var(--border)] bg-[var(--card)]/70 px-4 text-sm text-[var(--text)] placeholder:text-[color:var(--text-muted)] focus:outline-none focus:ring-2 focus:ring-[var(--primary)]/40 focus:border-[var(--primary)] transition';
        $buttonClass = 'w-full h-11 rounded-xl font-semibold text-white bg-[var(--primary)] hover:bg-[var(--primary-600)] disabled:opacity-60 disabled:cursor-not-allowed shadow-soft transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[var(--primary)]/50 focus-visible:ring-offset-[var(--bg)]';
    @endphp

    <p class="text-sm text-center text-[color:var(--text-muted)]">
        {{ __('Esta es un área segura de la aplicación. Por favor confirma tu contraseña antes de continuar.') }}
    </p>


    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <div>
            <label for="password" class="block text-sm font-semibold text-[var(--text)] mb-1">
                Contraseña
            </label>
            <input id="password" name="password" type="password" required autocomplete="current-password"
                class="{{ $inputClass }} @error('password') border-rose-500 focus:border-rose-500 focus:ring-rose-500/40 @enderror"
                aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}" />
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-[var(--primary-600)]" />
        </div>

        <button type="submit" class="{{ $buttonClass }}">
            Confirmar
        </button>
    </form>
</x-layouts.guest-auth>
