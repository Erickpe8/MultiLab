<x-ui.section-card title="Acciones del formulario" subtitle="Guarda los cambios una vez que todo esté correcto.">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        {{-- Estado --}}
        <div class="flex items-center gap-2">
            {{-- Sin cambios --}}
            <span x-show="!hasChanges && !loading"
                class="inline-flex items-center gap-2 rounded-full border border-[color:var(--border)] bg-white px-3 py-1 text-xs font-semibold text-[color:var(--text-muted)]">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
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
                <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"
                        fill="none"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
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
                <svg x-show="loading" class="h-5 w-5 animate-spin" style="display: none;" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"
                        fill="none"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>

                <span x-show="!loading">Guardar cambios</span>
                <span x-show="loading" style="display: none;">Guardando...</span>
            </button>
        </div>
    </div>
</x-ui.section-card>
