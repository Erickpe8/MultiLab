<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    private const GUARD = 'web';

    public function run(): void
    {
        $roles = [
            'superadmin',
            'aux_admin',
            'docente',
            'estudiante',
        ];

        foreach ($roles as $roleName) {
            Role::updateOrCreate(
                ['name' => $roleName, 'guard_name' => self::GUARD],
                []
            );
        }
    }
}
