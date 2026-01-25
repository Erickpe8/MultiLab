<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserTestSeeder;
use Tests\Traits\Database\RefreshDatabaseSkipDropForeign;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabaseSkipDropForeign;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $this->seed(UserTestSeeder::class);
    }

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertSee('Iniciar sesión');
    }

    public function test_invalid_credentials_do_not_authenticate_user(): void
    {
        $user = User::where('email', 'superadmin@multilab.test')->first();

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => $user?->email,
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_valid_credentials_authenticate_and_redirect(): void
    {
        $user = User::where('email', 'superadmin@multilab.test')->first();

        $response = $this->post(route('login'), [
            'email' => $user?->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('notify');
        $this->assertAuthenticatedAs($user);
    }
}
