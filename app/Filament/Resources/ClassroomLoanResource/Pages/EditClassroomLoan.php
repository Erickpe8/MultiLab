<?php

namespace App\Filament\Resources\ClassroomLoanResource\Pages;

use App\Filament\Resources\ClassroomLoanResource;
use App\Filament\Resources\Pages\AppEditRecord;
use App\Notifications\LoanApproved;
use Filament\Actions;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;

class EditClassroomLoan extends AppEditRecord
{
    protected static string $resource = ClassroomLoanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data = $this->splitDateTimeIntoTime($data, 'scheduled_end_at', 'scheduled_end_time');
        $data = $this->splitDateTimeIntoTime($data, 'actual_end_at', 'actual_end_time');

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = $this->mergeStartAndTime($data, 'scheduled_start_at', 'scheduled_end_time', 'scheduled_end_at');
        $data = $this->mergeStartAndTime($data, 'actual_start_at', 'actual_end_time', 'actual_end_at');

        return $data;
    }

    protected function afterSave(): void
    {
        if ($this->record->wasChanged('approved_by') && $this->record->approved_by !== null) {
            $requester = $this->record->requester; // Assuming ClassroomLoan model has 'requester' relationship
            if ($requester) {
                Notification::send($requester, new LoanApproved($this->record));
            }
        }
    }

    protected function splitDateTimeIntoTime(array $data, string $sourceKey, string $timeKey): array
    {
        if (empty($data[$sourceKey])) {
            return $data;
        }

        $data[$timeKey] = Carbon::parse($data[$sourceKey])->format('H:i');

        return $data;
    }

    protected function mergeStartAndTime(array $data, string $dateKey, string $timeKey, string $targetKey): array
    {
        if (
            empty($data[$dateKey] ?? null) ||
            empty($data[$timeKey] ?? null)
        ) {
            unset($data[$timeKey]);

            return $data;
        }

        $date = Carbon::parse($data[$dateKey]);
        $time = Carbon::parse($data[$timeKey]);

        $data[$targetKey] = $date
            ->copy()
            ->setTime($time->hour, $time->minute);

        unset($data[$timeKey]);

        return $data;
    }
}
