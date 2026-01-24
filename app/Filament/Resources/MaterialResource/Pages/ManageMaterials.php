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
            Actions\Action::make('showMaterialMetrics')
                ->label('Ver métricas de materiales')
                ->icon('heroicon-o-chart-bar')
                ->color('gray')
                ->modalHeading('Estado del inventario')
                ->modalWidth('5xl')
                ->modalContent(view('filament.widgets.material-metrics')),
            Actions\CreateAction::make(),
        ];
    }
}
