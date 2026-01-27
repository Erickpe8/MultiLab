<?php

namespace Tests\Feature\UserRequests;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserTestSeeder;
use Tests\Traits\Database\RefreshDatabaseSkipDropForeign;
use Tests\TestCase;

class BlockUserRequestTest extends TestCase
{
    use RefreshDatabaseSkipDropForeign;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $this->seed(UserTestSeeder::class);
    }

    public function test_superadmin_can_block_active_user_and_move_to_blocked(): void
    {
        $superadmin = User::where('email', 'superadmin@multilab.test')->firstOrFail();
        $target = User::factory()->create([
            'is_active'  => true,
            'is_blocked' => false,
            'role_name'  => 'docente',
        ]);

        $response = $this->actingAs($superadmin)->patchJson(route('user-management.block', $target));

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('users', [
            'id'         => $target->id,
            'is_active'  => false,
            'is_blocked' => true,
        ]);

        $this->assertFalse(User::pending()->where('id', $target->id)->exists());
        $this->assertTrue(User::where('id', $target->id)->where('is_blocked', true)->exists());
    }
}
