<?php

namespace App\Notifications;

use App\Filament\Resources\LoanResource;
use App\Models\Loan;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewLoanRequestNotification extends Notification
{
    use Queueable;

    public function __construct(public Loan $loan)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $loan = $this->loan->loadMissing('materials', 'borrower');

        $borrowerName = $loan->borrower?->name ?? 'Un solicitante';
        $itemsSummary = $loan->materials
            ->map(fn ($material) => $material->pivot->loan_qty . ' × ' . $material->name)
            ->implode(', ');

        $filamentNotification = FilamentNotification::make()
            ->title('Nuevo pedido de préstamo')
            ->body(sprintf('%s solicitó: %s', $borrowerName, $itemsSummary ?: 'materiales'))
            ->icon('heroicon-o-clipboard-document')
            ->info()
            ->duration('persistent')
            ->actions([
                Action::make('reviewLoan')
                    ->label('Revisar préstamo')
                    ->url(LoanResource::getUrl('view', ['record' => $loan->getKey()]))
                    ->button(),
            ]);

        return array_merge($filamentNotification->toArray(), ['format' => 'filament']);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
