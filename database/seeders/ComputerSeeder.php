<?php

namespace Database\Seeders;

use App\Models\Computer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ComputerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('computers')->truncate();
        Schema::enableForeignKeyConstraints();

        // 28 disponibles
        for ($i = 1; $i <= 28; $i++) {
            Computer::factory()->create([
                'name' => 'B202-PC' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'status' => 'disponible',
                'marca' => 'Dell Optiplex 3050',
                'main_card' => 'Intel HD Graphics 630',
                'processor' => 'Intel Core I5-7400 3.0 GHZ',
                'ram' => '16GB',
                'hard_drive' => 'HDD 1TB',
                'network_card' => '10/100/1000 BT',
                'graphics_card' => 'AMD Radeon R5 430',
            ]);
        }

        // 2 no disponibles
        for ($i = 29; $i <= 30; $i++) {
            Computer::factory()->create([
                'name' => 'B202-PC' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'status' => 'no_disponible',
                'marca' => 'Dell Optiplex 3050',
                'main_card' => 'Intel HD Graphics 630',
                'processor' => 'Intel Core I5-7400 3.0 GHZ',
                'ram' => '16GB',
                'hard_drive' => 'HDD 1TB',
                'network_card' => '10/100/1000 BT',
                'graphics_card' => 'AMD Radeon R5 430',
            ]);
        }
    }
}
