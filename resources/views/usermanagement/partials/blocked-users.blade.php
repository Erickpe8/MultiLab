{{-- resources/views/usermanagement/partials/blocked-users.blade.php --}}
<div class="space-y-4">
    <div class="flex flex-col gap-2 pb-4 border-b border-[var(--border)]">
        <div class="flex items-center gap-3">
            <div
                class="w-10 h-10 rounded-lg bg-gradient-to-br from-red-500/20 to-red-600/10 flex items-center justify-center">
                <x-ui.icon name="user-minus" size="lg" class="text-red-600 dark:text-red-400" />
            </div>
            <div>
                <h3 class="text-lg font-bold text-[var(--text)]">Usuarios Bloqueados</h3>
                <p class="text-sm text-[var(--text-muted)]">
                    Las cuentas suspendidas por seguridad se muestran aquí; desbloqueos serán notificados al equipo.
                </p>
            </div>
        </div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-500/10 border border-red-500/30 text-xs font-semibold text-red-600 uppercase tracking-[0.35em]">
            <x-ui.icon name="lock-closed" size="xs" class="text-red-600" />
            Estado: bloqueado
        </div>
    </div>

    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @foreach (request()->except('blocked_search', 'blocked_page', 'view') as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach

        <div class="relative">
            <input type="hidden" name="view" value="blocked">
            <input type="text" name="blocked_search" value="{{ request('blocked_search') }}"
                placeholder="Buscar por nombre o email..."
                class="w-full pl-10 pr-4 py-2 rounded-lg border border-[var(--border)]
                      bg-[var(--card)] text-[var(--text)] text-sm
                      focus:ring-2 focus:ring-red-500 focus:border-transparent
                      placeholder:text-[var(--text-muted)] transition-all">
            <x-ui.icon name="buscar" size="sm"
                class="absolute left-3 top-2.5 text-[var(--text-muted)]" />
        </div>

        <div class="flex gap-2">
            <button type="submit"
                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg
                       bg-red-600 hover:bg-red-700 text-white text-sm font-medium
                       transition-colors">
                <x-ui.icon name="filtrar" size="sm" class="text-white" />
                Filtrar
            </button>
            @if (request('blocked_search'))
                <a href="{{ route('user-management.index', array_filter(request()->except('blocked_search', 'blocked_page'))) }}"
                    class="inline-flex items-center justify-center px-4 py-2 rounded-lg
                          border border-[var(--border)] text-[var(--text)] text-sm font-medium
                          hover:bg-[var(--border)]/5 transition-colors">
                    <x-ui.icon name="cerrar" size="sm" class="text-[var(--text)]" />
                    Limpiar
                </a>
            @endif
        </div>
    </form>

    <div id="blocked-users-container">
        @if ($blockedUsers->count() > 0)
            <div class="overflow-x-auto rounded-lg border border-[var(--border)]">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-[var(--border)]/5 border-b border-[var(--border)] text-[var(--text-muted)] uppercase tracking-[0.3em] text-xs">
                            <th class="px-6 py-3 text-left">Usuario</th>
                            <th class="px-6 py-3 text-left hidden md:table-cell">Email</th>
                            <th class="px-6 py-3 text-left hidden lg:table-cell">Rol</th>
                            <th class="px-6 py-3 text-center">Estado</th>
                            <th class="px-6 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--border)]">
                        @foreach ($blockedUsers as $user)
                            @php
                                $mainRole = $user->roles->first()->name ?? 'Sin rol';
                            @endphp
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-gradient-to-br from-red-500 to-rose-500
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
                                                bg-red-500/10 text-red-700 dark:text-red-300
                                                border border-red-500/30">
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
                <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-red-500/10 flex items-center justify-center">
                    <x-ui.icon name="lock-closed" size="xl" class="w-10 h-10 text-red-600 dark:text-red-400" />
                </div>
                <h4 class="text-lg font-semibold text-[var(--text)] mb-1">
                    No hay usuarios bloqueados
                </h4>
                <p class="text-sm text-[var(--text-muted)] mb-4">
                    Las cuentas bloqueadas aparecerán aquí una vez que se active el nuevo flujo.
                </p>
                <button type="button"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-red-500/30 text-red-600 text-sm font-semibold
                           hover:bg-red-500/10 transition-colors cursor-not-allowed opacity-70">
                    <x-ui.icon name="lock-open" size="sm" class="text-red-600" />
                    En desarrollo
                </button>
            </div>
        @endif
    </div>
</div>
