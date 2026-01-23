# README.md para MultiLab
![Laravel](https://img.shields.io/badge/Laravel-10.50.0-red) ![PHP](https://img.shields.io/badge/PHP-%5E8.1-blue) ![Docker](https://img.shields.io/badge/Docker-supported-blue) ![MySQL](https://img.shields.io/badge/MySQL-supported-orange)

MultiLab es la plataforma institucional de la Fundación de Estudios Superiores Comfanorte (FESC) que centraliza la administración del Laboratorio de Software B201. Permite reservar el aula, controlar el inventario físico de la bodega, auditar los préstamos y mantener un historial de uso con roles afinados para docentes, estudiantes y auxiliares administrativos.

## Tabla de contenidos
- [Sobre el proyecto](#sobre-el-proyecto)
- [Arquitectura](#arquitectura)
- [Stack tecnológico](#stack-tecnológico)
- [Equipo y responsabilidades](#equipo-y-responsabilidades)
- [Instalación y configuración](#instalación-y-configuración)
- [Uso del sistema](#uso-del-sistema)
- [Estructura del proyecto](#estructura-del-proyecto)
- [Contribución](#contribución)
- [Licencia](#licencia)

## Sobre el proyecto
- **Problema**: El laboratorio B201 carece de un punto único de control para reservar el aula, supervisar los PCs, gestionar préstamos de materiales y certificar los roles, lo que provoca registros fragmentados y falta de control de stock.  
- **Usuarios principales**:  
  - `superadmin`: administra el sistema completo (panel de usuarios, auditoría).  
  - `aux_admin`: coordina inventario, autorizaciones y reportes internos.  
  - `docente`: solicita el aula, registra observaciones y actualiza estados del préstamo.  
  - `estudiante`: solicita materiales, registra devoluciones y consulta su historial.
- **Funcionalidades clave**:  
  - Reservas del aula B201 con control de estado (pendiente, aprobado, cancelado, en uso y finalizado) y cálculo automático de PCs disponibles.  
  - Panel administrativo en Filament para administrar préstamos, materiales, solicitudes y estaciones de trabajo.  
  - Control del inventario físico: catálogo, solicitudes de materiales, observaciones y movimientos del stock.  
  - Gestión completa de usuarios (aprobación, bloqueo, roles, auditoría) desde vistas personalizadas y formularios con modales.  
  - Autenticación Laravel Breeze, validación de perfiles y cambio de tema persistente vía `ProfileThemeController`.

## Arquitectura
- **Patrón**: MVC nativo de Laravel con Filament para la capa administrativa. Controllers (`app/Http/Controllers/`) reciben las peticiones, delegan validaciones a Form Requests y retornan vistas Blade o JSON; Filament Resources (`app/Filament/Resources`) encapsulan formularios, tablas y relaciones del dominio.  
- **Organización backend**:  
  - `app/Http/Controllers`: lógica HTTP (perfil, legal, user management).  
  - `app/Filament`: recursos, páginas y preocupaciones reusable (`Concerns\HasAppLayout`).  
  - `app/Models`: entidades clave (User, Loan, Material, Computer, ClassroomLoan …) con relaciones y casts.  
  - `app/Providers`: binding de servicios Livewire, permisos y rutas.  
- **Flujo de datos**: Vista Blade / Filament → Controller/FormRequest → Model/Filament Resource → Repository Eloquent → Decoración de vista o respuesta JSON. El middleware `check.area` protege rutas sensibles y `RoleHelper` centraliza decisiones de rol.  
- **Principios SOLID**:  
  - SRP: cada Filament Resource se enfoca en un dominio (materiales, préstamos, computadoras).  
  - OCP/DIP: `RoleHelper` y `CheckAreaAccess` exponen abstracciones reutilizables sin acoplamientos directos a permisos específicos.  
- **Patrones detectados**: Observers/eventos para notificaciones, uso de helper (RoleHelper) y servicios breves (ProfileThemeController) que actúan como adaptadores de estado.

## Stack tecnológico
- **Backend**: PHP ^8.1 con Laravel 10.50.0, Laravel Breeze para autenticación y `spatie/laravel-permission` para autorización por roles.  
- **Frontend**: Blade (layouts, componentes UI), Livewire/Filament para interactividad, TailwindCSS 3 y Flowbite como conjunto de estilos reutilizados; Vite compila `resources/js/app.js`, `resources/js/user-management.js` y `resources/css/app.css`.  
- **Base de datos**: MySQL (definido en `.env`: `DB_CONNECTION=mysql`).  
- **DevOps**: Docker Compose (`docker-compose.yml` + `Dockerfile`) apoya entornos replicables; también hay scripts para CLI (php artisan, npm).  
- **Herramientas**: Laragon (desarrollo local en Windows), DBeaver (consultas MySQL), Composer, npm, Git, PHPStorm/VSCode y Vite como bundler.

## Equipo y responsabilidades
| Colaborador | Rol | Módulos / Responsabilidades |
|-------------|-----|-----------------------------|
| Erick Sebastián Pérez Carvajal | Arquitecto / Full Stack | Diseño de la arquitectura MVC+Filament, seguridad (roles/permissions), pruebas, seeders institucionales. |
| David Arturo Aceros Ortiz | Backend / Inventarios | Componentes de préstamos de materiales, inventario de bodega, devoluciones, observaciones y estados de materiales. |
| Carlos José Mantilla Cote | Backend / Aula B201 | Reserva del aula, gestión de PCs, historial de sesiones y validaciones de exclusividad para docentes. |

## Instalación y configuración
1. **Requisitos previos**: PHP >=8.1, Composer, Node.js (>=16), MySQL, Docker (opcional) y Laragon (Windows).  
2. **Clonar**: `git clone <repositorio> MultiLab && cd MultiLab`.  
3. **Entorno**: `cp .env.example .env` y ajustar `APP_URL`, `DB_*`, `MAIL_*`, claves y `PROFILE_THEME`.  
4. **Dependencias**:  
   - `composer install`  
   - `npm install`  
5. **Migraciones**: `php artisan migrate`.  
6. **Seeders**: `php artisan db:seed --class=RoleSeeder` (permite cargar roles) y `php artisan db:seed --class=UserSeeder`.  
7. **Servidores**:  
   - Laravel (local): `php artisan serve`.  
   - Docker: `docker compose up -d` con servicios `app` y `db`.  

## Uso del sistema
- **Credenciales de prueba** (todos usan contraseña `Password123*` definida en `database/seeders/UserSeeder.php`):  
  - Superadmin: `admin@fesc.edu.co`.  
  - Auxiliar administrativo: `director@fesc.edu.co`.  
  - Auxiliar adicional: `auxiliar@fesc.edu.co`.  
  - Docente: `docente@fesc.edu.co`.  
  - Estudiante: `estudiante1@fesc.edu.co`.  
- **Comandos principales**:  
  - `php artisan migrate:fresh --seed`  
  - `php artisan optimize:clear`  
  - `npm run dev` (desarrollo) / `npm run build` (producción)  
  - `php artisan test` (pruebas)  
- **URLs clave**:  
  - Inicio/login: `http://localhost:8000/login`  
  - Panel Filament: `http://localhost:8000/filament`  
  - API autenticada: `http://localhost:8000/api/user`

## Estructura del proyecto
- `app/`: Controllers, Filament resources/pages, modelos, helpers y middleware.  
- `routes/`: `web.php`, `auth.php`, `api.php` y `modules/` (eliminados: ya no se cargan módulos vacíos).  
- `resources/`: Vistas Blade (`layouts`, `auth`, `usermanagement`, `profile`), componentes reutilizables, assets Tailwind y scripts JavaScript.  
- `database/`: Migraciones históricas (aulas, materiales, préstamos, inventarios, audit_log, computers) y seeders concretos.  
- `public/`: Entrada `index.php`, assets compilados (`build/`), imágenes e íconos.  
- `docker-compose.yml` + `Dockerfile`: Definen contenedores PHP, MySQL, Redis/Queue y configuración de puertos.  
- `tests/`: Suites `Feature` y `Unit` propias, integradas con PHPUnit 10.

## Contribución
- **Flujo Git**: ramas `feature/*` desde `develop`, merge a `develop` tras pruebas locales, release en `main`.  
- **Estándares**: seguir PSR-12, ejecutar `php artisan pint`, `npm run lint` y pruebas (`php artisan test`) antes de enviar un PR.  
- **Pull Requests**: describir cambios/contexto, mencionar comandos ejecutados, adjuntar capturas o logs relevantes y solicitar revisión activa.

## Licencia
El proyecto se publica bajo licencia MIT (ver `composer.json`). No hay archivo `LICENSE`, por lo que se debe crear uno si se requiere distribución oficial.
