<div id="notify" class="fixed top-4 right-4 z-50 hidden opacity-0 -translate-y-2
            transition-all duration-300 ease-in-out" style="
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

                <svg id="notify-icon" class="w-7 h-7 text-[var(--accent)] stroke-[2]" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor">
                    <!-- se reemplaza dinámicamente -->
                </svg>
            </div>

            <!-- MENSAJE -->
            <p id="notify-message" class="flex-1 text-[var(--text)] font-semibold text-base sm:text-lg leading-snug">
            </p>

            <!-- BOTÓN CERRAR -->
            <button type="button" id="notify-close" class="ml-1 sm:ml-2 inline-flex items-center justify-center
                           text-[color:var(--text)]/60 hover:text-[var(--text)]
                           transition-colors shrink-0" aria-label="Cerrar">

                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</div>
