<?php

namespace Tests\Feature\Reports;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Tests\Traits\Database\RefreshDatabaseSkipDropForeign;

class ReportsAuthorizationTest extends TestCase
{
    use RefreshDatabaseSkipDropForeign;

    public function test_summary_endpoint_requires_superadmin_role(): void
    {
        $this->actingAsAuxAdmin();

        $response = $this->getJson(route('reports.summary'));

        $response->assertStatus(403);
    }

    public function test_activity_endpoint_requires_superadmin_role(): void
    {
        $this->actingAsAuxAdmin();

        $response = $this->getJson(route('reports.activity'));

        $response->assertStatus(403);
    }

    public function test_reports_index_redirects_unverified_superadmin(): void
    {
        $role = Role::firstOrCreate([
            'name' => 'superadmin',
            'guard_name' => 'web',
        ]);

        $user = User::factory()->create([
            'email_verified_at' => null,
            'is_active' => true,
            'is_blocked' => false,
        ]);

        $user->syncRoles([$role]);

        $response = $this->actingAs($user)->get(route('reports.index'));

        $response->assertRedirect(route('verification.notice'));
    }
}
