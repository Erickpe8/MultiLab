<?php

namespace Tests\Feature\Profile;

use App\Models\User;
use Tests\Traits\Database\RefreshDatabaseSkipDropForeign;
use Tests\TestCase;

class ThemeTest extends TestCase
{
    use RefreshDatabaseSkipDropForeign;

    public function test_theme_update_returns_persisted_local_when_column_missing(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patchJson(route('profile.theme.update'), [
            'theme' => 'dark',
        ]);

        $response->assertOk();
        $response->assertJson([
            'ok' => true,
            'theme' => 'dark',
            'applied' => 'dark',
            'persisted' => 'local',
        ]);
    }

    public function test_theme_system_value_returns_applied_system(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patchJson(route('profile.theme.update'), [
            'theme' => 'system',
        ]);

        $response->assertOk();
        $response->assertJson([
            'applied' => 'system',
            'persisted' => 'local',
        ]);
    }
}
