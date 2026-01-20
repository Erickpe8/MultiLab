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
                        <x-ui.icon name="advertencia" size="lg" class="danger-icon-color" />
                    </div>
                </div>

                {{-- Contenido --}}
                <div class="flex-1 min-w-0">
                    {{-- Badge informativo --}}
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full
                                danger-badge-bg danger-badge-border mb-3">
                        <x-ui.icon name="advertencia" size="sm" class="danger-badge-icon" />
                        <span class="text-xs font-semibold danger-badge-text">
                            Solo para referencia - Funcionalidad deshabilitada
                        </span>
                    </div>

                    {{-- Colapsable --}}
                    <div x-data="{ expanded: false }">
                        <button type="button" @click="expanded = !expanded" class="group/btn inline-flex items-center gap-2 text-sm font-semibold transition-colors
                                   text-[var(--text)] hover:text-[var(--text)]/80">
                            <span x-text="expanded ? 'Ocultar información' : 'Ver qué se eliminaría'"></span>
                            <x-ui.icon name="expandir" size="sm"
                                class="transition-transform duration-200 text-[var(--text)]/60"
                                :class="{ 'rotate-180': expanded }" />
                        </button>

                        <div x-show="expanded" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 transform translate-y-0"
                            x-transition:leave-end="opacity-0 transform -translate-y-2" style="display: none;"
                            class="mt-4 p-4 rounded-lg danger-expanded-bg danger-expanded-border backdrop-blur-sm">

                            <div class="flex items-start gap-2 mb-3">
                                <x-ui.icon name="advertencia" size="sm"
                                    class="danger-warning-icon shrink-0 mt-0.5" />

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
                                            <x-ui.icon name="error" size="sm"
                                                class="danger-item-icon shrink-0" />

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
                        <x-ui.icon name="bloquear" size="sm" class="text-[var(--text)]/60" />
                        <span>Deshabilitado</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-ui.section-card>
