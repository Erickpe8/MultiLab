<?php

namespace App\Filament\Resources\MaterialRequestResource\Pages;

use App\Filament\Resources\LoanResource;
use App\Filament\Resources\MaterialRequestResource;
use App\Filament\Resources\Pages\AppViewRecord;
use App\Helpers\RoleHelper;
use App\Models\Loan;
use Filament\Actions;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ViewMaterialRequest extends AppViewRecord
{
    protected static string $resource = MaterialRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('reject')
                ->label('Rechazar solicitud')
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->visible(fn () => RoleHelper::hasAnyRole(['superadmin', 'aux_admin']) && $this->record->status === 'pendiente')
                ->action(function () {
                    $requestRecord = $this->record;

                    DB::transaction(function () use ($requestRecord) {
                        $requestRecord->update([
                            'status' => 'rechazada',
                        ]);

                        $loan = Loan::create([
                            'user_id' => $requestRecord->user_id,
                            'issued_by' => Auth::id(),
                            'loan_code' => 'R-' . strtoupper(Str::random(8)),
                            'loan_at' => $requestRecord->needed_at ?? now(),
                            'due_at' => $requestRecord->planned_return_at ?? now(),
                            'status' => 'rechazado',
                            'notes' => sprintf(
                                'Solicitud #%d rechazada automáticamente por la administración.',
                                $requestRecord->id,
                            ),
                        ]);

                        if ($requestRecord->material_id && $requestRecord->quantity) {
                            $loan->materials()->attach($requestRecord->material_id, [
                                'loan_qty' => $requestRecord->quantity,
                                'returned_qty' => 0,
                            ]);
                        }
                    });

                    if ($requestRecord->requester) {
                        Notification::make()
                            ->title('Solicitud rechazada')
                            ->body('Tu solicitud fue rechazada por el laboratorio.')
                            ->danger()
                            ->sendToDatabase($requestRecord->requester);
                    }
                })
                ->after(fn () => $this->redirect($this->getCurrentRecordUrl()))
                ->modalWidth('md'),
            Actions\Action::make('createLoan')
                ->label('Crear préstamo')
                ->icon('heroicon-o-clipboard-document-list')
                ->color('primary')
                ->visible(fn () => RoleHelper::hasAnyRole(['superadmin', 'aux_admin']) && $this->record->status !== 'rechazada')
                ->url(fn () => LoanResource::getUrl('create', ['material_request' => $this->record->getKey()])),
        ];
    }

    private function getCurrentRecordUrl(): string
    {
        return MaterialRequestResource::getUrl('view', ['record' => $this->record]);
    }
}
