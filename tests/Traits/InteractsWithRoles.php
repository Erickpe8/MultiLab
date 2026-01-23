<?php

namespace Tests\Traits;

use App\Models\User;
use Spatie\Permission\Models\Role;

trait InteractsWithRoles
{
    protected function createRole(string $name): Role
    {
        return Role::firstOrCreate([
            'name' => $name,
            'guard_name' => 'web',
        ]);
    }

    protected function createUserWithRole(string $roleName, array $attributes = []): User
    {
        $role = $this->createRole($roleName);

        $user = User::factory()->create(array_merge([
            'role_name' => $roleName,
        ], $attributes));

        $user->syncRoles([$role]);

        return $user->refresh();
    }

    protected function actingAsRole(string $roleName, array $attributes = []): User
    {
        $user = $this->createUserWithRole($roleName, $attributes);

        $this->actingAs($user);

        return $user;
    }

    protected function actingAsSuperAdmin(array $attributes = []): User
    {
        return $this->actingAsRole('superadmin', $attributes);
    }

    protected function actingAsAuxAdmin(array $attributes = []): User
    {
        return $this->actingAsRole('aux_admin', $attributes);
    }
}
