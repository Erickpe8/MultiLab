<x-layouts.guest-auth title="Recuperar contraseña" subtitle="Envía tu correo institucional para recibir el enlace">
    @php
        $inputClass = 'h-11 w-full rounded-xl border border-[var(--border)] bg-[var(--card)]/70 px-4 text-sm text-[var(--text)] placeholder:text-[color:var(--text-muted)] focus:outline-none focus:ring-2 focus:ring-[var(--primary)]/40 focus:border-[var(--primary)] transition';
        $buttonClass = 'w-full h-11 rounded-xl font-semibold text-white bg-[var(--primary)] hover:bg-[var(--primary-600)] disabled:opacity-60 disabled:cursor-not-allowed shadow-soft transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[var(--primary)]/50 focus-visible:ring-offset-[var(--bg)]';
        $mailConfigured = $mailConfigured ?? true;
    @endphp

    <div
        x-data="{ open: {{ $mailConfigured ? false : true }} }"
        x-on:keydown.escape.window="open = false"
        class="space-y-5"
    >
        <div x-show="open" x-cloak class="fixed inset-0 z-40 flex items-center justify-center px-4 py-6">
            <div class="fixed inset-0 bg-gray-900/75 dark:bg-gray-950/90 backdrop-blur-sm"></div>
            <div
                class="relative z-50 w-full max-w-xl space-y-4 rounded-2xl border border-[var(--border)] bg-[var(--card)] p-6 text-[var(--text)] shadow-lg shadow-[var(--primary-500)/10]"
                @click.away="open = false"
            >
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[var(--primary)]/10 text-[var(--primary)]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a7 7 0 100 14A7 7 0 009 2zm.75 4.75a.75.75 0 00-1.5 0v1.5a.75.75 0 001.5 0v-1.5zm0 4a.75.75 0 00-1.5 0v3a.75.75 0 001.5 0v-3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-[var(--text)]">
                        Servicio temporalmente no disponible
                    </h2>
                </div>
                <p class="text-sm text-[var(--text-muted)]">
                    La recuperación de contraseña por correo aún no está habilitada. Para activar este servicio, se debe configurar un proveedor de correo (SMTP) en el entorno de la aplicación. Esto se define en las variables de entorno (.env) por ejemplo: MAIL_MAILER, MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD y MAIL_FROM_ADDRESS. Cuando el correo esté configurado, podrás solicitar el enlace de recuperación sin problemas.
                </p>
                <p class="text-xs text-[var(--text-muted)]">
                    Si necesitas acceso inmediato, contacta al administrador del sistema.
                </p>
                <div class="flex justify-end">
                    <a
                        href="{{ route('login') }}"
                        class="rounded-lg bg-[var(--primary)] px-4 py-2 text-sm font-semibold text-white hover:bg-[var(--primary-600)] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[var(--primary)]/60 focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--bg)]"
                        @click="open = false"
                    >
                        Entendido
                    </a>
                </div>
            </div>
        </div>

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

            <div class="space-y-1">
                <button type="submit" class="{{ $buttonClass }}" @disabled(! $mailConfigured)>
                    ENVIAR ENLACE
                </button>
                @unless ($mailConfigured)
                    <p class="text-center text-xs text-[var(--text-muted)]">
                        Configuración de correo pendiente.
                    </p>
                @endunless
            </div>
        </form>

        @if (Route::has('login'))
            <p class="text-sm text-center text-[color:var(--text-muted)]">
                ¿Ya tienes cuenta?
                <a href="{{ route('login') }}" class="text-[var(--primary)] font-semibold hover:underline">
                    Inicia sesión
                </a>
            </p>
        @endif
    </div>
</x-layouts.guest-auth>
