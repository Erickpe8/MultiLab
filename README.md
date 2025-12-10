# 🧭 README – Sistema de Gestión de Inventarios y Préstamos Multilab FESC

## 🏢 1. Contexto e Idea de Proyecto
Multilab FESC es una iniciativa de la Unidad de Desarrollo de Software de la Fundación de Estudios Superiores Comfanorte (FESC), cuyo objetivo es construir desde cero una plataforma institucional que permita administrar de manera eficiente:

- Inventarios tecnológicos
- Materiales de laboratorio
- Reservas y préstamos
- Procesos de mantenimiento
- Incidencias, movimientos y auditoría interna

Actualmente estos procesos se realizan de forma manual o dispersa. El sistema digitalizará todo el flujo operativo, permitirá controles claros, reducirá pérdidas y brindará trazabilidad total sobre los activos institucionales.

## 💡 2. Objetivos del Proyecto
- Crear un sistema unificado que gestione inventarios y préstamos en los laboratorios FESC.
- Asegurar trazabilidad completa sobre activos, materiales, mantenimientos e incidencias.
- Diseñar una arquitectura modular y escalable desde el inicio.
- Implementar roles y validaciones que aseguren seguridad y orden institucional.
- Integrar herramientas modernas que mejoren la experiencia y hagan eficiente el desarrollo.

## 🧱 3. Arquitectura del Sistema (Planeada)
El sistema se construirá con una orientación hacia Clean Architecture, garantizando separación clara entre reglas de negocio, lógica de aplicación, controladores y capa de infraestructura.

```
Dominio (Reglas del negocio)
↓
Casos de uso (Aplicación)
↓
Adaptadores (Controladores, validaciones, vistas)
↓
Infraestructura (Persistencia, servicios externos)
```

## ⚙️ 4. Stack Tecnológico (Planeado)
- Laravel 10
- Blade
- TailwindCSS + Flowbite
- Axios
- Laravel Breeze
- Spatie\Permission
- MySQL
- SheetJS y jsPDF

## 🧩 5. Diseño de la Base de Datos (Pendiente por construir)
La base de datos será diseñada desde cero. Las áreas funcionales previstas incluyen:

- Gestión de usuarios y roles
- Gestión de activos tecnológicos
- Gestión de materiales
- Reservas y préstamos
- Movimientos de inventario
- Mantenimientos e incidencias
- Auditoría del sistema

## 🧭 6. Lógica de Negocio (Planeada)
El sistema deberá contemplar procesos como:

- Registro y control de activos y materiales
- Gestión de estados
- Reservas y préstamos
- Registro de mantenimientos
- Reporte de incidencias
- Movimientos de inventario con trazabilidad

## 🧠 7. Modelos y Relaciones (Por definir)
Aún no existe ningún modelo Eloquent. Su estructura final dependerá del análisis funcional.

## 🔐 8. Seguridad y Roles (Planeado)
- Usuario básico
- Administrador
- Superadministrador

## 🖼️ 9. Archivos e Imágenes (Planeado)
Se plantea implementar:
- Subida de imágenes
- Validación de formatos
- Optimización automática

## ⚡ 10. Interfaz y Comunicación (Planeado)
Incluye:
- Dashboard
- Vistas con Tailwind
- Tablas dinámicas
- Peticiones AJAX
- Reportes descargables

## 🧾 11. Ventajas del Diseño Propuesto
- Claridad en responsabilidades
- Base sólida para escalar
- Trazabilidad completa
- Mejora de procesos institucionales

## 🚀 12. Etapas de Desarrollo (Planificadas)
1. Requerimientos y análisis
2. Diseño de base de datos
3. Arquitectura limpia
4. Autenticación y roles
5. Inventarios
6. Reservas y préstamos
7. Mantenimientos e incidencias
8. Reportes
9. Pruebas y despliegue

## 🧱 13. Equipo de Desarrollo
**Desarrolladores (Estudiantes de Ingeniería de Software):**
- Erick Sebastián Pérez Carvajal
- David Arturo Aceros Ortiz
- Carlos José Mantilla Cote

Ingenieria de Software – FESC  
Año: 2025
