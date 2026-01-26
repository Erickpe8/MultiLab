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

    public function test_active_user_with_correct_credentials_can_login(): void
    {
        $user = User::factory()->create([
            'is_active'  => true,
            'is_blocked' => false,
            'password'   => 'password123',
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_pending_user_cannot_authenticate_and_sees_validation_error(): void
    {
        $pending = User::factory()->create([
            'is_active'  => false,
            'is_blocked' => false,
            'password'   => 'password123',
        ]);

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => $pending->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_blocked_user_cannot_authenticate_and_is_denied(): void
    {
        $blocked = User::factory()->create([
            'is_active'  => false,
            'is_blocked' => true,
            'password'   => 'password123',
        ]);

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => $blocked->email,
            'password' => 'password123',
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
