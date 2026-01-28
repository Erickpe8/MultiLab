<nav x-cloak
    x-data="{
        userManagementOpen: {{ json_encode(request()->routeIs('user-management.*')) }},
        inventoryMaterialsOpen: {{ json_encode(request()->routeIs('filament.dashboard.resources.materials.*') || request()->routeIs('filament.dashboard.resources.material-catalogs.*')) }},
        inventoryComputersOpen: {{ json_encode(request()->routeIs('filament.dashboard.resources.computers.*')) }},
        loansOpen: {{ json_encode(request()->routeIs('filament.dashboard.resources.loans.*')) }},
        classroomOpen: {{ json_encode(request()->routeIs('filament.dashboard.resources.classroom-loans.*')) }},
        accountOpen: false,
    }"
     class="fixed inset-y-0 left-0 z-40 w-64
        bg-[var(--card)] text-[var(--text)] border-r border-[var(--border)]
        transform transition-transform duration-200 -translate-x-full lg:translate-x-0 lg:min-h-screen lg:shadow-none lg:top-0 lg:overflow-y-auto
        backdrop-blur-sm"
     x-bind:class="{ 'translate-x-0': sidebarOpen }">
    <!-- Header / Logo + Theme toggle -->
    <div
        class="h-16 px-4 flex items-center justify-between border-b border-[var(--border)]
                bg-gradient-to-r from-[var(--primary)]/5 to-transparent">
        <a href="{{ url('/dashboard') }}" class="inline-flex items-center gap-2 group">
            <img src="{{ asset('images/FESC-30.png') }}" alt="Logo FESC"
                 class="h-8 w-auto transition-transform duration-300 group-hover:scale-105" />
        </a>
        {{-- Switch de tema --}}
        <x-theme-toggle id="theme-toggle-side" size="md" />

        @if (filament()->auth()->check())
            @livewire(Filament\Livewire\DatabaseNotifications::class, [
            'lazy' => false,
            ])
        @endif
    </div>

    <!-- Usuario -->
    <div class="px-4 py-4 border-b border-[var(--border)]">
        @php
            $user = Auth::user();
        @endphp

        @if ($user)
            <div class="p-3 rounded-lg bg-gradient-to-br from-[var(--primary)]/10 to-[var(--accent)]/5
                        border border-[var(--border)] text-center
                        hover:from-[var(--primary)]/15 hover:to-[var(--accent)]/10 transition-all duration-300">
                <p class="font-semibold text-sm whitespace-normal break-words text-[var(--text)]">
                    {{ $user->name }}
                </p>
                <p class="text-xs text-[var(--accent)] font-medium mt-1 leading-snug break-words">
                    {{ $user->display_role_label }}
                </p>
            </div>
        @else
            <div class="p-3 rounded-lg border border-dashed border-[var(--border)] bg-[var(--bg)] text-center text-sm text-[var(--text-muted)]">
                <p class="font-semibold">Acceso restringido</p>
                <p class="mt-1 text-[0.75rem]">Inicia sesión para cargar el menú completo.</p>
            </div>
        @endif
    </div>

    <!-- Navegación -->
    @if (!$user)
        <div class="flex-1 overflow-y-auto sidebar-scrollbar-hidden px-3 py-4 space-y-3">
            <div class="rounded-3xl border border-[var(--border)] bg-[var(--card)] p-4 text-sm text-[var(--text-muted)]">
                <p class="font-semibold text-[var(--text)]">Manual público</p>
                <p class="mt-2">Solo los usuarios autenticados pueden navegar en el dashboard.</p>
            </div>
        </div>
    @else
        <div class="flex-1 overflow-y-auto sidebar-scrollbar-hidden px-3 py-4 space-y-3">
        {{-- Principal --}}
        <div>
            <x-sidebar.section-label label="Principal" />

            <button
                onclick="window.location.href='{{ url('/dashboard') }}'"
                class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                    transition-all duration-200 group
                    {{ request()->is('dashboard')
    ? 'bg-gradient-to-r from-[var(--accent)] to-[var(--primary)] text-white shadow-lg'
    : 'hover:bg-[var(--border)]/20 text-[var(--text)]' }}">
                <div class="flex items-center gap-3">
                    <x-ui.icon name="inicio" size="lg"
                               class="transition-transform duration-200 group-hover:scale-110 {{ request()->is('dashboard') ? 'text-white' : 'text-[var(--text)]' }}" />
                    <span class="{{ request()->is('dashboard') ? 'text-white' : '' }}">Dashboard</span>
                </div>
            </button>
        </div>

        {{-- ADMINISTRACIÓN --}}
        @if($user->hasRole('superadmin'))
            <div class="pt-2">
                <x-sidebar.section-label label="Administración" />

                <div class="relative">
                    <button
                        @click="userManagementOpen = !userManagementOpen"
                        class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                            transition-all duration-200 group
                            {{ request()->routeIs('user-management.*')
        ? 'bg-gradient-to-r from-[var(--accent)] to-[var(--primary)] text-white shadow-lg'
        : 'hover:bg-[var(--border)]/20 text-[var(--text)]' }}">
                        <div class="flex items-center gap-3">
                            <x-ui.icon name="usuarios" size="lg"
                                       class="transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('user-management.*') ? 'text-white' : 'text-[var(--text)]' }}" />
                            <span class="{{ request()->routeIs('user-management.*') ? 'text-white' : '' }}">Control de Usuarios</span>
                        </div>
                        <x-ui.icon name="expandir" size="sm"
                                   class="transition-transform duration-200"
                                   x-bind:class="{ 'rotate-180': userManagementOpen }" />
                    </button>

                    <div x-show="userManagementOpen"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-1"
                         class="mt-2 ml-3 space-y-1 border-l-2 border-[var(--border)] pl-3">

                        @php
                            $pendingCount = \App\Models\User::pending()->count();
                            $userManagementView = request()->get('view', 'active');
                            $pendingIsActive = request()->routeIs('user-management.index') && $userManagementView === 'pending';
                            $pendingStateClasses = $pendingIsActive
                                ? 'bg-[var(--primary)]/10 text-[var(--primary)] font-medium'
                                : 'hover:bg-[var(--border)]/10 text-[var(--text)]/70';
                        @endphp

                        <x-sidebar.sub-item
                            :href="route('user-management.index', ['view' => 'active'])"
                            icon="usuarios"
                            label="Usuarios Activos"
                            :active="request()->routeIs('user-management.index') && $userManagementView === 'active'" />

                        <a
                            href="{{ route('user-management.index', ['view' => 'pending']) }}"
                            class="flex flex-col items-start gap-2 px-3 py-2 rounded-lg text-sm transition-all duration-200 group {{ $pendingStateClasses }}"
                            @if($pendingIsActive) aria-current="page" @endif
                        >
                            <span class="flex items-center gap-2">
                                <x-ui.icon name="heroicon-o-clock" size="sm" class="text-current" />
                                <span>Solicitudes Pendientes</span>
                            </span>

                            @if($pendingCount > 0)
                                <div class="w-full rounded-lg border border-yellow-500/20 bg-yellow-500/10">
                                    <div class="flex items-center gap-2 px-3 py-2 text-xs font-medium text-yellow-600 dark:text-yellow-400">
                                        <span class="w-2 h-2 rounded-full bg-yellow-500 animate-pulse"></span>
                                        <span>
                                            {{ $pendingCount }} solicitud{{ $pendingCount > 1 ? 'es' : '' }} pendiente{{ $pendingCount > 1 ? 's' : '' }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </a>

                        <x-sidebar.sub-item
                            :href="route('user-management.index', ['view' => 'blocked'])"
                            icon="heroicon-o-user-minus"
                            label="Usuarios Bloqueados"
                            :active="request()->routeIs('user-management.index') && $userManagementView === 'blocked'" />
                    </div>
                </div>
            </div>
        @endif

        {{-- MÉTRICAS --}}
        @if($user->hasRole('superadmin'))
            <div class="pt-2">
                <x-sidebar.section-label label="Métricas" />

                <x-sidebar.sub-item
                    :href="route('reports.index')"
                    icon="heroicon-o-chart-bar"
                    label="Métricas e Informes"
                    :active="request()->is('reports*')" />
            </div>
        @endif

        {{-- INVENTARIO --}}
        @if($user->hasAnyRole(['superadmin', 'aux_admin', 'docente', 'estudiante']))
            <div class="pt-2">
                <x-sidebar.section-label label="Inventario" />

                @if($user->hasAnyRole(['superadmin', 'aux_admin', 'docente', 'estudiante']))
                    <div class="relative">
                        <button
                            @click="inventoryMaterialsOpen = !inventoryMaterialsOpen"
                            class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                                transition-all duration-200 group
                                {{ (request()->routeIs('filament.dashboard.resources.materials.*') || request()->routeIs('filament.dashboard.resources.material-catalogs.*'))
        ? 'bg-gradient-to-r from-[var(--accent)] to-[var(--primary)] text-white shadow-lg'
        : 'hover:bg-[var(--border)]/20 text-[var(--text)]' }}">
                            <div class="flex items-center gap-3">
                                <x-ui.icon name="heroicon-o-archive-box" size="lg"
                                           class="transition-transform duration-200 group-hover:scale-110 {{ (request()->routeIs('filament.dashboard.resources.materials.*') || request()->routeIs('filament.dashboard.resources.material-catalogs.*')) ? 'text-white' : 'text-[var(--text)]' }}" />
                                <span class="{{ (request()->routeIs('filament.dashboard.resources.materials.*') || request()->routeIs('filament.dashboard.resources.material-catalogs.*')) ? 'text-white' : '' }}">Materiales</span>
                            </div>
                            <x-ui.icon name="expandir" size="sm"
                                       class="transition-transform duration-200"
                                       x-bind:class="{ 'rotate-180': inventoryMaterialsOpen }" />
                        </button>

                        <div x-show="inventoryMaterialsOpen"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-1"
                             class="mt-2 ml-3 space-y-1 border-l-2 border-[var(--border)] pl-3">

                            @if($user->hasAnyRole(['docente', 'estudiante']))
                                <x-sidebar.sub-item
                                    :href="route('filament.dashboard.resources.material-catalogs.index')"
                                    icon="heroicon-o-academic-cap"
                                    label="Catálogo de Materiales"
                                    :active="request()->routeIs('filament.dashboard.resources.material-catalogs.*')" />
                            @endif

                            @if($user->hasAnyRole(['superadmin', 'aux_admin']))
                                <x-sidebar.sub-item
                                    :href="route('filament.dashboard.resources.materials.index')"
                                    icon="heroicon-o-building-library"
                                    label="Inventario físico"
                                    :active="request()->routeIs('filament.dashboard.resources.materials.*')" />
                            @endif
                        </div>
                    </div>
                @endif

                @if($user->hasAnyRole(['superadmin', 'aux_admin']))
                    <div class="relative mt-2">
                        <button
                            @click="inventoryComputersOpen = !inventoryComputersOpen"
                            class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                                transition-all duration-200 group
                                {{ request()->routeIs('filament.dashboard.resources.computers.*')
        ? 'bg-gradient-to-r from-[var(--accent)] to-[var(--primary)] text-white shadow-lg'
        : 'hover:bg-[var(--border)]/20 text-[var(--text)]' }}">
                            <div class="flex items-center gap-3">
                                <x-ui.icon name="heroicon-o-computer-desktop" size="lg"
                                           class="transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('filament.dashboard.resources.computers.*') ? 'text-white' : 'text-[var(--text)]' }}" />
                                <span class="{{ request()->routeIs('filament.dashboard.resources.computers.*') ? 'text-white' : '' }}">Computadores</span>
                            </div>
                            <x-ui.icon name="expandir" size="sm"
                                       class="transition-transform duration-200"
                                       x-bind:class="{ 'rotate-180': inventoryComputersOpen }" />
                        </button>

                        <div x-show="inventoryComputersOpen"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-1"
                             class="mt-2 ml-3 border-l-2 border-[var(--border)] pl-3">
                            <x-sidebar.sub-item
                                :href="route('filament.dashboard.resources.computers.index')"
                                icon="heroicon-o-computer-desktop"
                                label="Inventario de computadoras"
                                :active="request()->routeIs('filament.dashboard.resources.computers.*')" />
                        </div>
                    </div>
                @endif
            </div>
        @endif

        {{-- PRÉSTAMOS --}}
        @if($user->hasAnyRole(['superadmin', 'aux_admin', 'docente', 'estudiante']))
            <div class="pt-2">
                <x-sidebar.section-label label="Préstamos" />

                <div class="relative">
                    <button
                        @click="loansOpen = !loansOpen"
                        class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                            transition-all duration-200 group
                            {{ request()->routeIs('filament.dashboard.resources.loans.*')
        ? 'bg-gradient-to-r from-[var(--accent)] to-[var(--primary)] text-white shadow-lg'
        : 'hover:bg-[var(--border)]/20 text-[var(--text)]' }}">
                        <div class="flex items-center gap-3">
                            <x-ui.icon name="prestamos" size="lg"
                                       class="transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('filament.dashboard.resources.loans.*') ? 'text-white' : 'text-[var(--text)]' }}" />
                            <span class="{{ request()->routeIs('filament.dashboard.resources.loans.*') ? 'text-white' : '' }}">Préstamos</span>
                        </div>
                        <x-ui.icon name="expandir" size="sm"
                                   class="transition-transform duration-200"
                                   x-bind:class="{ 'rotate-180': loansOpen }" />
                    </button>

                    <div x-show="loansOpen"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-1"
                         class="mt-2 ml-3 border-l-2 border-[var(--border)] pl-3">
                        <x-sidebar.sub-item
                            :href="route('filament.dashboard.resources.loans.index')"
                            icon="heroicon-o-credit-card"
                            label="Listado de préstamos"
                            :active="request()->routeIs('filament.dashboard.resources.loans.*')" />
                    </div>
                </div>
            </div>
        @endif

        {{-- AULA --}}
        @if($user->hasAnyRole(['superadmin', 'aux_admin', 'docente']))
            <div class="pt-2">
                <x-sidebar.section-label label="Aula" />

                <div class="relative">
                    <button
                        @click="classroomOpen = !classroomOpen"
                        class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                            transition-all duration-200 group
                            {{ request()->routeIs('filament.dashboard.resources.classroom-loans.*')
        ? 'bg-gradient-to-r from-[var(--accent)] to-[var(--primary)] text-white shadow-lg'
        : 'hover:bg-[var(--border)]/20 text-[var(--text)]' }}">
                        <div class="flex items-center gap-3">
                            <x-ui.icon name="aula" size="lg"
                                       class="transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('filament.dashboard.resources.classroom-loans.*') ? 'text-white' : 'text-[var(--text)]' }}" />
                            <span class="{{ request()->routeIs('filament.dashboard.resources.classroom-loans.*') ? 'text-white' : '' }}">Aula B202</span>
                        </div>
                        <x-ui.icon name="expandir" size="sm"
                                   class="transition-transform duration-200"
                                   x-bind:class="{ 'rotate-180': classroomOpen }" />
                    </button>

                    <div x-show="classroomOpen"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-1"
                         class="mt-2 ml-3 border-l-2 border-[var(--border)] pl-3">
                        <x-sidebar.sub-item
                            :href="route('filament.dashboard.resources.classroom-loans.index')"
                            icon="heroicon-o-building-office"
                            label="Reservas del aula"
                            :active="request()->routeIs('filament.dashboard.resources.classroom-loans.*')" />
                    </div>
                </div>
            </div>
        @endif

        {{-- CUENTA --}}
        <div class="pt-2">
            <x-sidebar.section-label label="Cuenta" />

            <div class="relative">
                <button
                    @click="accountOpen = !accountOpen"
                    class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                        transition-all duration-200 group
                        {{ request()->routeIs('profile.*') ? 'bg-gradient-to-r from-[var(--accent)] to-[var(--primary)] text-white shadow-lg' : 'hover:bg-[var(--border)]/20 text-[var(--text)]' }}">
                    <div class="flex items-center gap-3">
                        <x-ui.icon name="perfil" size="lg"
                                   class="transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('profile.*') ? 'text-white' : 'text-[var(--text)]' }}" />
                        <span class="{{ request()->routeIs('profile.*') ? 'text-white' : '' }}">Cuenta</span>
                    </div>
                    <x-ui.icon name="expandir" size="sm"
                               class="transition-transform duration-200"
                               x-bind:class="{ 'rotate-180': accountOpen }" />
                </button>

                <div x-show="accountOpen"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-1"
                     class="mt-2 ml-3 border-l-2 border-[var(--border)] pl-3 space-y-1">

                    <x-sidebar.sub-item
                        :href="route('profile.edit')"
                        icon="perfil"
                        label="Perfil"
                        :active="request()->routeIs('profile.*')" />

                    <form method="POST" action="{{ route('logout') }}"
                          onsubmit="localStorage.setItem('theme', 'light'); document.documentElement.dataset.theme = 'light'; document.documentElement.classList.remove('dark');">
                        @csrf
                        <button type="submit" class="w-full text-left">
                            <div class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-[var(--text)] hover:bg-[var(--border)]/10 transition-all duration-200">
                                <x-ui.icon name="logout" size="sm" class="text-current" />
                                <span>Cerrar sesión</span>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif
</nav>
