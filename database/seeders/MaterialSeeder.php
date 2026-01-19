<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Support\Str;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get categories and units to associate materials
        $toolsCategory = Category::where('code', 'tools')->first();
        $componentsCategory = Category::where('code', 'electronic-components')->first();
        $suppliesCategory = Category::where('code', 'practice-supplies')->first();

        $unit = Unit::where('code', 'unit')->first();
        $meter = Unit::where('code', 'meter')->first();
        $pack = Unit::where('code', 'pack')->first();
        $kit = Unit::where('code', 'kit')->first();

        $materials = [
            [
                'name' => 'Hammer',
                'category_id' => $toolsCategory->id,
                'unit_id' => $unit->id,
                'current_stock' => 10,
            ],
            [
                'name' => 'Red LEDs (Pack of 100)',
                'category_id' => $componentsCategory->id,
                'unit_id' => $pack->id,
                'current_stock' => 50,
            ],
            [
                'name' => 'Hacksaw',
                'category_id' => $toolsCategory->id,
                'unit_id' => $unit->id,
                'current_stock' => 5,
            ],
            [
                'name' => '830-Point Protoboard',
                'category_id' => $suppliesCategory->id,
                'unit_id' => $unit->id,
                'current_stock' => 30,
            ],
            [
                'name' => 'UTP Cable',
                'category_id' => $suppliesCategory->id,
                'unit_id' => $meter->id,
                'current_stock' => 200, // 200 meters
            ],
            [
                'name' => 'Resistor Kit (500 pieces)',
                'category_id' => $componentsCategory->id,
                'unit_id' => $kit->id,
                'current_stock' => 20,
            ],
        ];

        foreach ($materials as $material) {
            Material::create([
                'name' => $material['name'],
                'category_id' => $material['category_id'],
                'unit_id' => $material['unit_id'],
                'current_stock' => $material['current_stock'],
                'uuid' => Str::uuid(),
                'sku' => 'SKU-' . strtoupper(Str::random(8)),
                'min_stock' => 10,
                'max_stock' => 100,
                'has_expiry' => false,
            ]);
        }
    }
}