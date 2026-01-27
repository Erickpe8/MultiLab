<div class="space-y-6">
    <div>
        <p class="text-xs uppercase tracking-[0.4em] text-[var(--text-muted)]">Sección 6 · Reservas Aula B202</p>
        <h2 class="text-2xl font-semibold text-[var(--text)]">Solicitar y entender estados</h2>
    </div>
    <article class="rounded-3xl border border-[var(--border)] bg-[var(--card)] p-5 shadow-sm text-sm text-[var(--text-muted)]">
        <p class="text-xs uppercase tracking-[0.3em] text-[var(--text-muted)]">6.1 Solicitar reserva (Docente / Administrador Auxiliar)</p>
        <ol class="mt-3 space-y-2 list-decimal pl-5">
            <li>Elige Aula B202 y completa la fecha, hora de inicio y hora de finalización.</li>
            <li>Detalla el propósito académico, el número estimado de asistentes y los materiales requeridos.</li>
            <li>Envía la solicitud y espera la confirmación del auxiliar, quien validará conflicto de horario y equipos.</li>
        </ol>
        <div class="mt-3 text-xs text-amber-500">Error común: enviar varias solicitudes iguales. Espera respuesta antes de crear otra.</div>
    </article>
    <article class="rounded-3xl border border-[var(--border)] bg-[var(--card)] p-5 shadow-sm text-sm text-[var(--text-muted)]">
        <p class="text-xs uppercase tracking-[0.3em] text-[var(--text-muted)]">6.2 Estados de la reserva</p>
        <div class="mt-3 grid gap-3 md:grid-cols-2">
            <div class="rounded-2xl border border-[var(--border)] bg-[var(--bg)] p-3">
                <p class="font-semibold text-[var(--text)]">Pendiente</p>
                <p>La solicitud está en revisión. Un Administrador Auxiliar debe responder.</p>
            </div>
            <div class="rounded-2xl border border-[var(--border)] bg-[var(--bg)] p-3">
                <p class="font-semibold text-[var(--text)]">Aprobada</p>
                <p>El espacio quedó reservado y aparece en el calendario.</p>
            </div>
            <div class="rounded-2xl border border-[var(--border)] bg-[var(--bg)] p-3">
                <p class="font-semibold text-[var(--text)]">En uso</p>
                <p>La sesión está activa y el aula debe figurar como ocupada.</p>
            </div>
            <div class="rounded-2xl border border-[var(--border)] bg-[var(--bg)] p-3">
                <p class="font-semibold text-[var(--text)]">Finalizada</p>
                <p>El evento concluyó y el aula vuelve a estar disponible.</p>
            </div>
            <div class="rounded-2xl border border-[var(--border)] bg-[var(--bg)] p-3">
                <p class="font-semibold text-[var(--text)]">Cancelada</p>
                <p>La reserva se anuló; no genera historial activo.</p>
            </div>
        </div>
    </article>
</div>
