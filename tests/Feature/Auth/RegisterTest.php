<?php

namespace Tests\Feature\Auth;

use Tests\Traits\Database\RefreshDatabaseSkipDropForeign;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabaseSkipDropForeign;

    public function test_register_page_is_accessible(): void
    {
        $response = $this->get(route('register'));

        $response->assertOk();
        $response->assertSee('Pre-registro MultiLab');
    }

    public function test_registration_requires_mandatory_fields(): void
    {
        $response = $this->from(route('register'))->post(route('register'), []);

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }
}
