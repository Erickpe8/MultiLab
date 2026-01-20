<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    {{-- Usuarios Registrados --}}
    <a href="{{ route('user-management.index') }}" class="block group bg-white p-6 rounded-xl shadow-sm border border-gray-200
              hover:shadow-md hover:border-[var(--primary)]/40 transition">

        <div class="flex items-center gap-3 mb-3">
            <div class="p-2 rounded-lg bg-[var(--primary)]/10 text-[var(--primary)]">
                {{-- Icono usuarios --}}
                <x-ui.icon name="usuarios" size="lg" class="text-[var(--primary)]" />
            </div>

            <h3 class="text-lg font-bold text-gray-800 group-hover:text-[var(--primary)]">
                Usuarios Registrados
            </h3>
        </div>

        <p class="text-gray-600">
            Consulta la información completa de los usuarios activos del sistema.
        </p>
    </a>


    {{-- Solicitudes Pendientes --}}
    <a href="{{ route('user-management.index') }}#pendientes" class="block group bg-white p-6 rounded-xl shadow-sm border border-gray-200
              hover:shadow-md hover:border-yellow-400/60 transition">

        <div class="flex items-center gap-3 mb-3">
            <div class="p-2 rounded-lg bg-yellow-400/10 text-yellow-500">
                {{-- Icono alerta --}}
                <x-ui.icon name="advertencia" size="lg" class="text-yellow-500" />
            </div>

            <h3 class="text-lg font-bold text-gray-800 group-hover:text-yellow-600">
                Solicitudes Pendientes
            </h3>
        </div>

        <p class="text-gray-600">
            Revisa y aprueba solicitudes de registro enviadas por nuevos usuarios.
        </p>
    </a>


    {{-- Usuarios Preaprobados --}}
    <a href="{{ route('user-management.index') }}#preaprobados" class="block group bg-white p-6 rounded-xl shadow-sm border border-gray-200
            hover:shadow-md hover:border-indigo-400/60 transition">

        <div class="flex items-center gap-3 mb-3">
            <div class="p-2 rounded-lg bg-indigo-400/10 text-indigo-500">
                {{-- Icono checklist --}}
                <x-ui.icon name="exito" size="lg" class="text-indigo-500" />
            </div>

            <h3 class="text-lg font-bold text-gray-800 group-hover:text-indigo-600">
                Usuarios Preaprobados
            </h3>
        </div>

        <p class="text-gray-600">
            Usuarios creados internamente que aún no han sido activados.
        </p>
    </a>

</div>
