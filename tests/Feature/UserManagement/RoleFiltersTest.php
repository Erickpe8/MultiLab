<?php

namespace Tests\Feature\UserManagement;

use Spatie\Permission\Models\Role;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;
use Tests\Traits\Database\RefreshDatabaseSkipDropForeign;

class RoleFiltersTest extends TestCase
{
    use RefreshDatabaseSkipDropForeign;
    use CreatesTestUsers;

    protected function setUp(): void
    {
        parent::setUp();

        collect([
            'superadmin',
            'aux_admin',
            'docente',
            'estudiante',
        ])->each(fn (string $role) => Role::firstOrCreate([
            'name' => $role,
            'guard_name' => 'web',
        ]));
    }

    public function test_roles_are_available_in_the_view_context(): void
    {
        $this->actingAsSuperAdmin();

        $response = $this->get(route('user-management.index'));

        $response->assertOk();

        $roleNames = $response->viewData('roles')->pluck('name')->all();

        $this->assertEqualsCanonicalizing([
            'superadmin',
            'aux_admin',
            'docente',
            'estudiante',
        ], $roleNames);
    }

    public function test_blocked_role_filter_limits_blocked_users(): void
    {
        $blocked = $this->createUserWithRole('docente', [
            'is_active' => false,
            'is_blocked' => true,
        ]);

        $this->actingAsSuperAdmin();

        $response = $this->get(route('user-management.index', [
            'view' => 'blocked',
            'blocked_role' => 'docente',
        ]));

        $response->assertOk();

        $blockedUsers = $response->viewData('blockedUsers');

        $this->assertSame(1, $blockedUsers->total());
        $this->assertSame($blocked->id, $blockedUsers->items()[0]->id);
    }
}
