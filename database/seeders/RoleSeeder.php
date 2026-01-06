<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Spatie guarda roles/permissions con guard_name; esta app usa el guard web.
        $guard = 'web';

        $roles = [
            'Administrador',
            'Director de Programa',
            'Estudiante',
            'Auxiliar Administrativo',
            'Docente',
        ];

        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(
                ['name' => $roleName, 'guard_name' => $guard]
            );

            // Placeholder para asignar permisos más adelante (ej. $role->syncPermissions([...])).
        }
    }
}