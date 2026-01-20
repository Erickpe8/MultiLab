<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Multilab FESC · Panel principal
                </h2>
                <p class="text-sm text-gray-500">
                    Bienvenido, {{ auth()->user()->name }}.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <section class="bg-white rounded-2xl p-6 md:p-8 shadow-sm space-y-3">
                <div class="rounded-xl border border-[#D84040]/30 bg-[#1D1616]/5 px-4 py-3">
                    <p class="text-sm text-gray-700">
                        Este panel muestra accesos y módulos según tu rol en Multilab
                        (@foreach(auth()->user()->roles as $role)
                            <span
                                class="inline-flex items-center px-2 py-0.5 mx-1 rounded text-xs bg-[#D84040]/10 text-[#8E1616]">
                                {{ $role->name }}
                            </span>
                        @endforeach
                        ).
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- ============================
                SUPERADMIN (Director Programa)
                ============================ --}}
                @role('superadmin')
                {{-- Gestión de usuarios --}}
                <a href="{{ route('admin.users.index') }}" class="group block rounded-2xl border border-[#D84040]/40 bg-white shadow-soft
                              hover:shadow-lg transition hover:-translate-y-1 overflow-hidden">
                    <div class="h-1.5 bg-gradient-to-r from-[#8E1616] via-[#D84040] to-[#1D1616]"></div>
                    <div class="p-5">
                        <h3 class="text-lg font-semibold text-[#8E1616] mb-1">
                            Gestión de usuarios
                        </h3>
                        <p class="text-sm text-gray-600 mb-3">
                            Crear, editar y gestionar docentes, estudiantes y personal con acceso al sistema.
                        </p>
                        <span
                            class="inline-flex items-center text-sm font-semibold text-[#D84040] group-hover:underline">
                            Administrar usuarios
                            <x-ui.icon name="siguiente" size="sm" class="ml-1 text-current" />
                        </span>
                    </div>
                </a>

                {{-- Configuración e indicadores --}}
                <div class="rounded-2xl border border-[#D84040]/30 bg-[#1D1616]/90 text-[#EEEEEE] shadow-soft p-5">
                    <h3 class="text-lg font-semibold mb-1">
                        Visión general de Multilab
                    </h3>
                    <p class="text-sm text-[#EEEEEE]/90 mb-3">
                        Próximamente podrás visualizar indicadores clave: inventario, préstamos activos,
                        equipos en mantenimiento y uso por programa académico.
                    </p>
                    <p class="text-xs text-[#EEEEEE]/70">
                        Rol: Director de Programa (Superadmin).
                    </p>
                </div>
                @endrole

                {{-- ==========================================
                INVENTARIO (superadmin + aux_admin)
                ========================================== --}}
                @hasanyrole('superadmin|aux_admin')
                <a href="{{ route('inventory.assets.index') }}" class="group block rounded-2xl border border-[#D84040]/30 bg-white shadow-soft
                              hover:shadow-lg transition hover:-translate-y-1 overflow-hidden">
                    <div class="h-1.5 bg-gradient-to-r from-[#1D1616] via-[#8E1616] to-[#D84040]"></div>
                    <div class="p-5">
                        <h3 class="text-lg font-semibold text-[#8E1616] mb-1">
                            Gestión de activos
                        </h3>
                        <p class="text-sm text-gray-600 mb-3">
                            Administrar equipos, dispositivos y recursos tecnológicos de los laboratorios.
                        </p>
                        <span
                            class="inline-flex items-center text-sm font-semibold text-[#D84040] group-hover:underline">
                            Ir al inventario de activos
                            <x-ui.icon name="siguiente" size="sm" class="ml-1 text-current" />
                        </span>
                    </div>
                </a>

                <a href="{{ route('inventory.materials.index') }}" class="group block rounded-2xl border border-[#D84040]/30 bg-white shadow-soft
                              hover:shadow-lg transition hover:-translate-y-1 overflow-hidden">
                    <div class="h-1.5 bg-gradient-to-r from-[#D84040] via-[#8E1616] to-[#1D1616]"></div>
                    <div class="p-5">
                        <h3 class="text-lg font-semibold text-[#8E1616] mb-1">
                            Gestión de materiales
                        </h3>
                        <p class="text-sm text-gray-600 mb-3">
                            Controlar insumos, elementos de préstamo y consumibles.
                        </p>
                        <span
                            class="inline-flex items-center text-sm font-semibold text-[#D84040] group-hover:underline">
                            Ir al inventario de materiales
                            <x-ui.icon name="siguiente" size="sm" class="ml-1 text-current" />
                        </span>
                    </div>
                </a>
                @endhasanyrole

                {{-- ==========================================
                PRÉSTAMOS Y RESERVAS (superadmin, aux, docente)
                ========================================== --}}
                @hasanyrole('superadmin|aux_admin|docente')
                <a href="{{ route('loans.index') }}" class="group block rounded-2xl border border-[#D84040]/30 bg-white shadow-soft
                              hover:shadow-lg transition hover:-translate-y-1 overflow-hidden">
                    <div class="h-1.5 bg-gradient-to-r from-[#8E1616] via-[#D84040] to-[#1D1616]"></div>
                    <div class="p-5">
                        <h3 class="text-lg font-semibold text-[#8E1616] mb-1">
                            Préstamos tecnológicos
                        </h3>
                        <p class="text-sm text-gray-600 mb-3">
                            Gestionar solicitudes, entregas, devoluciones y seguimiento de equipos.
                        </p>
                        <span
                            class="inline-flex items-center text-sm font-semibold text-[#D84040] group-hover:underline">
                            Ver préstamos
                            <x-ui.icon name="siguiente" size="sm" class="ml-1 text-current" />
                        </span>
                    </div>
                </a>

                <a href="{{ route('reservations.index') }}" class="group block rounded-2xl border border-[#D84040]/30 bg-white shadow-soft
                              hover:shadow-lg transition hover:-translate-y-1 overflow-hidden">
                    <div class="h-1.5 bg-gradient-to-r from-[#1D1616] via-[#D84040] to-[#8E1616]"></div>
                    <div class="p-5">
                        <h3 class="text-lg font-semibold text-[#8E1616] mb-1">
                            Reservas de laboratorio
                        </h3>
                        <p class="text-sm text-gray-600 mb-3">
                            Programar uso de equipos y espacios en horarios específicos.
                        </p>
                        <span
                            class="inline-flex items-center text-sm font-semibold text-[#D84040] group-hover:underline">
                            Ver reservas
                            <x-ui.icon name="siguiente" size="sm" class="ml-1 text-current" />
                        </span>
                    </div>
                </a>
                @endhasanyrole

                {{-- ==========================================
                ESTUDIANTE (vista simplificada)
                ========================================== --}}
                @role('estudiante')
                <a href="{{ route('reservations.index') }}" class="group block rounded-2xl border border-[#D84040]/30 bg-white shadow-soft
                              hover:shadow-lg transition hover:-translate-y-1 overflow-hidden">
                    <div class="h-1.5 bg-gradient-to-r from-[#D84040] via-[#8E1616] to-[#1D1616]"></div>
                    <div class="p-5">
                        <h3 class="text-lg font-semibold text-[#8E1616] mb-1">
                            Mis reservas
                        </h3>
                        <p class="text-sm text-gray-600 mb-3">
                            Consulta tus reservas activas y el historial de solicitudes realizadas.
                        </p>
                        <span
                            class="inline-flex items-center text-sm font-semibold text-[#D84040] group-hover:underline">
                            Ver mis reservas
                            <x-ui.icon name="siguiente" size="sm" class="ml-1 text-current" />
                        </span>
                    </div>
                </a>

                <a href="{{ route('loans.index') }}" class="group block rounded-2xl border border-[#D84040]/30 bg-white shadow-soft
                              hover:shadow-lg transition hover:-translate-y-1 overflow-hidden">
                    <div class="h-1.5 bg-gradient-to-r from-[#1D1616] via-[#8E1616] to-[#D84040]"></div>
                    <div class="p-5">
                        <h3 class="text-lg font-semibold text-[#8E1616] mb-1">
                            Mis préstamos
                        </h3>
                        <p class="text-sm text-gray-600 mb-3">
                            Revisa tus equipos actualmente prestados y fechas de devolución.
                        </p>
                        <span
                            class="inline-flex items-center text-sm font-semibold text-[#D84040] group-hover:underline">
                            Ver mis préstamos
                            <x-ui.icon name="siguiente" size="sm" class="ml-1 text-current" />
                        </span>
                    </div>
                </a>
                @endrole
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
