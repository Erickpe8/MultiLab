@section('title', 'Administración de Usuarios')

<x-app-layout>

    <div class="max-w-7xl mx-auto space-y-6">
        <div class="space-y-4">
            <div>
                <h1 class="text-xl font-bold text-[var(--text)]">Gestión de Usuarios</h1>
                <p class="text-sm text-[var(--text-muted)]">
                    Controla los tres estados clave: activos con roles vigentes, solicitudes en revisión y bloqueados bajo vigilancia.
                </p>
            </div>

            @php
                $tabs = [
                    [
                        'key' => 'active',
                        'label' => 'Activos',
                        'description' => 'Accesos vigentes',
                        'href' => route('user-management.index', ['view' => 'active']),
                    ],
                    [
                        'key' => 'pending',
                        'label' => 'Pendientes',
                        'description' => 'Registros en revisión',
                        'href' => route('user-management.pending'),
                    ],
                    [
                        'key' => 'blocked',
                        'label' => 'Bloqueados',
                        'description' => 'Cuentas suspendidas',
                        'href' => route('user-management.blocked'),
                    ],
                ];
            @endphp

            <div class="flex flex-wrap gap-2">
                @foreach ($tabs as $tab)
                    @php
                        $isActiveTab = $view === $tab['key'];
                    @endphp
                    <a href="{{ $tab['href'] }}"
                        class="flex items-center gap-3 rounded-2xl border px-4 py-2 text-xs font-semibold uppercase tracking-[0.4em] transition duration-200 {{ $isActiveTab ? 'border-[var(--accent)] bg-[var(--border)]/70 text-[var(--text)] shadow-lg' : 'border-[var(--border)] bg-[var(--card)] text-[var(--text-muted)] hover:border-[var(--accent)]/60' }}">
                        <span class="flex h-2.5 w-2.5 items-center justify-center rounded-full bg-gradient-to-br from-[var(--accent)] to-[var(--primary)]"></span>
                        <span>{{ $tab['label'] }}</span>
                        <span class="text-[0.65rem] font-medium tracking-[0.5em]">
                            {{ $tab['description'] }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        @if ($view === 'pending')
            @include('usermanagement.partials.pending-requests')

        @elseif ($view === 'active')
            @include('usermanagement.partials.active-users')

        @elseif ($view === 'blocked')
            @include('usermanagement.partials.blocked-users')
        @else
            @include('usermanagement.partials.pending-requests')
        @endif
    </div>

    @include('usermanagement.partials.modals.approve-user')
    @include('usermanagement.partials.modals.reject-user')
    @include('usermanagement.partials.modals.edit-role')
    @include('usermanagement.partials.modals.delete-user')

    @push('scripts')
        @include('usermanagement.partials.scripts')
    @endpush

</x-app-layout>
