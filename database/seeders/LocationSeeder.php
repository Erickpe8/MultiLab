<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            ['name' => 'Lab B201', 'code' => 'LAB-B201', 'type' => 'laboratorio', 'capacity' => 30],
            ['name' => 'Storage Room', 'code' => 'STORAGE', 'type' => 'estanteria', 'capacity' => 500],
            ['name' => 'Office 101', 'code' => 'OFFICE-101', 'type' => 'otro', 'capacity' => 1],
            ['name' => 'IT Department', 'code' => 'IT-DEPT', 'type' => 'otro', 'capacity' => 10],
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}