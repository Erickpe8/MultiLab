<?php

namespace Tests\Feature\UserManagement;

use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;
use Tests\Traits\Database\RefreshDatabaseSkipDropForeign;

class DeactivationTest extends TestCase
{
    use RefreshDatabaseSkipDropForeign;
    use CreatesTestUsers;

    public function test_deactivate_endpoint_blocks_active_user(): void
    {
        $target = $this->createActiveUser();

        $this->actingAsSuperAdmin();
        $response = $this->patchJson(route('user-management.deactivate', $target));

        $response->assertOk();

        $this->assertTrue($target->fresh()->is_blocked);
        $this->assertFalse($target->fresh()->is_active);
    }
}
