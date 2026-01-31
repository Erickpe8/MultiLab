<?php

namespace Tests\Feature\Public;

use Tests\Traits\Database\RefreshDatabaseSkipDropForeign;
use Tests\TestCase;

class ManualTest extends TestCase
{
    use RefreshDatabaseSkipDropForeign;

    public function test_manual_page_is_public_and_displays_guides(): void
    {
        $response = $this->get(route('manual.index'));

        $response->assertOk();
        $response->assertSee('Manual de Usuario');
        $response->assertSee('<div id="toast-stack"', false);
        $response->assertDontSee('Filament');
        $response->assertDontSee('Dashboard');
    }

    public function test_manual_page_lists_all_roles_in_index(): void
    {
        $response = $this->get(route('manual.index'));

        $response->assertOk();
        $response->assertSee('Super Administrador');
        $response->assertSee('Administrador Auxiliar');
        $response->assertSee('Docente');
        $response->assertSee('Estudiante');
    }
}
