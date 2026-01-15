<footer class="bg-[var(--card)] border-t border-[var(--border)] mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

            {{-- Logo --}}
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/FESC-30.png') }}" alt="Logo FESC" class="h-7 w-auto" />
            </div>

            {{-- Links --}}
            <nav class="flex flex-wrap items-center justify-center gap-x-5 gap-y-2">

                {{-- Legal --}}
                <div class="flex items-center gap-3">
                    <span
                        class="hidden sm:inline text-[11px] font-semibold text-[var(--text-muted)] uppercase tracking-wider">
                        Legal
                    </span>

                    <a href="{{ route('legal.terms') }}" @guest target="_blank" rel="noopener noreferrer" @endguest
                        class="inline-flex items-center gap-1.5 text-sm text-[var(--text-secondary)]
                               hover:text-[var(--accent)] transition-colors group">
                        <svg class="w-3.5 h-3.5 text-[var(--accent)]/50 group-hover:text-[var(--accent)] transition-colors"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Términos y Condiciones
                    </a>

                    <span class="text-[var(--text-muted)]/70">|</span>

                    <a href="{{ route('legal.privacy') }}" @guest target="_blank" rel="noopener noreferrer" @endguest
                        class="inline-flex items-center gap-1.5 text-sm text-[var(--text-secondary)]
                               hover:text-[var(--accent)] transition-colors group">
                        <svg class="w-3.5 h-3.5 text-[var(--accent)]/50 group-hover:text-[var(--accent)] transition-colors"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Privacidad de Datos
                    </a>
                </div>

                {{-- Separador --}}
                <span class="hidden md:inline text-[var(--text-muted)]">•</span>

                {{-- Información --}}
                <div class="flex items-center gap-3">
                    <span
                        class="hidden sm:inline text-[11px] font-semibold text-[var(--text-muted)] uppercase tracking-wider">
                        Información
                    </span>

                    {{-- Dispara evento global (funciona en guest y auth) --}}
                    <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-team-modal'))" class="inline-flex items-center gap-1.5 text-sm text-[var(--text-secondary)]
                               hover:text-[var(--accent)] transition-colors group">
                        <svg class="w-3.5 h-3.5 text-[var(--accent)]/50 group-hover:text-[var(--accent)] transition-colors"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Equipo de Desarrollo
                    </button>
                </div>

            </nav>
        </div>
    </div>

    {{-- Modal --}}
    <x-team-modal />
</footer>
