<section x-data="{
        showCur: false,
        showNew: false,
        showConf: false,
        step: 1,
        currentPasswordVerified: false,
        loading: false,
        currentPassword: '',

        /**
         * Verifica la contraseña actual con el endpoint antes de permitir el cambio.
         * Entradas: Ninguna.
         * Salidas: Promise<void> (sin retorno significativo).
         */
        async verifyCurrentPassword() {
            if (!this.currentPassword) return;

            this.loading = true;

            try {
                const response = await fetch('{{ route('password.verify') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ current_password: this.currentPassword })
                });

                const data = await response.json();

                if (data.valid) {
                    this.currentPasswordVerified = true;
                    this.step = 2;

                    if (typeof showNotification === 'function') {
                        showNotification('Verificamos tu contraseña actual.', 'success');
                    }
                } else {
                        if (typeof showNotification === 'function') {
                            showNotification('La contraseña actual no coincide. Verifica e intenta nuevamente.', 'error');
                        }
                }
            } catch (error) {
                if (typeof showNotification === 'function') {
                    showNotification('No se pudo verificar la contraseña. Inténtalo de nuevo.', 'error');
                }
            } finally {
                this.loading = false;
            }
        }
    }" class="theme-text">
    <x-ui.section-card title="Actualizar contraseña" subtitle="Confirma tu contraseña actual para continuar.">
        <div class="space-y-6">
            {{-- Texto guía --}}
            <p class="text-sm text-[color:var(--text)]/70">
                <span x-show="step === 1">Primero confirma tu contraseña actual para continuar.</span>
                <span x-show="step === 2" style="display: none;">Ahora ingresa tu nueva contraseña y confírmala.</span>
            </p>

            {{-- PASO 1: Verificar contraseña actual --}}
            <div x-show="step === 1" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-x-4"
                x-transition:enter-end="opacity-100 transform translate-x-0">
                <div class="space-y-4">
                    <div>
                        <label for="verify_current_password"
                            class="block font-semibold text-[var(--text)] text-base mb-2">
                            Contraseña actual
                        </label>

                        <div class="relative">
                            <input id="verify_current_password" x-model="currentPassword"
                                :type="showCur ? 'text' : 'password'" autocomplete="current-password"
                                @keydown.enter="verifyCurrentPassword()" class="block w-full rounded-lg border border-[color:var(--border)] bg-[var(--bg)] px-4 py-2.5 pr-12 text-[var(--text)]
                                       placeholder:text-[color:var(--text)]/50
                                       focus:border-[var(--accent)] focus:outline-none focus:ring-2 focus:ring-[var(--accent)]
                                       transition-colors" placeholder="Ingresa tu contraseña actual" />

                            <button type="button"
                                class="absolute inset-y-0 right-0 px-3 flex items-center text-[color:var(--text)]/70 hover:text-[var(--text)] transition-colors"
                                @click="showCur = !showCur"
                                :aria-label="showCur ? 'Ocultar contraseña' : 'Mostrar contraseña'">
                                <svg x-show="!showCur" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>

                                <svg x-show="showCur" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.05 10.05 0 012.307-3.78M6.18 6.18A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.05 10.05 0 01-4.132 5.225M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="button" @click="verifyCurrentPassword()" :disabled="!currentPassword || loading"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-[var(--accent)] px-6 py-3 text-sm font-semibold text-white
                               transition-colors duration-200 hover:bg-[var(--primary)]
                               focus:outline-none focus:ring-2 focus:ring-[var(--accent)]
                               disabled:cursor-not-allowed disabled:opacity-50 min-w-[180px]">
                        <svg x-show="loading" class="h-5 w-5 animate-spin" style="display: none;" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"
                                fill="none"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>

                        <span x-show="!loading">Verificar contraseña</span>
                        <span x-show="loading" style="display: none;">Verificando...</span>
                    </button>
                </div>
            </div>

            {{-- PASO 2: Nueva contraseña --}}
            <form x-show="step === 2" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-x-4"
                x-transition:enter-end="opacity-100 transform translate-x-0" style="display: none;"
                id="formUpdatePassword" method="post" action="{{ route('profile.password.update') }}"
                @submit="loading = true" class="space-y-6">
                @csrf
                @method('patch')

                <input type="hidden" name="current_password" x-model="currentPassword">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="update_password_password"
                            class="block font-semibold text-[var(--text)] text-base mb-2">
                            Nueva contraseña
                        </label>

                        <div class="relative">
                            <input id="update_password_password" name="password" :type="showNew ? 'text' : 'password'"
                                autocomplete="new-password" required class="block w-full rounded-lg border border-[color:var(--border)] bg-[var(--bg)] px-4 py-2.5 pr-12 text-[var(--text)]
                                       placeholder:text-[color:var(--text)]/50
                                       focus:border-[var(--accent)] focus:outline-none focus:ring-2 focus:ring-[var(--accent)]
                                       transition-colors" placeholder="Mínimo 8 caracteres" />

                            <button type="button"
                                class="absolute inset-y-0 right-0 px-3 flex items-center text-[color:var(--text)]/70 hover:text-[var(--text)] transition-colors"
                                @click="showNew = !showNew"
                                :aria-label="showNew ? 'Ocultar contraseña' : 'Mostrar contraseña'">
                                <svg x-show="!showNew" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>

                                <svg x-show="showNew" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.05 10.05 0 012.307-3.78M6.18 6.18A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.05 10.05 0 01-4.132 5.225M3 3l18 18" />
                                </svg>
                            </button>
                        </div>

                        @error('password', 'updatePassword')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="update_password_password_confirmation"
                            class="block font-semibold text-[var(--text)] text-base mb-2">
                            Confirmar contraseña
                        </label>

                        <div class="relative">
                            <input id="update_password_password_confirmation" name="password_confirmation"
                                :type="showConf ? 'text' : 'password'" autocomplete="new-password" required class="block w-full rounded-lg border border-[color:var(--border)] bg-[var(--bg)] px-4 py-2.5 pr-12 text-[var(--text)]
                                       placeholder:text-[color:var(--text)]/50
                                       focus:border-[var(--accent)] focus:outline-none focus:ring-2 focus:ring-[var(--accent)]
                                       transition-colors" placeholder="Repite la contraseña" />

                            <button type="button"
                                class="absolute inset-y-0 right-0 px-3 flex items-center text-[color:var(--text)]/70 hover:text-[var(--text)] transition-colors"
                                @click="showConf = !showConf"
                                :aria-label="showConf ? 'Ocultar contraseña' : 'Mostrar contraseña'">
                                <svg x-show="!showConf" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>

                                <svg x-show="showConf" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.05 10.05 0 012.307-3.78M6.18 6.18A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.05 10.05 0 01-4.132 5.225M3 3l18 18" />
                                </svg>
                            </button>
                        </div>

                        @error('password_confirmation', 'updatePassword')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <p class="text-xs text-[color:var(--text)]/60">
                    Usa al menos 8 caracteres y combina letras, números y símbolos.
                </p>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <button type="submit" :disabled="loading" class="inline-flex min-w-[200px] items-center justify-center gap-2 rounded-lg bg-[var(--accent)] px-6 py-3 text-sm font-semibold text-white
                               transition-colors duration-200 hover:bg-[var(--primary)]
                               focus:outline-none focus:ring-2 focus:ring-[var(--accent)]
                               disabled:cursor-not-allowed disabled:opacity-50">
                        <svg x-show="loading" class="h-5 w-5 animate-spin" style="display: none;" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"
                                fill="none"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>

                        <span x-show="!loading">Guardar nueva contraseña</span>
                        <span x-show="loading" style="display: none;">Guardando...</span>
                    </button>

                    <button type="button"
                        @click="step = 1; currentPassword = ''; currentPasswordVerified = false; loading = false"
                        :disabled="loading" class="inline-flex items-center justify-center gap-2 rounded-lg border border-[color:var(--border)] px-6 py-3 text-sm font-medium text-[var(--text)]
                               transition-colors duration-200 hover:bg-[color:var(--border)]/20
                               focus:outline-none focus:ring-2 focus:ring-[var(--accent)]
                               disabled:cursor-not-allowed disabled:opacity-50">
                        Volver
                    </button>

                </div>
            </form>
        </div>
    </x-ui.section-card>
</section>

@push('scripts')
    <script>
        /**
         * Dispara las notificaciones de estado o error tras cargar el formulario.
         * Entradas: Ninguna.
         * Salidas: void (sin retorno).
         */
        document.addEventListener('DOMContentLoaded', () => {
            @if ($errors->updatePassword->any())
                if (typeof showNotification === 'function') {
                    showNotification('No fue posible actualizar tu contraseña. Verifica los campos e inténtalo de nuevo.', 'error');
                }
            @endif
                });
    </script>
@endpush
