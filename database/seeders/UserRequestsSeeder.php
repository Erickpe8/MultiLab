<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserRequestsSeeder extends Seeder
{
    /**
     * En MultiLab una solicitud no es una entidad aparte, es un usuario pendiente.
     */
    public function run(): void
    {
        $pendingUsers = [
            [
                'first_name' => 'Laura',
                'middle_name' => 'María',
                'first_surname' => 'Palacio',
                'second_surname' => 'Rojas',
                'email' => 'solicitud1@fesc.edu.co',
                'password' => 'Password123*',
                'gender' => 'F',
                'document_type' => 'CC',
                'document_number' => '3000000001',
                'phone' => '3180000001',
                'mobile' => '3100000001',
                'is_active' => false,
                'is_blocked' => false,
                'role_name' => 'docente',
                'area' => 'Sistemas',
            ],
            [
                'first_name' => 'Camilo',
                'middle_name' => null,
                'first_surname' => 'Ramos',
                'second_surname' => 'Muñoz',
                'email' => 'solicitud2@fesc.edu.co',
                'password' => 'Password123*',
                'gender' => 'M',
                'document_type' => 'TI',
                'document_number' => '3000000002',
                'phone' => '3180000002',
                'mobile' => '3100000002',
                'is_active' => false,
                'is_blocked' => false,
                'role_name' => 'estudiante',
                'area' => 'Ingeniería',
            ],
            [
                'first_name' => 'Natalia',
                'middle_name' => 'Beatriz',
                'first_surname' => 'Sierra',
                'second_surname' => 'Ríos',
                'email' => 'solicitud3@fesc.edu.co',
                'password' => 'Password123*',
                'gender' => 'F',
                'document_type' => 'CC',
                'document_number' => '3000000003',
                'phone' => '3180000003',
                'mobile' => '3100000003',
                'is_active' => false,
                'is_blocked' => false,
                'role_name' => 'aux_admin',
                'area' => 'Administración',
            ],
            [
                'first_name' => 'Eduardo',
                'middle_name' => 'Andrés',
                'first_surname' => 'Quintero',
                'second_surname' => 'López',
                'email' => 'solicitud4@fesc.edu.co',
                'password' => 'Password123*',
                'gender' => 'M',
                'document_type' => 'TI',
                'document_number' => '3000000004',
                'phone' => '3180000004',
                'mobile' => '3100000004',
                'is_active' => false,
                'is_blocked' => false,
                'role_name' => 'docente',
                'area' => 'Didáctica',
            ],
            [
                'first_name' => 'Daniela',
                'middle_name' => null,
                'first_surname' => 'Cárdenas',
                'second_surname' => 'Giraldo',
                'email' => 'solicitud5@fesc.edu.co',
                'password' => 'Password123*',
                'gender' => 'F',
                'document_type' => 'CC',
                'document_number' => '3000000005',
                'phone' => '3180000005',
                'mobile' => '3100000005',
                'is_active' => false,
                'is_blocked' => false,
                'role_name' => 'estudiante',
                'area' => 'Ciencias',
            ],
        ];

        foreach ($pendingUsers as $attributes) {
            User::updateOrCreate(
                ['email' => $attributes['email']],
                $attributes
            );
        }

        $blockedUsers = [
            [
                'first_name' => 'Luis',
                'middle_name' => 'Fernando',
                'first_surname' => 'Vargas',
                'second_surname' => 'Barrios',
                'email' => 'bloqueado1@fesc.edu.co',
                'password' => 'Password123*',
                'gender' => 'M',
                'document_type' => 'CC',
                'document_number' => '4000000001',
                'phone' => '3180000011',
                'mobile' => '3100000011',
                'is_active' => false,
                'is_blocked' => true,
                'role_name' => null,
                'area' => 'Infraestructura',
            ],
            [
                'first_name' => 'Marcela',
                'middle_name' => 'Elena',
                'first_surname' => 'Fernández',
                'second_surname' => 'Vega',
                'email' => 'bloqueado2@fesc.edu.co',
                'password' => 'Password123*',
                'gender' => 'F',
                'document_type' => 'TI',
                'document_number' => '4000000002',
                'phone' => '3180000012',
                'mobile' => '3100000012',
                'is_active' => false,
                'is_blocked' => true,
                'role_name' => null,
                'area' => 'Laboratorio',
            ],
            [
                'first_name' => 'Santiago',
                'middle_name' => null,
                'first_surname' => 'Rubio',
                'second_surname' => 'Nariño',
                'email' => 'bloqueado3@fesc.edu.co',
                'password' => 'Password123*',
                'gender' => 'M',
                'document_type' => 'CC',
                'document_number' => '4000000003',
                'phone' => '3180000013',
                'mobile' => '3100000013',
                'is_active' => false,
                'is_blocked' => true,
                'role_name' => null,
                'area' => 'Soporte',
            ],
            [
                'first_name' => 'Valentina',
                'middle_name' => 'Adriana',
                'first_surname' => 'Moreno',
                'second_surname' => 'Fajardo',
                'email' => 'bloqueado4@fesc.edu.co',
                'password' => 'Password123*',
                'gender' => 'F',
                'document_type' => 'TI',
                'document_number' => '4000000004',
                'phone' => '3180000014',
                'mobile' => '3100000014',
                'is_active' => false,
                'is_blocked' => true,
                'role_name' => null,
                'area' => 'Finanzas',
            ],
            [
                'first_name' => 'Andrés',
                'middle_name' => 'Daniel',
                'first_surname' => 'Medina',
                'second_surname' => 'Toro',
                'email' => 'bloqueado5@fesc.edu.co',
                'password' => 'Password123*',
                'gender' => 'M',
                'document_type' => 'CC',
                'document_number' => '4000000005',
                'phone' => '3180000015',
                'mobile' => '3100000015',
                'is_active' => false,
                'is_blocked' => true,
                'role_name' => null,
                'area' => 'Mantenimiento',
            ],
        ];

        foreach ($blockedUsers as $attributes) {
            User::updateOrCreate(
                ['email' => $attributes['email']],
                $attributes
            );
        }
    }
}
