<?php

namespace Tests\Feature\UserManagement;

use App\Models\User;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;
use Tests\Traits\Database\RefreshDatabaseSkipDropForeign;

class ViewModesTest extends TestCase
{
    use RefreshDatabaseSkipDropForeign;
    use CreatesTestUsers;

    public function test_blocked_tab_matches_blocked_users_count(): void
    {
        $this->actingAsSuperAdmin();

        User::factory()->count(3)->create([
            'is_active' => false,
            'is_blocked' => true,
        ]);

        $response = $this->get(route('user-management.index', ['view' => 'blocked']));

        $response->assertOk();
        $blockedUsers = $response->viewData('blockedUsers');

        $this->assertSame(
            User::where('is_blocked', true)->count(),
            $blockedUsers->total()
        );
    }

    public function test_invalid_view_value_falls_back_to_active_tab(): void
    {
        $this->actingAsSuperAdmin();

        $response = $this->get(route('user-management.index', ['view' => 'non-existent']));

        $response->assertOk();
        $this->assertSame('active', $response->viewData('view'));
    }

    public function test_active_users_can_be_filtered_by_role(): void
    {
        $this->actingAsSuperAdmin();

        $user = $this->createUserWithRole('docente');

        $response = $this->get(route('user-management.index', [
            'view' => 'active',
            'active_role' => 'docente',
        ]));

        $response->assertOk();
        $activeUsers = $response->viewData('activeUsers');

        $this->assertSame(1, $activeUsers->total());
        $this->assertContains($user->id, $activeUsers->pluck('id')->all());
    }
}
