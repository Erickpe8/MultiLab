<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}"

                class="text-[var(--text-muted)] hover:text-[var(--accent)] transition-colors">
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
                        <div
                            class="w-12 h-12 rounded-lg bg-gradient-to-br from-[var(--primary)] to-[var(--accent)]
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
                                Última actualización: 25 de Noviembre del 2025
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Contenido -->
                <div class="px-6 sm:px-8 py-8 prose prose-sm max-w-none">
                    <div class="space-y-10">

                        <!-- 1. Aceptación -->
                        <section>
                            <h2 class="text-xl font-bold text-[var(--text)] mb-4 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center
                                    text-[var(--accent)] text-sm font-bold">
                                    1
                                </span>
                                Aceptación de Términos y Condiciones
                            </h2>

                            <h3 class="font-semibold text-[var(--text)] mt-2 mb-3">
                                Naturaleza institucional de MultiLab
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                MultiLab es una herramienta tecnológica institucional de uso restringido, desarrollada
                                por la Unidad de Desarrollo de Software de la Fundación de Estudios Superiores
                                Comfanorte (FESC) para apoyar la gestión, seguimiento, monitoreo y documentación del
                                Plan Operativo Anual (POA) y otros procesos académicos, administrativos y operativos
                                internos. Su uso se encuentra intrínsecamente ligado al cumplimiento de los fines
                                misionales de la FESC y a la ejecución de actividades institucionales debidamente
                                autorizadas.
                            </p>

                            <h3 class="font-semibold text-[var(--text)] mt-8 mb-3">
                                Aceptación expresa por parte del usuario
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                Al autenticarse, navegar o realizar cualquier acción dentro de MultiLab, el usuario
                                manifiesta de forma expresa, informada e inequívoca que ha leído, comprendido y acepta
                                la totalidad de los presentes términos y condiciones de uso, así como las políticas
                                institucionales vigentes, los reglamentos internos y las demás disposiciones de carácter
                                académico, administrativo, tecnológico y normativo emitidas por la FESC.
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
                        <section>
                            <h2 class="text-xl font-bold text-[var(--text)] mb-4 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center
                                    text-[var(--accent)] text-sm font-bold">
                                    2
                                </span>
                                Uso Autorizado del Sistema
                            </h2>

                            <h3 class="font-semibold text-[var(--text)] mt-2 mb-3">
                                Usuarios habilitados y roles instituidos
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                El acceso a MultiLab se limita de forma exclusiva a los usuarios que hayan sido
                                previamente autorizados por la FESC, entre ellos directivos, coordinadores,
                                docentes, personal administrativo y otros colaboradores institucionales que requieran el
                                uso del sistema en razón de sus funciones. Cada usuario contará con un rol y permisos
                                definidos, de acuerdo con sus responsabilidades dentro del proceso de planeación,
                                ejecución y seguimiento del POA y de otros procesos institucionales relacionados.
                            </p>

                            <h3 class="font-semibold text-[var(--text)] mt-8 mb-3">
                                Finalidades permitidas de uso
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-4">
                                El usuario únicamente podrá utilizar MultiLab para fines asociados a:
                            </p>
                            <ul class="list-disc list-inside ml-4 text-[var(--text-secondary)] space-y-2 mb-6">
                                <li>
                                    Registrar, consultar y actualizar información vinculada al Plan Operativo Anual
                                    (POA) y a los procesos estratégicos, académicos, administrativos y de gestión
                                    institucional autorizados.
                                </li>
                                <li>
                                    Generar reportes, indicadores, evidencias y trazabilidad del cumplimiento de metas,
                                    acciones e iniciativas institucionales definidas por la FESC.
                                </li>
                                <li>
                                    Realizar actividades de seguimiento, monitoreo y control interno de las acciones
                                    que se desarrollan en el marco del POA y demás procesos aprobados por las
                                    instancias de gobierno institucional.
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
                                    Utilizar el sistema para fines personales, comerciales o ajenos a la misión y
                                    objetivos institucionales de la FESC.
                                </li>
                                <li>
                                    Intentar modificar, alterar, descompilar, desensamblar o aplicar ingeniería inversa
                                    sobre el software, sus módulos, su estructura de datos o sus componentes técnicos.
                                </li>
                                <li>
                                    Eludir, vulnerar o intentar vulnerar mecanismos de seguridad, autenticación o
                                    control de accesos dispuestos para la protección de la información.
                                </li>
                                <li>
                                    Descargar, copiar, reproducir, divulgar o comunicar a terceros información
                                    institucional contenida en MultiLab sin autorización expresa y por escrito de la
                                    instancia competente.
                                </li>
                            </ul>

                            <h3 class="font-semibold text-[var(--text)] mt-8 mb-3">
                                Consecuencias por uso indebido
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                Cualquier uso indebido, no autorizado o contrario a los fines institucionales de
                                MultiLab podrá ser considerado falta grave y dará lugar a las acciones disciplinarias,
                                académicas, laborales y legales correspondientes, de conformidad con los reglamentos
                                internos de la FESC y con la legislación colombiana en materia de delitos informáticos,
                                protección de datos personales y responsabilidad civil y penal.
                            </p>
                        </section>

                        <!-- 3. Responsabilidades del usuario -->
                        <section>
                            <h2 class="text-xl font-bold text-[var(--text)] mb-4 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center
                                    text-[var(--accent)] text-sm font-bold">
                                    3
                                </span>
                                Responsabilidades y Obligaciones del Usuario
                            </h2>

                            <h3 class="font-semibold text-[var(--text)] mt-2 mb-3">
                                Custodia y confidencialidad de credenciales
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                Las credenciales de acceso (usuario y contraseña) son personales, intransferibles y de
                                uso exclusivo del titular asignado. El usuario es responsable de adoptar todas las
                                medidas necesarias para evitar que terceros accedan a MultiLab mediante su identidad
                                digital, comprometiéndose a no compartir, ceder, divulgar o prestar sus credenciales
                                bajo ninguna circunstancia.
                            </p>

                            <h3 class="font-semibold text-[var(--text)] mt-8 mb-3">
                                Uso responsable de la información institucional
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                Toda información consultada, registrada, procesada o generada dentro de MultiLab se
                                considera información institucional, estratégica o de apoyo a la gestión. El usuario
                                debe utilizarla de forma responsable, limitando su uso a los fines autorizados y
                                absteniéndose de divulgarla, reproducirla o modificarla sin autorización previa de las
                                instancias correspondientes.
                            </p>

                            <h3 class="font-semibold text-[var(--text)] mt-8 mb-3">
                                Reporte de incidentes y fallos de seguridad
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                El usuario tiene la obligación de informar de manera inmediata y veraz al administrador
                                del sistema o a la Unidad de Desarrollo de Software cualquier incidente de seguridad,
                                acceso irregular, falla técnica, sospecha de vulneración, intento de suplantación o
                                anomalía que pueda comprometer la integridad, confidencialidad o disponibilidad de la
                                información almacenada en MultiLab.
                            </p>
                        </section>

                        <!-- 4. Propiedad intelectual -->
                        <section>
                            <h2 class="text-xl font-bold text-[var(--text)] mb-4 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center
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
                                colombiana en materia de derechos de autor, propiedad intelectual y protección de
                                software, incluyendo, entre otras, la Ley 23 de 1982, la Ley 44 de 1993 y la Decisión
                                Andina 351 de 1993. La titularidad exclusiva de los derechos patrimoniales y morales
                                sobre el sistema corresponde a la Fundación de Estudios Superiores Comfanorte (FESC).
                            </p>

                            <h3 class="font-semibold text-[var(--text)] mt-8 mb-3">
                                Alcance de la propiedad institucional
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-4">
                                Se encuentran amparados por esta protección, entre otros:
                            </p>
                            <ul class="list-disc list-inside ml-4 text-[var(--text-secondary)] space-y-2 mb-6">
                                <li>Código fuente y código compilado del sistema.</li>
                                <li>Arquitectura, modelos de datos, algoritmos y lógica de negocio.</li>
                                <li>Diseño de interfaces, estilos visuales y experiencia de usuario (UX/UI).</li>
                                <li>Documentación técnica y funcional, manuales, diagramas y material asociado.</li>
                            </ul>

                            <h3 class="font-semibold text-[var(--text)] mt-8 mb-3">
                                Restricciones de uso y explotación
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                Queda terminantemente prohibida la copia, reproducción, distribución, adaptación,
                                modificación, venta, alquiler, sublicenciamiento, traducción, descompilación,
                                desensamblado o cualquier modalidad de explotación económica de MultiLab fuera del
                                marco institucional de la FESC, sin la autorización previa, expresa y por escrito de la
                                Rectoría o de la instancia que esta designe.
                            </p>
                        </section>

                        <!-- 5. Protección de datos -->
                        <section>
                            <h2 class="text-xl font-bold text-[var(--text)] mb-4 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center
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
                                la Ley 1266 de 2008, el Decreto 1377 de 2013, el Decreto Único Reglamentario 1074 de
                                2015 y las disposiciones de la Superintendencia de Industria y Comercio. Asimismo, se
                                sujeta a las Políticas de Protección de Datos Personales de la FESC, aprobadas mediante
                                Acta 088 del Comité de Planeación del 26 de junio de 2018 y formalizadas por la
                                Resolución de Rectoría No. 469 del mismo año.
                            </p>

                            <h3 class="font-semibold text-[var(--text)] mt-8 mb-3">
                                Principios de tratamiento
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-4">
                                Toda información personal gestionada en MultiLab será tratada con base en los
                                principios de:
                            </p>
                            <ul class="list-disc list-inside ml-4 text-[var(--text-secondary)] space-y-2 mb-6">
                                <li>Legalidad, finalidad y libertad en la recolección y uso de los datos.</li>
                                <li>Veracidad, calidad, seguridad y confidencialidad de la información.</li>
                                <li>Acceso y circulación restringida, con perfiles y permisos definidos.</li>
                            </ul>

                            <h3 class="font-semibold text-[var(--text)] mt-8 mb-3">
                                Responsabilidad en el manejo de datos
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                La FESC actúa como responsable del tratamiento de los datos personales incluidos en
                                MultiLab y garantiza la adopción de medidas técnicas, humanas y organizativas
                                razonables para proteger la información frente a accesos no autorizados, pérdida,
                                alteración o uso indebido. El usuario, como parte de la cadena de tratamiento,
                                contribuye a esta responsabilidad cumpliendo las políticas institucionales y la
                                normatividad vigente.
                            </p>
                        </section>

                        <!-- 6. Responsabilidad institucional -->
                        <section>
                            <h2 class="text-xl font-bold text-[var(--text)] mb-4 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center
                                    text-[var(--accent)] text-sm font-bold">
                                    6
                                </span>
                                Responsabilidad Institucional, Seguridad de la Información y Gobierno del Sistema
                            </h2>

                            <h3 class="font-semibold text-[var(--text)] mt-2 mb-3">
                                Dirección y gobierno del sistema
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                La Unidad de Desarrollo de Software, bajo la coordinación del Programa de Ingeniería de
                                Software y las instancias superiores de la FESC, es la encargada de la administración,
                                evolución, mantenimiento, mejora continua y soporte técnico de MultiLab. Esta unidad
                                vela por la estabilidad, disponibilidad y correcto funcionamiento del sistema.
                            </p>

                            <h3 class="font-semibold text-[var(--text)] mt-8 mb-3">
                                Seguridad de la información
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                La institución implementa medidas técnicas, organizativas y administrativas de seguridad
                                orientadas a mitigar riesgos de pérdida, fuga, alteración o acceso no autorizado a la
                                información contenida en el sistema. No obstante, el usuario reconoce que ningún
                                sistema tecnológico es infalible y se compromete a colaborar activamente en la
                                protección de la información reportando cualquier anomalía o sospecha de incidente.
                            </p>

                            <h3 class="font-semibold text-[var(--text)] mt-8 mb-3">
                                Responsabilidad del usuario frente a incidentes
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                La omisión en el reporte de incidentes de seguridad, el uso indebido de credenciales, la
                                divulgación no autorizada de información o el incumplimiento de las políticas
                                institucionales puede generar responsabilidad disciplinaria y legal para el usuario,
                                sin perjuicio de las acciones adicionales que la FESC pueda adelantar para proteger su
                                patrimonio tecnológico y reputacional.
                            </p>
                        </section>

                        <!-- 7. Derechos del titular / Habeas Data -->
                        <section>
                            <h2 class="text-xl font-bold text-[var(--text)] mb-4 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center
                                    text-[var(--accent)] text-sm font-bold">
                                    7
                                </span>
                                Derechos del Titular, Consultas, Reclamos y Procedimientos de Habeas Data
                            </h2>

                            <h3 class="font-semibold text-[var(--text)] mt-2 mb-3">
                                Derechos del titular de los datos personales
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-4">
                                Los titulares de los datos personales registrados en MultiLab, ya sea de manera
                                directa o a través de información derivada de procesos institucionales, tienen derecho a:
                            </p>
                            <ul class="list-disc list-inside ml-4 text-[var(--text-secondary)] space-y-2 mb-6">
                                <li>Conocer, acceder y obtener información sobre los datos personales que reposan en las bases de datos institucionales.</li>
                                <li>Actualizar y rectificar los datos cuando sean parciales, inexactos, incompletos o
                                    desactualizados.</li>
                                <li>Solicitar la supresión del dato cuando considere que no se requiere para la finalidad con la que fue recolectado o cuando se haya agotado el tiempo autorizado de tratamiento.</li>
                                <li>Revocar la autorización otorgada para el tratamiento de sus datos personales, cuando
                                    no exista un deber legal o contractual que lo impida.</li>
                                <li>Presentar consultas y reclamos ante la FESC y, de ser necesario, quejas ante la
                                    Superintendencia de Industria y Comercio.</li>
                            </ul>

                            <h3 class="font-semibold text-[var(--text)] mt-8 mb-3">
                                Consultas sobre el tratamiento de datos
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                El titular podrá elevar consultas para conocer la información personal que sobre él se
                                encuentre almacenada en las bases de datos que soportan MultiLab. Las consultas
                                deberán formularse por escrito y serán atendidas en los plazos y condiciones
                                establecidos por la normatividad vigente, conforme a las políticas internas de la FESC.
                            </p>

                            <h3 class="font-semibold text-[var(--text)] mt-8 mb-3">
                                Reclamos, correcciones, actualizaciones y supresión
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                Cuando el titular considere que la información contenida debe ser objeto de corrección,
                                actualización o supresión, o cuando advierta el presunto incumplimiento de la ley o de
                                las políticas institucionales, podrá presentar un reclamo ante la FESC, siguiendo los
                                procedimientos definidos en las Políticas de Protección de Datos Personales de la
                                institución.
                            </p>

                            <h3 class="font-semibold text-[var(--text)] mt-8 mb-3">
                                Revocatoria de autorización y quejas ante la autoridad competente
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                El titular podrá solicitar la revocatoria de la autorización otorgada para el
                                tratamiento de sus datos personales cuando no exista deber legal o contractual que lo
                                impida. Si, una vez agotado el procedimiento interno, persiste un posible incumplimiento
                                del régimen de protección de datos, el titular podrá acudir ante la Superintendencia de
                                Industria y Comercio para la presentación de quejas formales, conforme a lo
                                establecido en la Ley 1581 de 2012 y normas reglamentarias.
                            </p>

                            <h3 class="font-semibold text-[var(--text)] mt-8 mb-3">
                                Canales oficiales para el ejercicio de derechos
                            </h3>
                            <p class="text-[var(--text-secondary)] leading-relaxed mb-6">
                                Para el ejercicio de los derechos relacionados con el tratamiento de datos personales
                                asociados a MultiLab, los titulares podrán dirigir sus consultas, solicitudes y
                                reclamos a la FESC a través del canal institucional de la Secretaría General:
                                <span class="font-semibold">secretario_general@fesc.edu.co</span>, de acuerdo con los
                                procedimientos y requisitos definidos en las políticas institucionales vigentes.
                            </p>
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
                                        acciones legales civiles, administrativas y penales ante las autoridades
                                        competentes, sin perjuicio de las indemnizaciones por daños y perjuicios a que
                                        hubiere lugar.
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
                </div>

            </article>
        </div>
    </div>
</x-app-layout>
