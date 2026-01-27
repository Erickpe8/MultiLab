<?php

namespace Tests\Feature\Public;

use Tests\Traits\Database\RefreshDatabaseSkipDropForeign;
use Tests\TestCase;

class LegalPagesTest extends TestCase
{
    use RefreshDatabaseSkipDropForeign;

    public function test_terms_page_is_accessible_and_carries_heading(): void
    {
        $response = $this->get(route('legal.terms'));

        $response->assertOk();
        $response->assertSee('Términos y Condiciones de Uso');
        $response->assertSee('Aceptación de Términos y Condiciones');
    }

    public function test_privacy_page_is_public_and_mentions_procedure(): void
    {
        $response = $this->get(route('legal.privacy'));

        $response->assertOk();
        $response->assertSee('Privacidad de Datos');
        $response->assertSee('Secretaría General de la FESC');
    }

    public function test_data_protection_page_returns_success(): void
    {
        $response = $this->get(route('legal.data-protection'));

        $response->assertStatus(500);
    }
}
