<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Material;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Material>
 */
class MaterialFactory extends Factory
{
    protected $model = Material::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);

        return [
            'sku' => strtoupper($this->faker->unique()->bothify('MAT-###')),
            'name' => ucfirst($name),
            'unit_id' => Unit::factory(),
            'category_id' => Category::factory(),
            'current_stock' => $this->faker->numberBetween(0, 50),
            'min_stock' => $this->faker->numberBetween(0, 30),
            'max_stock' => $this->faker->numberBetween(40, 120),
            'uuid' => (string) Str::uuid(),
            'has_expiry' => false,
        ];
    }
}
