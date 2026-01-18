<?php

namespace Database\Seeders;

use App\Models\ClassroomWorkstation;
use Illuminate\Database\Seeder;

class ClassroomWorkstationSeeder extends Seeder
{
    public function run(): void
    {
        $specMatrix = [
            ['cpu' => 'Intel i5 10400', 'ram' => '16 GB', 'gpu' => 'GTX 1650'],
            ['cpu' => 'Intel i7 12700', 'ram' => '32 GB', 'gpu' => 'RTX 3060'],
            ['cpu' => 'AMD Ryzen 5 5600G', 'ram' => '16 GB', 'gpu' => 'Integrada'],
        ];

        $maintenanceSeats = [6, 18];
        $outOfServiceSeats = [24];

        foreach (range(1, 30) as $seat) {
            $status = 'disponible';

            if (in_array($seat, $maintenanceSeats, true)) {
                $status = 'mantenimiento';
            } elseif (in_array($seat, $outOfServiceSeats, true)) {
                $status = 'fuera_servicio';
            }

            $specs = $specMatrix[($seat - 1) % count($specMatrix)];

            ClassroomWorkstation::updateOrCreate(
                ['code' => sprintf('B201-PC%02d', $seat)],
                [
                    'classroom_code' => 'B201',
                    'label' => "Estación {$seat}",
                    'seat_number' => $seat,
                    'status' => $status,
                    'specs' => $specs,
                    'notes' => $status === 'disponible' ? null : 'Requiere revisión antes de usarse.',
                ]
            );
        }
    }
}
