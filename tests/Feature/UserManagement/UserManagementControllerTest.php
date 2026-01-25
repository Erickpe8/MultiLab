<?php

namespace Tests\Feature\UserManagement;

use App\Models\User;
use Tests\Traits\Database\RefreshDatabaseSkipDropForeign;
use Tests\TestCase;

class UserManagementControllerTest extends TestCase
{
    use RefreshDatabaseSkipDropForeign;

    public function test_superadmin_sees_the_user_management_dashboard(): void
    {
        $this->actingAsSuperAdmin();

        $response = $this->get(route('user-management.index'));

        $response->assertOk();
        $response->assertViewIs('usermanagement.management');
    }

    public function test_aux_admin_cannot_access_the_user_management_dashboard(): void
    {
        $this->actingAsAuxAdmin();

        $response = $this->get(route('user-management.index'));

        $response->assertStatus(403);
    }

    public function test_superadmin_can_approve_a_pending_user(): void
    {
        $this->actingAsSuperAdmin();

        $this->createRole('docente');

        $pending = $this->createPendingUser();

        $response = $this->postJson(route('user-management.approve', $pending), [
            'role' => 'docente',
            'area' => 'educacion_superior',
        ]);

        $response->assertOk();

        $pending->refresh();

        $this->assertTrue($pending->is_active);
        $this->assertFalse($pending->is_blocked);
        $this->assertSame('docente', $pending->role_name);
        $this->assertTrue($pending->roles->pluck('name')->contains('docente'));
    }

    public function test_approve_requires_a_role(): void
    {
        $this->actingAsSuperAdmin();

        $pending = $this->createPendingUser();

        $response = $this->postJson(route('user-management.approve', $pending), [
            'area' => 'laboratorio',
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('role', $response->json('details'));
    }

    public function test_non_superadmin_cannot_approve_users(): void
    {
        $this->actingAsRole('docente');

        $pending = $this->createPendingUser();

        $response = $this->postJson(route('user-management.approve', $pending), [
            'role' => 'docente',
        ]);

        $response->assertStatus(403);
    }

    public function test_superadmin_can_update_user_role_and_area(): void
    {
        $this->actingAsSuperAdmin();

        $this->createRole('aux_admin');
        $target = User::factory()->create(['role_name' => 'estudiante']);

        $response = $this->putJson(route('user-management.update-role', $target), [
            'role' => 'aux_admin',
            'area' => 'redes_y_servicios',
        ]);

        $response->assertOk();

        $target->refresh();

        $this->assertSame('aux_admin', $target->role_name);
        $this->assertSame('redes_y_servicios', $target->area);
    }

    public function test_update_role_requires_a_valid_role(): void
    {
        $this->actingAsSuperAdmin();

        $target = User::factory()->create(['role_name' => 'estudiante']);

        $response = $this->putJson(route('user-management.update-role', $target), [
            'role' => 'nonexistent',
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('role', $response->json('details'));
    }

    public function test_superadmin_can_block_a_user(): void
    {
        $this->actingAsSuperAdmin();

        $target = User::factory()->create([
            'is_active' => true,
            'is_blocked' => false,
        ]);

        $response = $this->patchJson(route('user-management.block', $target));

        $response->assertOk();

        $target->refresh();

        $this->assertTrue($target->is_blocked);
        $this->assertFalse($target->is_active);
    }

    public function test_blocking_an_already_blocked_user_returns_error(): void
    {
        $this->actingAsSuperAdmin();

        $target = User::factory()->create([
            'is_active' => false,
            'is_blocked' => true,
        ]);

        $response = $this->patchJson(route('user-management.block', $target));

        $response->assertStatus(400);
    }

    public function test_superadmin_can_unblock_a_user(): void
    {
        $this->actingAsSuperAdmin();

        $target = User::factory()->create([
            'is_active' => false,
            'is_blocked' => true,
        ]);

        $response = $this->patchJson(route('user-management.unblock', $target));

        $response->assertOk();

        $target->refresh();

        $this->assertFalse($target->is_blocked);
        $this->assertTrue($target->is_active);
    }

    public function test_unblocking_a_non_blocked_user_returns_error(): void
    {
        $this->actingAsSuperAdmin();

        $target = User::factory()->create([
            'is_active' => true,
            'is_blocked' => false,
        ]);

        $response = $this->patchJson(route('user-management.unblock', $target));

        $response->assertStatus(400);
    }

    public function test_superadmin_can_destroy_other_users(): void
    {
        $this->actingAsSuperAdmin();

        $target = User::factory()->create();

        $response = $this->deleteJson(route('user-management.destroy', $target));

        $response->assertOk();

        $this->assertModelMissing($target);
    }

    public function test_superadmin_cannot_destroy_their_own_account(): void
    {
        $admin = $this->actingAsSuperAdmin();

        $response = $this->deleteJson(route('user-management.destroy', $admin));

        $response->assertStatus(400);
    }

    public function test_superadmin_can_reject_a_user(): void
    {
        $this->actingAsSuperAdmin();

        $pending = $this->createPendingUser();

        $response = $this->deleteJson(route('user-management.reject', $pending));

        $response->assertOk();

        $this->assertModelMissing($pending);
    }

    public function test_non_superadmin_cannot_reject_users(): void
    {
        $this->actingAsAuxAdmin();

        $pending = $this->createPendingUser();

        $response = $this->deleteJson(route('user-management.reject', $pending));

        $response->assertStatus(403);
    }

    private function createPendingUser(): User
    {
        return User::factory()->create([
            'is_active' => false,
            'is_blocked' => false,
        ]);
    }
}
