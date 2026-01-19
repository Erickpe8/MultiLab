<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            ['code' => 'unit', 'name' => 'Unit'],
            ['code' => 'meter', 'name' => 'Meter'],
            ['code' => 'pack', 'name' => 'Pack'],
            ['code' => 'kit', 'name' => 'Kit'],
            ['code' => 'box', 'name' => 'Box'],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}