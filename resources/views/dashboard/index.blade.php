{{-- resources/views/dashboard/index.blade.php --}}
<x-layouts.dashboard title="Panel Central" active="dashboard">
    {{-- HEADER DEL DASHBOARD --}}
    <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-semibold text-multilab-blue dark:text-multilab-gray">
                ¿Qué deseas hacer?
            </h1>
            <p class="text-sm text-multilab-darkblue dark:text-multilab-gray">
                Selecciona un módulo para continuar.
            </p>
        </div>

        <div class="w-full sm:w-72">
            <label for="moduleSearch" class="sr-only">Buscar módulo</label>
            <input id="moduleSearch" type="search" placeholder="Buscar módulo…" class="w-full rounded-md border border-multilab-gray dark:border-multilab-darkblue
                       bg-white dark:bg-multilab-dark px-3 py-2 text-sm
                       text-multilab-dark dark:text-multilab-gray placeholder-gray-400
                       focus:outline-none focus:ring-2 focus:ring-multilab-blue focus:border-multilab-blue" />
        </div>
    </div>

    {{-- EVITA WARNINGS SI NO EXISTE $modules --}}
    @php
        $modules = $modules ?? [];
    @endphp

    {{-- GRID DE MÓDULOS --}}
    @if(!empty($modules))
        <div id="modulesGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($modules as $module)
                <a href="{{ route($module['route']) }}" class="module-card group flex flex-col p-5 rounded-2xl bg-white dark:bg-multilab-dark
                                   border border-multilab-gray dark:border-multilab-darkblue shadow-card
                                   hover:shadow-soft transition-transform duration-200 hover:-translate-y-0.5
                                   focus:outline-none focus-visible:ring-2 focus-visible:ring-multilab-blue"
                    data-title="{{ Str::lower($module['title']) }}" data-desc="{{ Str::lower($module['description'] ?? '') }}">
                    {{-- Icono + Badge --}}
                    <div class="flex items-start justify-between mb-3">
                        <div class="text-multilab-blue dark:text-multilab-darkblue shrink-0">
                            {!! $module['icon'] ?? '<i class="fa-solid fa-circle text-2xl"></i>' !!}
                        </div>
                        @if(!empty($module['badge']))
                            <span class="ml-3 inline-flex items-center rounded-full bg-multilab-light dark:bg-multilab-darkblue/40
                                                   px-2.5 py-0.5 text-xs font-medium text-multilab-dark dark:text-multilab-gray">
                                {{ $module['badge'] }}
                            </span>
                        @endif
                    </div>

                    {{-- Título y descripción --}}
                    <h3 class="text-base font-semibold text-multilab-dark dark:text-multilab-gray mb-1">
                        {{ $module['title'] }}
                    </h3>

                    @if(!empty($module['description']))
                        <p class="text-sm text-multilab-darkblue dark:text-multilab-gray line-clamp-3">
                            {{ $module['description'] }}
                        </p>
                    @endif

                    {{-- Botón Ir al módulo --}}
                    <div class="mt-4 flex items-center text-sm font-medium text-multilab-blue dark:text-multilab-darkblue">
                        Ir al módulo
                        <svg class="ml-1.5 h-4 w-4 transition-transform duration-200 group-hover:translate-x-0.5"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        {{-- ALERTA SI NO HAY MÓDULOS --}}
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50
                       dark:bg-multilab-dark dark:text-red-400" role="alert">
            <svg class="shrink-0 inline w-4 h-4 mr-3 mt-[2px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="font-medium">No tienes módulos disponibles.</span>
            <p>Por favor, contacta con el administrador para obtener acceso.</p>
        </div>
    @endif

    {{-- SCRIPT FILTRO RÁPIDO --}}
    @push('scripts')
        <script>
            (function quickFilter() {
                const input = document.getElementById('moduleSearch');
                const cards = Array.from(document.querySelectorAll('.module-card'));
                if (!input || !cards.length) return;

                input.addEventListener('input', () => {
                    const q = input.value.trim().toLowerCase();
                    cards.forEach(card => {
                        const title = card.getAttribute('data-title') || '';
                        const desc = card.getAttribute('data-desc') || '';
                        const match = !q || title.includes(q) || desc.includes(q);
                        card.classList.toggle('hidden', !match);
                    });
                });
            })();
        </script>
    @endpush
</x-layouts.dashboard>
