<?php

namespace Database\Factories;

use App\Models\ClassroomWorkstation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassroomWorkstation>
 */
class ClassroomWorkstationFactory extends Factory
{
    protected $model = ClassroomWorkstation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $seatNumber = $this->faker->unique()->numberBetween(1, 40);

        return [
            'classroom_code' => 'B201',
            'code' => sprintf('B201-PC%02d', $seatNumber),
            'label' => "Estación {$seatNumber}",
            'status' => $this->faker->randomElement(['disponible', 'mantenimiento', 'fuera_servicio']),
            'seat_number' => $seatNumber,
            'specs' => [
                'cpu' => $this->faker->randomElement(['i5 10th', 'i7 12th', 'Ryzen 5 5600G']),
                'ram' => $this->faker->randomElement(['16 GB', '32 GB']),
                'gpu' => $this->faker->randomElement(['GTX 1660', 'RTX 3060', 'Integrada']),
            ],
            'notes' => $this->faker->boolean(20) ? $this->faker->sentence() : null,
        ];
    }
}
