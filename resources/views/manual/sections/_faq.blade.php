<div class="space-y-5">
    <div>
        <p class="text-xs uppercase tracking-[0.4em] text-[var(--text-muted)]">Sección 12 · FAQ</p>
        <h2 class="text-2xl font-semibold text-[var(--text)]">Preguntas frecuentes</h2>
        <p class="text-sm text-[var(--text-muted)]">1015 preguntas típicas: aquí respondemos las más frecuentes alineadas con los módulos descritos.</p>
    </div>
    <div class="space-y-3">
        @foreach ($faqEntries as $entry)
            <details class="rounded-2xl border border-[var(--border)] bg-[var(--card)] p-4 text-sm shadow-sm">
                <summary class="cursor-pointer font-semibold text-[var(--text)]">{{ $entry['question'] }}</summary>
                <p class="mt-2 text-[var(--text-muted)]">{{ $entry['answer'] }}</p>
            </details>
        @endforeach
    </div>
</div>
