{{-- resources/views/usermanagement/partials/blocked-users.blade.php --}}
@php
    use Illuminate\Support\Str;
    $actionButtonBase = 'h-10 w-10 inline-flex items-center justify-center rounded-xl border transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-offset-transparent shadow-sm';
    $btnIconGreen = $actionButtonBase . ' bg-[#009B72] border-[#009B72] text-white hover:bg-[#007f5d] hover:border-[#007f5d] focus-visible:ring-[#009B72]';
@endphp
<div class="space-y-4">
    <div class="flex flex-col gap-4 pb-4 border-b border-[var(--border)]">
        <div class="flex items-center gap-3">
            <div
                class="flex h-12 w-12 items-center justify-center rounded-2xl
                    border border-[#F40000]/30
                    bg-[#F40000]/10
                    dark:border-[#F40000]/40
                    dark:bg-[#F40000]/20"
            >
                <x-ui.icon name="block-user" class="h-6 w-6 text-[#F40000]" />
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

    <form method="GET" id="blocked-users-filter-form"
            class="flex flex-col gap-3 md:flex-row md:items-center md:gap-4">
        @foreach (request()->except('blocked_search', 'blocked_role', 'blocked_page', 'view') as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
        <input type="hidden" name="view" value="blocked">

        <div class="flex-1 min-w-0">
            <div class="relative">
                <input type="text" name="blocked_search" id="blocked-search-input"
                    value="{{ request('blocked_search') }}"
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
            <label for="blocked-role-select" class="sr-only">Rol</label>
            <select name="blocked_role" id="blocked-role-select"
                class="w-full md:w-56 h-10 rounded-xl border border-[var(--border)] bg-[var(--card)]
                       text-sm text-[var(--text)] px-3 transition-all focus:border-[var(--accent)] focus:ring-2 focus:ring-[var(--accent)]/20">
                <option value="">Todos los roles</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}"
                        {{ request('blocked_role') === $role->name ? 'selected' : '' }}>
                        {{ ucfirst($role->name) }}
                    </option>
                @endforeach
            </select>
            <x-ui.icon name="expandir" size="sm"
                class="absolute right-3 top-3 text-[var(--text-muted)] pointer-events-none" />
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
                        <tr class="bg-[var(--border)]/5 border-b border-[var(--border)] text-center">
                            <th
                                class="px-6 py-3 text-center text-xs font-semibold text-[var(--text)] uppercase tracking-wider">
                                Usuario
                            </th>
                            <th
                                class="px-6 py-3 text-center text-xs font-semibold text-[var(--text)] uppercase tracking-wider hidden md:table-cell">
                                Email
                            </th>
                            <th
                                class="px-6 py-3 text-center text-xs font-semibold text-[var(--text)] uppercase tracking-wider hidden lg:table-cell">
                                Rol
                            </th>
                            <th
                                class="px-6 py-3 text-center text-xs font-semibold text-[var(--text)] uppercase tracking-wider">
                                Estado
                            </th>
                            <th
                                class="px-6 py-3 text-center text-xs font-semibold text-[var(--text)] uppercase tracking-wider">
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
                    class="w-12 h-12 rounded-full bg-[#1D4ED8] flex items-center justify-center text-white font-bold text-lg shrink-0">
                                            {{ Str::upper(Str::substr(Str::of($user->name ?: 'Usuario')->trim(), 0, 1)) }}
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
                                    @php
                                        $roleLabel = $mainRole;
                                        $roleBadgeClass = $roleLabel === 'Sin rol'
                                            ? 'bg-[#F0C808] border-[#F0C808] text-black'
                                            : 'bg-[#1D4ED8] border-[#1D4ED8] text-white';
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold border {{ $roleBadgeClass }}">
                                        {{ $roleLabel }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold border bg-[#F40000] border-[#F40000] text-white">
                                        Bloqueado
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button"
                                            onclick="openUnblockModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}')"
                                            class="{{ $btnIconGreen }}"
                                            title="Desbloquear usuario"
                                            aria-label="Desbloquear usuario">
                                            <x-ui.icon name="heroicon-o-lock-open" size="sm"
                                                class="text-white" />
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

<x-modal name="unblock-user-modal" :show="false" maxWidth="lg">
    <form id="unblock-user-form" class="space-y-5">
        <header class="px-8 pt-7 pb-4 flex items-start justify-between gap-6 border-b border-slate-200/80">
            <div class="space-y-1">
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-slate-500">Confirmar desbloqueo</p>
                <p class="text-sm text-slate-500">El usuario podrá acceder nuevamente al sistema</p>
            </div>
            <button type="button"
                    class="h-10 w-10 grid place-items-center rounded-full text-[var(--text-muted)] hover:bg-black/5 focus:outline-none focus:ring-2 focus:ring-[var(--accent)] focus:ring-offset-1"
                    aria-label="Cerrar modal"
                    onclick="closeModal('unblock-user-modal')">
                ×
            </button>
        </header>

        <input type="hidden" id="unblock-user-id">

        <div class="space-y-5 px-8">
            <div class="flex items-center gap-4">
                <span class="h-12 w-12 rounded-full bg-[var(--primary-soft)] text-[var(--primary-600)] flex items-center justify-center font-semibold text-lg"
                      id="unblock-user-avatar">?</span>
                <div>
                    <p class="text-xl font-semibold text-[var(--text)]" id="unblock-user-name">Nombre del usuario</p>
                    <p class="text-sm text-slate-500" id="unblock-user-email">email@example.com</p>
                </div>
            </div>

            <p class="text-sm leading-relaxed text-[var(--text)]">
                ¿Desbloquear a <span class="font-semibold" id="unblock-confirm-user">este usuario</span>?
            </p>

            <p class="text-xs text-slate-500">
                El usuario podrá acceder nuevamente al sistema inmediatamente después de desbloquearlo.
            </p>
        </div>

        <footer class="px-8 py-5 flex items-center justify-end gap-3 border-t border-slate-200/80">
            <button type="button" onclick="closeModal('unblock-user-modal')"
                    class="h-11 px-6 rounded-full border border-slate-200 text-sm font-semibold text-[var(--text)] bg-white hover:bg-[var(--border)]/40 transition">
                Cancelar
            </button>
            <button type="submit" id="unblock-user-confirm-btn"
                    class="relative h-11 min-w-[170px] rounded-full bg-gradient-to-r from-green-500 to-emerald-600
                           px-6 text-sm font-semibold uppercase tracking-[0.2em] text-white disabled:opacity-70 disabled:cursor-not-allowed
                           flex items-center justify-center gap-2">
                <span class="spinner hidden">
                    <span class="w-3 h-3 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                </span>
                <span class="button-text">Desbloquear</span>
            </button>
        </footer>
    </form>

    @once
    <style>
        #unblock-user-confirm-btn:disabled .spinner {
            display: inline-flex;
        }
        #unblock-user-confirm-btn:disabled .button-text {
            visibility: hidden;
        }
        #unblock-user-confirm-btn .spinner {
            display: none;
        }
    </style>
    @endonce
</x-modal>

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