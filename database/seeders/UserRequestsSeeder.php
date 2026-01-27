<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\MaterialRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserRequestsSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPendingUsers();
        $this->seedBlockedUsers();
        $this->seedMaterialRequests();
    }

    private function seedPendingUsers(): void
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
            User::updateOrCreate(['email' => $attributes['email']], $attributes);
        }
    }

    private function seedBlockedUsers(): void
    {
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
            User::updateOrCreate(['email' => $attributes['email']], $attributes);
        }
    }

    private function seedMaterialRequests(): void
    {
        $materials = Material::query()->limit(8)->get();

        if ($materials->isEmpty()) {
            return;
        }

        $faker = fake()->seed(20260127);

        $definitions = [
            ['status' => 'pendiente', 'role' => 'estudiante', 'note' => 'Proyecto de bases de datos, requiere 2 adaptadores HDMI.', 'daysOffset' => 2, 'quantity' => 2],
            ['status' => 'aprobada', 'role' => 'docente', 'note' => 'Clase magistral de redes, solicita 4 cables UTP.', 'daysOffset' => 1, 'quantity' => 10],
            ['status' => 'rechazada', 'role' => 'estudiante', 'note' => 'Solicitud duplicada, ya hay préstamo activo.', 'daysOffset' => 0, 'quantity' => 1],
            ['status' => 'pendiente', 'role' => 'docente', 'note' => 'Clase de ingeniería de software necesita 2 kits.', 'daysOffset' => 3, 'quantity' => 1],
            ['status' => 'aprobada', 'role' => 'estudiante', 'note' => 'Taller de prototipado, se requiere multímetro.', 'daysOffset' => 4, 'quantity' => 2],
            ['status' => 'pendiente', 'role' => 'docente', 'note' => 'Reunión de evaluación, pide carro de servicio.', 'daysOffset' => 5, 'quantity' => 1],
        ];

        foreach ($definitions as $index => $definition) {
            $material = $materials->get($index % $materials->count());
            $requester = User::role($definition['role'])->inRandomOrder()->first();

            if (! $requester) {
                continue;
            }

            $neededAt = now()->addDays($definition['daysOffset']);
            $plannedReturn = (clone $neededAt)->addDays(3);

            MaterialRequest::updateOrCreate(
                [
                    'user_id' => $requester->id,
                    'material_id' => $material->id,
                    'needed_at' => $neededAt,
                ],
                [
                    'quantity' => $definition['quantity'],
                    'planned_return_at' => $plannedReturn,
                    'status' => $definition['status'],
                    'notes' => $definition['note'],
                ]
            );
        }
    }
}
