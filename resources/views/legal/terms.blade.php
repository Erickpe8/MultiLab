@section('title', 'Términos y condiciones')

@php
    $layout = auth()->check() ? 'app-layout' : 'guest-layout';
@endphp

<x-dynamic-component :component="$layout">
    <x-slot name="header">
        <div class="flex items-center gap-3">
            @php
                $backRoute = auth()->check()
                    ? route('dashboard')
                    : (\Illuminate\Support\Facades\Route::has('login') ? route('login') : url('/'));
            @endphp

            <a href="{{ $backRoute }}" class="text-[var(--text-muted)] hover:text-[var(--accent)] transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>

            <div>
                <h2 class="font-semibold text-2xl text-[var(--text)] leading-tight">
                    Términos y Condiciones de Uso
                </h2>
                <p class="mt-1 text-sm text-[var(--text-muted)]">
                    Marco normativo y condiciones de utilización de MultiLab
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 sm:py-10 bg-[var(--bg)] min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <article class="bg-[var(--card)] rounded-xl border border-[var(--border)] shadow-lg overflow-hidden">

                <!-- Header -->
                <div
                    class="px-6 sm:px-8 py-6 border-b border-[var(--border)] bg-gradient-to-r from-[var(--primary)]/5 to-transparent">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-[var(--primary)] to-[var(--accent)]
                            flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-[var(--text)]">
                                Términos y Condiciones de Uso – MultiLab
                            </h1>
                            <p class="text-sm text-[var(--text-muted)] mt-1">
                                Última actualización: 04 de Enero del 2026
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Contenido -->
                <div class="px-6 sm:px-8 py-8 prose prose-sm max-w-none">
                    <div class="space-y-10">

                        <div class="bg-[var(--card)] rounded-xl border border-[var(--border)] shadow-sm">
                            <div class="px-6 py-6 sm:py-8 space-y-4">
                                <p class="text-sm font-semibold text-[var(--text)]">Índice</p>
                                <div class="grid gap-2 sm:grid-cols-2 text-sm">
                                    <a href="#aceptacion"
                                        class="text-[var(--text-secondary)] hover:text-[var(--accent)] transition-colors block">
                                        1. Aceptación
                                    </a>
                                    <a href="#uso-autorizado"
                                        class="text-[var(--text-secondary)] hover:text-[var(--accent)] transition-colors block">
                                        2. Uso autorizado
                                    </a>
                                    <a href="#responsabilidades"
                                        class="text-[var(--text-secondary)] hover:text-[var(--accent)] transition-colors block">
                                        3. Responsabilidades
                                    </a>
                                    <a href="#propiedad"
                                        class="text-[var(--text-secondary)] hover:text-[var(--accent)] transition-colors block">
                                        4. Propiedad intelectual
                                    </a>
                                    <a href="#datos"
                                        class="text-[var(--text-secondary)] hover:text-[var(--accent)] transition-colors block">
                                        5. Protección de datos
                                    </a>
                                    <a href="#gobierno"
                                        class="text-[var(--text-secondary)] hover:text-[var(--accent)] transition-colors block">
                                        6. Gobierno y seguridad del sistema
                                    </a>
                                    <a href="#habeas-data"
                                        class="text-[var(--text-secondary)] hover:text-[var(--accent)] transition-colors block">
                                        7. Habeas Data
                                    </a>
                                    <a href="#trazabilidad-auditoria"
                                        class="text-[var(--text-secondary)] hover:text-[var(--accent)] transition-colors block">
                                        8. Trazabilidad y auditoría
                                    </a>
                                    <a href="#evidencias-archivos"
                                        class="text-[var(--text-secondary)] hover:text-[var(--accent)] transition-colors block">
                                        9. Evidencias y archivos
                                    </a>
                                    <a href="#disponibilidad-soporte"
                                        class="text-[var(--text-secondary)] hover:text-[var(--accent)] transition-colors block">
                                        10. Disponibilidad y soporte
                                    </a>
                                    <a href="#sanciones"
                                        class="text-[var(--text-secondary)] hover:text-[var(--accent)] transition-colors block">
                                        11. Sanciones y terminación
                                    </a>
                                    <a href="#modificaciones"
                                        class="text-[var(--text-secondary)] hover:text-[var(--accent)] transition-colors block">
                                        12. Modificaciones y vigencia
                                    </a>
                                    <a href="#ley-aplicable"
                                        class="text-[var(--text-secondary)] hover:text-[var(--accent)] transition-colors block">
                                        13. Ley aplicable
                                    </a>
                                    <a href="#definiciones"
                                        class="text-[var(--text-secondary)] hover:text-[var(--accent)] transition-colors block">
                                        14. Definiciones
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- 1. Aceptación -->
                        <section id="aceptacion">
                            <h2 class="text-xl font-bold text-[var(--text)] mb-4 flex items-center gap-2">
                                <span class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center
                                    text-[var(--accent)] text-sm font-bold">
                                    1
                                </span>
                                Aceptación de Términos y Condiciones
                            </h2>

                            <h3 class="font-semibold text-[var(--text)] mt-2 mb-3">
                                Naturaleza institucional de MultiLab
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                MultiLab es una herramienta tecnológica institucional de uso restringido, destinada a
                                administrar la operación diaria del Laboratorio de Software B201 de la Fundación de
                                Estudios Superiores Comfanorte (FESC). El sistema permite controlar el acceso a las
                                estaciones de trabajo (PCs), gestionar reservas y préstamos de recursos, registrar
                                observaciones asociadas al uso y generar históricos que respalden la gestión operativa
                                del laboratorio y su inventario físico.
                            </p>

                            <h3 class="font-semibold text-[var(--text)] mt-8 mb-3">
                                Aceptación expresa por parte del usuario
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                Al autenticarse, navegar o realizar cualquier acción dentro de MultiLab, el usuario
                                manifiesta de forma expresa, informada e inequívoca que ha leído, comprendido y acepta
                                la totalidad de los presentes términos y condiciones de uso, así como las políticas
                                institucionales vigentes, reglamentos internos y disposiciones de carácter académico,
                                administrativo, tecnológico y normativo emitidas por la FESC.
                            </p>

                            <h3 class="font-semibold text-[var(--text)] mt-8 mb-3">
                                Obligación de cumplimiento permanente
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                La utilización de MultiLab está condicionada al cumplimiento estricto y permanente de
                                estas disposiciones. En caso de no estar de acuerdo con lo aquí establecido, el usuario
                                deberá abstenerse de utilizar el sistema. La institución se reserva el derecho de
                                suspender o revocar el acceso a cualquier usuario que incumpla los presentes términos,
                                sin perjuicio de las acciones disciplinarias y legales a que haya lugar.
                            </p>
                        </section>

                        <!-- 2. Uso autorizado -->
                        <section id="uso-autorizado">
                            <h2 class="text-xl font-bold text-[var(--text)] mb-4 flex items-center gap-2">
                                <span class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center
                                    text-[var(--accent)] text-sm font-bold">
                                    2
                                </span>
                                Uso Autorizado del Sistema
                            </h2>

                            <h3 class="font-semibold text-[var(--text)] mt-2 mb-3">
                                Usuarios habilitados y roles instituidos
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                El acceso a MultiLab se limita de forma exclusiva a usuarios autorizados por la FESC,
                                incluyendo personal de apoyo y responsables del laboratorio, docentes, administrativos y
                                otros colaboradores que requieran el uso del sistema por razones de operación y control
                                del laboratorio. Cada usuario contará con roles y permisos definidos de acuerdo con sus
                                responsabilidades y funciones asignadas.
                            </p>

                            <h3 class="font-semibold text-[var(--text)] mt-8 mb-3">
                                Finalidades permitidas de uso
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-4">
                                El usuario únicamente podrá utilizar MultiLab para fines asociados a:
                            </p>
                            <ul class="list-disc list-inside ml-4 text-[var(--text-secondary)] space-y-2 mb-6">
                                <li>
                                    Gestionar reservas de recursos del laboratorio y asignación de uso de estaciones de
                                    trabajo (PCs) conforme a la disponibilidad y reglas internas.
                                </li>
                                <li>
                                    Registrar préstamos y devoluciones de herramientas y materiales de la bodega,
                                    incluyendo observaciones relevantes para el control de inventario.
                                </li>
                                <li>
                                    Consultar y generar históricos operativos que respalden la trazabilidad de uso,
                                    mantenimiento, incidentes y control de acceso al laboratorio.
                                </li>
                            </ul>

                            <h3 class="font-semibold text-[var(--text)] mt-8 mb-3">
                                Conductas prohibidas y usos no autorizados
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-4">
                                Se consideran expresamente prohibidas, entre otras, las siguientes conductas:
                            </p>
                            <ul class="list-disc list-inside ml-4 text-[var(--text-secondary)] space-y-2 mb-6">
                                <li>
                                    Utilizar el sistema para fines personales, comerciales o ajenos a la operación del
                                    laboratorio y a los objetivos institucionales de la FESC.
                                </li>
                                <li>
                                    Alterar registros de reservas, préstamos, inventario u observaciones con el fin de
                                    ocultar información, evadir responsabilidad o modificar trazabilidad.
                                </li>
                                <li>
                                    Intentar modificar, descompilar, desensamblar o aplicar ingeniería inversa sobre el
                                    software, sus módulos, su estructura de datos o sus componentes técnicos.
                                </li>
                                <li>
                                    Eludir, vulnerar o intentar vulnerar mecanismos de seguridad, autenticación o
                                    control de accesos dispuestos para la protección de la información.
                                </li>
                                <li>
                                    Descargar, copiar o divulgar a terceros información institucional contenida en
                                    MultiLab sin autorización expresa de la instancia competente.
                                </li>
                            </ul>

                            <h3 class="font-semibold text-[var(--text)] mt-8 mb-3">
                                Consecuencias por uso indebido
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                Cualquier uso indebido, no autorizado o contrario a los fines institucionales de
                                MultiLab podrá ser considerado falta grave y dará lugar a las acciones disciplinarias,
                                académicas, laborales y legales correspondientes, de conformidad con los reglamentos
                                internos de la FESC y con la legislación colombiana vigente.
                            </p>
                        </section>

                        <!-- 3. Responsabilidades del usuario -->
                        <section id="responsabilidades">
                            <h2 class="text-xl font-bold text-[var(--text)] mb-4 flex items-center gap-2">
                                <span class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center
                                    text-[var(--accent)] text-sm font-bold">
                                    3
                                </span>
                                Responsabilidades y Obligaciones del Usuario
                            </h2>

                            <h3 class="font-semibold text-[var(--text)] mt-2 mb-3">
                                Custodia y confidencialidad de credenciales
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                Las credenciales de acceso son personales e intransferibles. El usuario es responsable
                                de adoptar las medidas necesarias para evitar el acceso de terceros mediante su
                                identidad digital, comprometiéndose a no compartir ni divulgar sus credenciales.
                            </p>

                            <h3 class="font-semibold text-[var(--text)] mt-8 mb-3">
                                Registro veraz y oportuno
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                El usuario se compromete a registrar información veraz, completa y oportuna respecto a
                                reservas, préstamos, devoluciones, observaciones e incidentes. El registro debe reflejar
                                lo ocurrido en el laboratorio sin alteraciones deliberadas.
                            </p>

                            <h3 class="font-semibold text-[var(--text)] mt-8 mb-3">
                                Reporte de incidentes y fallos de seguridad
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                El usuario tiene la obligación de informar al responsable del laboratorio o a la Unidad
                                de Desarrollo de Software cualquier incidente de seguridad, acceso irregular, falla
                                técnica, sospecha de suplantación o anomalía que pueda comprometer la integridad,
                                confidencialidad o disponibilidad de la información registrada en MultiLab.
                            </p>
                        </section>

                        <!-- 4. Propiedad intelectual -->
                        <section id="propiedad">
                            <h2 class="text-xl font-bold text-[var(--text)] mb-4 flex items-center gap-2">
                                <span class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center
                                    text-[var(--accent)] text-sm font-bold">
                                    4
                                </span>
                                Propiedad Intelectual y Derechos de Autor
                            </h2>

                            <h3 class="font-semibold text-[var(--text)] mt-2 mb-3">
                                Titularidad y protección jurídica
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                MultiLab constituye una obra de creación intelectual protegida por la legislación
                                colombiana en materia de derechos de autor y propiedad intelectual. La titularidad
                                exclusiva de los derechos patrimoniales y morales sobre el sistema corresponde a la
                                Fundación de Estudios Superiores Comfanorte (FESC).
                            </p>

                            <h3 class="font-semibold text-[var(--text)] mt-8 mb-3">
                                Alcance de la propiedad institucional
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-4">
                                Se encuentran amparados por esta protección, entre otros:
                            </p>
                            <ul class="list-disc list-inside ml-4 text-[var(--text-secondary)] space-y-2 mb-6">
                                <li>Código fuente, código compilado y lógica de negocio.</li>
                                <li>Arquitectura del sistema, modelos de datos y estructura de permisos.</li>
                                <li>Diseño de interfaces, estilos visuales y experiencia de usuario (UX/UI).</li>
                                <li>Documentación técnica y funcional relacionada.</li>
                            </ul>

                            <h3 class="font-semibold text-[var(--text)] mt-8 mb-3">
                                Restricciones de uso y explotación
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                Queda prohibida la copia, distribución, adaptación, modificación o explotación de
                                MultiLab fuera del marco institucional de la FESC, sin autorización previa y por escrito
                                de la instancia competente.
                            </p>
                        </section>

                        <!-- 5. Protección de datos -->
                        <section id="datos">
                            <h2 class="text-xl font-bold text-[var(--text)] mb-4 flex items-center gap-2">
                                <span class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center
                                    text-[var(--accent)] text-sm font-bold">
                                    5
                                </span>
                                Protección de Datos Personales y Tratamiento de Información
                            </h2>

                            <h3 class="font-semibold text-[var(--text)] mt-2 mb-3">
                                Marco normativo aplicable
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                El tratamiento de datos personales dentro de MultiLab se rige por la Ley 1581 de 2012,
                                la Ley 1266 de 2008, el Decreto 1377 de 2013 y las demás disposiciones aplicables, así
                                como por las Políticas de Protección de Datos Personales de la FESC.
                            </p>

                            <h3 class="font-semibold text-[var(--text)] mt-8 mb-3">
                                Finalidad del tratamiento en MultiLab
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                La información personal registrada en MultiLab se utiliza para el control de acceso al
                                laboratorio, la trazabilidad de reservas y préstamos, la asignación de
                                responsabilidades,
                                el registro de incidentes y la generación de históricos que respalden la operación del
                                laboratorio y su inventario.
                            </p>
                        </section>

                        <!-- 6. Responsabilidad institucional -->
                        <section id="gobierno">
                            <h2 class="text-xl font-bold text-[var(--text)] mb-4 flex items-center gap-2">
                                <span class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center
                                    text-[var(--accent)] text-sm font-bold">
                                    6
                                </span>
                                Responsabilidad Institucional, Seguridad de la Información y Gobierno del Sistema
                            </h2>

                            <h3 class="font-semibold text-[var(--text)] mt-2 mb-3">
                                Administración del sistema
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                La Unidad de Desarrollo de Software de la FESC es la encargada de la administración,
                                evolución, mantenimiento y soporte técnico de MultiLab, en coordinación con las
                                instancias responsables del Laboratorio de Software B201.
                            </p>

                            <h3 class="font-semibold text-[var(--text)] mt-8 mb-3">
                                Seguridad y continuidad operativa
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                La institución implementa medidas de seguridad orientadas a mitigar riesgos de pérdida,
                                fuga o alteración de información. El usuario reconoce que debe colaborar activamente
                                reportando incidentes y siguiendo las políticas internas del laboratorio.
                            </p>
                        </section>

                        <!-- 7. Derechos del titular / Habeas Data -->
                        <section id="habeas-data">
                            <h2 class="text-xl font-bold text-[var(--text)] mb-4 flex items-center gap-2">
                                <span class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center
                                    text-[var(--accent)] text-sm font-bold">
                                    7
                                </span>
                                Derechos del Titular, Consultas, Reclamos y Procedimientos de Habeas Data
                            </h2>

                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                Los titulares de los datos personales registrados en MultiLab podrán ejercer sus
                                derechos de acceso, actualización, rectificación, supresión y revocatoria de
                                autorización, de acuerdo con los procedimientos institucionales y la normatividad
                                vigente en Colombia.
                            </p>

                            <h3 class="font-semibold text-[var(--text)] mt-8 mb-3">
                                Canal oficial para solicitudes
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                Para el ejercicio de derechos relacionados con el tratamiento de datos personales, los
                                titulares podrán dirigir sus consultas y reclamos a la FESC a través del canal
                                institucional de la Secretaría General:
                                <span class="font-semibold">secretario_general@fesc.edu.co</span>.
                            </p>
                        </section>

                        <!-- 8. Trazabilidad y auditoría -->
                        <section id="trazabilidad-auditoria">
                            <h2 class="text-xl font-bold text-[var(--text)] mb-4 flex items-center gap-2">
                                <span class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center
                                    text-[var(--accent)] text-sm font-bold">
                                    8
                                </span>
                                Trazabilidad y Auditoría del Sistema
                            </h2>

                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                MultiLab mantiene registros de actividad para garantizar trazabilidad y control interno,
                                incluyendo reservas, préstamos, devoluciones, observaciones, cambios de inventario y
                                acciones administrativas cuando aplique.
                            </p>

                            <ul class="list-disc list-inside ml-4 text-[var(--text-secondary)] space-y-2 mb-6">
                                <li>Usuario autenticado que ejecuta la operación.</li>
                                <li>Fecha y hora del evento.</li>
                                <li>Módulo afectado (reservas, préstamos, inventario, observaciones, etc.).</li>
                                <li>Descripción de cambios realizados cuando aplique.</li>
                            </ul>
                        </section>

                        <!-- 9. Evidencias y archivos -->
                        <section id="evidencias-archivos">
                            <h2 class="text-xl font-bold text-[var(--text)] mb-4 flex items-center gap-2">
                                <span class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center
                                    text-[var(--accent)] text-sm font-bold">
                                    9
                                </span>
                                Evidencias y archivos
                            </h2>

                            <h3 class="font-semibold text-[var(--text)] mt-2 mb-3">
                                Principio de pertinencia
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                Las evidencias y archivos cargados en MultiLab deben estar directamente vinculados con
                                la operación del laboratorio, el control de inventario, el soporte de observaciones,
                                incidentes, mantenimientos o procesos internos autorizados.
                            </p>

                            <h3 class="font-semibold text-[var(--text)] mt-8 mb-3">
                                Prohibiciones
                            </h3>
                            <ul class="list-disc list-inside ml-4 text-[var(--text-secondary)] space-y-2 mb-6">
                                <li>Incluir software malicioso o contenido que comprometa la plataforma.</li>
                                <li>Adjuntar material sin autorización institucional cuando se requiera.</li>
                                <li>Registrar datos sensibles no necesarios para el control del laboratorio.</li>
                            </ul>
                        </section>

                        <!-- 10. Disponibilidad, mantenimiento y soporte -->
                        <section id="disponibilidad-soporte">
                            <h2 class="text-xl font-bold text-[var(--text)] mb-4 flex items-center gap-2">
                                <span class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center
                                    text-[var(--accent)] text-sm font-bold">
                                    10
                                </span>
                                Disponibilidad, mantenimiento y soporte
                            </h2>

                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                MultiLab podrá estar sujeto a mantenimientos programados y correctivos necesarios para
                                su estabilidad. La FESC procurará comunicar las ventanas de mantenimiento con la
                                anticipación posible según la criticidad del cambio.
                            </p>

                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                El soporte técnico se presta conforme a los procedimientos institucionales de atención
                                y registro de incidentes.
                            </p>
                        </section>

                        <!-- 11. Sanciones, suspensión y terminación -->
                        <section id="sanciones">
                            <h2 class="text-xl font-bold text-[var(--text)] mb-4 flex items-center gap-2">
                                <span class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center
                                    text-[var(--accent)] text-sm font-bold">
                                    11
                                </span>
                                Sanciones, suspensión y terminación de acceso
                            </h2>

                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                El incumplimiento de estos términos, el uso indebido del sistema o la alteración de
                                registros de operación del laboratorio podrá generar restricciones, suspensión temporal
                                o revocatoria del acceso, sin perjuicio de acciones disciplinarias y legales.
                            </p>
                        </section>

                        <!-- 12. Modificaciones y vigencia -->
                        <section id="modificaciones">
                            <h2 class="text-xl font-bold text-[var(--text)] mb-4 flex items-center gap-2">
                                <span class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center
                                    text-[var(--accent)] text-sm font-bold">
                                    12
                                </span>
                                Modificaciones y vigencia
                            </h2>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                La FESC podrá actualizar estos términos y condiciones conforme a necesidades
                                institucionales. El uso posterior de MultiLab implicará la aceptación expresa de la
                                versión vigente.
                            </p>
                        </section>

                        <!-- 13. Ley aplicable -->
                        <section id="ley-aplicable">
                            <h2 class="text-xl font-bold text-[var(--text)] mb-4 flex items-center gap-2">
                                <span class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center
                                    text-[var(--accent)] text-sm font-bold">
                                    13
                                </span>
                                Ley aplicable y jurisdicción
                            </h2>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                Estos términos se rigen por la legislación colombiana vigente en materia tecnológica,
                                administrativa y de protección de datos, así como por las políticas internas
                                institucionales de la FESC.
                            </p>
                        </section>

                        <!-- 14. Definiciones -->
                        <section id="definiciones">
                            <h2 class="text-xl font-bold text-[var(--text)] mb-4 flex items-center gap-2">
                                <span class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center
                                    text-[var(--accent)] text-sm font-bold">
                                    14
                                </span>
                                Definiciones
                            </h2>
                            <div class="space-y-3 text-[var(--text-secondary)]">
                                <p><span class="font-semibold">MultiLab:</span> Plataforma institucional para
                                    administrar la operación del Laboratorio de Software B201 (reservas, préstamos,
                                    control de acceso, observaciones e históricos).</p>
                                <p><span class="font-semibold">Usuario:</span> Persona con credenciales habilitadas por
                                    la FESC para operar dentro de MultiLab.</p>
                                <p><span class="font-semibold">Reserva:</span> Registro de asignación de uso de un
                                    recurso (por ejemplo, estación de trabajo) en una fecha y franja horaria
                                    determinadas.</p>
                                <p><span class="font-semibold">Préstamo:</span> Entrega controlada de una herramienta o
                                    material desde la bodega del laboratorio para un uso autorizado.</p>
                                <p><span class="font-semibold">Devolución:</span> Retorno del recurso prestado, con
                                    verificación de estado y registro de observaciones cuando aplique.</p>
                                <p><span class="font-semibold">Observación:</span> Nota operativa registrada sobre el
                                    uso de un recurso, su estado, incidentes o novedades relevantes.</p>
                                <p><span class="font-semibold">Trazabilidad / Auditoría:</span> Registro sistemático de
                                    acciones y cambios dentro de MultiLab para fines de control interno.</p>
                                <p><span class="font-semibold">Rol / Permiso:</span> Conjunto de responsabilidades y
                                    autorizaciones para acceder a módulos y ejecutar acciones dentro del sistema.</p>
                                <p><span class="font-semibold">Incidente:</span> Evento que afecta la operación del
                                    laboratorio, la disponibilidad de recursos o la integridad de la información.</p>
                            </div>
                        </section>

                        <!-- Aviso -->
                        <div class="mt-8 p-4 rounded-lg bg-[var(--accent)]/10 border border-[var(--accent)]/30">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-[var(--accent)] shrink-0 mt-0.5" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-[var(--accent)] mb-1">
                                        Advertencia Legal Importante
                                    </p>
                                    <p class="text-xs text-[var(--text-secondary)] leading-relaxed">
                                        El incumplimiento de los presentes términos y condiciones, así como de las
                                        políticas institucionales de protección de datos personales, seguridad de la
                                        información y propiedad intelectual, podrá dar lugar a la suspensión del acceso
                                        a MultiLab, la aplicación de sanciones disciplinarias internas y el inicio de
                                        acciones legales ante las autoridades competentes, sin perjuicio de las medidas
                                        adicionales que la FESC adopte para proteger su patrimonio tecnológico y el
                                        inventario del laboratorio.
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 sm:px-8 py-4 border-t border-[var(--border)] bg-[var(--border)]/5">
                    <p class="text-xs text-[var(--text-secondary)] text-center">
                        Para consultas, solicitudes de información o aclaraciones relacionadas con estos términos y
                        condiciones de uso de MultiLab, o con el tratamiento de datos personales, los usuarios y
                        titulares podrán contactar a la Secretaría General de la FESC a través del correo:
                        <span class="font-semibold">secretario_general@fesc.edu.co</span>.
                    </p>
                    <p class="text-xs text-[var(--text-secondary)] text-center mt-2">
                        Soporte técnico: Unidad de Desarrollo de Software de la FESC, siguiendo los canales
                        institucionales autorizados.
                    </p>
                </div>

            </article>
        </div>
    </div>
</x-dynamic-component>
