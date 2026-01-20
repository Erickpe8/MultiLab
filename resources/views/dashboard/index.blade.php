<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <p class="text-xs uppercase tracking-[0.3em] text-[var(--text-muted)]">Panel de Control</p>
            <h1 class="text-2xl font-semibold text-[var(--text)]">Panel de Control</h1>
        </div>
    </x-slot>

    @php
        $sections = [
            [
                'tag' => 'Administración',
                'title' => 'Control y vigilancia',
                'subtitle' => 'Supervisa accesos, permisos y solicitudes críticas.',
                'cards' => [
                    [
                        'title' => 'Usuarios Activos',
                        'description' => 'Usuarios con acceso vigente y roles asignados.',
                        'icon' => 'heroicon-o-user-group',
                        'route' => 'user-management.index',
                        'params' => ['view' => 'active'],
                        'badge' => 'Usuarios',
                        'cta' => 'Ver usuarios',
                    ],
                    [
                        'title' => 'Solicitudes Pendientes',
                        'description' => 'Registros nuevos en revisión por el equipo de vigilancia.',
                        'icon' => 'heroicon-o-clock',
                        'route' => 'user-management.pending',
                        'badge' => 'Flujos',
                        'cta' => 'Revisar pendientes',
                    ],
                    [
                        'title' => 'Usuarios Bloqueados',
                        'description' => 'Cuentas suspendidas temporalmente por control de seguridad.',
                        'icon' => 'heroicon-o-user-minus',
                        'route' => 'user-management.blocked',
                        'badge' => 'Seguridad',
                        'cta' => 'Ver bloqueos',
                    ],
                ],
            ],
            [
                'tag' => 'Módulos',
                'title' => 'Accesos clave',
                'subtitle' => 'Navega rápido a los módulos que gestionan préstamos y aulas.',
                'cards' => [
                    [
                        'title' => 'Préstamos',
                        'description' => 'Administra solicitudes, entregas y devoluciones de equipos.',
                        'icon' => 'heroicon-o-credit-card',
                        'route' => 'filament.dashboard.resources.loans.index',
                        'badge' => 'Operaciones',
                        'cta' => 'Abrir módulo',
                    ],
                    [
                        'title' => 'Aula B201',
                        'description' => 'Consulta ocupación, reservas y disponibilidad del laboratorio.',
                        'icon' => 'heroicon-o-building-office',
                        'route' => 'filament.dashboard.resources.classroom-loans.index',
                        'badge' => 'Espacios',
                        'cta' => 'Ver aula B201',
                    ],
                ],
            ],
        ];
    @endphp

    <div class="space-y-8">
        <section class="rounded-3xl border border-[var(--border)] bg-[var(--card)] p-6 shadow-sm backdrop-blur-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-[var(--text-muted)]">Bienvenido</p>
                    <h2 class="text-2xl font-semibold text-[var(--text)]">
                        ¡Hola, {{ auth()->user()->name }}!
                    </h2>
                    <p class="mt-1 text-sm text-[var(--text-muted)]">
                        Accede a las secciones principales desde este panel personalizado.
                    </p>
                </div>

                <div
                    class="inline-flex items-center gap-2 rounded-full border border-[var(--border)] bg-[var(--border)]/20 px-4 py-2 text-xs font-semibold uppercase tracking-[0.3em] text-[var(--accent)]">
                    <x-ui.icon name="heroicon-o-shield-check" size="sm" class="text-[var(--primary)]" />
                    Cuenta activa
                </div>
            </div>
        </section>

        @foreach ($sections as $section)
            <section class="space-y-4">
                <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.4em] text-[var(--text-muted)]">{{ $section['tag'] }}</p>
                        <h2 class="text-xl font-semibold text-[var(--text)]">{{ $section['title'] }}</h2>
                        @if (!empty($section['subtitle']))
                            <p class="text-sm text-[var(--text-muted)]">{{ $section['subtitle'] }}</p>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($section['cards'] as $card)
                        @php
                            $routeName = $card['route'] ?? null;
                            $routeParams = $card['params'] ?? [];
                            $hasRoute = $routeName ? \Illuminate\Support\Facades\Route::has($routeName) : false;
                            $href = $hasRoute ? route($routeName, $routeParams) : ($card['href'] ?? '#');
                        @endphp

                        <a href="{{ $href }}"
                            aria-disabled="{{ $hasRoute ? 'false' : 'true' }}"
                            class="group block min-h-full rounded-2xl border border-[var(--border)] bg-[var(--card)] px-5 py-6 text-[var(--text)] transition duration-200 hover:-translate-y-0.5 hover:shadow-lg {{ $hasRoute ? 'focus-visible:outline focus-visible:outline-2 focus-visible:outline-[var(--accent)]' : 'cursor-not-allowed opacity-70' }}">
                            <div class="flex items-center justify-between gap-4">
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-2xl border border-[var(--border)] bg-[var(--border)]/30 text-[var(--primary)]">
                                    {{-- OJO: aquí NO uses :name en Blade si no estás seguro; usa name con {{ }} --}}
                                    <x-ui.icon name="{{ $card['icon'] }}" size="lg" />
                                </div>

                                <span class="text-[0.65rem] font-semibold uppercase tracking-[0.4em] text-[var(--text-muted)]">
                                    {{ $card['badge'] ?? 'Acceso' }}
                                </span>
                            </div>

                            <h3 class="mt-6 text-lg font-semibold text-[var(--text)]">{{ $card['title'] }}</h3>

                            <p class="mt-2 text-sm leading-relaxed text-[var(--text-muted)]">
                                {{ $card['description'] }}
                            </p>

                            <div class="mt-4 flex flex-col gap-2">
                                <span
                                    class="inline-flex items-center gap-2 text-sm font-semibold text-[var(--accent)] {{ $hasRoute ? 'group-hover:underline' : '' }}">
                                    {{ $card['cta'] ?? 'Ir al módulo' }}
                                    <x-ui.icon name="siguiente" size="sm" class="text-[var(--accent)]" />
                                </span>
                                @unless ($hasRoute)
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold uppercase tracking-[0.35em] text-[var(--text-muted)]">
                                        <x-ui.icon name="advertencia" size="xs" class="text-yellow-500" />
                                        Próximamente
                                    </span>
                                @endunless
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>
</x-app-layout>
