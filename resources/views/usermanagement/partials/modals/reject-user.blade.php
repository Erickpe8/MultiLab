{{-- resources/views/usermanagement/partials/modals/reject-user.blade.php --}}
<x-modal name="reject-user-modal" :show="false" maxWidth="lg">
    <div class="p-6 bg-[var(--card)]">
        <!-- Header -->
        <div class="flex items-start gap-4 mb-6">
            <div
                class="w-12 h-12 rounded-full bg-gradient-to-br from-red-500 to-red-600
                        flex items-center justify-center shrink-0">
                <x-ui.icon name="advertencia" size="lg" class="text-white" />
            </div>
            <div class="flex-1">
                <h3 class="text-xl font-bold text-[var(--text)] mb-1">
                    Rechazar Solicitud
                </h3>
                <p class="text-sm text-[var(--text-muted)]">
                    Esta acción eliminará permanentemente al usuario
                </p>
            </div>
            <button type="button" @click="show = false"
                class="text-[var(--text-muted)] hover:text-[var(--text)] transition-colors">
                <x-ui.icon name="cerrar" size="sm" class="text-[var(--text-muted)] hover:text-[var(--text)]" />
            </button>
        </div>

        <!-- User Info -->
        <div class="mb-6 p-4 rounded-lg bg-red-500/10 border border-red-500/20">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-red-500 to-red-600
                            flex items-center justify-center text-white font-bold"
                    id="reject-user-avatar">
                    ?
                </div>
                <div>
                    <p class="font-semibold text-[var(--text)]" id="reject-user-name">Nombre del Usuario</p>
                    <p class="text-sm text-[var(--text-muted)]" id="reject-user-email">email@ejemplo.com</p>
                </div>
            </div>
        </div>

        <!-- Warning Message -->
        <div class="mb-6 p-4 rounded-lg bg-amber-500/10 border border-amber-500/20">
            <div class="flex gap-3">
                <x-ui.icon name="advertencia" size="sm"
                    class="text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" />
                <div>
                    <p class="text-sm font-semibold text-amber-800 dark:text-amber-300 mb-1">
                        ¿Estás seguro de rechazar esta solicitud?
                    </p>
                    <p class="text-xs text-amber-700 dark:text-amber-400">
                        El usuario será eliminado permanentemente y no podrá registrarse nuevamente con el mismo correo.
                    </p>
                </div>
            </div>
        </div>

        <input type="hidden" id="reject-user-id">

        <!-- Actions -->
        <div class="flex flex-col-reverse sm:flex-row gap-3 justify-end">
            <button type="button" @click="show = false"
                class="px-5 py-2.5 rounded-lg border border-[var(--border)]
                       text-[var(--text)] bg-[var(--card)]
                       hover:bg-[var(--border)]/10 transition-all font-medium">
                Cancelar
            </button>
            <button type="button" id="reject-confirm-btn" onclick="confirmRejectUser()"
                class="px-6 py-2.5 rounded-lg bg-gradient-to-r from-red-500 to-red-600
                       text-white font-semibold hover:shadow-lg hover:scale-[1.02]
                       active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                <x-ui.icon name="eliminar" size="sm" class="text-white" />
                <span>Rechazar Solicitud</span>
            </button>
        </div>
    </div>
</x-modal>
