<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;
use Tests\Traits\Database\RefreshDatabaseSkipDropForeign;

class LogoutBehaviorTest extends TestCase
{
    use RefreshDatabaseSkipDropForeign;

    public function test_logout_clears_session_and_blocks_dashboard_access(): void
    {
        $user = User::factory()->create([
            'is_active' => true,
            'is_blocked' => false,
        ]);

        $this->actingAs($user)->post(route('logout'))->assertRedirect('/');

        $this->assertGuest();

        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }
}
