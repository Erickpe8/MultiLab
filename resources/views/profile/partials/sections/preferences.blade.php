<x-ui.section-card title="Preferencias"
    subtitle="Controla cómo y cuándo recibes notificaciones y el aspecto de la interfaz.">
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <input type="hidden" name="notify_email" value="0">
                <label for="notify_email" class="flex items-center gap-3 text-sm font-semibold theme-text">
                    <input id="notify_email" name="notify_email" type="checkbox" value="1"
                        x-model="current.notify_email"
                        class="h-4 w-4 rounded border theme-bd focus:ring-[var(--accent)]" />
                    Recibir notificaciones por correo
                </label>
                <p class="text-xs text-[color:var(--muted)]">
                    Correos sobre eventos importantes y recordatorios de hitos.
                </p>
                @error('notify_email')
                    <p class="mt-1 text-xs text-[var(--primary-600)]">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <input type="hidden" name="notify_in_app" value="0">
                <label for="notify_in_app" class="flex items-center gap-3 text-sm font-semibold theme-text">
                    <input id="notify_in_app" name="notify_in_app" type="checkbox" value="1"
                        x-model="current.notify_in_app"
                        class="h-4 w-4 rounded border theme-bd focus:ring-[var(--accent)]" />
                    Notificaciones dentro de la app
                </label>
                <p class="text-xs text-[color:var(--muted)]">
                    Alertas en el panel cuando hay novedades en tus áreas de interés.
                </p>
                @error('notify_in_app')
                    <p class="mt-1 text-xs text-[var(--primary-600)]">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="digest_frequency" class="block font-semibold theme-text text-sm mb-2">
                    Frecuencia de resumen
                </label>
                <select id="digest_frequency" name="digest_frequency" x-model="current.digest_frequency"
                    class="block w-full rounded-lg border theme-bd bg-[var(--bg)] px-4 py-3 theme-text focus:border-[var(--accent)] focus:outline-none focus:ring-2 focus:ring-[var(--accent)] transition-all">
                    <option value="none">Sin resúmenes</option>
                    <option value="daily">Resumen diario</option>
                    <option value="weekly">Resumen semanal</option>
                </select>
                @error('digest_frequency')
                    <p class="mt-1 text-xs text-[var(--primary-600)]">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="theme" class="block font-semibold theme-text text-sm mb-2">
                    Tema
                </label>
                <select id="theme" name="theme" x-model="current.theme" @change="setThemeFromSelect()"
                    class="block w-full rounded-lg border theme-bd bg-[var(--bg)] px-4 py-3 theme-text focus:border-[var(--accent)] focus:outline-none focus:ring-2 focus:ring-[var(--accent)] transition-all">
                    <option value="system">Predeterminado (sistema)</option>
                    <option value="light">Claro</option>
                    <option value="dark">Oscuro</option>
                </select>
                <p class="mt-1 text-xs text-[var(--text-muted)] flex items-center gap-2">
                    <span x-show="themeSaving">Guardando...</span>
                    <span x-show="!themeSaving && themeStatus" x-text="themeStatus"></span>
                </p>
                @error('theme')
                    <p class="mt-1 text-xs text-[var(--primary-600)]">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="pt-4 border-t border-[color:var(--border)]">
            <input type="hidden" name="compact_mode" value="0">
            <label for="compact_mode" class="flex items-center gap-3 text-sm font-semibold">
                <input id="compact_mode" name="compact_mode" type="checkbox" value="1" x-model="current.compact_mode"
                    class="h-4 w-4 rounded border theme-bd focus:ring-[var(--accent)]" />
                Activar modo compacto
            </label>
            <p class="text-xs text-[color:var(--muted)]">
                Reduce los espacios y muestra más información por pantalla.
            </p>
        </div>
    </div>
</x-ui.section-card>
