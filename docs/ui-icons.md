# Sistema de íconos compartidos

1. **Mapa de alias**: el archivo `config/icons.php` almacena los alias en español. Para agregar uno nuevo, solo agrega una línea `'alias' => 'heroicon-o-nombre'` (o el prefijo que corresponda) en el array `aliases`.

2. **Componente reutilizable**: usa `<x-ui.icon name="alias" />` dentro de tus vistas. El componente aplica tamaños, variantes y atributos accesibles automáticamente.

3. **Extensiones**: si necesitas un conjunto de iconos distinto o quieres cambiar los valores por defecto, ajusta `config/blade-icons.php` y vuelve a publicar la configuración con `php artisan vendor:publish --tag=blade-icons`.

4. **Uso rápido**:
   - `<x-ui.icon name="editar" size="sm" variant="primary" />`
   - `<x-ui.icon name="heroicon-o-bell" solid />`
   - Para accesibilidad, pasa `title="Texto descriptivo"`; si no se indica, el icono se renderiza como `aria-hidden="true"`.
