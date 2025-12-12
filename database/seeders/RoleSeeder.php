<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // IMPORTANTE: asegurar el guard_name correcto
        $guard = 'web';

        $roles = [
            'superadmin',
            'aux_admin',
            'docente',
            'estudiante',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role, 'guard_name' => $guard]
            );
        }
    }
}
