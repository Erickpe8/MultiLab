<?php

namespace Tests\Feature\Public;

use Tests\Traits\Database\RefreshDatabaseSkipDropForeign;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabaseSkipDropForeign;

    public function test_home_page_is_public_and_highlights_manual(): void
    {
        $response = $this->get(route('welcome'));

        $response->assertOk();
        $response->assertSee('Manual de Usuario');
        $response->assertSee('Explorar manual');
    }

    public function test_home_page_shows_operational_modules(): void
    {
        $response = $this->get(route('welcome'));

        $response->assertOk();
        $response->assertSee('Centraliza reservas');
        $response->assertSee('Guías rápidas por rol');
        $response->assertSee('Visibilidad total del laboratorio');
    }
}
