<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\PermissionRegistrar;
use Tests\Traits\InteractsWithRoles;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use InteractsWithRoles;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
