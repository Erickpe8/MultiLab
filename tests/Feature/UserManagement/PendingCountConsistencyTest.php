<?php

namespace Tests\Feature\UserManagement;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserTestSeeder;
use Tests\TestCase;
use Tests\Traits\Database\RefreshDatabaseSkipDropForeign;
use Tests\Traits\EnsuresVerifiedUser;

class PendingCountConsistencyTest extends TestCase
{
    use RefreshDatabaseSkipDropForeign;
    use EnsuresVerifiedUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $this->seed(UserTestSeeder::class);
    }

    public function test_pending_badge_matches_list_total(): void
    {
        $admin = $this->verifyUser(User::where('email', 'superadmin@multilab.test')->firstOrFail());

        User::factory()->count(4)->create([
            'is_active'  => false,
            'is_blocked' => false,
        ]);

        $response = $this->actingAs($admin)->get(route('user-management.index', ['view' => 'pending']));

        $response->assertOk();
        $pendingUsers = $response->viewData('pendingUsers');

        $this->assertEquals(User::pending()->count(), $pendingUsers->total());
    }
}
