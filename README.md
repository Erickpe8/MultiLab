# MultiLab

Sistema institucional de gestión del Laboratorio de Software B201 de la Fundación de Estudios Superiores Comfanorte (FESC).

## Descripción del Proyecto

MultiLab es un sistema desarrollado exclusivamente para el control operativo del Laboratorio de Software B201 de la FESC. El sistema administra dos componentes principales del laboratorio: el aula de cómputo B201 y la bodega de materiales, permitiendo el registro, control y seguimiento de préstamos y uso de recursos del laboratorio.

### Espacios del Laboratorio B201

El Laboratorio de Software B201 está conformado por dos espacios físicos:

#### Aula de cómputo B201
Sala equipada con PCs y recursos tecnológicos utilizada para clases y prácticas académicas. El préstamo o reserva del aula está restringido exclusivamente a docentes de la institución.

#### Bodega de materiales del laboratorio
Espacio destinado al almacenamiento de materiales físicos necesarios para asignaturas afines a la carrera, tales como: martillos, leds, seguetas, protoboards, cables, herramientas e insumos de práctica. Estos materiales pueden ser prestados tanto a estudiantes como a docentes.

## Componentes Funcionales

### Componente 1: Préstamo del Aula de Cómputo B201

Este componente permite la gestión y control del aula de cómputo B201, con las siguientes funcionalidades:

- **Reserva y préstamo del aula**: Los docentes pueden solicitar y registrar el uso del aula B201
- **Control de disponibilidad de PCs**: Seguimiento del estado y disponibilidad de los equipos de cómputo
- **Registro de observaciones**: Documentación del estado del aula y los equipos durante y después de su uso
- **Histórico de uso**: Mantenimiento de registros de sesiones, validaciones internas y uso del aula

**Restricción**: Este componente es de uso exclusivo para docentes.

### Componente 2: Préstamo de Materiales de Bodega

Este componente gestiona el inventario y préstamo de materiales físicos del laboratorio, con las siguientes funcionalidades:

- **Gestión de inventario**: Administración del catálogo de materiales disponibles en la bodega
- **Registro de préstamos y devoluciones**: Control de salida y entrada de materiales
- **Control de estado del material**: Seguimiento de la condición física de cada elemento
- **Registro de observaciones**: Documentación de uso, daños o desgaste de los materiales

**Disponibilidad**: Este componente está disponible tanto para estudiantes como para docentes.

## Roles del Sistema

MultiLab cuenta con cuatro roles principales, cada uno con responsabilidades específicas dentro del sistema:

### Director de Programa
- Supervisión general del uso y estado del laboratorio
- Acceso a reportes de uso del aula y materiales
- Aprobación de solicitudes especiales
- Revisión del histórico completo de préstamos y observaciones

### Docente
- Solicitud de préstamo y reserva del aula de cómputo B201
- Registro de observaciones sobre el estado del aula y equipos
- Solicitud de préstamo de materiales de la bodega
- Devolución de materiales prestados
- Consulta del histórico de sus propios préstamos

### Estudiante
- Solicitud de préstamo de materiales de la bodega
- Devolución de materiales prestados
- Consulta del histórico de sus propios préstamos
- Registro de observaciones sobre materiales devueltos

### Auxiliar Administrativo
- Gestión del inventario de materiales de bodega
- Registro y validación de préstamos y devoluciones
- Control de disponibilidad del aula B201
- Actualización del estado de PCs y materiales
- Registro de observaciones sobre el estado general del laboratorio

## Arquitectura del Sistema

MultiLab está construido bajo el patrón arquitectónico **Model–View–Controller (MVC) + UseCases**, aplicado de forma estricta para garantizar la separación de responsabilidades y la mantenibilidad del código.

### Estructura Arquitectónica
```
Request HTTP → Controller → UseCase → Models/Repositorios → View/Response
```

#### Controllers
Los controladores son responsables de:
- Recibir las peticiones HTTP del usuario
- Validar datos de entrada básicos
- Delegar la lógica de negocio a los UseCases correspondientes
- Retornar respuestas HTTP o vistas al cliente

Los controladores **NO** contienen lógica de negocio, únicamente orquestan el flujo de la aplicación.

#### UseCases
Los casos de uso representan acciones reales y específicas del laboratorio. Cada UseCase encapsula una operación completa del negocio, tales como:
- Prestar el aula de cómputo
- Registrar devolución de material
- Actualizar estado de un PC
- Consultar disponibilidad de materiales
- Registrar observaciones sobre el uso del aula

Los UseCases coordinan la interacción entre múltiples modelos y repositorios para completar una operación del sistema.

#### Models / Domain
Los modelos representan las entidades del dominio del laboratorio:
- **PCs**: Equipos de cómputo del aula B201
- **Materiales**: Elementos físicos de la bodega
- **Préstamos**: Registros de préstamos de aula o materiales
- **Observaciones**: Anotaciones sobre el estado de recursos
- **Usuarios**: Docentes, estudiantes y personal administrativo

Los modelos encapsulan las reglas de negocio propias de cada entidad y su interacción con la base de datos.

#### Views
Las vistas utilizan el motor de plantillas Blade de Laravel para renderizar la interfaz institucional del laboratorio. Las vistas son responsables únicamente de la presentación de datos al usuario, sin contener lógica de negocio.

### Flujo de Operación

1. El usuario realiza una acción en la interfaz (View)
2. El Controller recibe la petición HTTP
3. El Controller invoca al UseCase correspondiente
4. El UseCase ejecuta la lógica de negocio, interactuando con Models y Repositorios
5. El UseCase retorna el resultado al Controller
6. El Controller retorna una View o Response al usuario

## Tecnologías Utilizadas

- **Framework Backend**: Laravel 10.x
- **Base de Datos**: PhpMyAdmin
- **Motor de Plantillas**: Blade
- **Control de Versiones**: Git
- **Arquitectura**: MVC + UseCases

## Equipo de Desarrollo

### Erick Sebastián Pérez Carvajal
**GitHub**: [@erickpe8](https://github.com/erickpe8)

**Responsabilidades**:
- Arquitectura general del sistema
- Implementación del patrón MVC + UseCases
- Desarrollo de pruebas automatizadas
- Implementación de componentes de autenticación
- Desarrollo del módulo de gestión de perfil de usuario
- Elaboración de documentación técnica del proyecto
- Creación y mantenimiento de seeders institucionales

### David Arturo Aceros Ortiz
**GitHub**: [@Aceros113](https://github.com/Aceros113)

**Responsabilidades**:
- Desarrollo completo del componente de préstamo de materiales de bodega
- Implementación del sistema de inventario de materiales
- Desarrollo del módulo de préstamos de materiales
- Implementación del sistema de devoluciones
- Desarrollo del control de estados de materiales
- Implementación del sistema de observaciones para estudiantes y docentes

### Carlos José Mantilla Cote
**GitHub**: [@CarlosMantillaC](https://github.com/CarlosMantillaC)

**Responsabilidades**:
- Desarrollo completo del componente de préstamo del aula de cómputo B201
- Implementación del sistema de gestión del aula
- Desarrollo del módulo de gestión de PCs
- Implementación del control de disponibilidad del aula
- Desarrollo del sistema de control de uso exclusivo para docentes
- Implementación del histórico de sesiones y validaciones

## Instalación

Por definir

## Configuración

Por definir

## Uso

Por definir

## Pruebas

Por definir

## Licencia

Por definir

## Contacto

Fundación de Estudios Superiores Comfanorte (FESC)  
Laboratorio de Software B201

---

**Versión**: 0.1 
**Última actualización**: Por definir
