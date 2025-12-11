<footer class="bg-[var(--card)] border-t border-[var(--border)] mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">

        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">

            {{-- Logo MultiLab --}}
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/multilab.svg') }}" alt="Logo MultiLab" class="h-7 w-auto" />
                <span class="font-bold text-[var(--text)] text-sm">MultiLab</span>
            </div>

            {{-- Enlaces simples (sin rutas inexistentes) --}}
            <nav class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-sm">

                <span class="text-xs font-semibold text-[var(--text-muted)] uppercase tracking-wider hidden sm:inline">
                    Información
                </span>

                <span class="text-[var(--text-secondary)] opacity-70">
                    © {{ date('Y') }} – FESC
                </span>

                <span class="hidden md:inline text-[var(--text-muted)]">•</span>

                <span class="text-[var(--text-secondary)] opacity-70">
                    MultiLab v 0.01
                </span>

            </nav>
        </div>
    </div>
</footer>
