<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserTestSeeder extends Seeder
{
    public function run(): void
    {
        /**
         * SUPERADMIN
         * Director de Ingeniería de Software
         */
        $superadmin = User::create([
            'first_name'     => 'Director',
            'middle_name'    => null,
            'first_surname'  => 'Ingeniería',
            'second_surname' => 'Software',

            'email'          => 'superadmin@multilab.test',
            'password'       => Hash::make('password123'),

            'document_type'  => null,
            'document_number'=> null,
            'phone'          => null,

            'is_active'      => true,
        ]);
        $superadmin->assignRole('superadmin');


        /**
         * AUXILIAR ADMINISTRATIVO DEL LABORATORIO
         */
        $aux = User::create([
            'first_name'     => 'Auxiliar',
            'middle_name'    => null,
            'first_surname'  => 'Laboratorio',
            'second_surname' => null,

            'email'          => 'auxiliar@multilab.test',
            'password'       => Hash::make('password123'),

            'document_type'  => null,
            'document_number'=> null,
            'phone'          => null,

            'is_active'      => true,
        ]);
        $aux->assignRole('aux_admin');


        /**
         * DOCENTE
         */
        $docente = User::create([
            'first_name'     => 'Docente',
            'middle_name'    => null,
            'first_surname'  => 'FESC',
            'second_surname' => null,

            'email'          => 'docente@multilab.test',
            'password'       => Hash::make('password123'),

            'document_type'  => null,
            'document_number'=> null,
            'phone'          => null,

            'is_active'      => true,
        ]);
        $docente->assignRole('docente');


        /**
         * ESTUDIANTE
         */
        $estudiante = User::create([
            'first_name'     => 'Estudiante',
            'middle_name'    => null,
            'first_surname'  => 'FESC',
            'second_surname' => null,

            'email'          => 'estudiante@multilab.test',
            'password'       => Hash::make('password123'),

            'document_type'  => null,
            'document_number'=> null,
            'phone'          => null,

            'is_active'      => true,
        ]);
        $estudiante->assignRole('estudiante');
    }
}
