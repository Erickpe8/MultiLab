<?php

namespace App\Notifications;

use App\Filament\Resources\ClassroomLoanResource;
use App\Models\ClassroomLoan;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LoanApproved extends Notification
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
            ->title('Reserva de Aula Aprobada')
            ->body("Tu reserva del aula B202 para el tema '{$this->loan->subject}' ha sido aprobada por {$this->loan->approver->name}.")
            ->actions([
                Action::make('view')
                    ->label('Ver Detalles de la Reserva')
                    ->url(ClassroomLoanResource::getUrl('view', ['record' => $this->loan->id])),
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
