<nav x-cloak x-data="{ userManagementOpen: false }"
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
                'lazy' => false, // Temporalmente deshabilitado para depuración
            ])
        @endif
    </div>

    <!-- Usuario -->
    <div class="px-4 py-4 border-b border-[var(--border)]">
        @php
            $user = Auth::user();
        @endphp

        <div class="p-3 rounded-lg bg-gradient-to-br from-[var(--primary)]/10 to-[var(--accent)]/5
                    border border-[var(--border)] text-center
                    hover:from-[var(--primary)]/15 hover:to-[var(--accent)]/10 transition-all duration-300">
            {{-- Nombre: una sola línea con corte si toca --}}
            <p class="font-semibold text-sm truncate text-[var(--text)]">
                {{ $user->name }}
            </p>

            {{-- Cargo: SIN truncate, que pueda bajar a segunda línea --}}
            <p class="text-xs text-[var(--accent)] font-medium mt-1 leading-snug break-words">
                {{ $user->display_role_label }}
            </p>
        </div>
    </div>

    <!-- Navegación -->
    <div class="flex-1 overflow-y-auto px-3 py-4 space-y-3">
        <div>
            <p class="uppercase text-[10px] tracking-[0.15em] text-[var(--text)]/50 px-3 mb-2 font-bold">
                Principal
            </p>

            <button
                onclick="window.location.href='{{ url('/dashboard') }}'"
                class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                    transition-all duration-200 group
                    {{ request()->is('dashboard')
    ? 'bg-gradient-to-r from-[var(--accent)] to-[var(--primary)] text-white shadow-lg'
    : 'hover:bg-[var(--border)]/20 text-[var(--text)]' }}">
                <div class="flex items-center gap-3">
                    {{-- Home icon --}}
                    <x-ui.icon name="inicio" size="lg"
                               class="transition-transform duration-200 group-hover:scale-110 {{ request()->is('dashboard') ? 'text-white' : 'text-[var(--text)]' }}" />
                    <span class="{{ request()->is('dashboard') ? 'text-white' : '' }}">Dashboard</span>
                </div>
            </button>
        </div>

        {{-- ==========================================
            CONTROL DE USUARIOS (Solo SuperAdmin)
        ========================================== --}}
        @if(Auth::user()->hasRole('superadmin'))
            <div class="pt-2">
                <p class="uppercase text-[10px] tracking-[0.15em] text-[var(--text)]/50 px-3 mb-2 font-bold">
                    Administración
                </p>

                {{-- Dropdown de Control de Usuarios --}}
                <div class="relative">
                    <button
                        @click="userManagementOpen = !userManagementOpen"
                        class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                            transition-all duration-200 group
                            {{ request()->routeIs('user-management.*')
        ? 'bg-gradient-to-r from-[var(--accent)] to-[var(--primary)] text-white shadow-lg'
        : 'hover:bg-[var(--border)]/20 text-[var(--text)]' }}">
                        <div class="flex items-center gap-3">
                            {{-- Users icon --}}
                            <x-ui.icon name="usuarios" size="lg"
                                       class="transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('user-management.*') ? 'text-white' : 'text-[var(--text)]' }}" />
                            <span class="{{ request()->routeIs('user-management.*') ? 'text-white' : '' }}">Control de Usuarios</span>
                        </div>
                        {{-- Chevron --}}
                        <x-ui.icon name="expandir" size="sm"
                                   class="transition-transform duration-200"
                                   x-bind:class="{ 'rotate-180': userManagementOpen }" />

                    </button>

                    {{-- Submenu --}}
                    <div x-show="userManagementOpen"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-1"
                         class="mt-2 ml-3 space-y-1 border-l-2 border-[var(--border)] pl-3">

                        {{-- Panel Principal --}}
                        <a href="{{ route('user-management.index') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm
                                transition-all duration-200 group
                                {{ request()->routeIs('user-management.index')
        ? 'bg-[var(--primary)]/10 text-[var(--primary)] font-medium'
        : 'hover:bg-[var(--border)]/10 text-[var(--text)]/70' }}">
                            <x-ui.icon name="dashboard" size="sm" class="text-current" />
                            <span>Panel de Control</span>
                        </a>

                        {{-- Badge de Pendientes --}}
                        @php
                            $pendingCount = \App\Models\User::where('is_active', false)->count();
                        @endphp
                        @if($pendingCount > 0)
                            <div class="px-3 py-2 rounded-lg bg-yellow-500/10 border border-yellow-500/20">
                                <div class="flex items-center gap-2 text-xs">
                                    <span class="w-2 h-2 rounded-full bg-yellow-500 animate-pulse"></span>
                                    <span class="text-yellow-600 dark:text-yellow-400 font-medium">
                                        {{ $pendingCount }} solicitud{{ $pendingCount > 1 ? 'es' : '' }} pendiente{{ $pendingCount > 1 ? 's' : '' }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Módulos -->
        <div class="pt-2">
            <p class="uppercase text-[10px] tracking-[0.15em] text-[var(--text)]/50 px-3 mb-2 font-bold">
                Módulos
            </p>

            @if (auth()->user()?->hasAnyRole(['estudiante', 'docente']))
                <button
                    onclick="window.location.href=`{{ route('filament.dashboard.resources.material-catalogs.index') }}`"
                    class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                        transition-all duration-200 group
                        {{ request()->routeIs('filament.dashboard.resources.material-catalogs.*')
        ? 'bg-gradient-to-r from-[var(--accent)] to-[var(--primary)] text-white shadow-lg'
        : 'hover:bg-[var(--border)]/20 text-[var(--text)]' }}">
                    <div class="flex items-center gap-3">
                        <x-ui.icon name="heroicon-o-academic-cap" size="lg"
                                   class="transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('filament.dashboard.resources.material-catalogs.*') ? 'text-white' : 'text-[var(--text)]' }}" />
                        <span class="{{ request()->routeIs('filament.dashboard.resources.material-catalogs.*') ? 'text-white' : '' }}">Catálogo de Materiales</span>
                    </div>
                </button>
            @endif

            {{-- Links para Admin y SuperAdmin --}}
            @if (auth()->user()?->hasAnyRole(['superadmin', 'aux_admin']))
                <button
                    onclick="window.location.href=`{{ route('filament.dashboard.resources.materials.index') }}`"
                    class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                    transition-all duration-200 group
                    {{ request()->routeIs('filament.dashboard.resources.materials.*')
    ? 'bg-gradient-to-r from-[var(--accent)] to-[var(--primary)] text-white shadow-lg'
    : 'hover:bg-[var(--border)]/20 text-[var(--text)]' }}">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 transition-transform duration-200 group-hover:scale-110 stroke-current {{ request()->routeIs('filament.dashboard.resources.materials.*') ? 'text-white' : 'text-[var(--text)]' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke-width="1.5" d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zM6 10v6a2 2 0 002 2h8a2 2 0 002-2v-6a2 2 0 00-2-2H8a2 2 0 00-2 2zM12 2v3" />
                        </svg>
                        <span class="{{ request()->routeIs('filament.dashboard.resources.materials.*') ? 'text-white' : '' }}">Materiales</span>
                    </div>
                </button>
            @endif

            <button
                onclick="window.location.href=`{{ route('filament.dashboard.resources.loans.index') }}`"
                class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                    transition-all duration-200 group
                    {{ request()->routeIs('filament.dashboard.resources.loans.*')
    ? 'bg-gradient-to-r from-[var(--accent)] to-[var(--primary)] text-white shadow-lg'
    : 'hover:bg-[var(--border)]/20 text-[var(--text)]' }}">
                <div class="flex items-center gap-3">
                    {{-- Prestamo icon --}}
                    <x-ui.icon name="prestamos" size="lg"
                               class="transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('filament.dashboard.resources.loans.*') ? 'text-white' : 'text-[var(--text)]' }}" />
                    <span class="{{ request()->routeIs('filament.dashboard.resources.loans.*') ? 'text-white' : '' }}">Préstamos</span>
                </div>
            </button>

            @if (auth()->user()?->hasAnyRole(['superadmin', 'aux_admin']))
                <button
                    onclick="window.location.href=`{{ route('filament.dashboard.resources.computers.index') }}`"
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
                </button>
            @endif

            @if (auth()->user()?->hasAnyRole(['docente', 'superadmin', 'aux_admin']))
                <button
                    onclick="window.location.href=`{{ route('filament.dashboard.resources.classroom-loans.index') }}`"
                    class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                        transition-all duration-200 group
                        {{ request()->routeIs('filament.dashboard.resources.classroom-loans.*')
        ? 'bg-gradient-to-r from-[var(--accent)] to-[var(--primary)] text-white shadow-lg'
        : 'hover:bg-[var(--border)]/20 text-[var(--text)]' }}">
                    <div class="flex items-center gap-3">
                        {{-- Aula B201 icon --}}
                        <x-ui.icon name="aula" size="lg"
                                   class="transition-transform duration-200 group-hover:scale-110 {{ request()->routeIs('filament.dashboard.resources.classroom-loans.*') ? 'text-white' : 'text-[var(--text)]' }}" />
                        <span class="{{ request()->routeIs('filament.dashboard.resources.classroom-loans.*') ? 'text-white' : '' }}">Aula B201</span>
                    </div>
                </button>
            @endif
        </div>
    </div>

    <!-- Footer: Cuenta / Cerrar sesión -->
    <div class="border-t border-[var(--border)] p-3
            bg-gradient-to-t from-[var(--primary)]/5 to-transparent">

        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button class="w-full inline-flex items-center justify-between gap-2 px-3 py-2.5 rounded-lg
                    text-sm font-medium transition-all duration-200
                    bg-gradient-to-r from-[var(--border)]/15 to-[var(--border)]/5
                    hover:from-[var(--primary)]/20 hover:to-[var(--accent)]/10
                    border border-[var(--border)]/30 hover:border-[var(--accent)]/30
                    group">

                    <span class="truncate text-[var(--text)]">Cuenta</span>

                    <x-ui.icon name="expandir" size="sm"
                               class="text-[var(--accent)] transition-transform duration-200"
                               x-bind:class="{ 'rotate-180': open }" />
                </button>
            </x-slot>

            <x-slot name="content">

                <!-- PERFIL -->
                <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2 group
                    text-[var(--accent)]
                    hover:bg-transparent hover:text-[var(--accent)]">

                    <x-ui.icon name="perfil" size="sm"
                               class="text-[var(--accent)] transition-transform duration-200 group-hover:scale-110" />

                    <span>Perfil</span>
                </x-dropdown-link>

                <!-- CERRAR SESIÓN -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-dropdown-link :href="route('logout')"
                                     onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center gap-2 group
                        text-[var(--accent)]
                        hover:bg-transparent hover:text-[var(--accent)]
                        transition-all">

                        <x-ui.icon name="logout" size="sm"
                                   class="text-[var(--accent)] transition-transform duration-200 group-hover:translate-x-0.5" />

                        <span>Cerrar sesión</span>
                    </x-dropdown-link>
                </form>

            </x-slot>
        </x-dropdown>

    </div>

</nav>
