<?php

namespace Tests\Feature\UserRequests;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Tests\Traits\Database\RefreshDatabaseSkipDropForeign;

class UserRequestsAuthorizationTest extends TestCase
{
    use RefreshDatabaseSkipDropForeign;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate([
            'name' => 'docente',
            'guard_name' => 'web',
        ]);
    }

    public function test_aux_admin_cannot_approve_pending_request(): void
    {
        $pending = User::factory()->create([
            'is_active' => false,
            'is_blocked' => false,
        ]);

        $this->actingAsAuxAdmin();
        $response = $this->postJson(route('user-management.approve', $pending), [
            'role' => 'docente',
        ]);

        $response->assertStatus(403);
    }

    public function test_aux_admin_cannot_block_user(): void
    {
        $target = User::factory()->create([
            'is_active' => true,
            'is_blocked' => false,
        ]);

        $this->actingAsAuxAdmin();
        $response = $this->patchJson(route('user-management.block', $target));

        $response->assertStatus(403);
    }

    public function test_aux_admin_cannot_destroy_user(): void
    {
        $target = User::factory()->create([
            'is_active' => true,
            'is_blocked' => false,
        ]);

        $this->actingAsAuxAdmin();
        $response = $this->deleteJson(route('user-management.destroy', $target));

        $response->assertStatus(403);
    }
}
