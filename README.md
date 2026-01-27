# MultiLab

## Descripción general
MultiLab es la plataforma institucional para la administración de inventario, préstamos y uso de espacios en laboratorios universitarios. Diseñada para que auxiliares administrativos, docentes y estudiantes trabajen sobre una única fuente verificable, garantiza órdenes, controles y trazabilidad que sirven tanto para la operación diaria como para reportes de auditoría académica.

### Qué es MultiLab
Es una aplicación backend sobre Laravel 10 que combina dashboards de Filament con vistas Blade y componentes Livewire para representar recursos reales (equipos, materiales, espacios) y su ciclo de vida. Cada cambio queda registrado, cada préstamo es trazable y las rutas privadas reflejan los permisos institucionales.

### Para qué tipo de institución/laboratorio está pensado
MultiLab responde a laboratorios académicos de ingeniería, ciencias aplicadas o tecnologías de la información que necesitan manejar activos físicos reales (equipos de cómputo, kits, instrumentos y aulas taller), órdenes de préstamo, aprobaciones administrativas y evidencia histórica de quién usó qué y cuándo.

## Problema que resuelve
- **Desorden en inventario.** Evita registros fragmentados, mantiene categorías, marcas, ubicaciones y unidades, y aclara la disponibilidad de cada activo.
- **Falta de control de préstamos.** Busca solicitudes pendientes, valida autorizaciones y registra aprobaciones o rechazos con responsabilidad administrativa.
- **Dificultad para saber quién tiene qué y cuándo debe devolverlo.** Permite consultar el historial completo de cada préstamo, observar estados vencidos o con multa y conocer al responsable actual.

## Características principales
### Gestión de inventario (equipos y materiales)
Catálogo completo de assets, materiales, marcas, categorías y unidades con movimientos de inventario, bajas y reportes de stock. Cada registro enlaza con ubicaciones y fotografías cuando se requieren.

### Préstamos y devoluciones
Solicitudes de préstamos físicos y de aulas, registro de entregas y devoluciones, actas digitales y seguimiento de vencidos. La interfaz usa recursos (`Loan`, `ClassroomLoan`, `MaterialRequest`) para exponer estados, filtros y acciones aprobadas por el rol correspondiente.

### Control por roles
Spatie `laravel-permission` y middleware como `CheckAreaAccess` aplican reglas sobre los recursos, limitan vistas y permiten empujar acciones (aprobar préstamos, validar inventario) únicamente a los roles definidos.

### Historial y trazabilidad
Modelos como `AuditLog`, `AssetMovement`, `Loan` y `Computer` registran cada cambio, la responsable persona, fecha y comentarios. Las notificaciones internas (Filament Notifications, Livewire) mantienen informados a los implicados.

### Estados de equipos y préstamos
Estados claros (`disponible`, `en préstamo`, `reservado`, `pendiente de devolución`, `devuelto`, `vencido`, `con multa`) permiten filtrar activos, priorizar mantenimientos y orientar a auxiliares en la toma de decisiones.

### Solicitudes y validaciones
Estudiantes y docentes generan solicitudes, adjuntan información de uso y esperan aprobación. Los auxiliares (`aux_admin`) revisan requisitos, registran notas de respuesta y cambian el estado, mientras un `superadmin` puede intervenir en casos excepcionales.

## Roles y responsabilidades
- **superadmin:** Supervisa configuración global, gestiona usuarios críticos, accede a auditorías completas y resuelve excepciones.
- **aux_admin:** Coordina el inventario, valida solicitudes de préstamo y devolución, documenta entregas y prepara informes para la gestión académica.
- **docente:** Solicita materiales o espacios para actividades académicas, consulta préstamos activos de sus grupos y verifica devoluciones de estudiantes.
- **estudiante:** Solicita préstamos personales, sigue el estado de sus solicitudes, registra devoluciones y responde ante vencimientos notificados por el sistema.

## Flujo general del sistema
1. Un docente o estudiante crea una solicitud de préstamo o de sala desde el tablero institucional indicando fechas y justificación.
2. El sistema valida disponibilidad en el inventario y deja el registro en estado `pendiente` mientras notifica a auxiliares.
3. El `aux_admin` revisa la solicitud, verifica requisitos y pasa el estado a `aprobado` o `rechazado`, dejando comentarios oficiales.
4. Cuando se entrega el recurso, el auxiliar registra la entrega y actualiza los estados del equipo y del préstamo.
5. El usuario recibe alertas sobre devolución y, si el empleado devuelve el recurso, el sistema marca el préstamo como `devuelto`; ante demoras cambia a `vencido` o `con multa`.
6. Todas las operaciones quedan en los historiales de inventario, préstamos y auditoría para reportes institucionales.

## Tecnologías utilizadas
- **Backend:** Laravel 10 con PHP 8.1 y comandos artesanales (`artisan`) para migraciones, seeders y tareas programadas.
- **Autenticación:** Laravel Breeze (scaffolding) y middleware personalizado para reforzar políticas de acceso.
- **Administración:** Filament 3.3 con Livewire y Blade para recursos, listados, filtros y métricas de préstamos e inventario.
- **Frontend adicional:** Tailwind CSS, Flowbite, Alpine.js y Vite para compilar `resources/js/app.js`, `resources/js/user-management.js` y estilos institucionales.
- **Base de datos:** MySQL/MariaDB configurado desde `.env`, con migraciones que incluyen activos, préstamos, auditoría y solicitudes.
- **Control de acceso:** Spatie `laravel-permission` gobierna los permisos de los roles definidos.

## Instalación y configuración
1. Clona el repositorio y desplázate a la carpeta:
   ```bash
   git clone <origen> MultiLab
   cd MultiLab
   ```
2. Instala dependencias PHP y JavaScript:
   ```bash
   composer install
   npm install
   ```
3. Copia y ajusta el entorno:
   ```bash
   cp .env.example .env
   php artisan key:generate --ansi
   ```
   Configura la conexión a la base de datos, el correo institucional y otros secretos en `.env`.
4. Ejecuta migraciones y seeders realistas:
   ```bash
   php artisan migrate --seed
   ```
5. Inicia el servidor backend y el watcher de assets:
   ```bash
   npm run dev
   php artisan serve
   ```
6. Usuario inicial: `superadmin@fesc.edu.co` con contraseña `Password123*`. Cámbiala desde la interfaz o `php artisan tinker` para ambientes productivos.

## Datos de prueba (Seeders)
- `RoleSeeder`, `UserSeeder` y `UserRequestsSeeder` crean usuarios por rol, solicitudes y autorizaciones reales.
- `MaterialSeeder`, `ComputerSeeder`, `CategorySeeder` y `UnitSeeder` proveen catalogación de inventario.
- `LoanSeeder` y `ClassroomLoanSeeder` generan préstamos y devoluciones con estados variados; la semilla también contempla solicitudes en revisión.
- Usar estos seeders sólo en entornos locales o de prueba; los datos de producción deben ingresarse vía las interfaces oficiales.

## Estado del proyecto
MultiLab está en desarrollo activo con pruebas institucionales. Las funciones centrales (inventario, préstamos, trazabilidad) están operativas, y el equipo prioriza mejoras de reportes y sincronización con sistemas académicos complementarios en lugar de prometer un roadmap extenso.

## Documentación adicional
- `docs/local-setup.md`: guía de instalación en Laragon/Windows, verificación de servicios y mantenimiento diario.
- `docs/architecture.md`: descripción del dominio, modelos, permisos y flujos de negocio ya implementados.

## Créditos / Autoría
La Fundación de Estudios Superiores Comfanorte (FESC) — Unidad de Desarrollo de Software — impulsa MultiLab como herramienta de gestión académica. El equipo responsable integra desarrollo backend, diseño institucional de procesos y acompañamiento a auxiliares docentes para mantener el sistema alineado a las prácticas reales del laboratorio.
