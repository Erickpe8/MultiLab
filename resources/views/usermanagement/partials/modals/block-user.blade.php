{{-- resources/views/usermanagement/partials/modals/block-user.blade.php --}}
<x-modal name="block-user-modal" :show="false" maxWidth="lg">
    <form id="block-user-form" class="space-y-5">
        <header class="px-8 pt-7 pb-4 flex items-start justify-between gap-6 border-b border-slate-200/80">
            <div class="space-y-1">
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500">Bloquear usuario</p>
                <p class="text-sm text-slate-500">Suspensión temporal del acceso</p>
            </div>
            <button type="button"
                    class="h-10 w-10 grid place-items-center rounded-full text-[var(--text-muted)] hover:bg-black/5 focus:outline-none focus:ring-2 focus:ring-[var(--accent)] focus:ring-offset-1"
                    aria-label="Cerrar modal"
                    onclick="closeModal('block-user-modal')">
                ×
            </button>
        </header>

        <input type="hidden" id="block-user-id">

        <div class="space-y-5 px-8">
            <div class="flex items-center gap-4">
                <span class="h-12 w-12 rounded-full bg-red-500/10 text-red-600 flex items-center justify-center font-semibold text-lg"
                      id="block-user-avatar">?</span>
                <div>
                    <p class="text-xl font-semibold text-[var(--text)]" id="block-user-name">Nombre del usuario</p>
                    <p class="text-sm text-slate-500" id="block-user-email">email@example.com</p>
                </div>
            </div>

            <p class="text-sm leading-relaxed text-[var(--text)]">
                El usuario no podrá autenticarse ni acceder al sistema hasta que sea desbloqueado por un superadmin.
            </p>

            <p class="text-sm text-[var(--text)]">
                ¿Confirmas que deseas bloquear a <span class="font-semibold" id="block-confirm-user">este usuario</span>?
            </p>

            <p class="text-xs text-slate-500">
                El bloqueo no elimina la cuenta; solo suspende el acceso temporalmente hasta que un superadmin lo revierta.
            </p>
        </div>

        <footer class="px-8 py-5 flex items-center justify-end gap-3 border-t border-slate-200/80">
            <button type="button" onclick="closeModal('block-user-modal')"
                    class="h-11 px-6 rounded-full border border-slate-200 text-sm font-semibold text-[var(--text)] bg-white hover:bg-[var(--border)]/40 transition">
                Cancelar
            </button>
            <button type="submit" id="block-user-confirm-btn"
                    class="relative h-11 min-w-[170px] rounded-full bg-red-600 px-6 text-sm font-semibold uppercase tracking-[0.2em] text-white disabled:opacity-70 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                <span class="spinner hidden">
                    <span class="w-3 h-3 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                </span>
                <span class="button-text">Bloquear</span>
            </button>
        </footer>
    </form>

    @once
    <style>
        #block-user-confirm-btn:disabled .spinner {
            display: inline-flex;
        }
        #block-user-confirm-btn:disabled .button-text {
            visibility: hidden;
        }
        #block-user-confirm-btn .spinner {
            display: none;
        }
    </style>
    @endonce
</x-modal>
