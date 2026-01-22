<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{

    public function run(): void
    {
        $units = [
            ['code' => 'unit', 'name' => 'Unidad'],
            ['code' => 'meter', 'name' => 'Metro'],
            ['code' => 'pack', 'name' => 'Paquete'],
            ['code' => 'kit', 'name' => 'Kit'],
            ['code' => 'box', 'name' => 'Caja'],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}