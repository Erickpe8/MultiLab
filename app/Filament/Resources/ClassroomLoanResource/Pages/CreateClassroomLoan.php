<?php

namespace App\Filament\Resources\ClassroomLoanResource\Pages;

use App\Filament\Resources\ClassroomLoanResource;
use Filament\Actions;
use App\Filament\Resources\Pages\AppCreateRecord;
use Illuminate\Support\Carbon;

class CreateClassroomLoan extends AppCreateRecord
{
    protected static string $resource = ClassroomLoanResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = $this->mergeStartAndTime($data, 'scheduled_start_at', 'scheduled_end_time', 'scheduled_end_at');
        $data = $this->mergeStartAndTime($data, 'actual_start_at', 'actual_end_time', 'actual_end_at');

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
