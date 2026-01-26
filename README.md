# MultiLab

## Table of Contents
- [MultiLab](#multilab)
  - [Table of Contents](#table-of-contents)
  - [Overview](#overview)
  - [System Surface](#system-surface)
  - [Access \& Authorization](#access--authorization)
  - [Technology Stack](#technology-stack)
  - [Getting Started](#getting-started)
  - [Documentation Set](#documentation-set)
  - [Operational References](#operational-references)
  - [Next Steps](#next-steps)

## Overview
MultiLab centraliza la operación del Laboratorio de Software B202 de la Fundación de Estudios Superiores Comfanorte, cogestionando reservas de aula, asignación automática de estaciones, inventario físico, préstamos, ciclo de vida de usuarios y auditoría para devolver trazabilidad institucional a un espacio que antes se gestionaba con registros fragmentados.

## System Surface
Los tres dominios principales —classroom management, material loans y user lifecycle— se articulan mediante Filament Resources que alimentan formularios y tablas autorizadas por Spatie Permissions; los modelos centrales (User, Loan, Material, ClassroomLoan, Computer, AuditLog) representan los objetos del laboratorio y conectan con Filament para mostrar estados, filtros, acciones de aprobación y sincronización con estaciones de trabajo.

## Access & Authorization
El sistema reconoce cuatro roles: `superadmin` controla todo el sistema, aprueba usuarios y accede a auditorías; `aux_admin` coordina inventario y autorizaciones; `docente` administra reservas de aula y solicitudes de materiales; `estudiante` gestiona solicitudes personales. RoleHelper y el middleware CheckAreaAccess centralizan verificaciones de rol sobre cada solicitud antes de delegar en recursos o controladores.

## Technology Stack
El backend corre sobre PHP ^8.1 con Laravel 10.50, Breeze para autenticación y Spatie `laravel-permission` para autorizaciones, mientras que el frontend usa Blade con Livewire/Filament, Alpine.js, TailwindCSS, Flowbite y Vite que compila `resources/js/app.js`, `resources/js/user-management.js` y los estilos institucionales; MySQL gestiona la persistencia definida en `.env`.

## Getting Started
Se requiere PHP 8.1+, Composer, Node.js 16+, MySQL y Laragon o un host local equivalente; la puesta en marcha implica instalar dependencias (`composer install`, `npm install`), copiar `.env.example`, generar la clave (`php artisan key:generate --ansi`), crear la base `multilab`, ejecutar migraciones y seeders (`php artisan migrate --seed`) y levantar el servidor (`php artisan serve` o la configuración de Laragon). Se refiere a `docs/local-setup.md` para la guía completa y recomendaciones operativas.

## Documentation Set
- `docs/architecture.md`: descripción técnica profunda de la organización del proyecto, flujo de peticiones, modelo de dominio, helpers y reglas de trazabilidad.  
- `docs/local-setup.md`: pasos detallados para instalar la aplicación en Laragon/Windows, verificación de servicios y mantenimiento diario.  
- `docs/features/`: N/A (no documentado en las fuentes disponibles).

## Operational References
El front se despliega en `/dashboard`, la autenticación inicia en `/login`, los términos y la política de privacidad en `/terms` y `/privacy`, y la administración sucede en `/user-management` y `/filament` con rutas definidas en `routes/web.php` y `routes/auth.php`; los controladores clave (UserManagementController, ProfileController, LegalController) y el middleware `CheckAreaAccess` consolidan seguridad y respuesta institucional.

## Next Steps
Para detalles de arquitectura o flujos de negocio avanzados se remite a `docs/architecture.md`, y para pruebas de entorno y puesta en marcha a `docs/local-setup.md`; la documentación de características específicas de classroom, loans, materials, users o audit se encuentra pendiente (N/A) hasta que se validen fuentes adicionales.
