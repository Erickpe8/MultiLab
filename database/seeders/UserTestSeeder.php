<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserTestSeeder extends Seeder
{
    public function run(): void
    {
        // SUPERADMIN - Director de Ingeniería de Software
        $superadmin = User::create([
            'first_name'     => 'Director',
            'first_surname'  => 'Ingeniería',
            'second_surname' => 'Software',
            'email'          => 'superadmin@multilab.test',
            'password'       => Hash::make('password123'),
            'is_active'      => true,
        ]);
        $superadmin->assignRole('superadmin');


        // AUXILIAR
        $aux = User::create([
            'first_name'     => 'Auxiliar',
            'first_surname'  => 'Laboratorio',
            'email'          => 'auxiliar@multilab.test',
            'password'       => Hash::make('password123'),
            'is_active'      => true,
        ]);
        $aux->assignRole('aux_admin');


        // DOCENTE
        $docente = User::create([
            'first_name'     => 'Docente',
            'first_surname'  => 'FESC',
            'email'          => 'docente@multilab.test',
            'password'       => Hash::make('password123'),
            'is_active'      => true,
        ]);
        $docente->assignRole('docente');


        // ESTUDIANTE
        $estudiante = User::create([
            'first_name'     => 'Estudiante',
            'first_surname'  => 'FESC',
            'email'          => 'estudiante@multilab.test',
            'password'       => Hash::make('password123'),
            'is_active'      => true,
        ]);
        $estudiante->assignRole('estudiante');
    }
}