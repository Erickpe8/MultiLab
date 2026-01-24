<?php

namespace App\Filament\Widgets;

use App\Models\Material;
use Filament\Widgets\ChartWidget;

class MaterialStockDistribution extends ChartWidget
{
    protected static ?string $heading = 'Salud del stock';

    protected static ?string $maxHeight = '260px';

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $buckets = [
            'Sin stock' => 0,
            'Crítico' => 0,
            'Saludable' => 0,
        ];

        Material::select('current_stock', 'min_stock')->chunk(200, function ($materials) use (&$buckets) {
            foreach ($materials as $material) {
                $current = (int) ($material->current_stock ?? 0);
                $min = (int) ($material->min_stock ?? 0);

                if ($current <= 0) {
                    $buckets['Sin stock']++;
                } elseif ($min > 0 && $current <= $min) {
                    $buckets['Crítico']++;
                } else {
                    $buckets['Saludable']++;
                }
            }
        });

        $labels = array_keys($buckets);
        $data = array_values($buckets);

        return [
            'datasets' => [
                [
                    'label' => 'Materiales',
                    'data' => $data,
                    'backgroundColor' => [
                        '#f87171',
                        '#facc15',
                        '#34d399',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
