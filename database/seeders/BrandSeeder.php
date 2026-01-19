<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            ['name' => 'HP'],
            ['name' => 'Dell'],
            ['name' => 'Lenovo'],
            ['name' => 'Samsung'],
            ['name' => 'Acer'],
            ['name' => 'Logitech'],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}