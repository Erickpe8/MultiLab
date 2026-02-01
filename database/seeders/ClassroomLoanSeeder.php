<?php

namespace Database\Seeders;

use App\Models\ClassroomLoan;
use App\Models\ClassroomObservation;
use App\Models\ClassroomWorkstation;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClassroomLoanSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake();
        $faker->seed(20260127);

        $docentes = User::role('docente')->get();
        $approvers = User::role(['aux_admin', 'superadmin'])->get();

        if ($docentes->isEmpty()) {
            $docentes = User::factory()->count(3)->create()->each(fn (User $user) => $user->assignRole('docente'));
        }

        if ($approvers->isEmpty()) {
            $approvers = User::factory()->count(2)->create()->each(fn (User $user) => $user->assignRole('aux_admin'));
        }

        ClassroomLoan::query()->delete();

        $statuses = ['pendiente', 'aprobado', 'cancelado', 'rechazado'];
        $subjects = [
            'Programación II',
            'Laboratorio de Redes',
            'Arquitectura de Software',
            'Fundamentos de Robótica',
            'Diseño de Interfaces',
        ];
        $purposes = [
            'Clase guiada',
            'Demostración de proyecto de investigación',
            'Práctica de laboratorio mixto',
            'Capacitación de docentes invitados',
            'Revisión de equipos y calibración',
        ];

        foreach (range(0, 7) as $index) {
            $requester = $docentes->random();
            $approver = $approvers->random();
            $status = $statuses[$index % count($statuses)];
            $pcRequired = $faker->numberBetween(12, 28);

            if (in_array($status, ['pendiente', 'rechazado', 'cancelado'], true)) {
                $pcInUse = 0;
                $pcUnavailable = $faker->numberBetween(0, min(4, $pcRequired));
            } else {
                $pcUnavailable = $faker->numberBetween(0, min(3, $pcRequired - 6));
                $pcInUse = max(2, $pcRequired - $pcUnavailable);
            }

            $loan = ClassroomLoan::factory()
                ->state([
                    'status' => $status,
                    'subject' => $subjects[$index % count($subjects)],
                    'purpose' => $purposes[$index % count($purposes)],
                    'pc_required' => $pcRequired,
                    'pc_in_use' => $pcInUse,
                    'pc_unavailable' => $pcUnavailable,
                ])
                ->for($requester, 'requester')
                ->for($approver, 'approver')
                ->create();

            $workstationCount = max(4, min($pcRequired, 16));
            $workstations = ClassroomWorkstation::inRandomOrder()->take($workstationCount)->get();

            $pivotStatus = $status === 'pendiente' ? 'reservado' : 'en_uso';

            $pivotData = $workstations->mapWithKeys(function ($station) use ($faker, $loan, $pivotStatus) {
                return [
                    $station->id => [
                        'status' => $pivotStatus,
                        'metrics' => json_encode([
                            'uso_horas' => $faker->numberBetween(1, 6),
                        ]),
                        'assigned_user' => $loan->requester->first_name,
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
