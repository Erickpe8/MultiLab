<?php

namespace App\Filament\Resources\ClassroomLoanResource\Pages;

use App\Filament\Resources\ClassroomLoanResource;
use Filament\Actions;
use App\Filament\Resources\Pages\AppListRecords;

class ListClassroomLoans extends AppListRecords
{
    protected static string $resource = ClassroomLoanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
