<?php

namespace Tests\Feature\Auth;

use Tests\Traits\Database\RefreshDatabaseSkipDropForeign;
use Tests\TestCase;

class AccessControlTest extends TestCase
{
    use RefreshDatabaseSkipDropForeign;

    public function test_guest_is_redirected_from_dashboard(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_is_redirected_from_profile(): void
    {
        $response = $this->get(route('profile.edit'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_user_management(): void
    {
        $response = $this->get(route('user-management.index'));

        $response->assertRedirect(route('login'));
    }
}
