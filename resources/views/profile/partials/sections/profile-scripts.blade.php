    <script src="https://unpkg.com/cropperjs@1.5.13/dist/cropper.min.js"></script>
    <script>
        /**
         * Aplica el tema al documento según la opción indicada.
         * Entradas: theme (string) valor 'light', 'dark' o 'system' para aplicar.
         * Salidas: void (sin retorno).
         */
        window.applyProfileTheme = function (theme) {
            const root = document.documentElement;
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            const shouldUseDark = theme === 'dark' || (theme === 'system' && prefersDark);
            root.classList.toggle('dark', shouldUseDark);
        };

        /**
         * Ajusta el tema inicial y muestra notificaciones según el estado del servidor.
         * Entradas: Ninguna.
         * Salidas: void (sin retorno).
         */
        document.addEventListener('DOMContentLoaded', () => {
            const storedTheme = localStorage.getItem('theme');
            const serverTheme = @json($user->theme ?? 'system');
            const initialTheme = storedTheme ?? serverTheme;
            window.applyProfileTheme(initialTheme);
            localStorage.setItem('theme', initialTheme);


            @if ($errors->any())
                if (typeof showNotification === 'function') {
                    showNotification('Corrige los campos marcados y vuelve a intentarlo.', 'error');
                }
            @endif
        });
    </script>
