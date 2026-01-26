<?php

namespace App\Filament\Widgets;

use App\Models\Loan;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class MonthlyLoanRequestsChart extends ChartWidget
{
    protected static ?string $heading = 'Préstamos creados por mes';

    protected static ?string $maxHeight = '300px';

    protected int|string|array $columnSpan = [
        'sm' => 2,
        'md' => 2,
        'xl' => 2,
    ];

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $range = collect(range(0, 5))->map(fn (int $offset) => Carbon::now()->subMonths(5 - $offset)->startOfMonth());

        $records = Loan::query()
            ->selectRaw('DATE_FORMAT(loan_at, "%Y-%m") as month_key, COUNT(*) as total')
            ->where('loan_at', '>=', $range->first())
            ->groupBy('month_key')
            ->orderBy('month_key')
            ->pluck('total', 'month_key');

        $labels = $range->map(fn (Carbon $date) => $date->isoFormat('MMM YYYY'));

        $data = $range->map(function (Carbon $date) use ($records) {
            $key = $date->format('Y-m');
            return (int) ($records[$key] ?? 0);
        });

        return [
            'datasets' => [
                [
                    'label' => 'Préstamos registrados',
                    'data' => $data,
                    'backgroundColor' => '#1D4ED8',
                    'borderRadius' => 6,
                    'barThickness' => 24,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
