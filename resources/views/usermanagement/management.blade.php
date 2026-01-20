@section('title', 'Administración de Usuarios')
<x-app-layout>

    <div class="max-w-7xl mx-auto space-y-6">
        <div class="space-y-4">

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
