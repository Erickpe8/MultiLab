<?php

namespace App\Filament\Widgets;

use App\Models\Loan;
use Filament\Widgets\ChartWidget;

class LoanStatusBreakdown extends ChartWidget
{
    protected static ?string $heading = 'Préstamos por estado';

    protected static ?string $maxHeight = '260px';

    protected int|string|array $columnSpan = [
        'sm' => 2,
        'md' => 2,
        'xl' => 1,
    ];

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $statuses = [
            'pendiente',
            'aprobado',
            'rechazado',
            'cancelado',
            'devuelto',
            'con_multa',
            'perdido',
            'vencido',
        ];

        $counts = Loan::query()
            ->selectRaw('COALESCE(status, "pendiente") as status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $labels = collect($statuses)
            ->filter(fn ($status) => ($counts[$status] ?? 0) > 0)
            ->values();

        if ($labels->isEmpty()) {
            $labels = collect(['pendiente']);
        }

        $data = $labels->map(fn ($status) => (int) ($counts[$status] ?? 0))->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Préstamos',
                    'data' => $data,
                    'backgroundColor' => [
                        '#facc15',
                        '#34d399',
                        '#f87171',
                        '#f97316',
                        '#60a5fa',
                        '#a78bfa',
                        '#f472b6',
                        '#f59e0b',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels
                ->map(fn ($status) => ucfirst(str_replace('_', ' ', $status)))
                ->toArray(),
        ];
    }
}
