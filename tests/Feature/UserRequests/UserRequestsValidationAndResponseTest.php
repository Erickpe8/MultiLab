<?php

namespace Tests\Feature\UserRequests;

use App\Models\User;
use Tests\TestCase;
use Tests\Traits\Database\RefreshDatabaseSkipDropForeign;

class UserRequestsValidationAndResponseTest extends TestCase
{
    use RefreshDatabaseSkipDropForeign;

    public function test_approve_requires_an_existing_role(): void
    {
        $pending = User::factory()->create([
            'is_active' => false,
            'is_blocked' => false,
        ]);

        $this->actingAsSuperAdmin();
        $response = $this->postJson(route('user-management.approve', $pending), [
            'role' => 'nonexistent',
        ]);

        $response->assertStatus(422);
        $this->assertArrayHasKey('role', $response->json('details'));
    }

    public function test_block_response_returns_user_payload(): void
    {
        $target = User::factory()->create([
            'is_active' => true,
            'is_blocked' => false,
        ]);

        $this->actingAsSuperAdmin();
        $response = $this->patchJson(route('user-management.block', $target));

        $response->assertOk();
        $response->assertJsonPath('user.id', $target->id);
        $response->assertJsonPath('user.is_blocked', true);

        $this->assertTrue($target->fresh()->is_blocked);
    }
}
