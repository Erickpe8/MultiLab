<?php

namespace App\Filament\Resources\LoanResource\Pages;

use App\Filament\Resources\LoanResource;
use App\Filament\Resources\Pages\AppManageRecords;
use App\Helpers\RoleHelper;
use Filament\Actions;

class ManageLoans extends AppManageRecords
{
    protected static string $resource = LoanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('goToCreate')
                ->label('Registrar préstamo')
                ->icon('heroicon-o-plus-circle')
                ->color('primary')
                ->url(fn () => LoanResource::getUrl('create'))
                ->visible(fn () => RoleHelper::hasAnyRole(['superadmin', 'aux_admin'])),
        ];
    }
}
