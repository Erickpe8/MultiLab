<?php

namespace Tests\Feature\UserRequests;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserTestSeeder;
use Tests\Traits\Database\RefreshDatabaseSkipDropForeign;
use Tests\TestCase;

class ApproveUserRequestTest extends TestCase
{
    use RefreshDatabaseSkipDropForeign;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $this->seed(UserTestSeeder::class);
    }

    public function test_superadmin_can_approve_pending_user_and_assign_role(): void
    {
        $superadmin = User::where('email', 'superadmin@multilab.test')->firstOrFail();
        $pending = User::factory()->create([
            'is_active'  => false,
            'is_blocked' => false,
            'role_name'  => null,
            'area'       => null,
        ]);

        $response = $this->actingAs($superadmin)->postJson(route('user-management.approve', $pending), [
            'role' => 'docente',
            'area' => 'investigacion',
        ]);

        $response->assertOk();
        $response->assertJsonPath('user.is_active', true);
        $response->assertJsonPath('user.roles.0', 'docente');

        $this->assertDatabaseHas('users', [
            'id'         => $pending->id,
            'is_active'  => true,
            'is_blocked' => false,
            'role_name'  => 'docente',
            'area'       => 'investigacion',
        ]);

        $this->assertTrue($pending->fresh()->hasRole('docente'));
        $this->assertFalse(User::pending()->where('id', $pending->id)->exists());
    }
}
