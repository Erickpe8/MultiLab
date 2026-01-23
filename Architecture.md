# Organización del proyecto

Este repositorio sigue la estructura MVC de Laravel, con una capa administrativa basada en Filament y algunos helpers específicos para el laboratorio institucional.

## Estructura general
- `app/Http/Controllers/`: controladores HTTP (perfil, usuarios, temas, legal). Procesan requests, validan con Form Requests y delegan la vista o la respuesta JSON adecuada.  
- `app/Filament/Resources`: recursos que definen formularios, tablas, filtros y relaciones para préstamos, materiales, computadoras y aulas, actuando como interfaz real del laboratorio.  
- `app/Models`: entidades dominadas por la lógica de dominio (User, Loan, MaterialRequest, Computer, ClassroomLoan, AuditLog, etc.).  
- `app/Providers` + `app/Livewire/Filament`: configuran Livewire, layouts y componentes reutilizables. `app/View/Components` aloja los layouts base (`AppLayout`, `GuestLayout`) que usan la plantilla `resources/views/components/layouts`.  
- `resources/views`: vistas Blade para la parte pública (`auth`, `legal`, `profile`) y para la gestión (`usermanagement`, `components/layouts`). También se carga la lógica de notificaciones y el sidebar principal.

## Flujo de una petición típica
1. El usuario navega en una vista Blade o interactúa con Filament/Livewire.  
2. El `Controller` correspondiente valida la entrada (Form Request) y construye la respuesta.  
3. En Filament, las `Resource` y `Pages` usan el modelo Eloquent, definen formularios y guardan los datos directamente.  
4. `RoleHelper`, middleware `CheckAreaAccess` y el sistema de permisos de Spatie determinan el acceso por rol.  
5. Las respuestas vuelven a la vista, Filament, o se serializan como JSON para los scripts de usuario (modales de gestión, notificaciones).

## Capas auxiliares
- **Helpers** (`app/Helpers/RoleHelper`): centralizan comprobaciones de roles (`isSuperAdmin`, `isLabStaff`, `isEstudiante`).  
- **Middleware** (`app/Http/Middleware/CheckAreaAccess`): protege rutas adicionales con roles específicos.  
- **Livewire/Notify**: `resources/js/app.js` y `resources/js/user-management.js` extraen la lógica JS de los modales, usan `@vite` y consumen un pequeño componente `x-notify`.  
- **Docker**: `docker-compose.yml` y `Dockerfile` reproducen el entorno PHP/MySQL con Sail o Laragon.

## Estado de los UseCases
Aunque existen directorios planeados (`app/Application/UseCases`, `app/Domain`, `app/Infrastructure`), actualmente sólo contienen `.gitkeep`. La lógica se concentra en recursos Filament y controladores; esos directorios quedan como placeholders para una futura separación más estricta.

## Principios detectados
- **SRP/OCP**: cada Filament Resource se responsabiliza de un dominio (materiales, solicitudes, computadoras).  
- **DIP**: los controladores dependen de abstracciones (`RoleHelper`, middleware) que inyectan permisos sin acoplarse a clases concretas.  
- **Helper/Observer**: el sistema de auditorías (`RecordAudit` en `UserManagementController`) sigue un patrón sencillo de observación manual.
