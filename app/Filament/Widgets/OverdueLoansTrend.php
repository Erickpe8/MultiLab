<?php

namespace App\Filament\Widgets;

use App\Models\Loan;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class OverdueLoansTrend extends ChartWidget
{
    protected static ?string $heading = 'Préstamos vencidos por mes';

    protected static ?string $maxHeight = '300px';

    protected int|string|array $columnSpan = [
        'sm' => 2,
        'md' => 2,
        'xl' => 2,
    ];

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $range = collect(range(0, 5))->map(fn (int $offset) => Carbon::now()->subMonths(5 - $offset)->startOfMonth());

        $records = Loan::query()
            ->selectRaw('DATE_FORMAT(due_at, "%Y-%m") as month_key, COUNT(*) as total')
            ->whereNotNull('due_at')
            ->where('due_at', '>=', $range->first())
            ->where(function ($query) {
                $query->whereNull('return_at')
                    ->orWhereColumn('return_at', '>', 'due_at');
            })
            ->groupBy('month_key')
            ->pluck('total', 'month_key');

        $labels = $range->map(fn (Carbon $date) => $date->isoFormat('MMM YYYY'));

        $data = $range->map(function (Carbon $date) use ($records) {
            $key = $date->format('Y-m');
            return (int) ($records[$key] ?? 0);
        });

        return [
            'datasets' => [
                [
                    'label' => 'Préstamos vencidos',
                    'data' => $data,
                    'borderColor' => '#f97316',
                    'backgroundColor' => 'rgba(249, 115, 22, 0.2)',
                    'tension' => 0.45,
                    'fill' => 'origin',
                ],
            ],
            'labels' => $labels,
        ];
    }
}
