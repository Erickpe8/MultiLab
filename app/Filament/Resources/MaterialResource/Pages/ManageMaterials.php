<?php

namespace App\Filament\Resources\MaterialResource\Pages;

use App\Filament\Resources\MaterialResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMaterials extends ManageRecords
{
    protected static string $resource = MaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('showLoanCharts')
                ->label('Ver métricas de préstamos')
                ->icon('heroicon-o-chart-pie')
                ->color('gray')
                ->modalHeading('Actividad de préstamos')
                ->modalWidth('4xl')
                ->modalContent(view('filament.widgets.loan-metrics')),
            Actions\CreateAction::make(),
        ];
    }
}
