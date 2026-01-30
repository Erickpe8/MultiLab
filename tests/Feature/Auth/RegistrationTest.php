<?php

namespace Tests\Feature\Auth;

use Tests\Traits\Database\RefreshDatabaseSkipDropForeign;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabaseSkipDropForeign;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_registration_requires_institutional_email(): void
    {
        $response = $this->from(route('register'))->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Password123*',
            'password_confirmation' => 'Password123*',
        ]);

        $response->assertRedirect(route('register'));
        $response->assertInvalid('email');
        $this->assertGuest();
    }

    public function test_registration_rejects_similar_domain(): void
    {
        $response = $this->from(route('register'))->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@fesc.edu.co.co',
            'password' => 'Password123*',
            'password_confirmation' => 'Password123*',
        ]);

        $response->assertRedirect(route('register'));
        $response->assertInvalid('email');
        $this->assertGuest();
    }

    public function test_new_users_can_register_with_institutional_email(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'TestUser@FESC.EDU.CO',
            'password' => 'Password123*',
            'password_confirmation' => 'Password123*',
        ]);

        $this->assertGuest();
        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('users', [
            'email' => 'testuser@fesc.edu.co',
        ]);
    }
}
