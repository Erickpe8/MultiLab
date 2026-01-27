<div class="space-y-6">
    @php
        $totalMaterials = \App\Models\Material::count();
        $zeroStock = \App\Models\Material::where('current_stock', '<=', 0)->count();
        $criticalStock = \App\Models\Material::where('current_stock', '>', 0)
            ->whereColumn('current_stock', '<=', 'min_stock')
            ->count();
        $totalUnits = \App\Models\Material::sum('current_stock');
        $loanedUnits = (int) (\Illuminate\Support\Facades\DB::table('loan_materials as lm')
            ->join('loans as l', 'l.id', '=', 'lm.loan_id')
            ->whereNotIn('l.status', ['devuelto', 'devuelto_con_multa', 'cancelado', 'rechazado'])
            ->selectRaw('SUM(GREATEST(lm.loan_qty - lm.returned_qty, 0)) as total')
            ->value('total') ?? 0);
        $availableUnits = max($totalUnits - $loanedUnits, 0);

        $lowStockMaterials = \App\Models\Material::select('name', 'current_stock', 'min_stock')
            ->where(function ($query) {
                $query->where('current_stock', '<=', 0)
                    ->orWhereColumn('current_stock', '<=', 'min_stock');
            })
            ->orderBy('current_stock')
            ->limit(5)
            ->get();

        $recentMovements = \Illuminate\Support\Facades\DB::table('loan_materials as lm')
            ->join('materials as m', 'lm.material_id', '=', 'm.id')
            ->join('loans as l', 'lm.loan_id', '=', 'l.id')
            ->join('users as u', 'l.user_id', '=', 'u.id')
            ->select(
                'm.name as material',
                'lm.loan_qty',
                'l.status',
                'l.loan_at',
                'u.first_name',
                'u.first_surname',
            )
            ->orderByDesc('l.loan_at')
            ->limit(6)
            ->get();
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="rounded-2xl border border-[var(--border)] bg-[var(--card)] p-4">
            <p class="text-xs uppercase tracking-[0.4em] text-[var(--text-muted)]">Materiales</p>
            <p class="text-3xl font-semibold text-[var(--text)]">{{ $totalMaterials }}</p>
            <p class="text-sm text-[var(--text-muted)]">Registrados</p>
        </div>
        <div class="rounded-2xl border border-[var(--border)] bg-[var(--card)] p-4">
            <p class="text-xs uppercase tracking-[0.4em] text-[var(--text-muted)]">Sin stock</p>
            <p class="text-3xl font-semibold text-rose-500">{{ $zeroStock }}</p>
            <p class="text-sm text-[var(--text-muted)]">Requieren reposición</p>
        </div>
        <div class="rounded-2xl border border-[var(--border)] bg-[var(--card)] p-4">
            <p class="text-xs uppercase tracking-[0.4em] text-[var(--text-muted)]">Bajo stock</p>
            <p class="text-3xl font-semibold text-amber-500">{{ $criticalStock }}</p>
            <p class="text-sm text-[var(--text-muted)]">Por debajo del mínimo</p>
        </div>
        <div class="rounded-2xl border border-[var(--border)] bg-[var(--card)] p-4">
            <p class="text-xs uppercase tracking-[0.4em] text-[var(--text-muted)]">Disponibles</p>
            <p class="text-3xl font-semibold text-emerald-500">{{ number_format($availableUnits) }}</p>
            <p class="text-sm text-[var(--text-muted)]">Unidades en bodega</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="rounded-3xl border border-[var(--border)] bg-[var(--card)] p-4">
            <livewire:app.filament.widgets.material-stock-distribution />
        </div>

        <div class="rounded-3xl border border-[var(--border)] bg-[var(--card)] p-4">
            <livewire:app.filament.widgets.top-borrowed-materials />
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="rounded-3xl border border-[var(--border)] bg-[var(--card)] p-4">
            <p class="text-sm font-semibold text-[var(--text)] mb-3">Alerta de bajo stock</p>
            @if ($lowStockMaterials->isEmpty())
                <p class="text-sm text-[var(--text-muted)]">Todos los materiales están por encima del mínimo.</p>
            @else
                <ul class="divide-y divide-[var(--border)]">
                    @foreach ($lowStockMaterials as $item)
                        <li class="py-3 flex items-center justify-between gap-3">
                            <div>
                                <p class="font-medium text-[var(--text)]">{{ $item->name }}</p>
                                <p class="text-xs text-[var(--text-muted)]">Mínimo {{ $item->min_stock ?? 0 }} unidades</p>
                            </div>
                            <span class="text-sm font-semibold text-amber-500">{{ $item->current_stock }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="rounded-3xl border border-[var(--border)] bg-[var(--card)] p-4">
            <p class="text-sm font-semibold text-[var(--text)] mb-3">Últimos movimientos</p>
            @if ($recentMovements->isEmpty())
                <p class="text-sm text-[var(--text-muted)]">Aún no hay préstamos registrados.</p>
            @else
                <ul class="divide-y divide-[var(--border)]">
                    @foreach ($recentMovements as $movement)
                        <li class="py-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="font-medium text-[var(--text)]">{{ $movement->material }}</p>
                                    <p class="text-xs text-[var(--text-muted)]">
                                        {{ trim(($movement->first_name ?? '') . ' ' . ($movement->first_surname ?? '')) ?: '—' }} ·
                                        {{ \Illuminate\Support\Carbon::parse($movement->loan_at)->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                <span class="text-sm font-semibold text-[var(--accent)]">x{{ $movement->loan_qty }}</span>
                            </div>
                            <p class="mt-1 text-xs uppercase tracking-[0.3em] text-[var(--text-muted)]">
                                Estado: {{ strtoupper(str_replace('_', ' ', $movement->status)) }}
                            </p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
