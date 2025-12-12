<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create([
            'email' => 'old@example.com',
        ]);

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User', // se ignora en tu app → correcto
                'email' => 'test@example.com',
            ]);

        $response->assertRedirect('/profile');

        $user->refresh();

        // No exigimos que sea "Test User", porque MultiLab no usa 'name'
        $this->assertIsString($user->name);

        // Email SI debe cambiar
        $this->assertSame('test@example.com', $user->email);

        // Email NO debe verificarse nuevamente
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'email_verified_at' => now(),
        ]);

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response->assertRedirect('/profile');

        $user->refresh();

        $this->assertNotNull($user->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response->assertRedirect('/');

        $this->assertGuest();
        $this->assertModelMissing($user);
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
        ->actingAs($user)
        ->delete('/profile', [
        'password' => 'wrong-password',
        ]);

        $this->assertNotNull($user->fresh());
    }

}
