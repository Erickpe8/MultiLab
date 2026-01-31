<?php

namespace Tests\Feature\Reports;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserTestSeeder;
use Tests\Traits\Database\RefreshDatabaseSkipDropForeign;
use Tests\Traits\EnsuresVerifiedUser;
use Tests\TestCase;

class ReportsAccessTest extends TestCase
{
    use RefreshDatabaseSkipDropForeign;
    use EnsuresVerifiedUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $this->seed(UserTestSeeder::class);
    }

    private function createStatefulUser(string $role, bool $active, bool $blocked): User
    {
        $user = User::factory()->create([
            'is_active'  => $active,
            'is_blocked' => $blocked,
            'role_name'  => $role,
        ]);

        $user->syncRoles([$role]);

        return $user->refresh();
    }

    private function pendingUser(): User
    {
        return $this->createStatefulUser('docente', false, false);
    }

    private function blockedUser(): User
    {
        return $this->createStatefulUser('aux_admin', false, true);
    }

    private function superadmin(): User
    {
        return $this->verifyUser(User::where('email', 'superadmin@multilab.test')->firstOrFail());
    }

    public function test_pending_user_cannot_view_reports_index(): void
    {
        $pending = $this->pendingUser();

        $response = $this->actingAs($pending)->get(route('reports.index'));

        $response->assertStatus(403);
    }

    public function test_blocked_user_cannot_view_reports_index(): void
    {
        $blocked = $this->blockedUser();

        $response = $this->actingAs($blocked)->get(route('reports.index'));

        $response->assertStatus(403);
    }

    public function test_superadmin_can_view_reports_index(): void
    {
        $admin = $this->superadmin();

        $response = $this->actingAs($admin)->get(route('reports.index'));

        $response->assertOk();
        $response->assertViewIs('reports.index');
        $response->assertSee('Indicadores operativos y trazabilidad del laboratorio.');
    }

    public function test_pending_user_cannot_fetch_reports_summary(): void
    {
        $pending = $this->pendingUser();

        $response = $this->actingAs($pending)->get(route('reports.summary'));

        $response->assertStatus(403);
    }

    public function test_blocked_user_cannot_fetch_reports_summary(): void
    {
        $blocked = $this->blockedUser();

        $response = $this->actingAs($blocked)->get(route('reports.summary'));

        $response->assertStatus(403);
    }

    public function test_superadmin_can_fetch_reports_summary(): void
    {
        $admin = $this->superadmin();

        $response = $this->actingAs($admin)->getJson(route('reports.summary'));

        $response->assertOk();
        $response->assertJsonStructure([
            'cards',
            'updated_at',
        ]);
        $response->assertJsonCount(4, 'cards');
    }

    public function test_pending_user_cannot_fetch_reports_activity(): void
    {
        $pending = $this->pendingUser();

        $response = $this->actingAs($pending)->get(route('reports.activity'));

        $response->assertStatus(403);
    }

    public function test_blocked_user_cannot_fetch_reports_activity(): void
    {
        $blocked = $this->blockedUser();

        $response = $this->actingAs($blocked)->get(route('reports.activity'));

        $response->assertStatus(403);
    }

    public function test_superadmin_can_fetch_reports_activity(): void
    {
        $admin = $this->superadmin();

        $response = $this->actingAs($admin)->getJson(route('reports.activity'));

        $response->assertOk();
        $response->assertJsonStructure([
            'days',
            'updated_at',
        ]);
        $response->assertJsonCount(14, 'days');
    }

    public function test_pending_user_cannot_fetch_reports_inventory(): void
    {
        $pending = $this->pendingUser();

        $response = $this->actingAs($pending)->get(route('reports.inventory'));

        $response->assertStatus(403);
    }

    public function test_blocked_user_cannot_fetch_reports_inventory(): void
    {
        $blocked = $this->blockedUser();

        $response = $this->actingAs($blocked)->get(route('reports.inventory'));

        $response->assertStatus(403);
    }

    public function test_superadmin_can_fetch_reports_inventory(): void
    {
        $admin = $this->superadmin();

        $response = $this->actingAs($admin)->getJson(route('reports.inventory'));

        $response->assertOk();
        $response->assertJsonStructure([
            'low_stock',
            'overdue',
            'top_materials',
            'updated_at',
        ]);
    }
}
