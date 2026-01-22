<x-filament-panels::page>
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8 dark:bg-gray-800">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
            ¡Bienvenido, {{ auth()->user()->name }}!
        </h3>
        <p class="text-gray-600 mt-1 dark:text-gray-400">
            Te has autenticado correctamente. Selecciona un módulo para continuar.
        </p>
    </div>

    @role('superadmin')
        @include('cards.superadmin')
        @include('cards.material-requests')
    @endrole

    @role('aux_admin')
        @include('cards.auxiliar')
        @include('cards.material-requests')
    @endrole

    @role('docente')
        @include('cards.docente')
    @endrole

    @role('estudiante')
        @include('cards.estudiante')
    @endrole
</x-filament-panels::page>