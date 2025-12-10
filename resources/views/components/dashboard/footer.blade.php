{{--
Footer component for the dashboard.
Contains copyright information, quick links and a button to open
the developer team modal.
--}}
<footer
    class="bg-multilab-light/50 dark:bg-multilab-darkblue/20 border-t border-multilab-gray dark:border-multilab-darkblue px-4 py-4 flex flex-col md:flex-row items-center justify-between text-sm">
    <div class="text-multilab-dark dark:text-multilab-gray mb-2 md:mb-0">
        © 2025 Multilab FESC
    </div>
    <div class="flex items-center space-x-4 text-multilab-dark dark:text-multilab-gray">
        <a href="{{ route('policies.index') }}"
            class="hover:text-multilab-blue dark:hover:text-multilab-darkblue">Políticas del Sistema</a>
        <a href="{{ route('privacy.index') }}"
            class="hover:text-multilab-blue dark:hover:text-multilab-darkblue">Privacidad de Datos</a>
        {{-- Button to open the developer team dialog --}}
        <button id="openTeamDialog" type="button" class="btn-primary text-sm">Equipo Desarrollador</button>
    </div>
</footer>
