<?php

namespace App\Filament\Resources\ClassroomLoanResource\Pages;

use App\Filament\Resources\ClassroomLoanResource;
use Filament\Actions;
use App\Filament\Resources\Pages\AppEditRecord;

class EditClassroomLoan extends AppEditRecord
{
    protected static string $resource = ClassroomLoanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
