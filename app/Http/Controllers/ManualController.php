<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ManualController extends Controller
{
    public function index(): View
    {
        $rolesManual = [
            [
                'id' => 'rol-superadmin',
                'title' => 'Superadmin',
                'description' => 'Gestiona el ecosistema completo de MultiLab: configuración global, roles y supervisión de operaciones críticas.',
                'steps' => [
                    'Ingresar al sistema con autenticación verificada y acceder al panel principal.',
                    'Revisar reportes generales del laboratorio y alertas pendientes.',
                    'Administrar roles, permisos y aprobaciones críticas antes de delegar operaciones.',
                    'Monitorear cierres y respaldos de movimientos para consultas posteriores.'
                ],
                'actions' => [
                    'Validar nuevas cuentas, bloquear accesos no autorizados y asignar roles.',
                    'Configurar límites de préstamo y reglas de ahorro de recursos.',
                    'Supervisar alertas de estados críticos y coordinar con auxiliares.',
                    'Respaldar decisiones en el historial de movimientos para auditorías.'
                ],
                'states' => [
                    ['label' => 'Pendiente', 'class' => 'bg-yellow-100 text-yellow-800'],
                    ['label' => 'Aprobado', 'class' => 'bg-emerald-100 text-emerald-800'],
                    ['label' => 'Rechazado', 'class' => 'bg-red-100 text-red-800'],
                ],
                'tips' => [
                    'Usar filtros por estado para priorizar aprobaciones urgentes.',
                    'Documentar las razones de rechazo dentro de notas para trazabilidad.',
                    'Respaldar configuraciones con capturas antes de cambios masivos.'
                ],
            ],
            [
                'id' => 'rol-auxiliar',
                'title' => 'Auxiliar / Admin de laboratorio',
                'description' => 'Coordina el préstamo y devolución de equipos, mantiene inventario y responde a solicitudes diarias.',
                'steps' => [
                    'Loguearse y revisar el dashboard de préstamos y devoluciones.',
                    'Validar las solicitudes de los docentes o estudiantes antes de entregar recursos.',
                    'Registrar cada movimiento al entregar o recibir materiales/herramientas.',
                    'Cerrar los ciclos con observaciones y confirmar estados de devolución.'
                ],
                'actions' => [
                    'Revisar y confirmar reservas con fecha y responsable.',
                    'Actualizar observaciones de cada equipo antes de prestarlo.',
                    'Enviar recordatorios de devolución y marcar entregas recibidas.',
                    'Reportar inconsistencias al superadmin cuando se detecten daños.'
                ],
                'states' => [
                    ['label' => 'Pendiente', 'class' => 'bg-yellow-100 text-yellow-800'],
                    ['label' => 'En préstamo', 'class' => 'bg-blue-100 text-blue-800'],
                    ['label' => 'Devuelto', 'class' => 'bg-slate-100 text-slate-800'],
                ],
                'tips' => [
                    'Etiqueta cada equipo con su código antes de entregarlo para facilitar búsquedas.',
                    'Revisa el historial del equipo para detectar patrones de fallas.',
                    'Confirma los detalles del estado antes de cerrar el préstamo.'
                ],
            ],
            [
                'id' => 'rol-docente',
                'title' => 'Docente',
                'description' => 'Solicita recursos para clases y supervisa la devolución por parte de estudiantes.',
                'steps' => [
                    'Acceder al dashboard y crear una solicitud indicando materiales y plazos.',
                    'Adjuntar justificativos académicos si se requieren aprobaciones especiales.',
                    'Supervisar el estado de la solicitud y responder retroalimentación del auxiliar.',
                    'Confirmar la entrega en el aula y el estado de los recursos al concluir.'
                ],
                'actions' => [
                    'Bloquear fechas de laboratorio para clases especiales.',
                    'Solicitar soporte técnico antes de fechas críticas.',
                    'Revisar el estado de los materiales usados por estudiantes.'
                ],
                'states' => [
                    ['label' => 'Pendiente', 'class' => 'bg-yellow-100 text-yellow-800'],
                    ['label' => 'Hecho', 'class' => 'bg-emerald-100 text-emerald-800'],
                    ['label' => 'Devuelto', 'class' => 'bg-slate-100 text-slate-800'],
                ],
                'tips' => [
                    'Planifica las solicitudes con al menos 48h de anticipación.',
                    'Agrega notas claras para el auxiliar sobre prioridades o urgencias.',
                    'Usa plantillas guardadas para solicitudes recurrentes.'
                ],
            ],
            [
                'id' => 'rol-estudiante',
                'title' => 'Estudiante',
                'description' => 'Reserva y utiliza recursos del laboratorio para prácticas guiadas por docentes.',
                'steps' => [
                    'Entrar al tablero y revisar qué préstamos están autorizados.',
                    'Solicitar materiales reservando fecha, hora y responsable.',
                    'Recoger el equipo según indicaciones del auxiliar.',
                    'Entregar puntualmente y dejar evidencia de estado.'
                ],
                'actions' => [
                    'Agregar comentarios sobre uso o incidencias durante el préstamo.',
                    'Responder notificaciones de devolución o cambio de estado.',
                    'Subir evidencias (fotos/observaciones) si se solicita.'
                ],
                'states' => [
                    ['label' => 'Pendiente', 'class' => 'bg-yellow-100 text-yellow-800'],
                    ['label' => 'Aprobado', 'class' => 'bg-emerald-100 text-emerald-800'],
                    ['label' => 'Rechazado', 'class' => 'bg-red-100 text-red-800'],
                ],
                'tips' => [
                    'Lee las instrucciones específicas del material antes de usarlo.',
                    'Anota obstáculos para que el docente o auxiliar puedan mejorar procesos.',
                    'Cumple los plazos de devolución para evitar bloqueos.'
                ],
            ],
        ];

        return view('manual.index', compact('rolesManual'));
    }
}
