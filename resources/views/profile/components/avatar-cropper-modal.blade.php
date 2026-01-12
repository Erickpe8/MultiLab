@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css">
@endpush

<!-- Overlay -->
<div x-cloak x-show="cropOpen" x-transition.opacity class="fixed inset-0 z-[9999] bg-black/60 backdrop-blur-sm"
    @click="closeCropper()" aria-hidden="true"></div>

<!-- Modal -->
<div x-cloak x-show="cropOpen" x-transition:enter="transition ease-out duration-150"
    x-transition:enter-start="opacity-0 translate-y-2 scale-[0.98]"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-120"
    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
    x-transition:leave-end="opacity-0 translate-y-2 scale-[0.98]"
    class="fixed inset-0 z-[10000] grid place-items-center p-3 sm:p-4" @keydown.window.escape="closeCropper()"
    role="dialog" aria-modal="true" aria-labelledby="cropperTitle">

    <div class="w-full max-w-[420px] overflow-hidden rounded-2xl border border-[color:var(--border)] bg-[color:var(--bg)] shadow-2xl"
        @click.stop>

        <!-- Header -->
        <div
            class="flex items-start justify-between gap-3 border-b border-[color:var(--border)] px-4 py-3 bg-[color:var(--bg)]">
            <div class="min-w-0">
                <h3 id="cropperTitle" class="text-base font-semibold text-[var(--text)]">Recorta tu avatar</h3>
                <p class="mt-0.5 text-xs text-[color:var(--text-muted)] leading-snug">
                    Arrastra para centrar y usa los controles para ajustar.
                </p>
            </div>

            <button type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-[color:var(--border)] bg-[color:var(--bg)]
                       text-[color:var(--text-muted)] hover:bg-[color:var(--border)]/25 hover:text-[var(--text)]
                       focus:outline-none focus:ring-2 focus:ring-[var(--accent)]" @click="closeCropper()">
                <span class="sr-only">Cerrar</span>
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M10 8.586l4.95-4.95a1 1 0 111.414 1.415L11.414 10l4.95 4.95a1 1 0 01-1.414 1.414L10 11.414l-4.95 4.95a1 1 0 01-1.414-1.414l4.95-4.95-4.95-4.95a1 1 0 011.414-1.415L10 8.586z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="max-h-[calc(100vh-10rem)] overflow-y-auto px-4 py-3 space-y-3 bg-[color:var(--bg)]">
            <div id="cropperStage" class="relative mx-auto w-full max-w-[360px] aspect-square overflow-hidden rounded-xl
                       border border-dashed border-[color:var(--border)] bg-[color:var(--bg)]">
                <img id="cropperImage" :src="cropperImageSrc" alt="Vista previa de recorte"
                    class="block h-full w-full object-contain" />
            </div>

            <!-- Controls -->
            <div class="flex flex-wrap gap-2 justify-center">
                <button type="button"
                    class="inline-flex items-center gap-2 rounded-lg border border-[color:var(--border)] bg-[color:var(--bg)] px-3 py-2 text-xs font-semibold text-[var(--text)]
                           hover:bg-[color:var(--border)]/25 transition focus:outline-none focus:ring-2 focus:ring-[var(--accent)]"
                    @click="zoomOut()">
                    Zoom -
                </button>

                <button type="button"
                    class="inline-flex items-center gap-2 rounded-lg border border-[color:var(--border)] bg-[color:var(--bg)] px-3 py-2 text-xs font-semibold text-[var(--text)]
                           hover:bg-[color:var(--border)]/25 transition focus:outline-none focus:ring-2 focus:ring-[var(--accent)]"
                    @click="zoomIn()">
                    Zoom +
                </button>

                <button type="button"
                    class="inline-flex items-center gap-2 rounded-lg border border-[color:var(--border)] bg-[color:var(--bg)] px-3 py-2 text-xs font-semibold text-[var(--text)]
                           hover:bg-[color:var(--border)]/25 transition focus:outline-none focus:ring-2 focus:ring-[var(--accent)]"
                    @click="rotateLeft()">
                    Rotar
                </button>

                <button type="button"
                    class="inline-flex items-center gap-2 rounded-lg border border-[color:var(--border)] bg-[color:var(--bg)] px-3 py-2 text-xs font-semibold text-[var(--text)]
                           hover:bg-[color:var(--border)]/25 transition focus:outline-none focus:ring-2 focus:ring-[var(--accent)]"
                    @click="resetCrop()">
                    Reset
                </button>
            </div>

            <p class="text-center text-[11px] text-[color:var(--text-muted)]">
                Consejo: encuadra el rostro dentro del círculo.
            </p>
        </div>

        <!-- Footer -->
        <div class="border-t border-[color:var(--border)] px-4 py-3 bg-[color:var(--bg)]">
            <div class="flex flex-col gap-2 sm:flex-row sm:justify-end">
                <button type="button" class="w-full sm:w-auto inline-flex items-center justify-center rounded-lg
                           border border-[color:var(--border)] bg-[color:var(--bg)] px-4 py-2.5 text-sm font-semibold
                           text-[var(--text)] hover:bg-[color:var(--border)]/25 transition
                           focus:outline-none focus:ring-2 focus:ring-[var(--accent)]" @click="closeCropper()">
                    Cancelar
                </button>

                <button type="button" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg
                           bg-[var(--accent)] px-4 py-2.5 text-sm font-semibold text-white
                           hover:bg-[var(--primary)] transition
                           focus:outline-none focus:ring-2 focus:ring-[var(--accent)]" @click="applyCrop()">
                    Recortar y usar
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>
@endpush
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css">
@endpush

<!-- Overlay -->
<div x-cloak x-show="cropOpen" x-transition.opacity class="fixed inset-0 z-[9999] bg-black/60 backdrop-blur-sm"
    @click="closeCropper()" aria-hidden="true"></div>

<!-- Modal -->
<div x-cloak x-show="cropOpen" x-transition:enter="transition ease-out duration-150"
    x-transition:enter-start="opacity-0 translate-y-2 scale-[0.98]"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-120"
    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
    x-transition:leave-end="opacity-0 translate-y-2 scale-[0.98]"
    class="fixed inset-0 z-[10000] grid place-items-center p-3 sm:p-4" @keydown.window.escape="closeCropper()"
    role="dialog" aria-modal="true" aria-labelledby="cropperTitle">

    <div class="w-full max-w-[420px] overflow-hidden rounded-2xl border border-[color:var(--border)] bg-[color:var(--bg)] shadow-2xl"
        @click.stop>

        <!-- Header -->
        <div
            class="flex items-start justify-between gap-3 border-b border-[color:var(--border)] px-4 py-3 bg-[color:var(--bg)]">
            <div class="min-w-0">
                <h3 id="cropperTitle" class="text-base font-semibold text-[var(--text)]">Recorta tu avatar</h3>
                <p class="mt-0.5 text-xs text-[color:var(--text-muted)] leading-snug">
                    Arrastra para centrar y usa los controles para ajustar.
                </p>
            </div>

            <button type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-[color:var(--border)] bg-[color:var(--bg)]
                       text-[color:var(--text-muted)] hover:bg-[color:var(--border)]/25 hover:text-[var(--text)]
                       focus:outline-none focus:ring-2 focus:ring-[var(--accent)]" @click="closeCropper()">
                <span class="sr-only">Cerrar</span>
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M10 8.586l4.95-4.95a1 1 0 111.414 1.415L11.414 10l4.95 4.95a1 1 0 01-1.414 1.414L10 11.414l-4.95 4.95a1 1 0 01-1.414-1.414l4.95-4.95-4.95-4.95a1 1 0 011.414-1.415L10 8.586z"
                        clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="max-h-[calc(100vh-10rem)] overflow-y-auto px-4 py-3 space-y-3 bg-[color:var(--bg)]">
            <div id="cropperStage" class="relative mx-auto w-full max-w-[360px] aspect-square overflow-hidden rounded-xl
                       border border-dashed border-[color:var(--border)] bg-[color:var(--bg)]">
                <img id="cropperImage" :src="cropperImageSrc" alt="Vista previa de recorte"
                    class="block h-full w-full object-contain" />
            </div>

            <!-- Controls -->
            <div class="flex flex-wrap gap-2 justify-center">
                <button type="button"
                    class="inline-flex items-center gap-2 rounded-lg border border-[color:var(--border)] bg-[color:var(--bg)] px-3 py-2 text-xs font-semibold text-[var(--text)]
                           hover:bg-[color:var(--border)]/25 transition focus:outline-none focus:ring-2 focus:ring-[var(--accent)]"
                    @click="zoomOut()">
                    Zoom -
                </button>

                <button type="button"
                    class="inline-flex items-center gap-2 rounded-lg border border-[color:var(--border)] bg-[color:var(--bg)] px-3 py-2 text-xs font-semibold text-[var(--text)]
                           hover:bg-[color:var(--border)]/25 transition focus:outline-none focus:ring-2 focus:ring-[var(--accent)]"
                    @click="zoomIn()">
                    Zoom +
                </button>

                <button type="button"
                    class="inline-flex items-center gap-2 rounded-lg border border-[color:var(--border)] bg-[color:var(--bg)] px-3 py-2 text-xs font-semibold text-[var(--text)]
                           hover:bg-[color:var(--border)]/25 transition focus:outline-none focus:ring-2 focus:ring-[var(--accent)]"
                    @click="rotateLeft()">
                    Rotar
                </button>

                <button type="button"
                    class="inline-flex items-center gap-2 rounded-lg border border-[color:var(--border)] bg-[color:var(--bg)] px-3 py-2 text-xs font-semibold text-[var(--text)]
                           hover:bg-[color:var(--border)]/25 transition focus:outline-none focus:ring-2 focus:ring-[var(--accent)]"
                    @click="resetCrop()">
                    Reset
                </button>
            </div>

            <p class="text-center text-[11px] text-[color:var(--text-muted)]">
                Consejo: encuadra el rostro dentro del círculo.
            </p>
        </div>

        <!-- Footer -->
        <div class="border-t border-[color:var(--border)] px-4 py-3 bg-[color:var(--bg)]">
            <div class="flex flex-col gap-2 sm:flex-row sm:justify-end">
                <button type="button" class="w-full sm:w-auto inline-flex items-center justify-center rounded-lg
                           border border-[color:var(--border)] bg-[color:var(--bg)] px-4 py-2.5 text-sm font-semibold
                           text-[var(--text)] hover:bg-[color:var(--border)]/25 transition
                           focus:outline-none focus:ring-2 focus:ring-[var(--accent)]" @click="closeCropper()">
                    Cancelar
                </button>

                <button type="button" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg
                           bg-[var(--accent)] px-4 py-2.5 text-sm font-semibold text-white
                           hover:bg-[var(--primary)] transition
                           focus:outline-none focus:ring-2 focus:ring-[var(--accent)]" @click="applyCrop()">
                    Recortar y usar
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>
@endpush
