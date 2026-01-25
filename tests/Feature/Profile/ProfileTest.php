<?php

namespace Tests\Feature\Profile;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserTestSeeder;
use Tests\Traits\Database\RefreshDatabaseSkipDropForeign;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabaseSkipDropForeign;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $this->seed(UserTestSeeder::class);
    }

    public function test_authenticated_user_can_view_profile_page(): void
    {
        $user = User::where('email', 'auxiliar@multilab.test')->first();

        $response = $this->actingAs($user)->get(route('profile.edit'));

        $response->assertOk();
        $response->assertSee('Perfil');
    }

    public function test_profile_update_persists_changes(): void
    {
        $user = User::where('email', 'auxiliar@multilab.test')->first();

        $payload = [
            'first_name' => 'Auxiliar',
            'middle_name' => 'Renovado',
            'first_surname' => 'Laboratorio',
            'second_surname' => 'Prueba',
            'gender' => 'M',
            'email' => 'auxiliar.updated@multilab.test',
            'notify_email' => '1',
            'notify_in_app' => '0',
            'digest_frequency' => 'daily',
            'theme' => 'dark',
            'compact_mode' => '1',
            'phone' => '6012345678',
            'mobile' => '3161234567',
        ];

        $response = $this->actingAs($user)->patch(route('profile.update'), $payload);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success', 'Perfil actualizado correctamente.');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'first_name' => 'Auxiliar',
            'middle_name' => 'Renovado',
            'email' => 'auxiliar.updated@multilab.test',
            'phone' => '6012345678',
            'mobile' => '3161234567',
        ]);
    }
}
