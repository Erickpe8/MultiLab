<?php

namespace Database\Seeders;

use App\Models\Loan;
use App\Models\Material;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LoanSeeder extends Seeder
{
    public function run(): void
    {
        $this->ensureRejectedStatusIsAllowed();

        $materials = Material::query()->get();
        if ($materials->isEmpty()) {
            $this->call(MaterialSeeder::class);
            $materials = Material::query()->get();
        }

        if ($materials->isEmpty()) {
            $this->command?->warn('LoanSeeder: no hay materiales disponibles para asociar a los préstamos.');
            return;
        }

        $borrower = User::query()->first() ?? User::factory()->create();
        $issuer = User::query()->whereKeyNot($borrower->getKey())->first() ?? User::factory()->create();

        $now = now();
        $loanDefinitions = [
            [
                'loan_code' => 'L-DEMO-ABIERTO',
                'status' => 'abierto',
                'loan_at' => $now->copy()->subDays(1),
                'due_at' => $now->copy()->addDays(4),
                'notes' => 'Préstamo abierto para pruebas.',
            ],
            [
                'loan_code' => 'L-DEMO-VENCIDO',
                'status' => 'abierto',
                'loan_at' => $now->copy()->subDays(10),
                'due_at' => $now->copy()->subDays(2),
                'notes' => 'Préstamo con fecha de devolución vencida.',
            ],
            [
                'loan_code' => 'L-DEMO-DEVUELTO',
                'status' => 'devuelto',
                'loan_at' => $now->copy()->subDays(8),
                'due_at' => $now->copy()->subDays(3),
                'return_at' => $now->copy()->subDays(2),
                'notes' => 'Préstamo finalizado correctamente.',
            ],
            [
                'loan_code' => 'L-DEMO-MULTA',
                'status' => 'con_multa',
                'loan_at' => $now->copy()->subDays(15),
                'due_at' => $now->copy()->subDays(5),
                'notes' => 'Préstamo con multa pendiente por daños.',
            ],
            [
                'loan_code' => 'L-DEMO-RECHAZADO',
                'status' => 'rechazado',
                'loan_at' => $now->copy()->subDays(4),
                'due_at' => $now->copy()->subDays(1),
                'notes' => 'Solicitud rechazada por falta de stock.',
            ],
        ];

        foreach ($loanDefinitions as $definition) {
            $loan = Loan::updateOrCreate(
                ['loan_code' => $definition['loan_code']],
                [
                    'user_id' => $borrower->getKey(),
                    'issued_by' => $issuer->getKey(),
                    'loan_at' => $definition['loan_at'],
                    'due_at' => $definition['due_at'],
                    'return_at' => $definition['return_at'] ?? null,
                    'status' => $definition['status'],
                    'notes' => $definition['notes'],
                ]
            );

            $attachMaterials = $materials->shuffle()->take(min(3, $materials->count()));
            $pivotData = [];

            foreach ($attachMaterials as $material) {
                $loanQty = random_int(1, max(1, min(5, $material->current_stock ?: 5)));
                $isReturned = $definition['status'] === 'devuelto';
                $pivotData[$material->getKey()] = [
                    'loan_qty' => $loanQty,
                    'returned_qty' => $isReturned ? $loanQty : 0,
                ];
            }

            if (! empty($pivotData)) {
                $loan->materials()->sync($pivotData);
            }
        }
    }

}
