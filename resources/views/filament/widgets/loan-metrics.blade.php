<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        @php
            $totalLoans = \App\Models\Loan::count();
            $pendingLoans = \App\Models\Loan::where('status', 'pendiente')->count();
            $overdueLoans = \App\Models\Loan::whereNull('return_at')
                ->whereNotNull('due_at')
                ->where('due_at', '<', now())
                ->count();
        @endphp

        <div class="rounded-2xl border border-[var(--border)] bg-[var(--card)] p-4">
            <p class="text-xs uppercase tracking-[0.4em] text-[var(--text-muted)]">Total</p>
            <p class="text-3xl font-semibold text-[var(--text)]">{{ $totalLoans }}</p>
            <p class="text-sm text-[var(--text-muted)]">Préstamos registrados</p>
        </div>
        <div class="rounded-2xl border border-[var(--border)] bg-[var(--card)] p-4">
            <p class="text-xs uppercase tracking-[0.4em] text-[var(--text-muted)]">Pendientes</p>
            <p class="text-3xl font-semibold text-amber-500">{{ $pendingLoans }}</p>
            <p class="text-sm text-[var(--text-muted)]">Aún sin decisión</p>
        </div>
        <div class="rounded-2xl border border-[var(--border)] bg-[var(--card)] p-4">
            <p class="text-xs uppercase tracking-[0.4em] text-[var(--text-muted)]">Vencidos</p>
            <p class="text-3xl font-semibold text-rose-500">{{ $overdueLoans }}</p>
            <p class="text-sm text-[var(--text-muted)]">Requieren seguimiento</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="rounded-3xl border border-[var(--border)] bg-[var(--card)] p-4">
            <livewire:app.filament.widgets.loan-status-breakdown />
        </div>

        <div class="rounded-3xl border border-[var(--border)] bg-[var(--card)] p-4">
            <livewire:app.filament.widgets.monthly-loan-requests-chart />
        </div>
    </div>

    <div class="rounded-3xl border border-[var(--border)] bg-[var(--card)] p-4">
        <livewire:app.filament.widgets.overdue-loans-trend />
    </div>
</div>
