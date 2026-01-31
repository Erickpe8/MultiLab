<?php

namespace Tests\Feature\Reports;

use App\Models\Loan;
use App\Models\LoanMaterial;
use App\Models\Material;
use App\Models\User;
use Illuminate\Support\Carbon;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;
use Tests\Traits\Database\RefreshDatabaseSkipDropForeign;

class ReportsInventoryDataTest extends TestCase
{
    use RefreshDatabaseSkipDropForeign;
    use CreatesTestUsers;

    public function test_inventory_endpoint_reports_low_stock_overdue_loans_and_top_materials(): void
    {
        $superadmin = $this->actingAsSuperAdmin();
        $borrower = $this->createActiveUser();

        $material = Material::factory()->create([
            'current_stock' => 3,
            'min_stock' => 5,
        ]);

        $loan = Loan::factory()->create([
            'user_id' => $borrower->id,
            'issued_by' => $superadmin->id,
            'loan_code' => 'INV-OVERDUE',
            'loan_at' => Carbon::now()->subDays(7),
            'due_at' => Carbon::now()->subDays(1),
            'status' => 'vencido',
        ]);

        LoanMaterial::create([
            'loan_id' => $loan->id,
            'material_id' => $material->id,
            'loan_qty' => 4,
            'returned_qty' => 1,
        ]);

        $response = $this->getJson(route('reports.inventory'));

        $response->assertOk();
        $response->assertJsonStructure([
            'updated_at',
            'low_stock',
            'overdue',
            'top_materials',
        ]);

        $lowStock = $response->json('low_stock');
        $overdue = $response->json('overdue');
        $topMaterials = $response->json('top_materials');

        $this->assertNotEmpty($lowStock);
        $this->assertSame($material->name, $lowStock[0]['name']);
        $this->assertSame($material->current_stock, $lowStock[0]['stock']);

        $this->assertNotEmpty($overdue);
        $this->assertSame('INV-OVERDUE', $overdue[0]['code']);

        $this->assertNotEmpty($topMaterials);
        $this->assertSame($material->id, $topMaterials[0]['id']);
        $this->assertSame($material->name, $topMaterials[0]['name']);
        $this->assertSame(4, $topMaterials[0]['qty']);
    }

    public function test_inventory_endpoint_requires_superadmin_role(): void
    {
        $this->actingAsAuxAdmin();

        $response = $this->getJson(route('reports.inventory'));

        $response->assertStatus(403);
    }
}
