@extends('legal.layout')

@section('title', 'Privacidad de Datos')

@php
    $backRoute = auth()->check()
        ? (\Illuminate\Support\Facades\Route::has('filament.dashboard.pages.dashboard')
            ? route('filament.dashboard.pages.dashboard')
            : url('/dashboard'))
        : (\Illuminate\Support\Facades\Route::has('login')
            ? route('login')
            : url('/'));
@endphp

@section('legal-header')
    <div class="flex items-center gap-3">
        <a href="{{ $backRoute }}" class="text-[var(--text-muted)] hover:text-[var(--accent)] transition-colors">
            <x-ui.icon name="atras" size="md" class="text-current" />
        </a>
        <div>
            <h2 class="font-semibold text-2xl text-[var(--text)] leading-tight">
                Privacidad de Datos
            </h2>
            <p class="mt-1 text-sm text-[var(--text-muted)]">
                Política de Privacidad y Tratamiento de Datos Personales en MultiLab
            </p>
        </div>
    </div>
@endsection

@section('legal-content')
    <div class="py-8 sm:py-10 bg-[var(--bg)] overflow-y-auto">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <article class="bg-[var(--card)] rounded-xl border border-[var(--border)] shadow-lg">

                <!-- Header del documento -->
                <div
                    class="px-6 sm:px-8 py-6 border-b border-[var(--border)] bg-gradient-to-r from-[var(--primary)]/5 to-transparent">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-12 h-12 rounded-lg bg-gradient-to-br from-[var(--primary)] to-[var(--accent)]
                                flex items-center justify-center shadow-lg">
                            <x-ui.icon name="bloquear" size="md" class="w-6 h-6 text-white" />
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-[var(--text)]">
                                Política de Privacidad y Tratamiento de Datos Personales – MultiLab
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

                        <!-- Introducción -->
                        <div id="introduccion"
                            class="p-4 rounded-lg bg-[var(--accent)]/10 border border-[var(--accent)]/30">
                            <p class="text-sm text-[var(--text-secondary)] leading-relaxed">
                                La Fundación de Estudios Superiores Comfanorte – FESC adopta esta Política de Privacidad
                                con el fin de
                                garantizar el manejo ético, seguro y transparente de los datos personales administrados
                                en <strong>MultiLab</strong>,
                                sistema institucional orientado a la operación del Laboratorio de Software B202,
                                incluyendo el control de acceso,
                                la gestión de reservas, préstamos de recursos y la trazabilidad de uso.
                                En cumplimiento de la Ley 1581 de 2012, el Decreto 1377 de 2013, la Ley 1266 de 2008, el
                                Decreto Único 1074 de 2015
                                y la normatividad vigente en materia de protección de datos, la FESC define aquí los
                                parámetros para la recolección,
                                almacenamiento, tratamiento, circulación, actualización y supresión de la información
                                personal.
                            </p>
                        </div>

                        <!-- Índice -->
                        <div class="bg-[var(--card)] rounded-xl border border-[var(--border)] shadow-sm">
                            <div class="px-6 py-6 sm:py-8 space-y-4">
                                <p class="text-sm font-semibold text-[var(--text)]">Índice</p>
                                <div class="grid gap-2 sm:grid-cols-2 text-sm">
                                    <a href="#informacion-recopilamos"
                                        class="text-[var(--text-secondary)] hover:text-[var(--accent)] transition-colors block">
                                        1. Información que recopilamos
                                    </a>
                                    <a href="#uso-informacion"
                                        class="text-[var(--text-secondary)] hover:text-[var(--accent)] transition-colors block">
                                        2. Uso de la información
                                    </a>
                                    <a href="#seguridad"
                                        class="text-[var(--text-secondary)] hover:text-[var(--accent)] transition-colors block">
                                        3. Seguridad de los datos
                                    </a>
                                    <a href="#derechos"
                                        class="text-[var(--text-secondary)] hover:text-[var(--accent)] transition-colors block">
                                        4. Derechos del titular
                                    </a>
                                    <a href="#transferencia"
                                        class="text-[var(--text-secondary)] hover:text-[var(--accent)] transition-colors block">
                                        5. Transferencia y compartición
                                    </a>
                                    <a href="#encargados"
                                        class="text-[var(--text-secondary)] hover:text-[var(--accent)] transition-colors block">
                                        6. Encargados del tratamiento
                                    </a>
                                    <a href="#procedimientos"
                                        class="text-[var(--text-secondary)] hover:text-[var(--accent)] transition-colors block">
                                        7. Procedimientos
                                    </a>
                                    <a href="#principios"
                                        class="text-[var(--text-secondary)] hover:text-[var(--accent)] transition-colors block">
                                        8. Principios del tratamiento
                                    </a>
                                    <a href="#base-legal"
                                        class="text-[var(--text-secondary)] hover:text-[var(--accent)] transition-colors block">
                                        9. Base legal y autorización
                                    </a>
                                    <a href="#finalidades"
                                        class="text-[var(--text-secondary)] hover:text-[var(--accent)] transition-colors block">
                                        10. Finalidades detalladas
                                    </a>
                                    <a href="#retencion"
                                        class="text-[var(--text-secondary)] hover:text-[var(--accent)] transition-colors block">
                                        11. Retención y supresión
                                    </a>
                                    <a href="#cookies-tecnicos"
                                        class="text-[var(--text-secondary)] hover:text-[var(--accent)] transition-colors block">
                                        12. Cookies y registros técnicos
                                    </a>
                                    <a href="#menores"
                                        class="text-[var(--text-secondary)] hover:text-[var(--accent)] transition-colors block">
                                        13. Datos de menores
                                    </a>
                                    <a href="#incidentes"
                                        class="text-[var(--text-secondary)] hover:text-[var(--accent)] transition-colors block">
                                        14. Incidentes y reporte
                                    </a>
                                    <a href="#cambios"
                                        class="text-[var(--text-secondary)] hover:text-[var(--accent)] transition-colors block">
                                        15. Cambios y vigencia
                                    </a>
                                    <a href="#definiciones"
                                        class="text-[var(--text-secondary)] hover:text-[var(--accent)] transition-colors block">
                                        16. Definiciones
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- 1. Información que recopilamos -->
                        <section id="informacion-recopilamos">
                            <h2 class="text-xl font-bold text-[var(--text)] mb-6 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center text-[var(--accent)] text-sm font-bold">
                                    1
                                </span>
                                Información que Recopilamos
                            </h2>

                            <div class="text-[var(--text-secondary)] leading-relaxed space-y-4">
                                <p>
                                    MultiLab recopila información necesaria para identificar usuarios autorizados,
                                    administrar el acceso al laboratorio,
                                    gestionar reservas y préstamos, y garantizar trazabilidad operativa. El tratamiento
                                    se realiza bajo criterios de necesidad,
                                    pertinencia y proporcionalidad, asegurando siempre el uso mínimo indispensable
                                    conforme a la normativa colombiana.
                                </p>

                                <div class="space-y-4">
                                    <div class="flex items-start gap-2">
                                        <x-ui.icon name="exito" size="sm"
                                            class="text-[var(--accent)] shrink-0 mt-0.5" />
                                        <div>
                                            <p class="font-semibold text-[var(--text)]">Datos de identificación</p>
                                            <p class="text-sm">
                                                Nombre completo, documento de identificación, correo institucional y
                                                datos requeridos para asignación de rol/permisos.
                                                Esta información permite asociar responsabilidades y acciones dentro del
                                                laboratorio.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-2">
                                        <x-ui.icon name="exito" size="sm"
                                            class="text-[var(--accent)] shrink-0 mt-0.5" />
                                        <div>
                                            <p class="font-semibold text-[var(--text)]">Datos técnicos y de acceso</p>
                                            <p class="text-sm">
                                                IP, navegador, sistema operativo, fechas/horas de ingreso y registros de
                                                sesión para seguridad, auditoría y prevención de uso no autorizado.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-2">
                                        <x-ui.icon name="exito" size="sm"
                                            class="text-[var(--accent)] shrink-0 mt-0.5" />
                                        <div>
                                            <p class="font-semibold text-[var(--text)]">Datos de operación y
                                                trazabilidad</p>
                                            <p class="text-sm">
                                                Registros de reservas, préstamos, devoluciones, observaciones e
                                                incidencias relacionadas con el uso de PCs, herramientas y recursos del
                                                laboratorio.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- 2. Uso de la información -->
                        <section id="uso-informacion">
                            <h2 class="text-xl font-bold text-[var(--text)] mb-6 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center text-[var(--accent)] text-sm font-bold">
                                    2
                                </span>
                                Uso de la Información
                            </h2>

                            <div class="text-[var(--text-secondary)] leading-relaxed space-y-4">
                                <p>
                                    La información recopilada por MultiLab se utiliza exclusivamente para fines
                                    institucionales y operativos del Laboratorio de Software B202,
                                    garantizando control de acceso, trazabilidad, seguridad y continuidad del servicio.
                                </p>

                                <ul class="list-disc list-inside space-y-2 ml-4">
                                    <li>Autenticación, control de acceso y administración de roles/permisos.</li>
                                    <li>Gestión de reservas de recursos y asignación de uso de estaciones de trabajo.
                                    </li>
                                    <li>Gestión de préstamos y devoluciones de herramientas/materiales.</li>
                                    <li>Registro de observaciones e incidencias para control operativo e inventario.
                                    </li>
                                    <li>Auditoría interna, trazabilidad y control de seguridad del sistema.</li>
                                    <li>Cumplimiento de obligaciones legales y procedimientos institucionales
                                        aplicables.</li>
                                </ul>
                            </div>
                        </section>

                        <!-- 3. Seguridad -->
                        <section id="seguridad">
                            <h2 class="text-xl font-bold text-[var(--text)] mb-6 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center text-[var(--accent)] text-sm font-bold">
                                    3
                                </span>
                                Protección y Seguridad de los Datos
                            </h2>

                            <div class="text-[var(--text-secondary)] leading-relaxed space-y-3">
                                <p>
                                    La FESC implementa medidas técnicas, administrativas y organizacionales para evitar
                                    acceso no autorizado, pérdida,
                                    alteración o uso indebido de la información tratada en MultiLab. La protección de
                                    datos se fortalece con prácticas de seguridad,
                                    auditoría y mejora continua.
                                </p>

                                <ul class="list-disc list-inside space-y-2 ml-4">
                                    <li>Controles de acceso por roles y permisos.</li>
                                    <li>Registro de actividad para auditoría y trazabilidad.</li>
                                    <li>Respaldo y recuperación según prácticas internas.</li>
                                    <li>Uso de canales seguros cuando aplique (por ejemplo SSL/TLS en ambientes
                                        desplegados).</li>
                                    <li>Monitoreo de actividad para detección de comportamientos anómalos.</li>
                                </ul>
                            </div>
                        </section>

                        <!-- 4. Derechos -->
                        <section id="derechos">
                            <h2 class="text-xl font-bold text-[var(--text)] mb-6 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center text-[var(--accent)] text-sm font-bold">
                                    4
                                </span>
                                Derechos del Titular de los Datos
                            </h2>

                            <div class="text-[var(--text-secondary)] leading-relaxed space-y-3">
                                <p>
                                    Los titulares de información personal registrada en MultiLab cuentan con los
                                    derechos establecidos por la Ley 1581 de 2012 y normas aplicables.
                                    La FESC garantiza mecanismos formales para ejercerlos.
                                </p>

                                <ul class="list-disc list-inside space-y-2 ml-4">
                                    <li>Acceder a su información personal.</li>
                                    <li>Solicitar corrección, actualización o rectificación.</li>
                                    <li>Solicitar prueba de autorización (cuando aplique).</li>
                                    <li>Ser informado sobre el uso de su información.</li>
                                    <li>Presentar quejas ante la SIC si considera vulnerados sus derechos.</li>
                                    <li>Solicitar supresión cuando no subsista la finalidad y no exista deber
                                        legal/contractual.</li>
                                    <li>Revocar autorización, salvo restricciones legales.</li>
                                </ul>
                            </div>
                        </section>

                        <!-- 5. Transferencia -->
                        <section id="transferencia">
                            <h2 class="text-xl font-bold text-[var(--text)] mb-6 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center text-[var(--accent)] text-sm font-bold">
                                    5
                                </span>
                                Transferencia y Compartición de Información
                            </h2>

                            <p class="text-[var(--text-secondary)] leading-relaxed">
                                La información registrada en MultiLab no será compartida con terceros sin autorización
                                del titular,
                                salvo en los casos previstos por la ley o cuando sea necesario para el cumplimiento de
                                funciones institucionales.
                                Cualquier transferencia se realizará bajo lineamientos y controles institucionales.
                            </p>
                        </section>

                        <!-- 6. Encargados -->
                        <section id="encargados">
                            <h2 class="text-xl font-bold text-[var(--text)] mb-6 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center text-[var(--accent)] text-sm font-bold">
                                    6
                                </span>
                                Encargados del Tratamiento y Acceso Autorizado
                            </h2>

                            <p class="text-[var(--text-secondary)] leading-relaxed">
                                La FESC podrá delegar operaciones técnicas o administrativas a dependencias internas o
                                terceros autorizados,
                                siempre bajo obligaciones de confidencialidad y cumplimiento normativo. En ningún caso
                                se permitirá acceso
                                o uso de datos sin autorización formal y controles verificables.
                            </p>
                        </section>

                        <!-- 7. Procedimientos -->
                        <section id="procedimientos">
                            <h2 class="text-xl font-bold text-[var(--text)] mb-6 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center text-[var(--accent)] text-sm font-bold">
                                    7
                                </span>
                                Procedimientos para Consultas, Reclamos, Rectificación y Supresión
                            </h2>

                            <div class="text-[var(--text-secondary)] leading-relaxed space-y-4">
                                <p>
                                    La FESC dispone de mecanismos formales para presentar consultas, reclamos y
                                    solicitudes relacionadas con datos personales.
                                    Se atenderán conforme a los plazos de ley y procedimientos institucionales.
                                </p>

                                <div>
                                    <p class="font-semibold text-[var(--text)] mb-2">7.1 Consultas</p>
                                    <p>
                                        Podrán presentarse al correo institucional:
                                        <span
                                            class="font-medium text-[var(--accent)]">secretario_general@fesc.edu.co</span>.
                                        Se atenderán dentro de los diez (10) días hábiles siguientes y, si se requiere,
                                        podrá concederse prórroga conforme a ley.
                                    </p>
                                </div>

                                <div>
                                    <p class="font-semibold text-[var(--text)] mb-2">7.2 Reclamos</p>
                                    <p>
                                        Para corrección, actualización o supresión de datos, el titular podrá presentar
                                        un reclamo formal con la descripción del hecho,
                                        los datos a intervenir y soportes. Se responderá dentro de los quince (15) días
                                        hábiles siguientes y, si aplica, con prórroga legal.
                                    </p>
                                </div>

                                <div>
                                    <p class="font-semibold text-[var(--text)] mb-2">Requisitos mínimos</p>
                                    <ul class="list-disc list-inside space-y-2 ml-4">
                                        <li>Identificación del titular o representante autorizado.</li>
                                        <li>Descripción clara de la solicitud.</li>
                                        <li>Soportes/documentos cuando aplique.</li>
                                        <li>Contacto válido para notificaciones.</li>
                                    </ul>
                                </div>
                            </div>
                        </section>

                        <!-- 8. Principios -->
                        <section id="principios">
                            <h2 class="text-xl font-bold text-[var(--text)] mb-6 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center text-[var(--accent)] text-sm font-bold">
                                    8
                                </span>
                                Principios del Tratamiento
                            </h2>

                            <p class="text-[var(--text-secondary)] leading-relaxed">
                                El tratamiento de los datos personales en MultiLab se ajusta a los principios de la Ley
                                1581 de 2012:
                                legalidad, finalidad, libertad, veracidad o calidad, transparencia, acceso y circulación
                                restringida,
                                seguridad y confidencialidad.
                            </p>
                        </section>

                        <!-- 9. Base legal -->
                        <section id="base-legal">
                            <h2 class="text-xl font-bold text-[var(--text)] mb-6 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center text-[var(--accent)] text-sm font-bold">
                                    9
                                </span>
                                Base Legal y Autorización
                            </h2>

                            <p class="text-[var(--text-secondary)] leading-relaxed space-y-4">
                                <span class="block">
                                    El tratamiento de datos se realiza con autorización previa, expresa e informada del
                                    titular, salvo los casos en los que la ley permite su uso sin autorización.
                                </span>
                                <span class="block">
                                    En caso de revocatoria u oposición, la FESC verificará si subsisten finalidades u
                                    obligaciones legales que requieran conservar información.
                                </span>
                            </p>
                        </section>

                        <!-- 10. Finalidades -->
                        <section id="finalidades">
                            <h2 class="text-xl font-bold text-[var(--text)] mb-6 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center text-[var(--accent)] text-sm font-bold">
                                    10
                                </span>
                                Finalidades Detalladas del Tratamiento
                            </h2>

                            <p class="text-[var(--text-secondary)] leading-relaxed space-y-3">
                                <span class="block">
                                    La información se emplea para autenticación segura, control de acceso al
                                    laboratorio, administración de reservas y préstamos,
                                    trazabilidad, auditoría y mejora operativa del servicio.
                                </span>
                                <span class="block">
                                    También soporta la gestión de incidentes, la seguridad, la prevención de fraude y el
                                    cumplimiento de procedimientos institucionales.
                                </span>
                            </p>
                        </section>

                        <!-- 11. Retención -->
                        <section id="retencion">
                            <h2 class="text-xl font-bold text-[var(--text)] mb-6 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center text-[var(--accent)] text-sm font-bold">
                                    11
                                </span>
                                Retención, Conservación y Supresión
                            </h2>

                            <p class="text-[var(--text-secondary)] leading-relaxed space-y-3">
                                <span class="block">
                                    Los datos se conservan durante el tiempo necesario para cumplir las finalidades
                                    descritas, obligaciones legales y requerimientos institucionales de control interno.
                                </span>
                                <span class="block">
                                    Cuando cese la finalidad y no exista deber legal o institucional que lo impida, la
                                    información será suprimida conforme a procedimientos internos aplicables.
                                </span>
                            </p>
                        </section>

                        <!-- 12. Cookies y logs -->
                        <section id="cookies-tecnicos">
                            <h2 class="text-xl font-bold text-[var(--text)] mb-6 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center text-[var(--accent)] text-sm font-bold">
                                    12
                                </span>
                                Cookies y Registros Técnicos
                            </h2>

                            <p class="text-[var(--text-secondary)] leading-relaxed space-y-4">
                                <span class="block">
                                    MultiLab podrá registrar información técnica (por ejemplo IP, logs y metadatos de
                                    sesión) con fines de seguridad, auditoría, trazabilidad y mejora operativa.
                                </span>
                                <span class="block">
                                    Estos registros se utilizan exclusivamente para propósitos institucionales y no se
                                    comercializan ni se comparten con terceros no autorizados.
                                </span>
                            </p>
                        </section>

                        <!-- 13. Menores -->
                        <section id="menores">
                            <h2 class="text-xl font-bold text-[var(--text)] mb-6 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center text-[var(--accent)] text-sm font-bold">
                                    13
                                </span>
                                Tratamiento de Datos de Menores
                            </h2>

                            <p class="text-[var(--text-secondary)] leading-relaxed">
                                MultiLab es un sistema institucional de uso restringido. En el caso de tratar datos de
                                menores, se aplicarán garantías reforzadas,
                                priorizando el interés superior del menor y cumpliendo la normatividad vigente,
                                incluyendo autorizaciones cuando corresponda.
                            </p>
                        </section>

                        <!-- 14. Incidentes -->
                        <section id="incidentes">
                            <h2 class="text-xl font-bold text-[var(--text)] mb-6 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center text-[var(--accent)] text-sm font-bold">
                                    14
                                </span>
                                Gestión de Incidentes y Reporte
                            </h2>

                            <p class="text-[var(--text-secondary)] leading-relaxed space-y-3">
                                <span class="block">
                                    Ante incidentes de seguridad, accesos irregulares o anomalías, los usuarios deberán
                                    reportar de manera inmediata a los canales institucionales definidos.
                                </span>
                                <span class="block">
                                    La FESC documentará el caso, activará protocolos de respuesta y aplicará medidas
                                    para preservar la confidencialidad, integridad y disponibilidad de la información.
                                </span>
                            </p>
                        </section>

                        <!-- 15. Cambios -->
                        <section id="cambios">
                            <h2 class="text-xl font-bold text-[var(--text)] mb-6 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center text-[var(--accent)] text-sm font-bold">
                                    15
                                </span>
                                Cambios a la Política y Vigencia
                            </h2>

                            <p class="text-[var(--text-secondary)] leading-relaxed">
                                La FESC podrá actualizar esta política por cambios normativos o lineamientos
                                institucionales. Las versiones vigentes estarán disponibles en MultiLab.
                            </p>
                        </section>

                        <!-- 16. Definiciones -->
                        <section id="definiciones">
                            <h2 class="text-xl font-bold text-[var(--text)] mb-6 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center text-[var(--accent)] text-sm font-bold">
                                    16
                                </span>
                                Definiciones
                            </h2>

                            <div class="space-y-3 text-[var(--text-secondary)]">
                                <p><span class="font-semibold">Dato personal:</span> Información vinculada o que permite
                                    identificar a una persona natural.</p>
                                <p><span class="font-semibold">Dato sensible:</span> Información que afecta la intimidad
                                    o puede generar discriminación.</p>
                                <p><span class="font-semibold">Tratamiento:</span> Operaciones sobre datos personales
                                    (recolección, almacenamiento, uso, actualización, supresión).</p>
                                <p><span class="font-semibold">Responsable:</span> La FESC como entidad que define
                                    finalidades y medios del tratamiento.</p>
                                <p><span class="font-semibold">Titular:</span> Persona natural a quien pertenecen los
                                    datos.</p>
                                <p><span class="font-semibold">MultiLab:</span> Plataforma institucional para
                                    administrar la operación del Laboratorio de Software B202 (reservas, préstamos,
                                    control de acceso, observaciones e históricos).</p>
                                <p><span class="font-semibold">Trazabilidad:</span> Registro sistemático de acciones y
                                    cambios para fines de verificación y control.</p>
                                <p><span class="font-semibold">Incidente:</span> Evento que compromete la
                                    confidencialidad, integridad o disponibilidad de los datos.</p>
                            </div>
                        </section>

                    </div>
                </div>

                <!-- Footer del documento -->
                <div class="px-6 sm:px-8 py-4 border-t border-[var(--border)] bg-[var(--border)]/5">
                    <p class="text-xs text-[var(--text-secondary)] text-center">
                        Para consultas, solicitudes o reclamos relacionados con el tratamiento de datos personales,
                        los titulares podrán contactar a la Secretaría General de la FESC:
                        <span class="font-semibold">secretario_general@fesc.edu.co</span>.
                    </p>
                    <p class="text-xs text-[var(--text-secondary)] text-center mt-2">
                        Esta política podrá ser actualizada conforme a la normativa vigente y lineamientos
                        institucionales.
                    </p>
                </div>

            </article>
        </div>
    </div>
@endsection
