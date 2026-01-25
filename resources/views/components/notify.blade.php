<div id="toast-stack"
    role="status"
    aria-live="polite"
    aria-atomic="false"
    class="pointer-events-none fixed top-4 right-4 z-[9999] flex flex-col gap-3 w-full max-w-sm px-2 sm:px-0">
</div>

<div id="notify" class="fixed top-4 right-4 z-50 hidden opacity-0 transition-all duration-300 ease-out
        pointer-events-none"
    aria-hidden="true"
    inert
    style="
        --card: #ffffff;
        --border: #2563eb;
        --accent: #2563eb;
        --text: #1f2937;
     ">
    <div id="notify-card" class="max-w-md w-[92vw] sm:w-[460px] rounded-xl shadow-2xl border-l-8
                bg-[color:var(--card)] border-[color:var(--border)]">

        <div class="p-4 flex items-center gap-3">

            <!-- ICONO -->
            <div id="notify-icon-wrap" class="inline-flex items-center justify-center w-9 h-9 rounded-full
                        bg-[color:var(--border)]/40 shrink-0">
                <x-ui.icon id="notify-icon" name="info" size="xl"
                    class="w-7 h-7 text-[var(--accent)] stroke-[2]" />
            </div>

            <!-- MENSAJE -->
            <p id="notify-message" class="flex-1 text-[var(--text)] font-semibold text-base sm:text-lg leading-snug">
            </p>

            <!-- BOTÓN CERRAR -->
            <button type="button" id="notify-close" class="ml-1 sm:ml-2 inline-flex items-center justify-center
                           text-[color:var(--text)]/60 hover:text-[color:var(--text)]
                           transition-colors shrink-0" aria-label="Cerrar">

                <x-ui.icon name="cerrar" size="sm"
                    class="text-[color:var(--text)]/60 hover:text-[color:var(--text)] transition-colors" />
            </button>
        </div>
    </div>
</div>
