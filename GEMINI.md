<laravel-boost-guidelines>
=== reglas base ===

# Directrices Laravel Boost

Este documento resume las reglas de Laravel Boost para MultiLab. Respeta las versiones indicadas al trabajar en el proyecto.

## Paquetes fundamentales
- PHP 8.3.28  
- Laravel/framework v10  
- Filament v3  
- Livewire v3  
- Laravel Breeze v1  
- Laravel Sanctum v3  
- Laravel Pint v1  
- Laravel Sail v1  
- PHPUnit v10  
- Alpine.js v3  
- Tailwind CSS v3

## Convenciones generales
- Sigue la estructura de los archivos vecinos antes de crear nuevos.  
- Usa nombres descriptivos y reutiliza componentes ya existentes.  
- Evita scripts ad-hoc si las pruebas automatizadas ya cubren la funcionalidad.

## Bundling y documentación
- Si un cambio en el frontend no se refleja, ejecuta `vendor/bin/sail npm run dev`, `vendor/bin/sail npm run build` o `vendor/bin/sail composer run dev`.  
- Usa `search-docs` antes de buscar documentación externa.  
- Crea documentación adicional solo si el usuario la solicita.

=== reglas boost ===

## Laravel Boost y herramientas
- Usa las herramientas del servidor MCP (Artisan, comandos de control, logs).  
- Usa `list-artisan-commands` antes de invocar comandos desconocidos.  
- Al compartir URLs, obtén el enlace absoluto con `get-absolute-url`.  
- Usa `tinker` para depurar PHP y `database-query` para lecturas puntuales.  
- Si necesitas ver errores del navegador, consulta `browser-logs`.

## Búsqueda avanzada
- Lanza varias consultas simples con `search-docs` (palabras simples, frases, combinaciones).  
- Evita mencionar nombres de paquetes dentro de la consulta; ya están filtrados.

=== reglas PHP ===

## Estándares de PHP
- Usa llaves en todos los bloques de control.  
- Prefiere constructor con propiedad promovida.  
- Declara tipos explícitos para parámetros y retornos.  
- Usa PHPDoc detallado (formas de arrays, etc.).  
- Enumera valores con TitleCase.

=== reglas Sail ===

## Laravel Sail
- Ejecuta todos los comandos con `vendor/bin/sail` (`sail up`, `sail stop`, `sail artisan`, `sail npm`).  
- Instala dependencias y ejecuta Artisan/List with `vendor/bin/sail`.

=== pruebas ===

## Test
- Cada cambio debe pasar pruebas: `vendor/bin/sail artisan test --compact`.  
- Corre solo los tests necesarios tras el cambio.

=== laravel/core ===

## Haz las cosas al estilo Laravel
- Usa make commands (`sail artisan make:*`).  
- Usa relaciones Eloquent y evita `DB::`.  
- Crea factories y seeders al levantar nuevos modelos.  
- Usa Form Requests para validaciones.  
- Implementa `ShouldQueue` en jobs pesados.  
- Prefiere gates y policies para autorizaciones.  
- Usa `route()` para URLs y `config()` en vez de `env()` directo.  
- Corre `pint` (`vendor/bin/sail bin pint --dirty`) antes de finalizar y evita `--test`.

=== laravel v10 ===

## Laravel 10
- Middleware en `app/Http/Kernel.php`, providers en `app/Providers/`.  
- Usa `$casts = []` en los modelos (no `casts()`).  
- Usa `search-docs` para versiones específicas.

=== livewire v3 ===

## Livewire 3
- Usa `wire:model.live` y namespace `App\Livewire`.  
- Usa `$this->dispatch()` para eventos.  
- Emplea hooks (`mount`, `updatedFoo`) y directivas modernas.
- Alpine ya viene incluido; usa sus plugins permitidos.

=== tailwindcss ===

## Tailwind CSS 3
- Respeta clases existentes, extrae patrones a componentes y prefiere gaps en lugar de márgenes.  
- Asegura compatibilidad con modo oscuro (`dark:`).  
- Solo usa clases admitidas por Tailwind v3.
</laravel-boost-guidelines>
