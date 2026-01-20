{{-- resources/views/usermanagement/partials/active-users.blade.php --}}
<div class="space-y-4">
    <div class="flex flex-col gap-4 pb-4 border-b border-[var(--border)]">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500/20 to-emerald-600/10
                            flex items-center justify-center">
                    <x-ui.icon name="equipo-desarrollo" size="lg"
                        class="text-green-600 dark:text-green-400" />
                </div>
                <div>
                    <h3 class="text-lg font-bold text-[var(--text)]">
                        Usuarios Activos
                    </h3>
                    <p class="text-sm text-[var(--text-muted)]">
                        {{ $activeUsers->total() }} usuarios registrados
                    </p>
                </div>
            </div>
        </div>

        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach (request()->except('active_search', 'active_role', 'active_page') as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach

            <div class="relative">
                <input type="text" name="active_search" value="{{ request('active_search') }}"
                    placeholder="Buscar por nombre o email..."
                    class="w-full pl-10 pr-4 py-2 rounded-lg border border-[var(--border)]
                              bg-[var(--card)] text-[var(--text)] text-sm
                              focus:ring-2 focus:ring-green-500 focus:border-transparent
                              placeholder:text-[var(--text-muted)] transition-all">
                <x-ui.icon name="buscar" size="sm"
                    class="absolute left-3 top-2.5 text-[var(--text-muted)]" />
            </div>

            <div class="relative">
                <select name="active_role"
                    class="w-full px-4 py-2 rounded-lg border border-[var(--border)]
                               bg-[var(--card)] text-[var(--text)] text-sm
                               focus:ring-2 focus:ring-green-500 focus:border-transparent
                               transition-all appearance-none">
                    <option value="">Todos los roles</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}"
                            {{ request('active_role') === $role->name ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
                <x-ui.icon name="expandir" size="sm"
                    class="absolute right-3 top-3 text-[var(--text-muted)] pointer-events-none" />
            </div>

            <div class="flex gap-2">
                <button type="submit"
                    class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg
                               bg-green-600 hover:bg-green-700 text-white text-sm font-medium
                               transition-colors">
                    <x-ui.icon name="filtrar" size="sm" class="text-white" />
                    Filtrar
                </button>

                @if (request('active_search') || request('active_role'))
                    <a href="{{ route('user-management.index', array_filter(request()->except('active_search', 'active_role', 'active_page'))) }}"
                        class="inline-flex items-center justify-center px-4 py-2 rounded-lg
                              border border-[var(--border)] text-[var(--text)] text-sm font-medium
                              hover:bg-[var(--border)]/5 transition-colors">
                        <x-ui.icon name="cerrar" size="sm" class="text-[var(--text)]" />
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div id="active-users-container">
        @if ($activeUsers->count() > 0)
            <div class="overflow-x-auto rounded-lg border border-[var(--border)]">
                <table class="w-full" id="active-users-table">
                    <thead>
                        <tr class="bg-[var(--border)]/5 border-b border-[var(--border)]">
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-[var(--text)] uppercase tracking-wider">
                                Usuario
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-[var(--text)] uppercase tracking-wider hidden md:table-cell">
                                Email
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-[var(--text)] uppercase tracking-wider hidden lg:table-cell">
                                Rol
                            </th>
                            <th
                                class="px-6 py-3 text-center text-xs font-semibold text-[var(--text)] uppercase tracking-wider">
                                Estado
                            </th>
                            <th
                                class="px-6 py-3 text-right text-xs font-semibold text-[var(--text)] uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--border)]">
                        @foreach ($activeUsers as $user)
                            @php
                                $mainRole = $user->roles->first()->name ?? 'Sin rol';
                            @endphp
                            <tr class="hover:bg-[var(--border)]/5 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-gradient-to-br from-[var(--primary)] to-[var(--accent)]
                                                    flex items-center justify-center text-white font-bold text-sm shrink-0">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-[var(--text)]">
                                                {{ $user->name }}
                                            </div>
                                            <div class="text-sm text-[var(--text-muted)] md:hidden">
                                                {{ $user->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">
                                    <div class="text-sm text-[var(--text)]">{{ $user->email }}</div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap hidden lg:table-cell">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                 bg-blue-500/20 text-blue-700 dark:text-blue-300 border border-blue-500/30 w-fit">
                                        {{ $mainRole }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span
                                        class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold
                                                 bg-green-500/20 text-green-700 dark:text-green-300
                                                 border border-green-500/30">
                                        Activo
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                    <button type="button"
                                        onclick="openEditRoleModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($mainRole) }}', '', {{ $user->is_active ? 'true' : 'false' }})"
                                        class="inline-flex items-center justify-center p-1.5 rounded-lg
                                                bg-blue-500/10 text-blue-600 dark:text-blue-400
                                                hover:bg-blue-500/20 transition-all
                                                border border-blue-500/20 hover:border-blue-500/40"
                                        title="Editar usuario">
                                        <x-ui.icon name="editar" size="sm"
                                            class="text-blue-600 dark:text-blue-400" />
                                    </button>

                                    <button type="button"
                                        onclick="confirmBlockUser({{ $user->id }}, '{{ addslashes($user->name) }}', this)"
                                        class="inline-flex items-center justify-center p-1.5 rounded-lg
                                               bg-amber-500/10 text-amber-600 dark:text-amber-400
                                               hover:bg-amber-500/20 transition-all
                                               border border-amber-500/20 hover:border-amber-500/40"
                                        title="Bloquear usuario">
                                        <x-ui.icon name="lock-closed" size="sm"
                                            class="text-amber-600 dark:text-amber-400" />
                                    </button>

                                    <button type="button"
                                        onclick="deleteUser({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}')"
                                        class="inline-flex items-center justify-center p-1.5 rounded-lg
                                               bg-red-500/10 text-red-600 dark:text-red-400
                                               hover:bg-red-500/20 transition-all
                                               border border-red-500/20 hover:border-red-500/40"
                                        title="Eliminar usuario">
                                        <x-ui.icon name="eliminar" size="sm"
                                            class="text-red-600 dark:text-red-400" />
                                    </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($activeUsers->hasPages())
                <div class="mt-4 pt-4 border-t border-[var(--border)]">
                    <x-pagination :paginator="$activeUsers" pageName="active_page" />
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-500/10 flex items-center justify-center">
                    <x-ui.icon name="equipo-desarrollo" size="xl"
                        class="w-10 h-10 text-gray-600 dark:text-gray-400" />
                </div>
                <h4 class="text-lg font-semibold text-[var(--text)] mb-1">
                    @if (request('active_search') || request('active_role'))
                        No se encontraron resultados
                    @else
                        No hay usuarios activos
                    @endif
                </h4>
                <p class="text-sm text-[var(--text-muted)] mb-4">
                    @if (request('active_search') || request('active_role'))
                        Intenta ajustar los filtros de búsqueda
                    @else
                        Los usuarios aparecerán aquí una vez sean aprobados
                    @endif
                </p>
                @if (request('active_search') || request('active_role'))
                    <a href="{{ route('user-management.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                              bg-[var(--primary)] text-white font-medium
                              hover:bg-[var(--primary)]/90 transition-colors">
                        <x-ui.icon name="cerrar" size="sm" class="text-white" />
                        Limpiar filtros
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
