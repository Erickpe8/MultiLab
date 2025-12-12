<x-app-layout>
    <x-slot name="header">
        @php
            $user = auth()->user();
            $roleLabel = $user?->display_role_label ?? 'Sin rol asignado';
        @endphp
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-800">
                {{ __('Panel de Control') }}
            </h2>

            <span class="px-4 py-1.5 rounded-full bg-indigo-100 text-indigo-700 text-sm font-medium">
                {{ strtoupper($roleLabel) }}
            </span>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-800">
                    ¡Bienvenido, {{ auth()->user()->name }}!
                </h3>
                <p class="text-gray-600 mt-1">
                    Te has autenticado correctamente. Selecciona un módulo para continuar.
                </p>
            </div>

            @role('superadmin')
                @include('cards.superadmin')
            @endrole

            @role('aux_admin')
                @include('cards.auxiliar')
            @endrole

            @role('docente')
                @include('cards.docente')
            @endrole

            @role('estudiante')
                @include('cards.estudiante')
            @endrole

        </div>
    </div>
</x-app-layout>
