<?php

namespace App\Notifications;

use App\Filament\Resources\ClassroomLoanResource;
use App\Models\ClassroomLoan;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LoanApprovalRequest extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public ClassroomLoan $loan)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        $filamentNotification = FilamentNotification::make()
            ->title('Nueva Solicitud de Reserva de Aula')
            ->body("El docente {$this->loan->requester->name} ha solicitado una reserva del aula B201 para el tema '{$this->loan->subject}'.")
            ->actions([
                Action::make('view')
                    ->label('Revisar Solicitud')
                    ->url(ClassroomLoanResource::getUrl('edit', ['record' => $this->loan->id])),
            ])
            ->duration('persistent'); // Add this line

        return array_merge($filamentNotification->toArray(), ['format' => 'filament']);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
