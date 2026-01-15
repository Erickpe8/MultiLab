<section
    x-data="passwordUpdate({
        verifyUrl: '{{ route('password.verify') }}',
        csrf: document.querySelector('meta[name=csrf-token]')?.getAttribute('content') ?? '',
        startStep: @json(session('status') === 'password-updated' ? 1 : ($errors->updatePassword->any() ? 2 : 1)),
        startVerified: @json(session('status') === 'password-updated' ? false : $errors->updatePassword->any()),
    })"
    class="theme-text"
>
    <x-ui.section-card
        title="Actualizar contraseña"
        subtitle="Confirma tu contraseña actual antes de ingresar una nueva."
    >
        <div class="space-y-6">

            <p class="text-sm text-[color:var(--text)]/70">
                <span x-show="step === 1">Primero confirma tu contraseña actual para continuar.</span>
                <span x-show="step === 2" style="display:none">Ahora ingresa tu nueva contraseña y confírmala.</span>
            </p>

            {{-- PASO 1 --}}
            <div
                x-show="step === 1"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-x-4"
                x-transition:enter-end="opacity-100 transform translate-x-0"
            >
                <div class="space-y-4">
                    <div>
                        <label class="block font-semibold mb-2">Contraseña actual</label>

                        <div class="relative">
                            <input
                                x-model="currentPassword"
                                :type="showCur ? 'text' : 'password'"
                                autocomplete="current-password"
                                @keydown.enter.prevent="verifyCurrentPassword"
                                class="block w-full rounded-lg border theme-bd bg-[var(--bg)] px-4 py-2.5 pr-12
                                       placeholder:text-[color:var(--text)]/50
                                       focus:border-[var(--accent)] focus:ring-2 focus:ring-[var(--accent)]"
                                placeholder="Ingresa tu contraseña actual"
                            >

                            <button
                                type="button"
                                class="absolute inset-y-0 right-0 px-3 text-[color:var(--text)]/70"
                                @click="showCur = !showCur"
                            >
                                👁
                            </button>
                        </div>
                    </div>

                    <button
                        type="button"
                        @click="verifyCurrentPassword"
                        :disabled="!currentPassword || loading"
                        class="inline-flex min-w-[200px] items-center justify-center gap-2
                               rounded-lg bg-[var(--accent)] px-6 py-3 text-sm font-semibold text-white
                               hover:bg-[var(--primary)]
                               disabled:opacity-50"
                    >
                        <span x-show="!loading">Verificar contraseña</span>
                        <span x-show="loading" style="display:none">Verificando…</span>
                    </button>
                </div>
            </div>

            {{-- PASO 2 --}}
            <form
                x-show="step === 2"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-x-4"
                x-transition:enter-end="opacity-100 transform translate-x-0"
                style="display:none"
                method="post"
                action="{{ route('profile.password.update') }}"
                @submit="loading = true"
            >
                @csrf
                @method('patch')

                <input type="hidden" name="current_password" x-model="currentPassword">

                @if ($errors->updatePassword->any())
                    <p class="text-sm text-red-600">
                        Hubo errores en los campos. Corrígelos e inténtalo nuevamente.
                    </p>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold mb-2">Nueva contraseña</label>
                        <input
                            name="password"
                            required
                            autocomplete="new-password"
                            :type="showNew ? 'text' : 'password'"
                            class="block w-full rounded-lg border theme-bd bg-[var(--bg)] px-4 py-2.5
                                   focus:border-[var(--accent)] focus:ring-2 focus:ring-[var(--accent)]"
                        >
                        @error('password', 'updatePassword')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-semibold mb-2">Confirmar contraseña</label>
                        <input
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            :type="showConf ? 'text' : 'password'"
                            class="block w-full rounded-lg border theme-bd bg-[var(--bg)] px-4 py-2.5
                                   focus:border-[var(--accent)] focus:ring-2 focus:ring-[var(--accent)]"
                        >
                        @error('password_confirmation', 'updatePassword')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 mt-6">
                    <button
                        type="submit"
                        :disabled="loading"
                        class="min-w-[200px] rounded-lg bg-[var(--accent)] px-6 py-3
                               text-sm font-semibold text-white hover:bg-[var(--primary)]
                               disabled:opacity-50"
                    >
                        Guardar nueva contraseña
                    </button>

                    <button
                        type="button"
                        @click="resetToStepOne"
                        class="rounded-lg border px-6 py-3 text-sm"
                    >
                        Volver
                    </button>

                    @if (session('status') === 'password-updated')
                        <span class="text-green-600 font-medium">
                            Contraseña actualizada correctamente.
                        </span>
                    @endif
                </div>
            </form>

        </div>
    </x-ui.section-card>
</section>

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('passwordUpdate', (cfg) => ({
                showCur: false,
                showNew: false,
                showConf: false,

                step: cfg.startStep ?? 1,
                currentPasswordVerified: cfg.startVerified ?? false,
                currentPassword: '',
                loading: false,

                async verifyCurrentPassword() {
                    if (!this.currentPassword) return;

                    this.loading = true;

                    try {
                        const response = await fetch(cfg.verifyUrl, {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': cfg.csrf,
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: JSON.stringify({ current_password: this.currentPassword }),
                        });

                        const contentType = response.headers.get('content-type') ?? '';
                        const text = await response.text();

                        if (!contentType.includes('application/json')) {
                            this.handleHttpError(response.status, text, true);
                            return;
                        }

                        const payload = this.safeParseJson(text);

                        if (!response.ok) {
                            this.handleHttpError(response.status, payload);
                            return;
                        }

                        if (!payload.valid) {
                            showNotification?.('La contraseña actual es incorrecta.', 'error');
                            return;
                        }

                        this.currentPasswordVerified = true;
                        this.step = 2;
                        showNotification?.('Contraseña verificada correctamente.', 'success');
                    } catch (error) {
                        showNotification?.('Error al verificar la contraseña.', 'error');
                    } finally {
                        this.loading = false;
                    }
                },

                handleHttpError(status, payload = {}, nonJson = false) {
                    if (status === 401) {
                        showNotification?.('Sesión expirada, inicia sesión nuevamente.', 'error');
                        return;
                    }

                    if (status === 419) {
                        showNotification?.('CSRF expiró, recarga la página e intenta otra vez.', 'error');
                        return;
                    }

                    if (status === 302 || nonJson) {
                        showNotification?.('Respuesta inválida (redirect o HTML). Revisa la sesión.', 'error');
                        return;
                    }

                    const message = payload?.message ?? 'No fue posible verificar la contraseña.';
                    showNotification?.(message, 'error');
                },

                safeParseJson(text) {
                    try {
                        return JSON.parse(text);
                    } catch {
                        return {};
                    }
                },

                resetToStepOne() {
                    this.step = 1;
                    this.currentPassword = '';
                    this.currentPasswordVerified = false;
                    this.loading = false;
                    this.showCur = false;
                    this.showNew = false;
                    this.showConf = false;
                },
            }));
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof showNotification !== 'function') {
                return;
            }

            @if (session('status') === 'password-updated')
                showNotification('Contraseña actualizada correctamente.', 'success');
            @endif

            @if ($errors->updatePassword->any())
                showNotification('No fue posible actualizar la contraseña. Revisa los campos.', 'error');
            @endif
        });
    </script>
@endpush
