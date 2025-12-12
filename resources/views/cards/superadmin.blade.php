<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    {{-- Usuarios Registrados --}}
    <a href="{{ route('superadmin.users') }}" class="block group bg-white p-6 rounded-xl shadow-sm border border-gray-200
              hover:shadow-md hover:border-[var(--primary)]/40 transition">

        <div class="flex items-center gap-3 mb-3">
            <div class="p-2 rounded-lg bg-[var(--primary)]/10 text-[var(--primary)]">
                {{-- Icono usuarios --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                        d="M17 20h5v-2.5A4.5 4.5 0 0017.5 13h-.548m-9.225 7H2v-2.5A4.5 4.5 0 016.5 13h.548m8.404-6A4 4 0 1111 3a4 4 0 014.5 4h-9m0 0a4 4 0 11-1.548 7.705" />
                </svg>
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
    <a href="{{ route('superadmin.users') }}#pendientes" class="block group bg-white p-6 rounded-xl shadow-sm border border-gray-200
              hover:shadow-md hover:border-yellow-400/60 transition">

        <div class="flex items-center gap-3 mb-3">
            <div class="p-2 rounded-lg bg-yellow-400/10 text-yellow-500">
                {{-- Icono alerta --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                        d="M12 9v4m0 4h.01M4.93 19h14.14c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.2 16c-.77 1.33.19 3 1.73 3z" />
                </svg>
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
    <a href="{{ route('superadmin.users') }}#preaprobados" class="block group bg-white p-6 rounded-xl shadow-sm border border-gray-200
            hover:shadow-md hover:border-indigo-400/60 transition">

        <div class="flex items-center gap-3 mb-3">
            <div class="p-2 rounded-lg bg-indigo-400/10 text-indigo-500">
                {{-- Icono checklist --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                        d="M5 13l4 4L19 7m-7 13a9 9 0 110-18 9 9 0 010 18z" />
                </svg>
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
