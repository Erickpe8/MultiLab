<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DeviceModel;
use App\Models\Brand;
use App\Models\Category;

class DeviceModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hp = Brand::where('name', 'HP')->first();
        $dell = Brand::where('name', 'Dell')->first();
        $lenovo = Brand::where('name', 'Lenovo')->first();
        $samsung = Brand::where('name', 'Samsung')->first();

        $peripherals = Category::where('code', 'peripherals')->first();

        $deviceModels = [
            [
                'brand_id' => $hp->id,
                'category_id' => $peripherals->id,
                'name' => 'EliteDesk 800 G6',
            ],
            [
                'brand_id' => $dell->id,
                'category_id' => $peripherals->id,
                'name' => 'Latitude 7420',
            ],
            [
                'brand_id' => $lenovo->id,
                'category_id' => $peripherals->id,
                'name' => 'ThinkVision P27h-20',
            ],
            [
                'brand_id' => $samsung->id,
                'category_id' => $peripherals->id,
                'name' => '24" Curved Monitor',
            ],
        ];

        foreach ($deviceModels as $model) {
            DeviceModel::create($model);
        }
    }
}