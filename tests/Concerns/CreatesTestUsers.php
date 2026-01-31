<?php

namespace Tests\Concerns;

use App\Models\User;
use Spatie\Permission\Models\Role;

trait CreatesTestUsers
{
    protected function createRole(string $name): Role
    {
        return Role::firstOrCreate([
            'name' => $name,
            'guard_name' => 'web',
        ]);
    }

    protected function createUser(array $attributes = []): User
    {
        return User::factory()->create($attributes);
    }

    protected function createPendingUser(array $attributes = []): User
    {
        return $this->createUser(array_merge([
            'is_active' => false,
            'is_blocked' => false,
        ], $attributes));
    }

    protected function createBlockedUser(array $attributes = []): User
    {
        return $this->createUser(array_merge([
            'is_active' => false,
            'is_blocked' => true,
        ], $attributes));
    }

    protected function createActiveUser(array $attributes = []): User
    {
        return $this->createUser(array_merge([
            'is_active' => true,
            'is_blocked' => false,
        ], $attributes));
    }

    protected function createUserWithRole(string $role, array $attributes = []): User
    {
        $user = $this->createActiveUser($attributes);

        $user->syncRoles([$this->createRole($role)]);

        return $user->refresh();
    }
}
