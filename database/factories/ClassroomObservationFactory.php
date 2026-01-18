<?php

namespace Database\Factories;

use App\Models\ClassroomLoan;
use App\Models\ClassroomObservation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassroomObservation>
 */
class ClassroomObservationFactory extends Factory
{
    protected $model = ClassroomObservation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'classroom_loan_id' => ClassroomLoan::factory(),
            'recorded_by' => User::factory(),
            'type' => $this->faker->randomElement(['inicio', 'durante', 'cierre', 'incidente', 'reporte']),
            'description' => $this->faker->paragraph(),
            'severity' => $this->faker->numberBetween(1, 5),
            'metadata' => [
                'temperatura' => $this->faker->numberBetween(18, 26),
                'equipos_operativos' => $this->faker->numberBetween(20, 30),
            ],
            'evidence_path' => null,
        ];
    }
}
