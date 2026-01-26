# MultiLab

## Overview
MultiLab centraliza la gestión institucional del Laboratorio de Software B202 de la Fundación de Estudios Superiores Comfanorte, ofreciendo una sola plataforma que unifica las reservas del aula, el control de inventario físico y la trazabilidad del uso por parte de los distintos roles autorizados.

## Scope & Limits
El sistema da cobertura exclusiva a los procesos relacionados con la operación de B202, sus equipos, materiales asociados y los usuarios identificados en el ciclo académico, sin integrar otros laboratorios ni servicios externos a los dominios de aula, préstamo y usuarios autorizados.

## Problem Statement
Antes de MultiLab, el laboratorio carecía de un centro de control único para las reservas del aula, el seguimiento de los equipos y materiales, el control del inventario y la certificación de roles, lo que generaba registros fragmentados, ausencia de control de stock y dificultad para auditar la actividad de los usuarios.

## Solution Overview
MultiLab incorpora reserva de aula con asignación automática de estaciones, gestión de inventario con flujos de solicitud y retorno, control de usuarios con asignaciones de roles y aprobaciones, y auditoría completa para garantizar responsabilidad y trazabilidad en todas las operaciones del laboratorio.

## System Context
La solución se apoya en Laravel MVC extendido con Filament para la capa administrativa, Spatie Permissions y middleware de acceso para la autorización, y tanto Blade como Livewire para servir las vistas públicas y los paneles internos que son consumidos por docentes, auxiliares, estudiantes y superadministradores.

## Core Capabilities
El producto agrupa tres dominios: la programación y seguimiento del aula B202 con estados de reserva y sincronización de estaciones, la gestión del inventario y préstamo de materiales con aprobaciones, devoluciones parciales y alertas de vencimiento, y el ciclo de vida del usuario, incluyendo aprobación, bloqueo y auditoría; todo ejecutado mediante recursos Filament con autenticación Laravel Breeze.

## User Roles and Authorization
Se definen cuatro roles: `superadmin` administra totalmente el sistema, aprueba usuarios y accede a auditorías; `aux_admin` coordina inventario y operaciones administrativas; `docente` gestiona reservas y solicita préstamos; `estudiante` solicita materiales y consulta su historial; los chequeos de rol se centralizan en RoleHelper y se aplican por medio del middleware CheckAreaAccess apoyado en la infraestructura de hechos de Spatie.

## System Architecture
MultiLab combina Laravel, Filament y Livewire dentro de un patrón MVC claro: controladores en `app/Http/Controllers` reciben requests, delegan validaciones a form requests y retornan vistas Blade o respuestas JSON, mientras que los recursos en `app/Filament/Resources` modelan formularios, tablas y relaciones desde los modelos situados en `app/Models`, con helper y proveedores que aseguran consistencia y separación por dominio.

## Request Flow
Un usuario navega en una vista Blade o en el panel Filament, la solicitud pasa por rutas generales, controladores o resources que validan con Form Requests y aplican CheckAreaAccess/RoleHelper para autorizar, los modelos Eloquent realizan la lógica del dominio, se persiste en MySQL y la respuesta se renderiza en Blade o Livewire según el cliente.

## Technology Stack
La pila tecnológica respalda el backend en PHP 8.1 con Laravel 10, Breeze y Spatie Permissions, la capa frontend en Blade con componentes Livewire, y herramientas modernas de compilación y estilos.

### Backend
El backend usa PHP ^8.1, Laravel 10.50, Breeze para autenticación, Spatie `laravel-permission` para autorizaciones, MySQL como base de datos y un conjunto de helpers, middleware y recursos Filament para encapsular la lógica de negocios con pruebas que se ejecutan mediante PHPUnit y comandos Artisan.

### Frontend
En el frontend se combinan vistas Blade, Livewire/Filament para interfaces administrativas reactivas, Alpine.js para interactividad ligera, Tailwind CSS 3 junto con Flowbite para componentes UI y Vite 5 compila `resources/js/app.js`, `resources/js/user-management.js` y los estilos bajo el esquema `@vite`.

## Project Structure
La organización estándar de Laravel se mantiene con `app/` para controladores, recursos, modelos, helpers y middleware, `routes/` para web, auth y api, `resources/` para vistas y scripts, `database/` para migraciones/seeders, `public/` para la entrada `index.php` y assets compilados, y `tests/` para suites Feature/Unit que sustentan la calidad.

## Core Code Entities
Las entidades centrales reflejan el dominio de laboratorio y materiales, respaldadas por relaciones claras y estructuras Filament para gestionar vistas y filtros.

### Primary Models
Los modelos principales incluyen `User` (usuarios con roles y áreas, con relaciones hacia préstamos y aulas), `Loan` y `LoanMaterial` (seguimiento de préstamos físicos), `Material` (inventario con categorías y unidades), `ClassroomLoan` y `ClassroomLoanWorkstation` (reservas del aula B201 y asignación de estaciones), `Computer` y `ClassroomWorkstation` (inventario de PCs sincronizado) y `AuditLog` (registro de cambios con relación a usuarios).

### Filament Resources
Los recursos en `app/Filament/Resources` gestionan entidades críticas: `LoanResource` administra los préstamos físicos, `MaterialResource` controla el inventario y alertas de stock, `ClassroomLoanResource` coordina la agenda del aula y la asignación automática de PCs, `ComputerResource` mantiene el inventario de equipos y sincroniza estaciones de trabajo, y `UserResource` faculta la administración de usuarios, roles y estados desde formularios, tablas y acciones de aprobación.

### Controllers and Middleware
Los controladores clave (`UserManagementController`, `ProfileController`, `ProfileThemeController`, `LegalController`) cubren aprobación de usuarios, perfiles, temas y páginas legales, y la autorización se refuerza con el middleware `CheckAreaAccess` junto con RoleHelper y las políticas de Spatie; se registran audiciones a través de helpers/aplicaciones específicas.

## Configuration and Environment
La configuración se basa en `.env` con `APP_URL=http://127.0.0.1:8000`, conexión MySQL a `DB_DATABASE=multilab` con usuario `root`, sesiones y caché en disco, queue `sync`, mailer SMTP apuntando a `mailpit`, Vite enlazando variables Pusher, y rutas de cache y vistas en `/tmp/laravel-*` como alternativa a sistemas de archivos persistentes.

## Development Environment
El entorno local requiere PHP >= 8.1, Composer, Node.js >= 16, MySQL y se recomienda Laragon para Windows o el comando `php artisan serve`. Se instalan dependencias con `composer install` y `npm install`, se genera la clave de aplicación, se ejecutan migraciones y seeders y se levanta el servidor local; se usan credenciales de prueba con contraseña `Password123*` para superadmin, aux_admin, docente y estudiante.

## Key URLs and Entry Points
La autenticación inicia en `/login`, la gestión de usuarios en `/user-management`, el panel administrativo en Filament bajo `/filament`, mientras que `/terms` y `/privacy` alojan documentación legal, y `/profile` y `/dashboard` cubren la experiencia autenticada convencional; las rutas públicas y Filament entran por `routes/web.php` y `routes/auth.php`.

## Traceability & Audit
La trazabilidad se respalda con `AuditLog` y apoyos en los controllers mediante registros de cambios y observadores manuales, asegurando que todas las acciones críticas de reservas, préstamos y gestión de usuarios queden auditadas para cumplimientos institucionales.

## Legal Documentation & Compliance
Las políticas legales y de privacidad se entregan a través de `LegalController`, mientras que la autenticación con Laravel Breeze y la aplicación de roles de Spatie garantizan controles de acceso documentados y verificables para cada dominio operativo.

## Next Steps
Para profundizar en la instalación, consulta la guía Getting Started, en los flujos de aula o materiales dirige a sus apartados dedicados, y para auditoría, roles o la interfaz Filament revisa los documentos técnicos relacionados que complementan esta visión institucional.
