<?php

namespace App\Notifications;

use App\Filament\Resources\LoanResource;
use App\Filament\Resources\MaterialRequestResource;
use App\Models\MaterialRequest;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewMaterialRequestNotification extends Notification
{
    use Queueable;

    public function __construct(public MaterialRequest $materialRequest)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $material = $this->materialRequest->material;
        $requester = $this->materialRequest->requester;

        $materialName = $material?->name ?? 'material';
        $requesterName = $requester?->name ?? 'Un estudiante';
        $quantity = $this->materialRequest->quantity;
        $neededAt = optional($this->materialRequest->needed_at)?->format('d/m H:i') ?? 'una fecha pendiente';

        $filamentNotification = FilamentNotification::make()
            ->title('Nueva solicitud de material')
            ->body(
                sprintf(
                    '%s solicitó %d %s para %s.',
                    $requesterName,
                    $quantity,
                    strtolower($materialName),
                    $neededAt,
                )
            )
            ->icon('heroicon-o-paper-airplane')
            ->info()
            ->duration('persistent')
            ->actions([
                Action::make('view')
                    ->label('Ver detalles')
                    ->url(
                        MaterialRequestResource::getUrl('view', [
                            'record' => $this->materialRequest->getKey(),
                        ])
                    )
                    ->button(),
                Action::make('createLoan')
                    ->label('Crear préstamo')
                    ->url(
                        LoanResource::getUrl('create', [
                            'material_request' => $this->materialRequest->getKey(),
                        ])
                    )
                    ->button(),
            ]);

        return array_merge($filamentNotification->toArray(), ['format' => 'filament']);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
