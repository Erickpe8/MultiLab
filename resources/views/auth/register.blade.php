<x-layouts.guest-auth title="Pre-registro {{ config('app.name') }}" subtitle="Complete sus datos institucionales para solicitar acceso">

    <style>
        :root[data-theme='dark'] input {
            background-color: var(--card);
        }
    </style>

    @php
        $inputClass = 'h-11 w-full rounded-xl border border-[var(--border)] bg-[var(--card)]/70 px-4 text-sm text-[var(--text)] placeholder:text-[color:var(--text-muted)] focus:outline-none focus:ring-2 focus:ring-[var(--primary)]/40 focus:border-[var(--primary)] transition';
        $buttonClass = 'w-full h-11 rounded-xl font-semibold text-white bg-[var(--primary)] hover:bg-[var(--primary-600)] disabled:opacity-60 disabled:cursor-not-allowed shadow-soft transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[var(--primary)]/50 focus-visible:ring-offset-[var(--bg)]';
        $termsUrl = Route::has('legal.terms')
            ? route('legal.terms')
            : (Route::has('terms') ? route('terms') : url('/terms'));
        $privacyUrl = Route::has('legal.privacy')
            ? route('legal.privacy')
            : (Route::has('privacy') ? route('privacy') : url('/privacy'));
    @endphp


        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

        <div class="grid gap-2 md:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-semibold text-[var(--text)] mb-1">
                    Nombre completo
                </label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                    placeholder="Juan Pablo Pérez"
                    class="{{ $inputClass }} @error('name') border-rose-500 focus:border-rose-500 focus:ring-rose-500/40 @enderror"
                    aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}" />
                <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs text-[var(--primary-600)]" />
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-[var(--text)] mb-1">
                    Correo institucional
                </label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                    placeholder="correo@fesc.edu.co"
                    pattern="^[^@\s]+@fesc\.edu\.co$"
                    title="Solo se permite el correo institucional @fesc.edu.co"
                    class="{{ $inputClass }} @error('email') border-rose-500 focus:border-rose-500 focus:ring-rose-500/40 @enderror"
                    aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}" />
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-[var(--primary-600)]" />
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-[var(--text)] mb-1">
                    Contraseña
                </label>
                <input id="password" name="password" type="password" required
                    placeholder="●●●●●●●●"
                    class="{{ $inputClass }} @error('password') border-rose-500 focus:border-rose-500 focus:ring-rose-500/40 @enderror"
                    aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}" />
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-[var(--primary-600)]" />
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-[var(--text)] mb-1">
                    Confirmar contraseña
                </label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    placeholder="●●●●●●●●"
                    class="{{ $inputClass }} @error('password_confirmation') border-rose-500 focus:border-rose-500 focus:ring-rose-500/40 @enderror"
                    aria-invalid="{{ $errors->has('password_confirmation') ? 'true' : 'false' }}" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs text-[var(--primary-600)]" />
            </div>
        </div>

        <button type="submit" class="{{ $buttonClass }}">
            ENVIAR PREREGISTRO
        </button>

        <p class="text-sm text-center text-[color:var(--text-muted)]">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}" class="text-[var(--primary)] font-semibold hover:underline">
                Inicia sesión
            </a>
        </p>
    </form>

    <div class="mt-1 text-xs text-center text-[color:var(--text-muted)] leading-snug">
        Al registrarte aceptas los
        <a href="{{ $termsUrl }}" target="_blank" rel="noopener noreferrer"
            class="text-[var(--primary)] hover:underline font-semibold">
            Términos y Condiciones
        </a>
        y la
        <a href="{{ $privacyUrl }}" target="_blank" rel="noopener noreferrer"
            class="text-[var(--primary)] hover:underline font-semibold">
        Política de Privacidad y Tratamiento de Datos Personales
        </a>
        de {{ config('app.name') }}
    </div>




</x-layouts.guest-auth>
