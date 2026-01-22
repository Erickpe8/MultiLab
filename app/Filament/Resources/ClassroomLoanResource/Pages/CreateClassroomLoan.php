<?php

namespace App\Filament\Resources\ClassroomLoanResource\Pages;

use App\Filament\Resources\ClassroomLoanResource;
use App\Filament\Resources\Pages\AppCreateRecord;
use App\Models\User;
use App\Notifications\LoanApprovalRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class CreateClassroomLoan extends AppCreateRecord
{
    protected static string $resource = ClassroomLoanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = $this->mergeStartAndTime($data, 'scheduled_start_at', 'scheduled_end_time', 'scheduled_end_at');


        $status = $data['status'] ?? null;

        if (! empty($data['approved_by']) && ($status === null || $status === 'pendiente')) {
            $data['status'] = 'aprobado';
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $creatorId = Auth::id();

        $admins = User::role(['superadmin', 'aux_admin'])
            ->when($creatorId, fn ($query) => $query->where('id', '!=', $creatorId))
            ->get();

        if ($admins->isNotEmpty()) {
            Notification::send($admins, new LoanApprovalRequest($this->record));
        }
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
