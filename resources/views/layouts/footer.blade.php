<footer class="bg-[var(--card)] border-t border-[var(--border)] mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

            {{-- Logo --}}
            <div class="flex items-center gap-2">
                <x-brand.logo variant="horizontal" class="h-7 w-auto" />
                <span class="font-bold text-[var(--text)] text-sm">{{ config('app.name') }}</span>
            </div>

            {{-- Links --}}
            <nav class="flex flex-wrap items-center justify-center gap-x-5 gap-y-2">

                {{-- Legal --}}
                <div class="flex items-center gap-3">
                    <span class="hidden sm:inline text-[11px] font-semibold text-[var(--text-muted)] uppercase tracking-wider">
                        Legal
                    </span>

                    <a href="{{ route('legal.terms') }}" @guest target="_blank" rel="noopener noreferrer" @endguest
                       class="inline-flex items-center gap-1.5 text-sm text-[var(--text-secondary)]
                              hover:text-[var(--accent)] transition-colors group">
                        <x-ui.icon name="documentos" size="sm"
                            class="text-[var(--accent)]/50 group-hover:text-[var(--accent)] transition-colors" />
                        Términos y Condiciones
                    </a>

                    <span class="text-[var(--text-muted)]/70">|</span>

                    <a href="{{ route('legal.privacy') }}" @guest target="_blank" rel="noopener noreferrer" @endguest
                       class="inline-flex items-center gap-1.5 text-sm text-[var(--text-secondary)]
                              hover:text-[var(--accent)] transition-colors group">
                        <x-ui.icon name="bloquear" size="sm"
                            class="text-[var(--accent)]/50 group-hover:text-[var(--accent)] transition-colors" />
                        Privacidad de Datos
                    </a>
                </div>

                {{-- Separador --}}
                <span class="hidden md:inline text-[var(--text-muted)]">•</span>

                {{-- Información --}}
                <div class="flex items-center gap-3">
                    <span class="hidden sm:inline text-[11px] font-semibold text-[var(--text-muted)] uppercase tracking-wider">
                        Información
                    </span>

                    {{-- Dispara evento global (funciona en guest y auth) --}}
                    <x-ui.button variant="ghost" type="button"
                                 onclick="window.dispatchEvent(new CustomEvent('open-team-modal'))"
                                 class="inline-flex items-center gap-1.5 text-sm text-[var(--text-secondary)]">
                        <x-ui.icon name="equipo-desarrollo" size="sm"
                            class="text-[var(--accent)]/50" />
                        Equipo de Desarrollo
                    </x-ui.button>
                </div>

            </nav>
        </div>
    </div>

    {{-- Modal --}}
    <x-team-modal />
</footer>
