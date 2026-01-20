<x-ui.section-card title="Acciones del formulario" subtitle="Guarda los cambios una vez que todo esté correcto.">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        {{-- Estado --}}
        <div class="flex items-center gap-2">
            {{-- Sin cambios --}}
            <span x-show="!hasChanges && !loading"
                class="inline-flex items-center gap-2 rounded-full border border-[color:var(--border)] bg-white px-3 py-1 text-xs font-semibold text-[color:var(--text-muted)]">
                <x-ui.icon name="info" size="xs" class="text-[color:var(--text-muted)]" />
                No hay cambios pendientes
            </span>

            {{-- Listo para guardar --}}
            <span x-show="hasChanges && !loading" style="display: none;"
                class="inline-flex items-center gap-2 rounded-full border border-[color:var(--border)] bg-white px-3 py-1 text-xs font-semibold text-[color:var(--text)]">
                <span class="h-2 w-2 rounded-full bg-[var(--accent)]"></span>
                Cambios listos para guardar
            </span>

            {{-- Guardando --}}
            <span x-show="loading" style="display: none;"
                class="inline-flex items-center gap-2 rounded-full border border-[color:var(--border)] bg-white px-3 py-1 text-xs font-semibold text-[color:var(--text)]">
                <x-ui.icon name="refrescar" size="xs" class="animate-spin" />
                Guardando…
            </span>

        </div>

        {{-- Acción --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
            <button type="submit" :disabled="!hasChanges || loading" class="inline-flex min-w-[200px] items-center justify-center gap-2 rounded-lg
                       border border-transparent bg-[var(--accent)] px-6 py-3 text-sm font-semibold text-white
                       transition-all duration-200 hover:bg-[var(--primary)] hover:shadow-lg
                       focus:outline-none focus:ring-2 focus:ring-[var(--accent)]
                       disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:shadow-none">
                <x-ui.icon x-show="loading" name="refrescar" size="sm" class="animate-spin" />

                <span x-show="!loading">Guardar cambios</span>
                <span x-show="loading" style="display: none;">Guardando...</span>
            </button>
        </div>
    </div>
</x-ui.section-card>
