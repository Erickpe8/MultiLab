<?php

namespace App\Filament\Resources\MaterialCatalogResource\Pages;

use App\Filament\Resources\MaterialCatalogResource;
use App\Filament\Resources\Pages\AppListRecords;

class BrowseMaterials extends AppListRecords
{
    protected static string $resource = MaterialCatalogResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
