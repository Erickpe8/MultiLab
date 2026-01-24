<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Facades\DB;
use Filament\Widgets\ChartWidget;

class TopBorrowedMaterials extends ChartWidget
{
    protected static ?string $heading = 'Materiales más prestados';

    protected static ?string $maxHeight = '280px';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $topMaterials = DB::table('loan_materials as lm')
            ->join('materials as m', 'lm.material_id', '=', 'm.id')
            ->select('m.name', DB::raw('SUM(lm.loan_qty) as total_loaned'))
            ->groupBy('m.id', 'm.name')
            ->orderByDesc('total_loaned')
            ->limit(5)
            ->get();

        if ($topMaterials->isEmpty()) {
            return [
                'datasets' => [[
                    'label' => 'Préstamos',
                    'data' => [0],
                    'backgroundColor' => ['#60a5fa'],
                    'borderRadius' => 6,
                ]],
                'labels' => ['Sin datos'],
            ];
        }

        return [
            'datasets' => [[
                'label' => 'Unidades prestadas',
                'data' => $topMaterials->pluck('total_loaned')->map(fn ($value) => (int) $value),
                'backgroundColor' => '#8E1616',
                'borderRadius' => 6,
            ]],
            'labels' => $topMaterials->pluck('name'),
        ];
    }
}
