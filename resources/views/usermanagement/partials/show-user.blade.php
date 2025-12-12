<x-app-layout>

    <div class="p-6">

        {{-- TITULO DEL SUBMÓDULO --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Información del Usuario</h1>
            <p class="text-gray-600">Consulta detallada del usuario registrado.</p>
        </div>

        {{-- CARD PRINCIPAL --}}
        <div class="bg-white rounded-xl shadow-sm border p-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <h3 class="font-semibold text-gray-700">Nombre completo</h3>
                    <p class="text-gray-900 text-lg">{{ $user->name }}</p>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-700">Correo electrónico</h3>
                    <p class="text-gray-900 text-lg">{{ $user->email }}</p>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-700">Área</h3>
                    <p class="text-gray-900">{{ $user->area ?? '—' }}</p>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-700">Cargo</h3>
                    <p class="text-gray-900">{{ $user->position ?? '—' }}</p>
                </div>

                <div class="col-span-2">
                    <h3 class="font-semibold text-gray-700">Roles asignados</h3>
                    <div class="flex flex-wrap gap-2 mt-1">
                        @foreach ($user->roles as $role)
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-lg text-sm">
                                {{ ucfirst($role->name) }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-700">Estado</h3>
                    <span class="px-3 py-1 rounded-lg bg-green-100 text-green-800 text-sm">
                        Activo
                    </span>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-700">Fecha de registro</h3>
                    <p class="text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                </div>

            </div>

        </div>

        {{-- BOTON VOLVER --}}
        <div class="mt-6">
            <a href="{{ route('superadmin.users') }}"
                class="inline-block px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 border text-gray-700">
                ← Volver al panel de usuarios
            </a>
        </div>

    </div>

</x-app-layout>
