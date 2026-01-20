@props([
    'id' => 'theme-toggle',
    'size' => 'md',
    'label' => 'Cambiar tema',
])

@php
    $sizes = [
        'md' => ['w' => 'w-16', 'h' => 'h-9', 'knob' => 'w-7 h-7', 'icon' => 'w-4 h-4'],
        'lg' => ['w' => 'w-20', 'h' => 'h-11', 'knob' => 'w-9 h-9', 'icon' => 'w-5 h-5'],
    ];
    $sz = $sizes[$size] ?? $sizes['md'];
@endphp

<label class="theme-toggle-wrapper relative inline-flex items-center cursor-pointer select-none group"
    aria-label="{{ $label }}" data-size="{{ $size }}">
    {{-- input controlador (peer) --}}
    <input id="{{ $id }}-input" data-theme-toggle type="checkbox" class="sr-only" aria-label="{{ $label }}">

    {{-- pista (track) --}}
    <span
        class="track relative {{ $sz['w'] }} {{ $sz['h'] }} rounded-full overflow-hidden
                 transition-all duration-300 flex items-center px-1
                 shadow-md hover:shadow-xl
                 ring-2 ring-transparent group-hover:ring-[var(--accent)]/30
                 group-focus-visible:ring-[var(--accent)]">
        {{-- icono luna (izq) -> solo DARK --}}
        <span
            class="icon-moon absolute left-1.5 inline-flex items-center justify-center {{ $sz['icon'] }}
                     opacity-0 transition-all duration-300">
            <x-ui.icon name="luna" size="md" class="{{ $sz['icon'] }} fill-current" />
        </span>

        {{-- icono sol (der) -> solo LIGHT --}}
        <span
            class="icon-sun absolute right-1.5 inline-flex items-center justify-center {{ $sz['icon'] }}
                     opacity-100 transition-all duration-300">
            <x-ui.icon name="sol" size="md" class="{{ $sz['icon'] }} text-[var(--accent)]" />
        </span>

        {{-- Knob --}}
        <span
            class="knob relative z-10 {{ $sz['knob'] }} rounded-full
                     shadow-lg transform-gpu transition-all duration-300
                     group-hover:scale-105"></span>
    </span>
</label>

@once
    <style>
        /* === COLORES BASE === */
        .theme-toggle-wrapper .track {
            background: linear-gradient(135deg, #89b4d9 0%, #5a9fd4 100%);
        }

        .theme-toggle-wrapper .knob {
            background: #fdfbf7;
        }

        .theme-toggle-wrapper .icon-sun {
            color: #d4a574;
        }

        /* === DARK MODE === */
        html.dark .theme-toggle-wrapper .track {
            background: linear-gradient(135deg, #2c4a6b 0%, #1e3a5f 100%);
        }

        html.dark .theme-toggle-wrapper .knob {
            background: #e8dfd1;
        }

        html.dark .theme-toggle-wrapper .icon-moon {
            color: #a3c4e0;
        }

        /* === MOVIMIENTO DEL KNOB === */
        /* md */
        label[data-size="md"]>input:checked+.track .knob {
            transform: translateX(1.75rem);
        }

        /* lg */
        label[data-size="lg"]>input:checked+.track .knob {
            transform: translateX(2.25rem);
        }

        /* Hover scale */
        label[data-size="md"]:hover>input:checked+.track .knob,
        label[data-size="md"]>input:checked+.track .knob:hover {
            transform: translateX(1.75rem) scale(1.05);
        }

        label[data-size="lg"]:hover>input:checked+.track .knob,
        label[data-size="lg"]>input:checked+.track .knob:hover {
            transform: translateX(2.25rem) scale(1.05);
        }

        /* === CONMUTACIoN DE ICONOS === */
        label>input:checked+.track .icon-sun {
            opacity: 0;
            transform: scale(0.8) rotate(-90deg);
        }

        label>input:checked+.track .icon-moon {
            opacity: 1;
            transform: scale(1) rotate(0deg);
        }

        label>input:not(:checked)+.track .icon-sun {
            opacity: 1;
            transform: scale(1) rotate(0deg);
        }

        label>input:not(:checked)+.track .icon-moon {
            opacity: 0;
            transform: scale(0.8) rotate(90deg);
        }

        /* === HOVER EFFECTS === */
        .theme-toggle-wrapper:hover .track {
            filter: brightness(1.05);
        }

        .theme-toggle-wrapper:active .track {
            transform: scale(0.98);
        }

        /* === ANIMACIONES === */
        @keyframes spin-slow {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        @keyframes tilt {

            0%,
            100% {
                transform: rotate(0deg);
            }

            25% {
                transform: rotate(-5deg);
            }

            75% {
                transform: rotate(5deg);
            }
        }

        /* AnimaciÃ³n sutil al cambiar */
        label>input+.track {
            animation: none;
        }

        label>input:checked+.track {
            animation: tilt 0.3s ease-in-out;
        }
    </style>
@endonce
