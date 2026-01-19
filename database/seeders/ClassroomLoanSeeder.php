<?php

namespace Database\Seeders;

use App\Models\ClassroomLoan;
use App\Models\ClassroomObservation;
use App\Models\ClassroomWorkstation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class ClassroomLoanSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake();

        $docentes = User::role('docente')->get();
        $approvers = User::role(['aux_admin', 'superadmin'])->get();

        if ($docentes->isEmpty()) {
            $docentes = User::factory()->count(3)->create()->each(fn (User $user) => $user->assignRole('docente'));
        }

        if ($approvers->isEmpty()) {
            $approvers = User::factory()->count(2)->create()->each(fn (User $user) => $user->assignRole('aux_admin'));
        }

        ClassroomLoan::query()->delete();

        foreach (range(1, 8) as $index) {
            $requester = $docentes->random();
            $approver = $approvers->random();

            $loan = ClassroomLoan::factory()
                ->for($requester, 'requester')
                ->for($approver, 'approver')
                ->create();

            $workstationCount = max(4, min($loan->pc_required, 12));
            $workstations = ClassroomWorkstation::inRandomOrder()->take($workstationCount)->get();

            $pivotStatus = $loan->status === 'pendiente' ? 'reservado' : 'en_uso';

            $pivotData = $workstations->mapWithKeys(function ($station) use ($faker, $loan, $pivotStatus) {
                return [
                    $station->id => [
                        'status' => $pivotStatus,
                        'metrics' => json_encode([
                            'uso_horas' => $faker->numberBetween(1, 6),
                        ]),
                        'assigned_user' => $loan->requester->first_name ?? $loan->requester->name,
                        'notes' => $faker->boolean(30) ? $faker->sentence() : null,
                    ],
                ];
            });

            $loan->workstations()->syncWithoutDetaching($pivotData->toArray());

            $observationCount = $faker->numberBetween(1, 3);
            $observations = ClassroomObservation::factory()
                ->count($observationCount)
                ->state(fn () => [
                    'classroom_loan_id' => $loan->id,
                    'recorded_by' => $approvers->random()->id,
                ])
                ->create();

            $incidentCount = $observations->where('type', 'incidente')->count();
            $loan->update(['incidents_count' => $incidentCount]);
        }
    }
}
