<x-app-layout>

    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-[var(--text)]">Gestión de Usuarios</h1>
                <p class="text-sm text-[var(--text-muted)]">Aprueba o rechaza preregistros y administra roles.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('user-management.index', ['view' => 'pending']) }}"
                    class="px-4 py-2 rounded-lg text-sm font-semibold border border-[var(--border)]
                           {{ $view === 'pending' ? 'bg-[var(--primary)] text-white' : 'bg-[var(--card)] text-[var(--text)] hover:bg-[var(--border)]/20' }}">
                    Solicitudes pendientes
                </a>
                <a href="{{ route('user-management.index', ['view' => 'active']) }}"
                    class="px-4 py-2 rounded-lg text-sm font-semibold border border-[var(--border)]
                           {{ $view === 'active' ? 'bg-[var(--primary)] text-white' : 'bg-[var(--card)] text-[var(--text)] hover:bg-[var(--border)]/20' }}">
                    Usuarios activos
                </a>
            </div>
        </div>

        @if ($view === 'pending')
            @include('usermanagement.partials.pending-requests')
        @endif

        @if ($view === 'active')
            @include('usermanagement.partials.active-users')
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

