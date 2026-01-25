{{-- resources/views/components/dashboard/sidebar.blade.php --}}
@props([
    'active'  => null,
    'accent'  => 'blue',
    'modules' => [],
])

@php
    use Illuminate\Support\Facades\Route;

    // Acento activo (rojo Ingeniería de Software por defecto o una variante alternativa)
    // Ítems base
    $navItems = [
        ['route' => 'dashboard',            'icon' => 'fas fa-home',        'label' => 'Panel principal',        'active_key' => 'dashboard'],
        ['route' => 'admin.users.index',    'icon' => 'fas fa-users',       'label' => 'Gestionar Usuarios',     'active_key' => 'admin.users.index'],
        ['route' => 'admin.users.create',   'icon' => 'fas fa-user-plus',   'label' => 'Crear Nuevos Usuarios',  'active_key' => 'admin.users.create'],
        ['route' => 'documents.index',      'icon' => 'fas fa-id-card',     'label' => 'Tipos de Documento',     'active_key' => 'documents.index'],
        ['route' => 'genders.index',        'icon' => 'fas fa-venus-mars',  'label' => 'Géneros',                'active_key' => 'genders.index'],
        ['route' => 'institutions.index',   'icon' => 'fas fa-university',  'label' => 'Instituciones',          'active_key' => 'institutions.index'],
        ['route' => 'programs.index',       'icon' => 'fas fa-book',        'label' => 'Programas Académicos',   'active_key' => 'programs.index'],
    ];
@endphp

<aside class="h-full flex flex-col bg-[var(--surface)] text-[var(--text)] border-r border-[var(--border)] shadow-xl">

    {{-- Encabezado con logo y botón cerrar (móvil) --}}
    <div class="flex items-center justify-between px-4 py-4 border-b border-[var(--border)]">
        <a href="{{ url('/') }}" class="flex items-center space-x-2">
            <x-brand.logo variant="icon" class="h-8 w-auto" />
            <span class="font-semibold text-[var(--text)]">Ingeniería de Software</span>
        </a>
            <button
                type="button"
                data-sidebar-close
                class="lg:hidden inline-flex items-center justify-center p-2 rounded-md
                       text-[var(--text)] hover:bg-[var(--border)]/20"
                aria-label="Cerrar sidebar"
            >
                <x-ui.icon name="cerrar" size="lg" />
            </button>
    </div>

    {{-- Usuario --}}
    <div class="px-4 py-4 border-b border-[var(--border)] flex items-center space-x-3">
        <img src="{{ asset('images/avatar-default.png') }}" alt="Avatar" class="h-10 w-10 rounded-full border border-[var(--border)]">
        <div>
            <p class="text-xs uppercase tracking-[0.4em] text-[var(--text-secondary)]">Bienvenido,</p>
            <p class="text-sm font-semibold text-[var(--text)]">{{ Auth::user()->name ?? 'Usuario' }}</p>
            <x-ui.badge variant="muted" class="mt-1 normal-case text-[10px]">
                {{ Auth::user()->display_role_label ?? 'Usuario' }}
            </x-ui.badge>
        </div>
    </div>

    {{-- Navegación --}}
    <nav class="flex-1 overflow-y-auto py-4">
        <ul class="space-y-1">
            @foreach ($navItems as $item)
                @php
                    $isActive = $active === $item['active_key'];
                    $href = Route::has($item['route']) ? route($item['route']) : '#';
                    $titleIfMissing = Route::has($item['route']) ? '' : ' (pendiente de crear ruta)';
                @endphp
                <li>
                    <x-ui.button
                        variant="{{ $isActive ? 'primary' : 'ghost' }}"
                        href="{{ $href }}"
                        class="w-full justify-start gap-3 px-3 py-2.5 text-sm font-medium"
                        @if($titleIfMissing) title="Ruta {{ $item['route'] }}{{ $titleIfMissing }}" @endif
                    >
                        <i class="{{ $item['icon'] }} text-lg"></i>
                        <span>{{ $item['label'] }}</span>
                    </x-ui.button>
                </li>
            @endforeach

            {{-- Módulos dinámicos (opcionales) --}}
            @if (!empty($modules))
                <li class="mt-4 px-3 text-xs font-semibold uppercase tracking-wider text-multilab-dark dark:text-multilab-gray">
                    Módulos
                </li>
                @foreach ($modules as $module)
                    @php
                        // Soporta ['route' => 'nombre.ruta'] o ['url' => '/custom']
                        $moduleHref = !empty($module['route']) && Route::has($module['route'])
                                      ? route($module['route'], $module['params'] ?? [])
                                      : ($module['url'] ?? '#');
                    @endphp
                    <li>
                        <x-ui.button variant="ghost" href="{{ $moduleHref }}"
                                     class="w-full justify-start gap-3 px-3 py-2.5 text-sm font-medium">
                            @if (!empty($module['icon']))
                                {!! $module['icon'] !!}
                            @else
                                <i class="fas fa-cube text-lg"></i>
                            @endif
                            <span>{{ $module['title'] ?? $module['name'] ?? 'Módulo' }}</span>
                        </x-ui.button>
                    </li>
                @endforeach
            @endif
        </ul>
    </nav>

    {{-- Barra inferior --}}
    <div class="px-4 py-3 border-t border-[var(--border)] flex items-center justify-around">
        <x-ui.button variant="ghost" href="{{ Route::has('policies.index') ? route('policies.index') : '#' }}"
                     class="p-2 text-[var(--text)]" title="{{ Route::has('policies.index') ? 'Políticas' : 'Políticas (ruta pendiente)' }}">
            <i class="fas fa-file-contract"></i>
        </x-ui.button>
        <x-ui.button variant="ghost" href="{{ Route::has('privacy.index') ? route('privacy.index') : '#' }}"
                     class="p-2 text-[var(--text)]" title="{{ Route::has('privacy.index') ? 'Privacidad' : 'Privacidad (ruta pendiente)' }}">
            <i class="fas fa-user-secret"></i>
        </x-ui.button>
        <form method="POST" action="{{ Route::has('logout') ? route('logout') : '#' }}">
            @csrf
            <x-ui.button variant="ghost" type="submit"
                         class="p-2 text-[var(--text)]" title="{{ Route::has('logout') ? 'Cerrar sesión' : 'Logout (ruta pendiente)' }}">
                <i class="fas fa-sign-out-alt"></i>
            </x-ui.button>
        </form>
    </div>
</aside>
