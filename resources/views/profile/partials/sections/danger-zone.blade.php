<x-ui.section-card title="Zona de peligro" subtitle="Acciones irreversibles que afectan permanentemente tu cuenta.">
    {{-- Wrapper: ESTA ES LA TARJETA ROJA --}}
    <div class="group relative overflow-hidden rounded-xl
            border-2 danger-zone-border
            danger-zone-bg bg-rose-50
            shadow-sm hover:shadow-lg transition-all duration-300
            backdrop-blur-sm">

        {{-- Decoración de fondo animada --}}
        <div class="absolute inset-0 danger-zone-decoration">
            <div class="absolute top-0 right-0 w-32 h-32 danger-glow-1 rounded-full blur-2xl animate-pulse"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 danger-glow-2 rounded-full blur-2xl animate-pulse"
                style="animation-delay: 1s;"></div>
        </div>

        <div class="relative p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row items-start gap-4">
                {{-- Icono --}}
                <div class="shrink-0">
                    <div class="w-12 h-12 rounded-full danger-icon-bg
                            flex items-center justify-center
                            shadow-lg danger-icon-ring">
                        <svg class="w-6 h-6 danger-icon-color" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>

                {{-- Contenido --}}
                <div class="flex-1 min-w-0">
                    {{-- Badge informativo --}}
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full
                                danger-badge-bg danger-badge-border mb-3">
                        <svg class="w-4 h-4 danger-badge-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-xs font-semibold danger-badge-text">
                            Solo para referencia - Funcionalidad deshabilitada
                        </span>
                    </div>

                    {{-- Colapsable --}}
                    <div x-data="{ expanded: false }">
                        <button type="button" @click="expanded = !expanded" class="group/btn inline-flex items-center gap-2 text-sm font-semibold transition-colors
                                   text-[var(--text)] hover:text-[var(--text)]/80">
                            <span x-text="expanded ? 'Ocultar información' : 'Ver qué se eliminaría'"></span>
                            <svg class="w-4 h-4 transition-transform duration-200 text-[var(--text)]/60"
                                :class="{ 'rotate-180': expanded }" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="expanded" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 transform translate-y-0"
                            x-transition:leave-end="opacity-0 transform -translate-y-2" style="display: none;"
                            class="mt-4 p-4 rounded-lg danger-expanded-bg danger-expanded-border backdrop-blur-sm">

                            <div class="flex items-start gap-2 mb-3">
                                <svg class="w-5 h-5 danger-warning-icon shrink-0 mt-0.5" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>

                                {{-- SOLO ESTE TEXTO EN NEGRO --}}
                                <p class="text-xs leading-relaxed font-medium text-[var(--text)]">
                                    <strong>Advertencia:</strong> La eliminación de cuenta sería permanente e
                                    irreversible.
                                    Se borrarían todos los datos, configuraciones y contenido asociado.
                                </p>
                            </div>

                            <div class="pt-3 danger-divider">
                                {{-- TÍTULO EN NEGRO --}}
                                <p class="text-xs font-bold mb-2.5 uppercase tracking-wide text-[var(--text)]/80">
                                    Se eliminarían:
                                </p>

                                <div class="grid sm:grid-cols-2 gap-2">
                                    @php
                                        $dangerItems = [
                                            'Información personal',
                                            'Archivos y documentos',
                                            'Historial de actividad',
                                            'Configuraciones',
                                        ];
                                    @endphp

                                    @foreach ($dangerItems as $item)
                                        <div class="flex items-center gap-2 p-2 rounded-lg danger-item-bg">
                                            <svg class="w-4 h-4 danger-item-icon shrink-0" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                    clip-rule="evenodd" />
                                            </svg>

                                            {{-- SOLO EL TEXTO EN NEGRO --}}
                                            <span class="text-xs text-[var(--text)]">{{ $item }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Botón deshabilitado --}}
                <div class="shrink-0 w-full sm:w-auto">
                    <button type="button" disabled title="Esta función está deshabilitada por política del sistema"
                        class="w-full sm:w-auto px-5 py-3 rounded-lg text-sm font-semibold
                            danger-button-disabled
                            cursor-not-allowed opacity-70
                            flex items-center justify-center gap-2
                            transition-all">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <span>Deshabilitado</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-ui.section-card>
