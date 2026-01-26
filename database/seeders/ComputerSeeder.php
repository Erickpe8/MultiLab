<?php

namespace Database\Seeders;

use App\Models\Computer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComputerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('computers')->truncate();

        // 28 disponibles
        for ($i = 1; $i <= 28; $i++) {
            Computer::factory()->create([
                'name' => 'B202-PC' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'status' => 'disponible',
            ]);
        }

        // 2 no disponibles
        for ($i = 29; $i <= 30; $i++) {
            Computer::factory()->create([
                'name' => 'B202-PC' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'status' => 'no_disponible',
            ]);
        }
    }
}
