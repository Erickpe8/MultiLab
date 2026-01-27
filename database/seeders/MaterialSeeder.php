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
        $materials = [
            ['sku' => 'MAT-001', 'name' => 'Mouse inalámbrico Logitech M185', 'category_code' => 'perifericos', 'unit_code' => 'unit', 'current_stock' => 42, 'min_stock' => 6, 'max_stock' => 120, 'has_expiry' => false],
            ['sku' => 'MAT-002', 'name' => 'Teclado mecánico Corsair K55', 'category_code' => 'perifericos', 'unit_code' => 'unit', 'current_stock' => 18, 'min_stock' => 4, 'max_stock' => 60, 'has_expiry' => false],
            ['sku' => 'MAT-003', 'name' => 'Audífonos con micrófono Logitech H390', 'category_code' => 'perifericos', 'unit_code' => 'unit', 'current_stock' => 12, 'min_stock' => 3, 'max_stock' => 30, 'has_expiry' => false],
            ['sku' => 'MAT-004', 'name' => 'Monitor Dell 24 HDR', 'category_code' => 'perifericos', 'unit_code' => 'unit', 'current_stock' => 7, 'min_stock' => 2, 'max_stock' => 20, 'has_expiry' => false],
            ['sku' => 'MAT-005', 'name' => 'Cable HDMI 2 m', 'category_code' => 'proyeccion', 'unit_code' => 'unit', 'current_stock' => 25, 'min_stock' => 6, 'max_stock' => 50, 'has_expiry' => false],
            ['sku' => 'MAT-006', 'name' => 'Adaptador HDMI a VGA', 'category_code' => 'proyeccion', 'unit_code' => 'unit', 'current_stock' => 14, 'min_stock' => 4, 'max_stock' => 30, 'has_expiry' => false],
            ['sku' => 'MAT-007', 'name' => 'Cámara web Logitech C270', 'category_code' => 'perifericos', 'unit_code' => 'unit', 'current_stock' => 9, 'min_stock' => 3, 'max_stock' => 20, 'has_expiry' => false],
            ['sku' => 'MAT-008', 'name' => 'Micro USB-C 65 W (cargador)', 'category_code' => 'consumibles', 'unit_code' => 'unit', 'current_stock' => 11, 'min_stock' => 3, 'max_stock' => 25, 'has_expiry' => false],
            ['sku' => 'MAT-009', 'name' => 'Cable UTP Cat6 (metro)', 'category_code' => 'red', 'unit_code' => 'meter', 'current_stock' => 220, 'min_stock' => 40, 'max_stock' => 500, 'has_expiry' => false],
            ['sku' => 'MAT-010', 'name' => 'Switch PoE 8 puertos Cisco', 'category_code' => 'red', 'unit_code' => 'unit', 'current_stock' => 4, 'min_stock' => 1, 'max_stock' => 10, 'has_expiry' => false],
            ['sku' => 'MAT-011', 'name' => 'Cartucho de tóner negro Brother TN-660', 'category_code' => 'consumibles', 'unit_code' => 'unit', 'current_stock' => 6, 'min_stock' => 2, 'max_stock' => 12, 'has_expiry' => true],
            ['sku' => 'MAT-012', 'name' => 'Baterías recargables AA (pack 4)', 'category_code' => 'consumibles', 'unit_code' => 'pack', 'current_stock' => 15, 'min_stock' => 3, 'max_stock' => 30, 'has_expiry' => true],
            ['sku' => 'MAT-013', 'name' => 'Cinta aislante 10 m', 'category_code' => 'consumibles', 'unit_code' => 'unit', 'current_stock' => 33, 'min_stock' => 6, 'max_stock' => 80, 'has_expiry' => false],
            ['sku' => 'MAT-014', 'name' => 'Destornillador de precisión punta Phillips', 'category_code' => 'herramientas', 'unit_code' => 'unit', 'current_stock' => 26, 'min_stock' => 6, 'max_stock' => 60, 'has_expiry' => false],
            ['sku' => 'MAT-015', 'name' => 'Kit de herramientas manuales (32 piezas)', 'category_code' => 'herramientas', 'unit_code' => 'kit', 'current_stock' => 6, 'min_stock' => 1, 'max_stock' => 12, 'has_expiry' => false],
            ['sku' => 'MAT-016', 'name' => 'Multímetro digital Fluke 115', 'category_code' => 'herramientas', 'unit_code' => 'unit', 'current_stock' => 4, 'min_stock' => 1, 'max_stock' => 8, 'has_expiry' => false],
            ['sku' => 'MAT-017', 'name' => 'Soporte articulado para monitor', 'category_code' => 'mobiliario', 'unit_code' => 'unit', 'current_stock' => 10, 'min_stock' => 2, 'max_stock' => 25, 'has_expiry' => false],
            ['sku' => 'MAT-018', 'name' => 'Silla ergonómica de laboratorio', 'category_code' => 'mobiliario', 'unit_code' => 'unit', 'current_stock' => 12, 'min_stock' => 3, 'max_stock' => 30, 'has_expiry' => false],
            ['sku' => 'MAT-019', 'name' => 'Carro de servicio con ruedas', 'category_code' => 'mobiliario', 'unit_code' => 'unit', 'current_stock' => 5, 'min_stock' => 1, 'max_stock' => 12, 'has_expiry' => false],
            ['sku' => 'MAT-020', 'name' => 'Rack móvil para componentes', 'category_code' => 'mobiliario', 'unit_code' => 'unit', 'current_stock' => 2, 'min_stock' => 1, 'max_stock' => 8, 'has_expiry' => false],
        ];

        $fakerSeed = 20260127;
        $faker = fake();
        $faker->seed($fakerSeed);

        foreach ($materials as $materialData) {
            $category = Category::where('code', $materialData['category_code'])->first();
            $unit = Unit::where('code', $materialData['unit_code'])->first();

            if (! $category || ! $unit) {
                continue;
            }

            Material::updateOrCreate(
                ['sku' => $materialData['sku']],
                [
                    'name' => $materialData['name'],
                    'category_id' => $category->id,
                    'unit_id' => $unit->id,
                    'current_stock' => $materialData['current_stock'],
                    'min_stock' => $materialData['min_stock'],
                    'max_stock' => $materialData['max_stock'],
                    'uuid' => (string) (Material::where('sku', $materialData['sku'])->value('uuid') ?? Str::uuid()),
                    'has_expiry' => $materialData['has_expiry'],
                    'expiry_date' => $materialData['has_expiry'] ? $faker->dateTimeBetween('now', '+1 year') : null,
                ]
            );
        }
    }
}
