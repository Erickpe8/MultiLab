<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ManualController extends Controller
{
    public function index(): View
    {
        $roleCards = [
            [
                'slug' => 'superadmin',
                'title' => 'Super Administración',
                'overview' => 'Lidera el ecosistema MultiLab y mantiene la visibilidad de reservas, préstamos y solicitudes.',
                'capabilities' => [
                    'Ver el panel principal y los resúmenes de actividad global en tiempo real.',
                    'Revisar, aprobar o rechazar solicitudes y préstamos con fundamentos claros.',
                    'Editar perfiles y asignar auxiliares para equilibrar cargas operativas.',
                    'Supervisar inventario en riesgo y activar alertas de auditoría cuando sea necesario.',
                ],
                'limitations' => [
                    'No se debe reservar el Aula B202 directamente en nombre de docentes.',
                    'No puede crearse un quinto rol fuera de los cuatro autorizados.',
                ],
                'flows' => [
                    'Abre el módulo de solicitudes pendientes y anota observaciones antes de decidir.',
                    'Actualiza un perfil con datos corregidos y deja constancia en la sección de auditoría.',
                    'Coordina con un Administrador Auxiliar para validar niveles de stock antes de responder a un préstamo masivo.',
                ],
            ],
            [
                'slug' => 'aux_admin',
                'title' => 'Administrador Auxiliar',
                'overview' => 'Ejecuta tareas tácticas: aprueba préstamos, registra devoluciones y coordina reservas del laboratorio.',
                'capabilities' => [
                    'Crear y confirmar reservas del Aula B202 solicitadas por docentes.',
                    'Aprobar préstamos tras validar stock y condiciones del ítem.',
                    'Registrar devoluciones, movimientos de materiales y ajustes de inventario.',
                ],
                'limitations' => [
                    'No puede revertir decisiones de un Super Administrador sin respaldo documental.',
                    'No realiza cambios en la estructura de roles o migraciones del sistema.',
                ],
                'flows' => [
                    'Recibe una solicitud de reserva, revisa la disponibilidad y la aprueba con fechas concretas.',
                    'Aprueba un préstamo confirmando que la unidad solicitada está disponible y en buen estado.',
                    'Registra el ingreso de nuevos materiales con código interno y observa el stock mínimo.',
                ],
            ],
            [
                'slug' => 'docente',
                'title' => 'Docente',
                'overview' => 'Organiza clases y eventos solicitando espacios y materiales clave para las actividades académicas.',
                'capabilities' => [
                    'Solicitar la reserva del Aula B202 con objetivos, horarios y materiales de apoyo.',
                    'Pedir préstamos de proyectores, portátiles o periféricos para clases especiales.',
                    'Consultar su propio historial de préstamos y devoluciones.',
                ],
                'limitations' => [
                    'No aprueba ni rechaza solicitudes de otros usuarios.',
                    'No registra materiales nuevos ni edita inventario.',
                ],
                'flows' => [
                    'Describe el propósito de la reserva, fechas y número de estudiantes antes de enviarla.',
                    'Selecciona los materiales específicos y justifica el préstamo con la unidad académica.',
                    'Confirma la devolución indicando la condición del equipo y responde a observaciones del Administrador Auxiliar.',
                ],
            ],
            [
                'slug' => 'estudiante',
                'title' => 'Estudiante',
                'overview' => 'Solicita materiales de apoyo y monitorea el estado de sus préstamos personales.',
                'capabilities' => [
                    'Solicitar préstamos de materiales disponibles para actividades prácticas.',
                    'Consultar el historial propio de préstamos, devoluciones y sanciones.',
                    'Recibir notificaciones sobre vencimientos y regularizaciones.',
                ],
                'limitations' => [
                    'No puede reservar el Aula B202 ni modificar perfiles de otros usuarios.',
                    'No aprueba ni rechaza solicitudes ajenas.',
                ],
                'flows' => [
                    'Escoge el material necesario, explica el uso académico y envía el préstamo.',
                    'Sigue las indicaciones de devolución para evitar estados vencidos o sanciones.',
                ],
            ],
        ];

        $faqEntries = [
            [
                'question' => '¿Qué hago si olvido mi contraseña?',
                'answer' => 'En la pantalla de ingreso busca el enlace "¿Olvidó su contraseña?" y sigue los pasos. Si la cuenta universitaria no responde, contacta al responsable de TI para que reestablezca el acceso.',
            ],
            [
                'question' => '¿Qué requisitos debo cumplir antes de ingresar al sistema?',
                'answer' => 'Usar un navegador actualizado, tener conexión estable y contar con credenciales válidas. Verifica que el rol asignado (Super Administrador, Administrador Auxiliar, docente o estudiante) esté activado antes de iniciar sesión.',
            ],
            [
                'question' => '¿Cómo ingreso a la plataforma MultiLab?',
                'answer' => 'Accede a la URL principal del laboratorio, introduce tu correo institucional y contraseña. Si el sistema solicita un segundo factor, sigue el paso adicional que aparezca en pantalla.',
            ],
            [
                'question' => '¿Qué muestra el panel principal y cómo lo interpreto?',
                'answer' => 'El panel despliega tarjetas clave como solicitudes recientes, préstamos activos y alertas de inventario. Consulta la barra de estado para detectar bloqueos e ingresa a cada módulo desde los accesos rápidos.',
            ],
            [
                'question' => '¿Cómo reviso el listado de usuarios registrados?',
                'answer' => 'Desde la gestión de usuarios (solo Super Administrador) filtra por rol, estado o unidad. Puedes usar el buscador para localizar personas por nombre o correo y observar cuándo fue la última actualización.',
            ],
            [
                'question' => '¿Qué debo hacer con una solicitud pendiente de usuario?',
                'answer' => 'Lee la causa adjunta, verifica datos y decide si apruebas, rechazas o solicitas más información. Todo cambio debe documentarse para conservar trazabilidad.',
            ],
            [
                'question' => '¿Cómo solicito una reserva en el Aula B202?',
                'answer' => 'Desde el módulo de reservas elige aula, fecha, rango horario y materiales. Agrega el objetivo de la sesión y guarda la solicitud para que el Administrador Auxiliar la revise.',
            ],
            [
                'question' => '¿Qué significan los estados de reserva?',
                'answer' => 'Pendiente: en revisión (sin confirmación). Aprobada: el Administrador Auxiliar confirmó la hora. En uso: la sesión está activa y el aula se registra como ocupada. Finalizada: la actividad concluyó. Cancelada: la solicitud se anuló y no se puede reactivar.',
            ],
            [
                'question' => '¿Cómo consulto el inventario disponible?',
                'answer' => 'Ve a gestión de materiales, filtra por categoría o unidad y consulta stock, código y estado. Los registros indican si la unidad está disponible, en préstamo o en mantenimiento.',
            ],
            [
                'question' => '¿Qué debo verificar antes de aprobar un préstamo?',
                'answer' => 'Confirma que el ítem solicitado esté en estado "Disponible", revisa el stock mínimo y valida que el solicitante tenga permiso para ese material. Registra cualquier anotación antes de dar el visto bueno.',
            ],
            [
                'question' => '¿Cómo gestiono una devolución de material?',
                'answer' => 'Marca el préstamo como devuelto, registra la condición del equipo y vuelve a ajustar el inventario. Si hay daños, detalla la observación y activa la alerta correspondiente.',
            ],
            [
                'question' => '¿Dónde encuentro el historial y auditoría?',
                'answer' => 'En la sección de historial puedes filtrar por usuario, material o fecha. Cada acción queda registrada para consultas posteriores sobre préstamos, reservas y cambios en usuarios.',
            ],
        ];

        $tocSections = [
            ['id' => 'intro', 'label' => 'Introducción'],
            ['id' => 'access', 'label' => 'Acceso al sistema'],
            ['id' => 'roles', 'label' => 'Roles de usuario'],
            ['id' => 'dashboard', 'label' => 'Panel principal'],
            ['id' => 'users', 'label' => 'Gestión de usuarios'],
            ['id' => 'reservations', 'label' => 'Reservas del laboratorio'],
            ['id' => 'materials', 'label' => 'Gestión de materiales'],
            ['id' => 'loans', 'label' => 'Préstamos de materiales'],
            ['id' => 'audit', 'label' => 'Historial y auditoría'],
            ['id' => 'logout', 'label' => 'Cierre de sesión'],
            ['id' => 'recommendations', 'label' => 'Recomendaciones finales'],
            ['id' => 'faq', 'label' => 'FAQ'],
        ];

        $roleChips = ['superadmin', 'aux_admin', 'docente', 'estudiante'];

        return view('manual.index', compact('roleCards', 'faqEntries', 'tocSections', 'roleChips'));
    }
}
