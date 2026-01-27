<?php

namespace Database\Seeders;

use App\Models\Computer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComputerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('computers')->truncate();

        $records = [];

        foreach (range(1, 16) as $number) {
            $records[] = [
                'name' => sprintf('B201-PC-%03d', $number),
                'serial_number' => sprintf('DL-B201-%03d', $number),
                'status' => 'disponible',
                'notes' => 'Estación fija en laboratorio B201, lista para prácticas dirigidas.',
            ];
        }

        foreach (range(17, 18) as $number) {
            $records[] = [
                'name' => sprintf('B201-PC-%03d', $number),
                'serial_number' => sprintf('DL-B201-%03d', $number),
                'status' => 'no_disponible',
                'notes' => 'Pendiente de mantenimiento preventivo, piezas en almacén.',
            ];
        }

        foreach (range(19, 20) as $number) {
            $records[] = [
                'name' => sprintf('B201-PC-%03d', $number),
                'serial_number' => sprintf('DL-B201-%03d', $number),
                'status' => 'disponible',
                'notes' => 'Reservada para clases magistrales y pruebas de laboratorio.',
            ];
        }

        foreach (range(1, 12) as $number) {
            $status = $number > 10 ? 'no_disponible' : 'disponible';
            $records[] = [
                'name' => sprintf('B201-LAP-%03d', $number),
                'serial_number' => sprintf('HP-LAP-%03d', $number),
                'status' => $status,
                'notes' => $status === 'disponible'
                    ? 'Portátil preparado para desplazarse a auditorios.'
                    : 'En ciclo de calibración y actualización de software.',
            ];
        }

        $monitors = [
            ['name' => 'B201-MON-001', 'serial_number' => 'LG-MON-001', 'notes' => 'Monitor IPS 24" para estación B201.'],
            ['name' => 'B201-MON-002', 'serial_number' => 'LG-MON-002', 'notes' => 'Monitor IPS 24" para estación B202.'],
            ['name' => 'B201-MON-003', 'serial_number' => 'LG-MON-003', 'notes' => 'Monitor 27" con calibración de color lista.'],
            ['name' => 'B201-MON-004', 'serial_number' => 'LG-MON-004', 'notes' => 'Para formación en auditorios.'],
        ];

        foreach ($monitors as $monitor) {
            $records[] = array_merge($monitor, [
                'status' => 'disponible',
            ]);
        }

        foreach ($records as $record) {
            Computer::create($record);
        }
    }
}
