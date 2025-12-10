{{-- resources/views/components/dashboard/sidebar.blade.php --}}
@props([
    'active'  => null,
    'accent'  => 'blue',
    'modules' => [],
])

@php
    use Illuminate\Support\Facades\Route;

    // Acento activo (azul Multilab por defecto o rojo opcional)
    $activeBgClass  = $accent === 'red' ? 'bg-red-600' : 'bg-multilab-blue';
    $activeTextClass = 'text-white';
    $linkBaseClass   = 'flex items-center px-3 py-2 rounded-lg transition-colors';
    $linkHoverClass  = 'hover:bg-multilab-light dark:hover:bg-multilab-darkblue/30';
    $linkTextClass   = 'text-multilab-dark dark:text-multilab-gray';

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

<aside class="h-full flex flex-col bg-white dark:bg-multilab-dark shadow-soft">

    {{-- Encabezado con logo y botón cerrar (móvil) --}}
    <div class="flex items-center justify-between px-4 py-4 border-b border-multilab-gray dark:border-multilab-darkblue">
        <a href="{{ url('/') }}" class="flex items-center space-x-2">
            <img src="{{ asset('images/logo-fesc.png') }}" alt="Logo FESC" class="h-8 w-auto">
            <span class="font-semibold text-multilab-blue dark:text-multilab-gray">Multilab</span>
        </a>
        <button
            type="button"
            data-sidebar-close
            class="lg:hidden inline-flex items-center justify-center p-2 rounded-md
                   text-multilab-dark dark:text-multilab-gray
                   hover:bg-multilab-light dark:hover:bg-multilab-darkblue/30"
            aria-label="Cerrar sidebar"
        >
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Usuario --}}
    <div class="px-4 py-4 border-b border-multilab-gray dark:border-multilab-darkblue flex items-center space-x-3">
        <img src="{{ asset('images/avatar-default.png') }}" alt="Avatar" class="h-10 w-10 rounded-full">
        <div>
            <p class="text-sm font-medium text-multilab-dark dark:text-multilab-gray">Bienvenido,</p>
            <p class="text-sm font-semibold text-multilab-blue dark:text-multilab-gray">{{ Auth::user()->name ?? 'Usuario' }}</p>
        </div>
    </div>

    {{-- Navegación --}}
    <nav class="flex-1 overflow-y-auto py-4">
        <ul class="space-y-1">
            @foreach ($navItems as $item)
                @php
                    $isActive = $active === $item['active_key'];
                    // Si la ruta NO existe, cae a '#'
                    $href = Route::has($item['route']) ? route($item['route']) : '#';
                    $titleIfMissing = Route::has($item['route']) ? '' : ' (pendiente de crear ruta)';
                @endphp
                <li>
                    <a
                        href="{{ $href }}"
                        @if($titleIfMissing) title="Ruta {{ $item['route'] }}{{ $titleIfMissing }}" @endif
                        class="{{ $linkBaseClass }} {{ $linkHoverClass }} {{ $isActive ? $activeBgClass.' '.$activeTextClass.' rounded-full' : $linkTextClass }}"
                    >
                        <i class="{{ $item['icon'] }} mr-3"></i>
                        <span>{{ $item['label'] }}</span>
                    </a>
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
                        <a href="{{ $moduleHref }}" class="{{ $linkBaseClass }} {{ $linkHoverClass }} {{ $linkTextClass }}">
                            @if (!empty($module['icon']))
                                {!! $module['icon'] !!}
                            @else
                                <i class="fas fa-cube mr-3"></i>
                            @endif
                            <span>{{ $module['title'] ?? $module['name'] ?? 'Módulo' }}</span>
                        </a>
                    </li>
                @endforeach
            @endif
        </ul>
    </nav>

    {{-- Barra inferior --}}
    <div class="px-4 py-3 border-t border-multilab-gray dark:border-multilab-darkblue flex items-center justify-around">
        <a
            href="{{ Route::has('policies.index') ? route('policies.index') : '#' }}"
            class="text-multilab-dark dark:text-multilab-gray hover:text-multilab-blue dark:hover:text-multilab-darkblue"
            title="{{ Route::has('policies.index') ? 'Políticas' : 'Políticas (ruta pendiente)' }}"
        >
            <i class="fas fa-file-contract"></i>
        </a>
        <a
            href="{{ Route::has('privacy.index') ? route('privacy.index') : '#' }}"
            class="text-multilab-dark dark:text-multilab-gray hover:text-multilab-blue dark:hover:text-multilab-darkblue"
            title="{{ Route::has('privacy.index') ? 'Privacidad' : 'Privacidad (ruta pendiente)' }}"
        >
            <i class="fas fa-user-secret"></i>
        </a>
        <form method="POST" action="{{ Route::has('logout') ? route('logout') : '#' }}">
            @csrf
            <button
                type="submit"
                class="text-multilab-dark dark:text-multilab-gray hover:text-multilab-blue dark:hover:text-multilab-darkblue"
                title="{{ Route::has('logout') ? 'Cerrar sesión' : 'Logout (ruta pendiente)' }}"
            >
                <i class="fas fa-sign-out-alt"></i>
            </button>
        </form>
    </div>
</aside>
