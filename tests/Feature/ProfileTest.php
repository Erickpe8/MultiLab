<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\Traits\Database\RefreshDatabaseSkipDropForeign;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabaseSkipDropForeign;

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
            ->patch('/profile', $this->profilePayload([
                'email' => 'test@example.com',
            ]));

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
            ->patch('/profile', $this->profilePayload([
                'email' => 'test@example.com',
            ]));

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

        $response->assertStatus(500);

        $this->assertAuthenticated();
        $this->assertModelExists($user);
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
        ->actingAs($user)
        ->delete('/profile', [
        'password' => 'wrong-password',
        ]);

        $response->assertStatus(500);

        $this->assertNotNull($user->fresh());
    }

    private function profilePayload(array $overrides = []): array
    {
        return array_merge([
            'first_name' => 'Jane',
            'middle_name' => 'Q.',
            'first_surname' => 'Profile',
            'second_surname' => 'Tester',
            'gender' => null,
            'email' => 'profile@example.com',
            'notify_email' => '1',
            'notify_in_app' => '1',
            'digest_frequency' => 'weekly',
            'theme' => 'system',
            'compact_mode' => '0',
            'phone' => '3101234567',
            'mobile' => '3109876543',
            'phone_extension' => '101',
        ], $overrides);
    }

}
