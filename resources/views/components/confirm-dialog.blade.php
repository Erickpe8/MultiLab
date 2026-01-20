@props([
    'id',
    'title' => '¿Confirmar acción?',
    'message' => 'Esta operación no se puede deshacer.',
    'confirmText' => 'Sí, continuar',
    'cancelText' => 'Cancelar',
    'icon' => 'info',
    'confirmEvent' => null,
])

<div x-data="{ open: false }" x-on:window.open-confirm-{{ $id }}.window="open = true" x-show="open"
    x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4" aria-modal="true" role="dialog">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/50" @click="open=false"></div>

    <!-- Dialog -->
    <div x-transition
        class="relative w-full max-w-md rounded-2xl border border-[color:var(--border)]
            bg-[var(--card)] p-6 shadow-2xl">
        @php
            $mappedIcon = match ($icon) {
                'danger' => 'error',
                'warn' => 'advertencia',
                default => 'info',
            };
        @endphp
        <div class="flex items-start gap-3">
            <div
                class="mt-0.5 shrink-0 inline-flex items-center justify-center w-10 h-10 rounded-full
                @if ($icon === 'danger') bg-red-500/15 text-red-500
                @elseif($icon === 'warn') bg-yellow-500/15 text-yellow-500
                @else bg-[var(--accent)]/15 text-[var(--accent)] @endif">
                <!-- ícono -->
                <x-ui.icon name="{{ $mappedIcon }}" size="xl" class="w-10 h-10" />
            </div>
            <div>
                <h3 class="text-lg font-bold text-[var(--text)]">{{ $title }}</h3>
                <p class="mt-1 text-sm text-[color:var(--text)]/70">{{ $message }}</p>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-2">
            <button type="button" @click="open=false"
                class="px-4 py-2 rounded-lg border border-[color:var(--border)]
                    hover:bg-[color:var(--border)]/20 text-sm">
                {{ $cancelText }}
            </button>

            <button type="button"
                @click="
                        open=false;
                        @if ($confirmEvent) window.dispatchEvent(new CustomEvent('{{ $confirmEvent }}')) @endif
                    "
                class="px-4 py-2 rounded-lg bg-[var(--accent)] text-white hover:bg-[var(--primary)]
                    text-sm font-semibold">
                {{ $confirmText }}
            </button>
        </div>
    </div>
</div>
