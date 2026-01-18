<?php

namespace Database\Factories;

use App\Models\ClassroomLoan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassroomLoan>
 */
class ClassroomLoanFactory extends Factory
{
    protected $model = ClassroomLoan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = Carbon::instance($this->faker->dateTimeBetween('-1 week', '+2 weeks'));
        $end = (clone $start)->addHours($this->faker->numberBetween(1, 3));

        $status = $this->faker->randomElement([
            'pendiente',
            'aprobado',
            'rechazado',
            'en_uso',
            'finalizado',
            'cancelado',
        ]);

        $actualStart = in_array($status, ['en_uso', 'finalizado'], true)
            ? (clone $start)->addMinutes($this->faker->numberBetween(-10, 15))
            : null;

        $actualEnd = in_array($status, ['finalizado'], true)
            ? (clone $end)->addMinutes($this->faker->numberBetween(-15, 20))
            : null;

        $pcRequired = $this->faker->numberBetween(10, 28);
        $pcInUse = $status === 'pendiente'
            ? 0
            : $this->faker->numberBetween(6, $pcRequired);

        $pcUnavailable = max(0, $pcRequired - $pcInUse - $this->faker->numberBetween(0, 2));

        return [
            'classroom_code' => 'B201',
            'requested_by' => User::factory(),
            'approved_by' => User::factory(),
            'subject' => $this->faker->randomElement([
                'Programación II',
                'Arquitectura de Software',
                'Electiva UX',
                'Gestión de Proyectos',
            ]),
            'purpose' => $this->faker->sentence(),
            'status' => $status,
            'scheduled_start_at' => $start,
            'scheduled_end_at' => $end,
            'actual_start_at' => $actualStart,
            'actual_end_at' => $actualEnd,
            'pc_required' => $pcRequired,
            'pc_in_use' => $pcInUse,
            'pc_unavailable' => $pcUnavailable,
            'workstations_snapshot' => [
                'operativas' => $pcRequired - $pcUnavailable,
                'en_mantenimiento' => $pcUnavailable,
            ],
            'incidents_count' => $this->faker->numberBetween(0, 3),
            'access_instructions' => $this->faker->boolean(60) ? 'Solicitar llave en coordinación académica.' : null,
            'notes' => $this->faker->boolean(40) ? $this->faker->sentence() : null,
        ];
    }
}
