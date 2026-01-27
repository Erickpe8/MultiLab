<?php

namespace Database\Seeders;

use App\Models\Loan;
use App\Models\Material;
use App\Models\User;
use Illuminate\Database\Seeder;

class LoanSeeder extends Seeder
{
    public function run(): void
    {
        $materials = Material::query()->get();

        if ($materials->isEmpty()) {
            $this->call(MaterialSeeder::class);
            $materials = Material::query()->get();
        }

        if ($materials->isEmpty()) {
            $this->command?->warn('LoanSeeder: no hay materiales disponibles para crear préstamos.');
            return;
        }

        $issuers = User::role(['aux_admin', 'superadmin'])->get();

        $loanDefinitions = [
            [
                'loan_code' => 'L-EST-ABIERTO-001',
                'status' => 'En curso',
                'borrower_role' => 'estudiante',
                'loan_offset' => -2,
                'duration' => 7,
                'notes' => 'Préstamo activo para validación del prototipo de redes.',
            ],
            [
                'loan_code' => 'L-EST-VENCIDO-001',
                'status' => 'Vencido',
                'borrower_role' => 'estudiante',
                'loan_offset' => -12,
                'duration' => 5,
                'notes' => 'Material pendiente tras defensa final del proyecto.',
            ],
            [
                'loan_code' => 'L-DOC-DEVUELTO-001',
                'status' => 'Devuelto',
                'borrower_role' => 'docente',
                'loan_offset' => -18,
                'duration' => 10,
                'return_offset' => 9,
                'notes' => 'Equipo usado en la sesión de arquitectura de software.',
            ],
            [
                'loan_code' => 'L-DOC-MULTA-001',
                'status' => 'Con multa',
                'borrower_role' => 'docente',
                'loan_offset' => -30,
                'duration' => 12,
                'return_offset' => 20,
                'notes' => 'Equipo regresado con retraso y un componente dañado.',
            ],
            [
                'loan_code' => 'L-EST-PERDIDO-001',
                'status' => 'Perdido',
                'borrower_role' => 'estudiante',
                'loan_offset' => -40,
                'duration' => 6,
                'notes' => 'Kit reportado como extraviado durante salida de campo.',
            ],
            [
                'loan_code' => 'L-EST-ABIERTO-002',
                'status' => 'En curso',
                'borrower_role' => 'estudiante',
                'loan_offset' => -1,
                'duration' => 4,
                'notes' => 'Solicitado para completar el taller de mediciones.',
            ],
        ];

        foreach ($loanDefinitions as $definition) {
            $borrower = User::role($definition['borrower_role'])->inRandomOrder()->first()
                ?? User::factory()->create();
            $issuer = $issuers->random() ?? User::factory()->create();

            $loanAt = now()->addDays($definition['loan_offset']);
            $dueAt = (clone $loanAt)->addDays($definition['duration']);
            $returnAt = isset($definition['return_offset'])
                ? (clone $loanAt)->addDays($definition['return_offset'])
                : null;

            $loan = Loan::updateOrCreate(
                ['loan_code' => $definition['loan_code']],
                [
                    'user_id' => $borrower->getKey(),
                    'issued_by' => $issuer->getKey(),
                    'loan_at' => $loanAt,
                    'due_at' => $dueAt,
                    'return_at' => $returnAt,
                    'status' => $definition['status'],
                    'notes' => $definition['notes'],
                ]
            );

            $attachMaterials = $materials->shuffle()->take(min(3, $materials->count()));
            $pivotData = [];

            foreach ($attachMaterials as $material) {
                $available = max(1, $material->current_stock);
                $loanQty = random_int(1, min(3, $available));
                $pivotData[$material->getKey()] = [
                    'loan_qty' => $loanQty,
                    'returned_qty' => $definition['status'] === 'Devuelto' ? $loanQty : 0,
                ];
            }

            if (! empty($pivotData)) {
                $loan->materials()->sync($pivotData);
            }
        }
    }
}
