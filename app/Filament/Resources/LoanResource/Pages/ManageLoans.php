<?php

namespace App\Filament\Resources\LoanResource\Pages;

use App\Filament\Resources\LoanResource;
use App\Filament\Resources\Pages\AppManageRecords;
use Filament\Actions;

class ManageLoans extends AppManageRecords
{
    protected static string $resource = LoanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
