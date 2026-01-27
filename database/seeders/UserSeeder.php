<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private const PASSWORD = 'Password123*';
    private const SUPERADMIN_EMAIL = 'superadmin@fesc.edu.co';
    private const AUX_COUNT = 24;
    private const DOCENTE_COUNT = 1020;
    private const ESTUDIANTE_COUNT = 4080;

    public function run(): void
    {
        $faker = \Faker\Factory::create('es_CO');
        $faker->seed(20260127);

        $this->createSuperadmin();
        $this->createRoleBatch('aux_admin', self::AUX_COUNT, 'aux', 2, 'CC', 1100000000, 6011000000, $faker);
        $this->createRoleBatch('docente', self::DOCENTE_COUNT, 'docente', 4, 'CC', 1200000000, 6012000000, $faker);
        $this->createRoleBatch('estudiante', self::ESTUDIANTE_COUNT, 'estudiante', 4, 'TI', 2100000000, 6013000000, $faker);
    }

    private function createSuperadmin(): void
    {
        $admin = [
            'first_name' => 'Camilo',
            'middle_name' => 'Andrés',
            'first_surname' => 'Pérez',
            'second_surname' => 'Ávila',
            'email' => self::SUPERADMIN_EMAIL,
            'gender' => 'M',
            'document_type' => 'CC',
            'document_number' => '1000000001',
            'phone' => '6010000001',
        ];

        $user = User::updateOrCreate(
            ['email' => $admin['email']],
            array_merge(
                $admin,
                [
                    'password' => self::PASSWORD,
                    'is_active' => true,
                    'role_name' => 'superadmin',
                ]
            )
        );

        $user->syncRoles(['superadmin']);
    }

    private function createRoleBatch(
        string $role,
        int $quantity,
        string $emailPrefix,
        int $padLength,
        string $documentType,
        int $documentStart,
        int $phoneStart,
        \Faker\Generator $faker
    ): void {
        for ($i = 1; $i <= $quantity; $i++) {
            $email = sprintf('%s%0' . $padLength . 'd@fesc.edu.co', $emailPrefix, $i);
            $gender = $faker->randomElement(['M', 'F']);
            $names = $this->buildNames($faker, $gender);

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'first_name' => $names['first_name'],
                    'middle_name' => $names['middle_name'],
                    'first_surname' => $names['first_surname'],
                    'second_surname' => $names['second_surname'],
                    'email' => $email,
                    'gender' => $gender,
                    'document_type' => $documentType,
                    'document_number' => (string) ($documentStart + $i),
                    'phone' => (string) ($phoneStart + $i),
                    'password' => self::PASSWORD,
                    'is_active' => true,
                    'role_name' => $role,
                ]
            );

            $user->syncRoles([$role]);
        }
    }

    private function buildNames(\Faker\Generator $faker, string $gender): array
    {
        $firstName = $gender === 'F' ? $faker->firstNameFemale() : $faker->firstNameMale();
        $middleName = $faker->boolean(30) ? $faker->firstNameMale() : null;
        $firstSurname = $faker->lastName();
        $secondSurname = $faker->boolean(70) ? $faker->lastName() : null;

        return [
            'first_name' => $firstName,
            'middle_name' => $middleName,
            'first_surname' => $firstSurname,
            'second_surname' => $secondSurname,
        ];
    }
}
