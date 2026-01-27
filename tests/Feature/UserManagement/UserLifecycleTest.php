<?php

namespace Tests\Feature\UserManagement;

use App\Models\AuditLog;
use App\Models\ClassroomLoan;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserTestSeeder;
use Tests\Traits\Database\RefreshDatabaseSkipDropForeign;
use Tests\TestCase;

class UserLifecycleTest extends TestCase
{
    use RefreshDatabaseSkipDropForeign;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $this->seed(UserTestSeeder::class);
    }

    public function test_superadmin_can_approve_pending_user_and_logs_audit(): void
    {
        $admin = User::where('email', 'superadmin@multilab.test')->first();
        $pending = User::factory()->create([
            'is_active' => false,
            'is_blocked' => false,
        ]);

        $response = $this->actingAs($admin)->postJson(route('user-management.approve', $pending), [
            'role' => 'docente',
            'area' => 'investigacion',
        ]);

        $response->assertOk();
        $this->assertTrue($pending->fresh()->is_active);
        $this->assertSame('docente', $pending->fresh()->role_name);
        $this->assertDatabaseHas('audit_log', [
            'table_name' => 'users',
            'row_pk' => (string) $pending->id,
            'action' => 'update',
        ]);
    }

    public function test_superadmin_can_block_user_and_records_audit(): void
    {
        $admin = User::where('email', 'superadmin@multilab.test')->first();
        $target = User::factory()->create([
            'is_active' => true,
            'is_blocked' => false,
        ]);

        $response = $this->actingAs($admin)->patchJson(route('user-management.block', $target));

        $response->assertOk();
        $this->assertTrue($target->fresh()->is_blocked);
        $this->assertDatabaseHas('audit_log', [
            'table_name' => 'users',
            'row_pk' => (string) $target->id,
            'action' => 'update',
        ]);
    }

    public function test_deleting_user_with_classroom_loan_relationship_fails(): void
    {
        $admin = User::where('email', 'superadmin@multilab.test')->first();
        $target = User::factory()->create([
            'is_active' => true,
            'is_blocked' => false,
        ]);

        ClassroomLoan::factory()->create([
            'requested_by' => $target->id,
            'approved_by' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->deleteJson(route('user-management.destroy', $target));

        $response->assertStatus(500);
        $response->assertJsonStructure(['error']);
        $this->assertModelExists($target);
    }

    public function test_superadmin_can_destroy_user_without_restrictions(): void
    {
        $admin = User::where('email', 'superadmin@multilab.test')->first();
        $target = User::factory()->create([
            'is_active' => true,
            'is_blocked' => false,
        ]);

        $response = $this->actingAs($admin)->deleteJson(route('user-management.destroy', $target));

        $response->assertOk();
        $this->assertModelMissing($target);
    }
}
