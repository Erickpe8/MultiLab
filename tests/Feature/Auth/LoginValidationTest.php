<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Tests\TestCase;
use Tests\Traits\Database\RefreshDatabaseSkipDropForeign;

class LoginValidationTest extends TestCase
{
    use RefreshDatabaseSkipDropForeign;

    public function test_email_is_required_to_login(): void
    {
        $response = $this->from(route('login'))->post(route('login'), [
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors([
            'email' => 'Escribe tu correo institucional.',
        ]);
    }

    public function test_email_must_be_a_valid_address(): void
    {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'invalid-email',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors([
            'email' => 'Ingresa un correo válido.',
        ]);
    }

    public function test_password_is_required_for_login(): void
    {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'user@example.com',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors([
            'password' => 'Escribe tu contraseña.',
        ]);
    }

    public function test_password_must_have_minimum_length(): void
    {
        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'user@example.com',
            'password' => 'short',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors([
            'password' => 'La contraseña debe tener al menos 8 caracteres.',
        ]);
    }

    public function test_blocked_user_receives_blocked_message(): void
    {
        $blocked = User::factory()->create([
            'is_active' => false,
            'is_blocked' => true,
            'password' => 'password123',
        ]);

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => $blocked->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors([
            'email' => 'Tu usuario está bloqueado. Contacta al administrador.',
        ]);

        $this->clearLoginThrottle($blocked->email);
    }

    public function test_inactive_user_receives_pending_message(): void
    {
        $pending = User::factory()->create([
            'is_active' => false,
            'is_blocked' => false,
            'password' => 'password123',
        ]);

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => $pending->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors([
            'email' => 'Tu usuario está pendiente de aprobación.',
        ]);

        $this->clearLoginThrottle($pending->email);
    }

    public function test_unverified_account_receives_pending_message(): void
    {
        $unverified = User::factory()->unverified()->create([
            'is_active' => true,
            'is_blocked' => false,
            'password' => 'password123',
        ]);

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => $unverified->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors([
            'email' => 'Tu usuario está pendiente de aprobación.',
        ]);

        $this->clearLoginThrottle($unverified->email);
    }

    private function clearLoginThrottle(string $email): void
    {
        $signature = Str::transliterate(Str::lower($email) . '|127.0.0.1');
        RateLimiter::clear($signature);
    }
}
