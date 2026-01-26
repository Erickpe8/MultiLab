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
            MaterialSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            UserRequestsSeeder::class,
            ClassroomLoanSeeder::class,
            LoanSeeder::class,
            ComputerSeeder::class,
        ]);
    }

}
