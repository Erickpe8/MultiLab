<?php

namespace Tests\Feature\UserManagement;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserTestSeeder;
use Tests\Traits\Database\RefreshDatabaseSkipDropForeign;
use Tests\TestCase;

class PendingViewTest extends TestCase
{
    use RefreshDatabaseSkipDropForeign;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $this->seed(UserTestSeeder::class);
    }

    public function test_superadmin_can_open_pending_view_tab(): void
    {
        $admin = User::where('email', 'superadmin@multilab.test')->first();

        $response = $this->actingAs($admin)->get(route('user-management.index', ['view' => 'pending']));

        $response->assertOk();
        $response->assertViewIs('usermanagement.management');
        $response->assertViewHas('view', 'pending');
    }

    public function test_aux_admin_is_forbidden_from_pending_tab(): void
    {
        $aux = User::where('email', 'auxiliar@multilab.test')->first();

        $response = $this->actingAs($aux)->get(route('user-management.index', ['view' => 'pending']));

        $response->assertStatus(403);
    }
}
