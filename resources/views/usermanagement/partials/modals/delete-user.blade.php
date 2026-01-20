{{-- resources/views/usermanagement/partials/modals/delete-user.blade.php --}}
<x-modal name="delete-user-modal" :show="false" maxWidth="lg">
    <div class="p-6">
        <!-- Header -->
        <div class="flex items-start gap-4 mb-6">
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-red-600 to-red-700
                        flex items-center justify-center shrink-0 animate-pulse">
                <x-ui.icon name="eliminar" size="lg" class="text-white" />
            </div>
            <div class="flex-1">
                <h3 class="text-xl font-bold text-red-600 dark:text-red-400 mb-1">
                    ⚠️ Eliminar Usuario Permanentemente
                </h3>
                <p class="text-sm text-[var(--text-muted)]">
                    Esta acción NO se puede deshacer
                </p>
            </div>
        </div>

        <!-- User Info -->
        <div class="mb-6 p-4 rounded-lg bg-red-500/10 border border-red-500/20">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[var(--primary)] to-[var(--accent)]
                            flex items-center justify-center text-white font-bold"
                    id="delete-user-avatar">
                    ?
                </div>
                <div>
                    <p class="font-semibold text-[var(--text)]" id="delete-user-name">Nombre del usuario</p>
                    <p class="text-xs text-[var(--text-muted)]" id="delete-user-email">email@example.com</p>
                </div>
            </div>
        </div>

        <!-- Warning List -->
        <div class="mb-6 p-4 rounded-lg bg-red-500/10 border border-red-500/30 space-y-2">
            <p class="text-sm font-semibold text-red-700 dark:text-red-300 mb-3">
                Se eliminará permanentemente:
            </p>
            <ul class="space-y-2 text-xs text-red-600 dark:text-red-400">
                <li class="flex items-start gap-2">
                    <x-ui.icon name="error" size="sm"
                        class="text-red-600 dark:text-red-400 shrink-0 mt-0.5" />
                    <span>Toda su información personal y de contacto</span>
                </li>
                <li class="flex items-start gap-2">
                    <x-ui.icon name="error" size="sm"
                        class="text-red-600 dark:text-red-400 shrink-0 mt-0.5" />
                    <span>Todos sus archivos y documentos cargados</span>
                </li>
                <li class="flex items-start gap-2">
                    <x-ui.icon name="error" size="sm"
                        class="text-red-600 dark:text-red-400 shrink-0 mt-0.5" />
                    <span>Su historial completo de actividad</span>
                </li>
                <li class="flex items-start gap-2">
                    <x-ui.icon name="error" size="sm"
                        class="text-red-600 dark:text-red-400 shrink-0 mt-0.5" />
                    <span>Todas sus configuraciones y preferencias</span>
                </li>
            </ul>
        </div>

        <!-- Hidden field para user ID -->
        <input type="hidden" id="delete-user-id">

        <!-- Actions -->
        <div class="flex flex-col-reverse sm:flex-row gap-3 justify-end">
            <button type="button"
                    onclick="closeModal('delete-user-modal')"
                    class="px-5 py-2.5 rounded-lg border border-[var(--border)]
                           text-[var(--text)] bg-[var(--card)]
                           hover:bg-[var(--border)]/10 transition-all font-medium">
                Cancelar
            </button>
            <button type="button"
                    id="delete-confirm-btn"
                    onclick="confirmDeleteUser()"
                    class="px-6 py-2.5 rounded-lg bg-gradient-to-r from-red-600 to-red-700
                           text-white font-semibold hover:shadow-lg hover:scale-[1.02]
                           active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                <x-ui.icon name="eliminar" size="sm" class="text-white" />
                <span>Sí, Eliminar Permanentemente</span>
            </button>
        </div>
    </div>
</x-modal>
