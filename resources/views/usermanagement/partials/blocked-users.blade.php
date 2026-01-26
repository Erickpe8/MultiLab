{{-- resources/views/usermanagement/partials/blocked-users.blade.php --}}
@php
    use Illuminate\Support\Str;
@endphp
<div class="space-y-4">
    <div class="flex flex-col gap-4 pb-4 border-b border-[var(--border)]">
        <div class="flex items-center gap-3">
            <div
                class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[var(--primary-soft)] to-[var(--primary-600)]
                        flex items-center justify-center">
                <x-ui.icon name="lock-closed" size="lg" class="text-[var(--primary-600)] dark:text-[var(--primary-600)]" />
            </div>
            <div>
                <h3 class="text-lg font-bold text-[var(--text)]">
                    Usuarios Bloqueados
                </h3>
                <p class="text-sm text-[var(--text-muted)]">
                    {{ $blockedUsers->total() }} {{ Str::plural('usuario bloqueado', $blockedUsers->total()) }}
                </p>
            </div>
        </div>
        <p class="text-sm text-[var(--text-muted)]">
            Solo verás cuentas suspendidas. Usa la búsqueda para localizar nombres, correos o roles y reactivar rápidamente.
        </p>
    </div>

    <form method="GET" id="blocked-users-filter-form" class="flex flex-col gap-3">
        @foreach (request()->except('blocked_search', 'blocked_role', 'blocked_page', 'view') as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
        <input type="hidden" name="view" value="blocked">

        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
            <div class="flex-1 relative">
                <input type="text" name="blocked_search" id="blocked-search-input"
                    value="{{ request('blocked_search') }}"
                    placeholder="Buscar por nombre o correo"
                    class="w-full pl-10 pr-4 py-2 rounded-lg border border-[var(--border)]
                          bg-[var(--card)] text-[var(--text)] text-sm
                          focus:ring-2 focus:ring-[var(--primary)] focus:border-transparent
                          placeholder:text-[var(--text-muted)] transition-all">
                <x-ui.icon name="buscar" size="sm"
                    class="absolute left-3 top-2.5 text-[var(--text-muted)]" />
            </div>

            <div class="relative w-full sm:w-64">
                <label for="blocked-role-select" class="sr-only">Rol</label>
                <select name="blocked_role" id="blocked-role-select"
                    class="w-full px-4 py-2 rounded-lg border border-[var(--border)]
                           bg-[var(--card)] text-[var(--text)] text-sm
                           focus:ring-2 focus:ring-[var(--primary)] focus:border-transparent
                           appearance-none transition-all">
                    <option value="">Todos los roles</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}"
                            {{ request('blocked_role') === $role->name ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
                <x-ui.icon name="expandir" size="sm"
                    class="absolute right-3 top-2.5 text-[var(--text-muted)] pointer-events-none" />
            </div>
        </div>

        @if (request('blocked_search') || request('blocked_role'))
            <div class="flex flex-wrap items-center gap-2 text-xs font-semibold text-[var(--text-muted)]">
                <span>
                    Filtrando por:
                    @if (request('blocked_search'))
                        “{{ request('blocked_search') }}”
                    @endif
                    @if (request('blocked_role'))
                        ({{ ucfirst(request('blocked_role')) }})
                    @endif
                </span>
                <a href="{{ route('user-management.index', ['view' => 'blocked']) }}"
                    class="inline-flex items-center gap-1 px-3 py-1 rounded-full border border-[var(--border)]
                          bg-[var(--card)] text-[var(--text)] hover:border-[var(--accent)]/60 transition-colors">
                    <x-ui.icon name="cerrar" size="xs" class="text-[var(--text-muted)]" />
                    Limpiar filtros
                </a>
            </div>
        @endif
    </form>

    <div id="blocked-users-container">
        @if ($blockedUsers->count() > 0)
            <div class="overflow-x-auto rounded-lg border border-[var(--border)]">
                <table class="w-full" id="blocked-users-table">
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
                        @foreach ($blockedUsers as $user)
                            @php
                                $mainRole = $user->roles->first()->name ?? 'Sin rol';
                            @endphp
                            <tr class="hover:bg-[var(--border)]/5 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-full bg-gradient-to-br from-[var(--primary-soft)] to-[var(--primary-600)]
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

                                <td class="px-6 py-4 hidden md:table-cell">
                                    <div class="text-sm text-[var(--text)]">{{ $user->email }}</div>
                                </td>

                                <td class="px-6 py-4 hidden lg:table-cell">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                bg-blue-500/10 text-blue-700 dark:text-blue-300 border border-blue-500/30 w-fit">
                                        {{ $mainRole }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span
                        class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold
                                                bg-[var(--primary-soft)] text-[var(--primary-600)] dark:text-[var(--primary-600)]
                                                border border-[color-mix(in oklab, var(--primary) 50%, var(--border))]">
                                        Bloqueado
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button"
                                            onclick="confirmUnblockUser({{ $user->id }}, '{{ addslashes($user->name) }}', this)"
                                            class="inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg
                                                   bg-gradient-to-r from-emerald-500 to-teal-600
                                                   text-white text-sm font-medium
                                                   hover:shadow-lg transition-all">
                                            <x-ui.icon name="lock-open" size="sm" class="text-white" />
                                            Desbloquear
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($blockedUsers->hasPages())
                <div class="mt-4 pt-4 border-t border-[var(--border)]">
                    <x-pagination :paginator="$blockedUsers" pageName="blocked_page" />
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-[var(--primary-soft)] flex items-center justify-center">
                    <x-ui.icon name="lock-closed" size="xl" class="w-10 h-10 text-[var(--primary-600)] dark:text-[var(--primary-600)]" />
                </div>
                <h4 class="text-lg font-semibold text-[var(--text)] mb-1">
                    @if (request('blocked_search') || request('blocked_role'))
                        No se encontraron resultados
                    @else
                        No hay usuarios bloqueados
                    @endif
                </h4>
                <p class="text-sm text-[var(--text-muted)] mb-4">
                    @if (request('blocked_search') || request('blocked_role'))
                        Ajusta los filtros o limpia la búsqueda para reintentar.
                    @else
                        Las cuentas bloqueadas aparecerán aquí una vez que sean suspendidas.
                    @endif
                </p>
                @if (request('blocked_search') || request('blocked_role'))
                    <a href="{{ route('user-management.index', ['view' => 'blocked']) }}"
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('blocked-users-filter-form');
        const searchInput = document.getElementById('blocked-search-input');
        const roleSelect = document.getElementById('blocked-role-select');
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
