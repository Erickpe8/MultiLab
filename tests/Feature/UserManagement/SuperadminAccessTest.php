<?php

namespace Tests\Feature\UserManagement;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserTestSeeder;
use Tests\Traits\Database\RefreshDatabaseSkipDropForeign;
use Tests\TestCase;

class SuperadminAccessTest extends TestCase
{
    use RefreshDatabaseSkipDropForeign;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $this->seed(UserTestSeeder::class);
    }

    public function test_aux_admin_cannot_access_user_management(): void
    {
        $aux = User::where('email', 'auxiliar@multilab.test')->first();

        $response = $this->actingAs($aux)->get(route('user-management.index'));

        $response->assertStatus(403);
    }

    public function test_superadmin_can_access_user_management(): void
    {
        $admin = User::where('email', 'superadmin@multilab.test')->first();

        $response = $this->actingAs($admin)->get(route('user-management.index'));

        $response->assertOk();
        $response->assertViewIs('usermanagement.management');
    }
}
