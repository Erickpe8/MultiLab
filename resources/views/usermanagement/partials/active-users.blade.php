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

        <form method="GET" id="active-users-filter-form"
            class="flex flex-col gap-3 md:flex-row md:items-center md:gap-4">
            @foreach (request()->except('active_search', 'active_role', 'view', 'active_page') as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            <input type="hidden" name="view" value="active">

            <div class="flex-1 min-w-0">
                <div class="relative">
                    <input type="text" name="active_search" id="active-search-input"
                        value="{{ request('active_search') }}"
                        placeholder="Buscar por nombre o correo"
                        class="w-full h-10 rounded-xl border border-[var(--border)] bg-[var(--card)]
                              text-sm text-[var(--text)] placeholder:text-[var(--text-muted)] pl-10 pr-4 transition-all
                              focus:border-[var(--accent)] focus:ring-2 focus:ring-[var(--accent)]/20">
                    <div class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 flex items-center">
                        <x-ui.icon name="buscar" size="sm" class="text-[var(--text-muted)]" />
                    </div>
                </div>
            </div>

            <div class="w-full md:w-auto relative">
                <label for="active-role-select" class="sr-only">Rol</label>
                <select name="active_role" id="active-role-select"
                    class="w-full md:w-56 h-10 rounded-xl border border-[var(--border)] bg-[var(--card)]
                           text-sm text-[var(--text)] px-3 transition-all focus:border-[var(--accent)] focus:ring-2 focus:ring-[var(--accent)]/20">
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

            @if (request('active_search') || request('active_role'))
                <div class="text-[var(--text)] text-xs font-semibold flex items-center gap-2">
                    <span>Filtros activos:</span>
                    <span class="text-[var(--text-muted)]">
                        @if (request('active_search'))
                            “{{ request('active_search') }}”
                        @endif
                        @if (request('active_role'))
                            ({{ ucfirst(request('active_role')) }})
                        @endif
                    </span>
                    <a href="{{ route('user-management.index', ['view' => 'active']) }}"
                        class="text-[var(--accent)] hover:underline">
                        Limpiar
                    </a>
                </div>
            @endif
        </form>
    </div>

        <div id="active-users-container" class="mt-4">
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
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const form = document.getElementById('active-users-filter-form');
                const searchInput = document.getElementById('active-search-input');
                const roleSelect = document.getElementById('active-role-select');
                let debounceTimeout;

                const submitForm = () => {
                    if (form) {
                        form.submit();
                    }
                };

                if (searchInput) {
                    searchInput.addEventListener('input', function () {
                        clearTimeout(debounceTimeout);
                        debounceTimeout = setTimeout(submitForm, 400);
                    });
                }

                if (roleSelect) {
                    roleSelect.addEventListener('change', submitForm);
                }
            });
        </script>
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
                                        onclick="confirmBlockUser({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}')"
                                        class="inline-flex items-center justify-center p-1.5 rounded-lg
                                               bg-[var(--primary-soft)] text-[var(--primary-600)]
                                               hover:bg-[color-mix(in oklab, var(--primary), transparent 70%)]
                                               transition-all
                                               border border-[color-mix(in oklab, var(--primary) 50%, var(--border))]
                                               hover:border-[color-mix(in oklab, var(--primary) 70%, var(--border))]"
                                        title="Bloquear usuario"
                                        aria-label="Bloquear usuario">
                                        <x-ui.icon name="block-user" size="sm"
                                            class="text-[var(--primary-600)] dark:text-[var(--primary-600)]" />
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
