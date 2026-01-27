<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'code' => 'computadores',
                'name' => 'Computadores de escritorio',
                'description' => 'Torres y estaciones fijas alineadas con los salones del laboratorio, listas para pruebas de sistemas embebidos y redes.',
            ],
            [
                'code' => 'portatiles',
                'name' => 'Portátiles',
                'description' => 'Laptops para prácticas externas, acompañamiento de proyectos y uso en espacios múltiples de la universidad.',
            ],
            [
                'code' => 'perifericos',
                'name' => 'Periféricos',
                'description' => 'Teclados, mouse, monitores, audífonos y cámaras para equipar estaciones de trabajo y laboratorios móviles.',
            ],
            [
                'code' => 'red',
                'name' => 'Red y conectividad',
                'description' => 'Switches, routers, paneles de parcheo y cables Cat6/Cat6a para mantener la infraestructura cableada del laboratorio.',
            ],
            [
                'code' => 'proyeccion',
                'name' => 'Proyección y audio',
                'description' => 'Cañones, sistemas de audio, controles y cables AV para salas multimedia, auditorio y clases guiadas.',
            ],
            [
                'code' => 'herramientas',
                'name' => 'Herramientas',
                'description' => 'Herramientas manuales y eléctricas usadas para montaje, calibración y mantenimiento de equipos y estructuras.',
            ],
            [
                'code' => 'consumibles',
                'name' => 'Consumibles',
                'description' => 'Adaptadores, baterías, cargadores, cables, cartuchos y otros insumos que se reponen frecuentemente.',
            ],
            [
                'code' => 'mobiliario',
                'name' => 'Mobiliario técnico',
                'description' => 'Mesas, sillas, carros y apoyos ergonómicos que soportan la operación diaria del laboratorio.',
            ],
        ];

        foreach ($categories as $definition) {
            $uuid = Category::where('code', $definition['code'])->value('uuid') ?? Str::uuid();

            Category::updateOrCreate(
                ['code' => $definition['code']],
                [
                    'name' => $definition['name'],
                    'description' => $definition['description'],
                    'uuid' => (string) $uuid,
                    'is_active' => true,
                ]
            );
        }
    }
}
