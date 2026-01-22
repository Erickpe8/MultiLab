<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            UnitSeeder::class,
            BrandSeeder::class,
            LocationSeeder::class,
            DeviceModelSeeder::class,
            MaterialSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            ClassroomWorkstationSeeder::class,
            ClassroomLoanSeeder::class,
            ComputerSeeder::class,
        ]);
    }

}
