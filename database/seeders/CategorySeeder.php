<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{

    public function run(): void
    {
        $categories = [
            [
                'name' => 'Herramienta',
                'description' => 'General purpose tools for mechanical and electronic work.',
            ],
            [
                'name' => 'Componente Electrónico',
                'description' => 'Resistors, capacitors, LEDs, integrated circuits, etc.',
            ],
            [
                'name' => 'Suministro de Práctica',
                'description' => 'Protoboards, wires, and other consumables for labs.',
            ],
            [
                'name' => 'Periférico',
                'description' => 'Keyboards, mice, monitors, and other computer peripherals.',
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'description' => $category['description'],
                'uuid' => Str::uuid(),
                'code' => Str::slug($category['name']),
                'is_active' => true,
            ]);
        }
    }
}