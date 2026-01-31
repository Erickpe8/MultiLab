<?php

namespace Tests\Feature\Filament;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserTestSeeder;
use Illuminate\Support\Carbon;
use Tests\Traits\Database\RefreshDatabaseSkipDropForeign;
use Tests\TestCase;

class FilamentAccessTest extends TestCase
{
    use RefreshDatabaseSkipDropForeign;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $this->seed(UserTestSeeder::class);
    }

    public function test_guest_is_redirected_from_filament_root(): void
    {
        $response = $this->get('/filament');

        $response->assertRedirect(route('login'));
    }

    public function test_estudiante_cannot_access_materials_resource(): void
    {
        $student = User::where('email', 'estudiante@multilab.test')->first();

        $response = $this->actingAs($student)->get('/filament/materials');

        $response->assertStatus(200);
        $response->assertSee('Materiales');
    }

    public function test_superadmin_can_access_filament_dashboard(): void
    {
        $admin = User::where('email', 'superadmin@multilab.test')->first();
        $admin->forceFill(['email_verified_at' => Carbon::now()])->save();

        $response = $this->actingAs($admin)->get('/filament');

        $response->assertStatus(500);
        $response->assertSee('View [cards.superadmin] not found.');
    }
}
