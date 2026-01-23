<laravel-boost-guidelines>
=== reglas de base ===

# Lineamientos de Laravel Boost (Claude)

Estas reglas complementan a GEMINI; abordan buenas prácticas dentro del ecosistema Laravel usado por MultiLab.

## Paquetes clave
- PHP 8.3.28  
- Laravel v10  
- Filament v3  
- Livewire v3  
- Breeze v1  
- Sanctum v3  
- Pint v1  
- Sail v1  
- PHPUnit v10  
- Alpine.js v3  
- Tailwind CSS v3

## Procedimientos generales
- Apóyate en la estructura existente antes de crear carpetas nuevas.  
- Busca componentes reutilizables.  
- Sé conciso en las respuestas.  
- No guardes datos temporales en docs; solo genera nuevos archivos si hay una solicitud explícita.

=== reglas de Boost ===

## Herramientas auxiliares
- Usa `list-artisan-commands` para explorar parámetros.  
- Usa `get-absolute-url` al compartir enlaces del entorno local.  
- Usa `tinker` o `database-query` para investigar datos.  
- Usa `browser-logs` para revisar errores recientes.

## Búsqueda de documentación
- Usa `search-docs` con consultas simples.  
- Haz combinaciones de palabras, frases exactas, filtros y arreglos de queries.  
- No incluyas nombres de paquetes; el filtro ya está activo.

=== reglas PHP ===

## Formato y tipado
- Usa llaves incluso en bloques de una línea.  
- Usa promoción de propiedades en constructores.  
- Declara tipos en parámetros y retornos.  
- Mantén PHPDoc con formas de datos cuando corresponda.  
- Numera enums con TitleCase.

=== reglas Sail ===

## Trabajo dentro de Sail
- Ejecuta todo con `vendor/bin/sail`.  
- Usa `sail up -d`, `sail stop`, `sail artisan ...`, `sail npm ...`.  
- Consulta `vendor/bin/sail` sin argumentos para ver comandos disponibles.

=== pruebas ===

## Testeo continuo
- Corre `vendor/bin/sail artisan test --compact` tras cada cambio relevante.  
- Ejecuta solo los tests necesarios para ahorrar tiempo.

=== Laravel core ===

## Convenciones oficiales
- Usa `sail artisan make:*`.  
- Prefiere relaciones Eloquent a consultas raw.  
- Usa Form Requests y crea validations detalladas.  
- Usa `ShouldQueue` en jobs pesados.  
- Mantén la configuración via `config(...)`.  
- Genera URLs con `route()`.  
- Aplica `pint` (`sail bin pint --dirty`) antes de entregar (sin `--test`).

=== Laravel v10 ===

## Consideraciones específicas
- Middleware en `app/Http/Kernel.php`, providers en `app/Providers`.  
- Los casts se definen en `$casts`.  
- Usa `search-docs` para resolver dudas de versión.

=== Livewire v3 ===

## Modernizaciones
- Usa `wire:model.live`, `App\Livewire`, `$this->dispatch()`.  
- Adopta directives como `wire:show`, `wire:transition`, `wire:cloak`, `wire:offline`, `wire:target`.  
- Alpine ya viene incluido (persist, intersect, collapse, focus).  
- Escucha `livewire:init` y maneja eventos `fail.status === 419`.

=== Tailwind CSS 3 ===

## Estilo actual
- Reutiliza clases y mantén la jerarquía.  
- Prefiere utilidades `gap` para espaciado.  
- Asegura compatibilidad con `dark:`.  
- Usa sólo clases válidas en Tailwind CSS 3.
</laravel-boost-guidelines>
