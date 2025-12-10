{{--
Developer team modal.
Hidden by default; toggled with vanilla JavaScript.
--}}
<div id="teamDialog" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
    <div
        class="bg-white dark:bg-multilab-dark border-2 border-multilab-gray dark:border-multilab-darkblue rounded-lg p-6 w-11/12 max-w-md shadow-soft">

        <h2 class="text-xl font-semibold mb-4 text-multilab-blue dark:text-multilab-gray">
            Equipo Desarrollador
        </h2>

        <ul class="space-y-2 text-multilab-dark dark:text-multilab-gray">
            <li>Erick Sebastián Pérez Carvajal</li>
            <li>David Arturo Aceros Ortiz</li>
            <li>Carlos José Mantilla Cote</li>
        </ul>

        <div class="mt-6 text-right">
            <button id="closeTeamDialog" type="button"
                class="px-4 py-2 bg-multilab-blue text-white rounded hover:bg-multilab-darkblue transition">
                Cerrar
            </button>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        (() => {
            const openButton = document.getElementById('openTeamDialog');
            const dialog = document.getElementById('teamDialog');
            const closeButton = document.getElementById('closeTeamDialog');

            if (openButton && dialog && closeButton) {
                openButton.addEventListener('click', () => dialog.classList.remove('hidden'));
                closeButton.addEventListener('click', () => dialog.classList.add('hidden'));
            }
        })();
    </script>
@endpush
