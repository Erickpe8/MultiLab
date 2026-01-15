<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
class UserSeeder extends Seeder
{
    private const PASSWORD = 'Password123*';

    public function run(): void
    {
        $users = [
            [
                'role' => 'Administrador',
                'attributes' => [
                    'first_name'     => 'Admin',
                    'middle_name'    => null,
                    'first_surname'  => 'Central',
                    'second_surname' => 'Fesc',
                    'email'          => 'admin@fesc.edu.co',
                    'gender'         => 'M',
                    'document_type'  => 'CC',
                    'document_number'=> '1000000001',
                    'phone'          => '6010000001',
                ],
            ],
            [
                'role' => 'Director de Programa',
                'attributes' => [
                    'first_name'     => 'Jesus',
                    'middle_name'    => 'Antonio',
                    'first_surname'  => 'Figueroa',
                    'second_surname' => 'Guerrero',
                    'email'          => 'director@fesc.edu.co',
                    'gender'         => 'M',
                    'document_type'  => 'CC',
                    'document_number'=> '1000000002',
                    'phone'          => '6010000002',
                ],
            ],
            [
                'role' => 'Estudiante',
                'attributes' => [
                    'first_name'     => 'Juliana',
                    'middle_name'    => null,
                    'first_surname'  => 'Montoya',
                    'second_surname' => 'Pena',
                    'email'          => 'estudiante1@fesc.edu.co',
                    'gender'         => 'F',
                    'document_type'  => 'TI',
                    'document_number'=> '2000000001',
                    'phone'          => '6010000003',
                ],
            ],
            [
                'role' => 'Auxiliar Administrativo',
                'attributes' => [
                    'first_name'     => 'Auxiliar',
                    'middle_name'    => null,
                    'first_surname'  => 'Operaciones',
                    'second_surname' => null,
                    'email'          => 'auxiliar@fesc.edu.co',
                    'gender'         => 'M',
                    'document_type'  => 'CC',
                    'document_number'=> '1000000003',
                    'phone'          => '6010000004',
                ],
            ],
            [
                'role' => 'Docente',
                'attributes' => [
                    'first_name'     => 'Carlos',
                    'middle_name'    => null,
                    'first_surname'  => 'Munoz',
                    'second_surname' => 'Rojas',
                    'email'          => 'docente@fesc.edu.co',
                    'gender'         => 'M',
                    'document_type'  => 'CC',
                    'document_number'=> '1000000004',
                    'phone'          => '6010000005',
                ],
            ],
        ];

        foreach ($users as $definition) {
            $email = $definition['attributes']['email'];

            $user = User::updateOrCreate(
                ['email' => $email],
                array_merge(
                    $definition['attributes'],
                    [
                        'password'  => self::PASSWORD,
                        'is_active' => true,
                    ]
                )
            );

            $user->syncRoles([$definition['role']]);
        }
    }
}
