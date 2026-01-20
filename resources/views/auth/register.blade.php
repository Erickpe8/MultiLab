<x-layouts.guest-auth title="Pre-registro MultiLab" subtitle="Complete sus datos institucionales para solicitar acceso">
    @php
$inputClass = 'h-11 w-full rounded-xl border border-[var(--border)] bg-[var(--card)]/70 px-4 text-sm text-[var(--text)] placeholder:text-[color:var(--text-muted)] focus:outline-none focus:ring-2 focus:ring-[#D84040]/40 focus:border-[#D84040] transition';
$buttonClass = 'w-full h-11 rounded-xl font-semibold text-white bg-[#8E1616] hover:bg-[#D84040] disabled:opacity-60 disabled:cursor-not-allowed shadow-soft transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[#D84040]/50 focus-visible:ring-offset-[var(--bg)]';
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
                    placeholder="Juan Pablo Pérez" class="{{ $inputClass }}" />
                <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs text-[#D84040]" />
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-[var(--text)] mb-1">
                    Correo institucional
                </label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                    placeholder="correo@fesc.edu.co" class="{{ $inputClass }}" />
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-[#D84040]" />
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-[var(--text)] mb-1">
                    Contraseña
                </label>
                <input id="password" name="password" type="password" required
                    placeholder="●●●●●●●●" class="{{ $inputClass }}" />
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-[#D84040]" />
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-[var(--text)] mb-1">
                    Confirmar contraseña
                </label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    placeholder="●●●●●●●●" class="{{ $inputClass }}" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs text-[#D84040]" />
            </div>
        </div>

        <button type="submit" class="{{ $buttonClass }}">
            ENVIAR PREREGISTRO
        </button>

        <p class="text-sm text-center text-[color:var(--text-muted)]">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}" class="text-[#8E1616] font-semibold hover:underline">
                Inicia sesión
            </a>
        </p>
    </form>

    <div class="mt-1 text-xs text-center text-[color:var(--text-muted)] leading-snug">
        Al registrarte aceptas los
        <a href="{{ $termsUrl }}" target="_blank" rel="noopener noreferrer"
            class="text-[#8E1616] hover:underline font-semibold">
            Términos y Condiciones
        </a>
        y la
        <a href="{{ $privacyUrl }}" target="_blank" rel="noopener noreferrer"
            class="text-[#8E1616] hover:underline font-semibold">
            Política de Privacidad y Tratamiento de Datos Personales
        </a>
        de MultiLab
    </div>




</x-layouts.guest-auth>
