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
                    Privacidad de Datos
                </h2>
                <p class="mt-1 text-sm text-[var(--text-muted)]">
                    Política de protección de datos personales
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 sm:py-10 bg-[var(--bg)] min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <article class="bg-[var(--card)] rounded-xl border border-[var(--border)] shadow-lg overflow-hidden">

                <!-- Header del documento -->
                <div
                    class="px-6 sm:px-8 py-6 border-b border-[var(--border)] bg-gradient-to-r from-[var(--primary)]/5 to-transparent">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-[var(--primary)] to-[var(--accent)]
                                flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-[var(--text)]">Política de Privacidad y Tratamiento de
                                Datos Personales</h1>
                            <p class="text-sm text-[var(--text-muted)] mt-1">
                                Última actualización: 25 de Noviembre del 2025
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Contenido -->
                <div class="px-6 sm:px-8 py-8 prose prose-sm max-w-none">
                    <div class="space-y-10">

                        <!-- Introducción -->
                        <div class="p-4 rounded-lg bg-[var(--accent)]/10 border border-[var(--accent)]/30">
                            <p class="text-sm text-[var(--text-secondary)] leading-relaxed">
                                La Fundación de Estudios Superiores Comfanorte – FESC, como institución de educación
                                superior comprometida con la gestión responsable de la información, adopta esta Política
                                de Privacidad con el fin de garantizar el manejo ético, seguro y transparente de los
                                datos personales administrados en el Sistema MultiLab. En cumplimiento de la Ley 1581
                                de 2012, el Decreto 1377 de 2013, la Ley 1266 de 2008, el Decreto Único 1074 de 2015 y
                                la normatividad vigente en materia de protección de datos, la FESC establece los
                                parámetros que rigen la recolección, almacenamiento, tratamiento, circulación,
                                actualización y eliminación de la información personal de los usuarios. Este documento
                                refleja el compromiso institucional de proteger la integridad, disponibilidad y
                                confidencialidad de los datos, promoviendo una cultura de respeto, seguridad y buenas
                                prácticas dentro del ecosistema académico, administrativo y tecnológico asociado con
                                MultiLab.
                            </p>
                        </div>

                        <!-- 1. Información que recopilamos -->
                        <section>
                            <h2 class="text-xl font-bold text-[var(--text)] mb-6 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center text-[var(--accent)] text-sm font-bold">
                                    1
                                </span>
                                Información que Recopilamos
                            </h2>

                            <div class="text-[var(--text-secondary)] leading-relaxed space-y-4">
                                <p>
                                    MultiLab recopila información que resulta fundamental para garantizar el correcto
                                    funcionamiento del sistema, facilitar los procesos académicos y administrativos, y
                                    asegurar la trazabilidad institucional. La información recolectada permite
                                    identificar a los usuarios, registrar su actividad dentro del sistema, verificar su
                                    rol y mantener un registro confiable de las acciones ejecutadas. Todo tratamiento se
                                    realiza bajo criterios de necesidad, pertinencia y proporcionalidad, asegurando
                                    siempre el mínimo uso indispensable conforme a la normativa colombiana.
                                </p>

                                <div class="space-y-4">
                                    <!-- Item -->
                                    <div class="flex items-start gap-2">
                                        <svg class="w-5 h-5 text-[var(--accent)] shrink-0 mt-0.5" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <div>
                                            <p class="font-semibold text-[var(--text)]">Datos de Identificación</p>
                                            <p class="text-sm">
                                                Se recoge información básica como nombre completo, número de
                                                identificación, correo institucional, cargo o rol asignado en MultiLab,
                                                y demás datos necesarios para asociar al usuario con los procesos
                                                académicos y administrativos en los que participa. Esta información
                                                permite determinar permisos, accesos y responsabilidades dentro de la
                                                plataforma.
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Item -->
                                    <div class="flex items-start gap-2">
                                        <svg class="w-5 h-5 text-[var(--accent)] shrink-0 mt-0.5" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <div>
                                            <p class="font-semibold text-[var(--text)]">Datos Técnicos de Acceso</p>
                                            <p class="text-sm">
                                                Incluye direcciones IP, tipo de navegador, sistema operativo, fechas y
                                                horas de ingreso, duración de sesiones, dispositivos utilizados y
                                                registros técnicos que permiten mejorar la seguridad, identificar
                                                comportamientos inusuales y fortalecer mecanismos de autenticación.
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Item -->
                                    <div class="flex items-start gap-2">
                                        <svg class="w-5 h-5 text-[var(--accent)] shrink-0 mt-0.5" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <div>
                                            <p class="font-semibold text-[var(--text)]">Datos de Uso</p>
                                            <p class="text-sm">
                                                Corresponde a las interacciones dentro del sistema: formularios
                                                completados, actividades realizadas, procesos gestionados, tiempos de
                                                ejecución y rutas de navegación. Estos datos permiten garantizar la
                                                trazabilidad, evaluar el cumplimiento de funciones y detectar
                                                oportunidades de mejora en los procesos institucionales.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- 2. Uso de la información -->
                        <section>
                            <h2 class="text-xl font-bold text-[var(--text)] mb-6 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center text-[var(--accent)] text-sm font-bold">
                                    2
                                </span>
                                Uso de la Información
                            </h2>

                            <div class="text-[var(--text-secondary)] leading-relaxed space-y-4">
                                <p>
                                    La información recopilada por MultiLab es utilizada exclusivamente para fines
                                    institucionales y dentro del marco legal vigente. Cada dato almacenado cumple una
                                    función específica dentro de los procesos académicos, administrativos y operativos,
                                    permitiendo la adecuada gestión del Plan Operativo Anual. La FESC garantiza que el
                                    tratamiento de los datos se realiza siguiendo criterios de finalidad, veracidad,
                                    seguridad y confidencialidad, evitando el uso indebido o no autorizado de la
                                    información personal.
                                </p>

                                <ul class="list-disc list-inside space-y-2 ml-4">
                                    <li>Garantizar el correcto funcionamiento, disponibilidad y estabilidad del sistema.
                                    </li>
                                    <li>Administrar procesos académicos, administrativos y operativos asociados al POA.
                                    </li>
                                    <li>Ejecutar controles internos, auditorías digitales y seguimiento institucional.
                                    </li>
                                    <li>Optimizar la experiencia del usuario mediante mejoras tecnológicas y de diseño.
                                    </li>
                                    <li>Cumplir requerimientos legales, regulatorios o administrativos exigidos por
                                        autoridades como el MEN, SIC o entes de control.</li>
                                </ul>
                            </div>
                        </section>

                        <!-- 3. Protección de datos -->
                        <section>
                            <h2 class="text-xl font-bold text-[var(--text)] mb-6 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center text-[var(--accent)] text-sm font-bold">
                                    3
                                </span>
                                Protección y Seguridad de los Datos
                            </h2>

                            <div class="text-[var(--text-secondary)] leading-relaxed space-y-3">
                                <p>
                                    La FESC implementa un conjunto de medidas técnicas, administrativas y
                                    organizacionales destinadas a garantizar la seguridad de los datos tratados en
                                    MultiLab. Estas medidas buscan evitar el acceso no autorizado, la pérdida de
                                    información, filtraciones, alteraciones o usos indebidos. La protección de datos es
                                    un compromiso permanente dentro de la institución y se fortalece a través de
                                    auditorías, actualizaciones tecnológicas y políticas internas de seguridad.
                                </p>

                                <ul class="list-disc list-inside space-y-2 ml-4">
                                    <li>Sistemas avanzados de encriptación para los datos sensibles almacenados y
                                        transmitidos.</li>
                                    <li>Autenticación segura mediante credenciales institucionales, roles y permisos.
                                    </li>
                                    <li>Controles de acceso basados en funciones, evitando la manipulación por usuarios
                                        no autorizados.</li>
                                    <li>Monitoreo constante de la actividad dentro del sistema para detectar
                                        comportamientos anómalos.</li>
                                    <li>Mecanismos de respaldo automatizado y protocolos de recuperación ante fallos.
                                    </li>
                                    <li>Uso obligatorio de canales cifrados SSL/TLS para proteger la información en
                                        tránsito.</li>
                                </ul>
                            </div>
                        </section>

                        <!-- 4. Derechos del titular -->
                        <section>
                            <h2 class="text-xl font-bold text-[var(--text)] mb-6 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center text-[var(--accent)] text-sm font-bold">
                                    4
                                </span>
                                Derechos del Titular de los Datos
                            </h2>

                            <div class="text-[var(--text-secondary)] leading-relaxed space-y-3">
                                <p>
                                    Los titulares de la información personal registrada en MultiLab cuentan con un
                                    conjunto de derechos protegidos por la Ley 1581 de 2012 y demás normativas
                                    aplicables. La FESC garantiza que los usuarios podrán ejercer estos derechos a
                                    través de mecanismos formales, seguros y accesibles, asegurando siempre una
                                    respuesta clara, oportuna y transparente.
                                </p>

                                <ul class="list-disc list-inside space-y-2 ml-4">
                                    <li>Acceder a la información personal registrada en el sistema.</li>
                                    <li>Solicitar corrección, actualización o rectificación de datos incorrectos o
                                        incompletos.</li>
                                    <li>Solicitar prueba de la autorización otorgada para el tratamiento de sus datos.
                                    </li>
                                    <li>Ser informado sobre el uso específico que se da a la información recolectada.
                                    </li>
                                    <li>Presentar quejas ante la Superintendencia de Industria y Comercio si considera
                                        vulnerados sus derechos.</li>
                                    <li>Solicitar la supresión de datos cuando desaparezca la finalidad o subsistan
                                        incumplimientos normativos.</li>
                                    <li>Revocar la autorización otorgada en cualquier momento, salvo restricciones
                                        legales.</li>
                                </ul>
                            </div>
                        </section>

                        <!-- 5. Compartir información -->
                        <section>
                            <h2 class="text-xl font-bold text-[var(--text)] mb-6 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center text-[var(--accent)] text-sm font-bold">
                                    5
                                </span>
                                Transferencia y Compartición de Información
                            </h2>

                            <p class="text-[var(--text-secondary)] leading-relaxed">
                                La información registrada en MultiLab no será compartida con terceros sin la
                                autorización expresa del titular, excepto en situaciones previstas por la ley o cuando
                                resulte necesario para el cumplimiento de funciones institucionales. La FESC garantiza
                                que toda transferencia de datos será realizada bajo estrictos protocolos de seguridad y
                                únicamente a entidades debidamente autorizadas, tales como organismos estatales de
                                control, entidades reguladoras o áreas internas responsables de procesos académicos o
                                administrativos.
                            </p>
                        </section>

                        <!-- 6. Tratamiento por terceros autorizados -->
                        <section>
                            <h2 class="text-xl font-bold text-[var(--text)] mb-6 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center text-[var(--accent)] text-sm font-bold">
                                    6
                                </span>
                                Encargados del Tratamiento y Acceso Autorizado
                            </h2>

                            <p class="text-[var(--text-secondary)] leading-relaxed">
                                La FESC podrá delegar el tratamiento de datos a terceros internos o externos que cuenten
                                con la capacidad técnica y administrativa para garantizar la seguridad de la
                                información. Cada encargado deberá cumplir estrictamente las obligaciones derivadas de
                                la ley, los principios de esta política y los acuerdos de confidencialidad establecidos
                                por la institución. En ningún caso se permitirá que terceros accedan, modifiquen o
                                utilicen datos personales sin autorización formal, expresa y verificable.
                            </p>
                        </section>

                        <!-- 7. Procedimientos del titular -->
                        <section>
                            <h2 class="text-xl font-bold text-[var(--text)] mb-6 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-[var(--accent)]/10 flex items-center justify-center text-[var(--accent)] text-sm font-bold">
                                    7
                                </span>
                                Procedimientos para Consultas, Reclamos, Rectificación y Supresión
                            </h2>

                            <div class="text-[var(--text-secondary)] leading-relaxed space-y-4">

                                <p>
                                    Para garantizar el ejercicio pleno de los derechos de los titulares, la FESC dispone
                                    de mecanismos formales y gratuitos que permiten presentar consultas, reclamos,
                                    solicitudes de rectificación, actualizaciones, supresión de datos o revocatorias de
                                    autorización. Estos procesos se realizan conforme a los plazos establecidos por la
                                    legislación colombiana y a través de los canales institucionales habilitados para
                                    tal fin.
                                </p>

                                <!-- Consultas -->
                                <div>
                                    <p class="font-semibold text-[var(--text)] mb-2">7.1 Consultas</p>
                                    <p>
                                        Los titulares podrán consultar la información personal contenida en MultiLab
                                        mediante solicitud enviada al correo institucional: <span class="font-medium text-[var(--accent)]">secretario_general@fesc.edu.co</span>
                                        <br>
                                        Las solicitudes serán atendidas dentro de los diez (10) días hábiles siguientes,
                                        conforme a lo establecido en la normatividad vigente.
                                    </p>
                                </div>

                                <!-- Reclamos -->
                                <div>
                                    <p class="font-semibold text-[var(--text)] mb-2">7.2 Reclamos</p>
                                    <p>
                                        Para corrección, actualización o supresión de datos, el titular podrá presentar
                                        un reclamo formal que incluya la descripción del hecho, los datos a corregir y
                                        la documentación necesaria. La FESC dará respuesta dentro de los quince (15)
                                        días hábiles siguientes, prorrogables según la complejidad del caso.
                                    </p>
                                </div>

                                <!-- Revocatoria -->
                                <div>
                                    <p class="font-semibold text-[var(--text)] mb-2">7.3 Revocatoria de la Autorización
                                    </p>
                                    <p>
                                        La revocatoria de autorización podrá solicitarse cuando el titular considere que
                                        no se respetan los principios, deberes o garantías constitucionales y legales
                                        que rigen el tratamiento de datos. En estos casos, la FESC evaluará la solicitud
                                        y determinará si la finalidad del tratamiento aún subsiste o si procede la
                                        revocatoria total o parcial.
                                    </p>
                                </div>

                                <!-- Canales de contacto -->
                                <div class="mt-3">
                                    <p class="font-medium text-[var(--text)] mb-1">Canales oficiales</p>

                                    <ul class="list-disc list-inside space-y-1 ml-4">
                                        <li>Secretaría General: <span
                                                class="font-medium text-[var(--accent)]">secretario_general@fesc.edu.co</span>
                                        </li>
                                        <li>Consultas de soporte técnico: Unidad de Desarrollo de Software</li>
                                    </ul>
                                </div>

                            </div>
                        </section>

                    </div>
                </div>

                <!-- Footer del documento -->
                <div class="px-6 sm:px-8 py-4 border-t border-[var(--border)] bg-[var(--border)]/5">
                    <p class="text-xs text-[var(--text-secondary)] text-center">
                        Esta política podrá ser actualizada conforme a la normativa vigente. Las modificaciones serán
                        informadas a través de los canales institucionales oficiales de la FESC.
                    </p>
                </div>

            </article>
        </div>
    </div>
</x-app-layout>
