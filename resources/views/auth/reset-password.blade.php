<x-layouts.guest-auth title="Restablecer contraseña" subtitle="Elige una nueva contraseña para tu cuenta">
    @php
        $inputClass = 'h-11 w-full rounded-xl border border-[var(--border)] bg-[var(--card)]/70 px-4 text-sm text-[var(--text)] placeholder:text-[color:var(--text-muted)] focus:outline-none focus:ring-2 focus:ring-[var(--primary)]/40 focus:border-[var(--primary)] transition';
        $buttonClass = 'w-full h-11 rounded-xl font-semibold text-white bg-[var(--primary)] hover:bg-[var(--primary-600)] disabled:opacity-60 disabled:cursor-not-allowed shadow-soft transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[var(--primary)]/50 focus-visible:ring-offset-[var(--bg)]';
    @endphp


    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="block text-sm font-semibold text-[var(--text)] mb-1">
                Correo institucional
            </label>
            <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required autofocus
                class="{{ $inputClass }}" />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-[var(--primary-600)]" />
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-[var(--text)] mb-1">
                Nueva contraseña
            </label>
            <input id="password" name="password" type="password" required autocomplete="new-password"
                class="{{ $inputClass }}" />
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-[var(--primary-600)]" />
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-[var(--text)] mb-1">
                Confirmar contraseña
            </label>
            <input id="password_confirmation" name="password_confirmation" type="password" required
                autocomplete="new-password" class="{{ $inputClass }}" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs text-[var(--primary-600)]" />
        </div>

        <button type="submit" class="{{ $buttonClass }}">
            RESTABLECER CONTRASEÑA
        </button>
    </form>
</x-layouts.guest-auth>
