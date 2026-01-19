<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Tools',
                'description' => 'General purpose tools for mechanical and electronic work.',
            ],
            [
                'name' => 'Electronic Components',
                'description' => 'Resistors, capacitors, LEDs, integrated circuits, etc.',
            ],
            [
                'name' => 'Practice Supplies',
                'description' => 'Protoboards, wires, and other consumables for labs.',
            ],
            [
                'name' => 'Peripherals',
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