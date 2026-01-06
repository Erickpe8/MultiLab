@section('title', 'Pre-registro MultiLab')

<x-guest-layout>
    <div class="min-h-screen relative flex items-center justify-center bg-cover bg-center bg-no-repeat px-4 py-8"
        style="background-image: url('{{ asset('images/FESC.JPG') }}');">

        {{-- Overlay (sin blur): claro y oscuro --}}
        <div
            class="absolute inset-0 bg-gradient-to-br from-white/60 via-white/50 to-white/65 dark:from-black/60 dark:via-black/50 dark:to-black/65">
        </div>

        {{-- Card --}}
        <div class="relative z-10 w-full max-w-3xl">
            <div class="rounded-2xl border border-red-500/25 bg-white dark:bg-[var(--card)]
                        shadow-2xl overflow-hidden">

                {{-- Header --}}
                <div class="px-8 py-7 bg-white dark:bg-[var(--card)]">
                    <div class="flex items-center gap-5">
                        <a href="{{ route('welcome') }}" class="shrink-0" aria-label="Volver al inicio">
                            <img src="{{ asset('images/FESC-30.png') }}" alt="FESC"
                                class="h-12 w-auto transition-transform hover:scale-105" />
                        </a>

                        <div class="w-px h-10 bg-red-500/20"></div>

                        <div class="min-w-0">
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-[var(--text)] leading-tight">
                                Pre-registro MultiLab
                            </h2>
                            <p class="mt-1 text-sm text-[color:var(--text-muted)]">
                                Complete sus datos para solicitar acceso institucional
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Separador fino --}}
                <div class="h-px bg-red-500/25"></div>

                {{-- Body --}}
                <div class="px-8 sm:px-10 py-2">
                    <form method="POST" action="{{ route('register') }}" class="space-y-7">
                        @csrf

                        {{-- Grid --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            {{-- Nombre --}}
                            <div>
                                <label for="name" class="block text-base font-semibold text-[var(--text)] mb-2">
                                    Nombre completo
                                </label>
                                <input
                                    id="name"
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                    autofocus
                                    placeholder="Ej: Juan Pablo Pérez"
                                    class="w-full rounded-2xl border border-red-500/30 bg-white dark:bg-[var(--bg)]/35
                                           px-5 py-2 text-base text-[var(--text)]
                                           placeholder:text-[color:var(--text-muted)]
                                           focus:outline-none focus:ring-2 focus:ring-red-500/25 focus:border-red-500/55
                                           transition"
                                />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            {{-- Correo --}}
                            <div>
                                <label for="email" class="block text-base font-semibold text-[var(--text)] mb-2">
                                    Correo institucional
                                </label>
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    placeholder="correo@fesc.edu.co"
                                    class="w-full rounded-2xl border border-red-500/30 bg-white dark:bg-[var(--bg)]/35
                                           px-5 py-2 text-base text-[var(--text)]
                                           placeholder:text-[color:var(--text-muted)]
                                           focus:outline-none focus:ring-2 focus:ring-red-500/25 focus:border-red-500/55
                                           transition"
                                />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            {{-- Contraseña --}}
                            <div>
                                <label for="password" class="block text-base font-semibold text-[var(--text)] mb-2">
                                    Contraseña
                                </label>
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    placeholder="••••••••"
                                    class="w-full rounded-2xl border border-red-500/30 bg-white dark:bg-[var(--bg)]/35
                                           px-5 py-2 text-base text-[var(--text)]
                                           placeholder:text-[color:var(--text-muted)]
                                           focus:outline-none focus:ring-2 focus:ring-red-500/25 focus:border-red-500/55
                                           transition"
                                />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            {{-- Confirmar --}}
                            <div>
                                <label for="password_confirmation" class="block text-base font-semibold text-[var(--text)] mb-2">
                                    Confirmar contraseña
                                </label>
                                <input
                                    id="password_confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    required
                                    placeholder="••••••••"
                                    class="w-full rounded-2xl border border-red-500/30 bg-white dark:bg-[var(--bg)]/35
                                           px-5 py-2 text-base text-[var(--text)]
                                           placeholder:text-[color:var(--text-muted)]
                                           focus:outline-none focus:ring-2 focus:ring-red-500/25 focus:border-red-500/55
                                           transition"
                                />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>

                        </div>

                        {{-- Actions --}}
                        <div class="pt-2 flex flex-col-reverse sm:flex-row gap-4 sm:items-center sm:justify-between">
                            <a href="{{ route('login') }}"
                                class="text-base text-[color:var(--text-muted)] hover:text-red-600 transition-colors underline underline-offset-4">
                                ¿Ya tiene cuenta? Inicie sesión
                            </a>

                            <button
                                type="submit"
                                class="inline-flex justify-center items-center gap-2
                                       w-full sm:w-auto px-7 py-4 rounded-2xl font-bold text-base
                                       text-white bg-red-600 hover:bg-red-700
                                       transition shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500/25"
                            >
                                ENVIAR PREREGISTRO
                            </button>
                        </div>

                        @php
                            $termsUrl = \Illuminate\Support\Facades\Route::has('legal.terms')
                                ? route('legal.terms')
                                : (\Illuminate\Support\Facades\Route::has('terms')
                                    ? route('terms')
                                    : url('/legal/terms'));

                            $privacyUrl = \Illuminate\Support\Facades\Route::has('legal.privacy')
                                ? route('legal.privacy')
                                : (\Illuminate\Support\Facades\Route::has('privacy')
                                    ? route('privacy')
                                    : url('/legal/privacy'));
                        @endphp

                        {{-- Legal --}}
                        <div class="pt-6 border-t border-red-500/20">
                            <p class="text-sm text-center text-[color:var(--text-muted)] leading-relaxed">
                                Al registrarse, acepta automáticamente los
                                <a href="{{ $termsUrl }}" target="_blank" rel="noopener noreferrer"
                                    class="font-semibold text-red-600 hover:text-red-700 transition-colors underline underline-offset-4">
                                    Términos y Condiciones
                                </a>
                                y la
                                <a href="{{ $privacyUrl }}" target="_blank" rel="noopener noreferrer"
                                    class="font-semibold text-red-600 hover:text-red-700 transition-colors underline underline-offset-4">
                                    Política de Privacidad
                                </a>
                                del sistema.
                            </p>
                            <br>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
</x-guest-layout>
