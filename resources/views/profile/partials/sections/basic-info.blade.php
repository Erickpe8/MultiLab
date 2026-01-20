<x-ui.section-card title="Información básica"
    subtitle="Actualiza los datos personales y las preferencias que definen tu experiencia en el portal.">
    <div class="space-y-6">

        {{-- Nombres --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="first_name" class="block font-semibold theme-text text-base mb-2">Primer nombre</label>
                <input id="first_name" name="first_name" type="text" x-model="current.first_name"
                    autocomplete="given-name" required class="block w-full rounded-lg border theme-bd bg-white px-4 py-2.5 theme-text
                           placeholder:text-[color:var(--muted)]
                           focus:border-[var(--accent)] focus:outline-none focus:ring-2 focus:ring-[var(--accent)]
                           transition-colors" />
                @error('first_name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="middle_name" class="block font-semibold theme-text text-base mb-2">Segundo nombre</label>
                <input id="middle_name" name="middle_name" type="text" x-model="current.middle_name"
                    autocomplete="additional-name" class="block w-full rounded-lg border theme-bd bg-white px-4 py-2.5 theme-text
                           placeholder:text-[color:var(--muted)]
                           focus:border-[var(--accent)] focus:outline-none focus:ring-2 focus:ring-[var(--accent)]
                           transition-colors" />
                @error('middle_name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Apellidos --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="first_surname" class="block font-semibold theme-text text-base mb-2">Primer apellido</label>
                <input id="first_surname" name="first_surname" type="text" x-model="current.first_surname"
                    autocomplete="family-name" required class="block w-full rounded-lg border theme-bd bg-white px-4 py-2.5 theme-text
                           placeholder:text-[color:var(--muted)]
                           focus:border-[var(--accent)] focus:outline-none focus:ring-2 focus:ring-[var(--accent)]
                           transition-colors" />
                @error('first_surname')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="second_surname" class="block font-semibold theme-text text-base mb-2">Segundo apellido</label>
                <input id="second_surname" name="second_surname" type="text" x-model="current.second_surname"
                    autocomplete="family-name" class="block w-full rounded-lg border theme-bd bg-white px-4 py-2.5 theme-text
                           placeholder:text-[color:var(--muted)]
                           focus:border-[var(--accent)] focus:outline-none focus:ring-2 focus:ring-[var(--accent)]
                           transition-colors" />
                @error('second_surname')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Género + Email --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="gender" class="block font-semibold theme-text text-base mb-2">Género</label>

                <select id="gender" name="gender" x-model="current.gender" class="block w-full rounded-lg border theme-bd bg-white px-4 py-2.5 theme-text
                           focus:border-[var(--accent)] focus:outline-none focus:ring-2 focus:ring-[var(--accent)]
                           transition-colors appearance-none">
                    <option value="">Sin especificar</option>
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                    <option value="O">Otro</option>
                </select>

                @error('gender')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block font-semibold theme-text text-base mb-2">Correo institucional</label>
                <input id="email" name="email" type="email" x-model="current.email" autocomplete="email" required class="block w-full rounded-lg border theme-bd bg-white px-4 py-2.5 theme-text
                           placeholder:text-[color:var(--muted)]
                           focus:border-[var(--accent)] focus:outline-none focus:ring-2 focus:ring-[var(--accent)]
                           transition-colors" placeholder="correo@institucion.edu" />

                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

                {{-- bloque verificación queda igual --}}
                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                    <div class="mt-3 p-3 rounded-lg verification-warning-bg verification-warning-border">
                        <div class="flex items-start gap-2">
                            <x-ui.icon name="advertencia" size="sm"
                                class="verification-warning-icon mt-0.5 shrink-0" />
                            <div class="flex-1 space-y-1">
                                <p class="text-sm verification-warning-text">Tu dirección de correo no ha sido verificada.</p>
                                <button type="button" form="send-verification" onclick="this.form.submit()"
                                    class="text-sm font-medium verification-warning-link hover:underline transition-all">
                                    Reenviar enlace de verificación
                                </button>
                            </div>

                        </div>

                    </div>
                @endif
            </div>
        </div>

        {{-- Teléfonos --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="phone" class="block font-semibold theme-text text-base mb-2">Teléfono fijo</label>
                <input id="phone" name="phone" type="text" x-model="current.phone" autocomplete="tel" inputmode="tel"
                    class="block w-full rounded-lg border theme-bd bg-white px-4 py-2.5 theme-text
                           placeholder:text-[color:var(--muted)]
                           focus:border-[var(--accent)] focus:outline-none focus:ring-2 focus:ring-[var(--accent)]
                           transition-colors" />
                @error('phone')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="mobile" class="block font-semibold theme-text text-base mb-2">Celular</label>
                <input id="mobile" name="mobile" type="text" x-model="current.mobile" autocomplete="tel" inputmode="tel"
                    class="block w-full rounded-lg border theme-bd bg-white px-4 py-2.5 theme-text
                           placeholder:text-[color:var(--muted)]
                           focus:border-[var(--accent)] focus:outline-none focus:ring-2 focus:ring-[var(--accent)]
                           transition-colors" />
                @error('mobile')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone_extension" class="block font-semibold theme-text text-base mb-2">Extensión</label>
                <input id="phone_extension" name="phone_extension" type="text" x-model="current.phone_extension"
                    autocomplete="off" class="block w-full rounded-lg border theme-bd bg-white px-4 py-2.5 theme-text
                           placeholder:text-[color:var(--muted)]
                           focus:border-[var(--accent)] focus:outline-none focus:ring-2 focus:ring-[var(--accent)]
                           transition-colors" />
                @error('phone_extension')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

    </div>
</x-ui.section-card>
