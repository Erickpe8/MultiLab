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
        // Crear materiales con los IDs correctos
        $materials = [
            // Herramientas
            [
                'name' => 'Martillo',
                'category_code' => 'tools',
                'unit_code' => 'unit',
                'current_stock' => 15,
            ],
            [
                'name' => 'Destornillador de Punta Plana #2',
                'category_code' => 'tools',
                'unit_code' => 'unit',
                'current_stock' => 25,
            ],
            [
                'name' => 'Destornillador de Punta Phillips #1',
                'category_code' => 'tools',
                'unit_code' => 'unit',
                'current_stock' => 25,
            ],
            [
                'name' => 'Pinzas de Electricista',
                'category_code' => 'tools',
                'unit_code' => 'unit',
                'current_stock' => 10,
            ],
            [
                'name' => 'Multímetro Digital',
                'category_code' => 'tools',
                'unit_code' => 'unit',
                'current_stock' => 5,
            ],

            // Componentes electrónicos
            [
                'name' => 'Memoria RAM DDR4 8GB',
                'category_code' => 'electronic-components',
                'unit_code' => 'unit',
                'current_stock' => 20,
            ],
            [
                'name' => 'Disco SSD 256GB',
                'category_code' => 'electronic-components',
                'unit_code' => 'unit',
                'current_stock' => 15,
            ],
            [
                'name' => 'Disco HDD 1TB',
                'category_code' => 'electronic-components',
                'unit_code' => 'unit',
                'current_stock' => 10,
            ],
            [
                'name' => 'LED Rojo 5mm (Paquete de 100)',
                'category_code' => 'electronic-components',
                'unit_code' => 'pack',
                'current_stock' => 50,
            ],
            [
                'name' => 'Protoboard 830 puntos',
                'category_code' => 'practice-supplies',
                'unit_code' => 'unit',
                'current_stock' => 30,
            ],
            [
                'name' => 'Cable UTP Cat6 (Metro)',
                'category_code' => 'practice-supplies',
                'unit_code' => 'meter',
                'current_stock' => 200,
            ],
            [
                'name' => 'Kit de Resistencias (500 piezas)',
                'category_code' => 'electronic-components',
                'unit_code' => 'kit',
                'current_stock' => 20,
            ],
            [
                'name' => 'Fuente de Poder ATX 500W',
                'category_code' => 'electronic-components',
                'unit_code' => 'unit',
                'current_stock' => 8,
            ],
            [
                'name' => 'Tarjeta Madre MicroATX',
                'category_code' => 'electronic-components',
                'unit_code' => 'unit',
                'current_stock' => 5,
            ],
        ];

        foreach ($materials as $materialData) {
            $category = \App\Models\Category::where('code', $materialData['category_code'])->first();
            $unit = \App\Models\Unit::where('code', $materialData['unit_code'])->first();

            if ($category && $unit) {
                Material::create([
                    'name' => $materialData['name'],
                    'category_id' => $category->id,
                    'unit_id' => $unit->id,
                    'current_stock' => $materialData['current_stock'],
                    'uuid' => Str::uuid(),
                    'sku' => 'SKU-' . strtoupper(Str::random(8)),
                    'min_stock' => 5,
                    'max_stock' => 100,
                    'has_expiry' => false,
                ]);
            }
        }
    }
}