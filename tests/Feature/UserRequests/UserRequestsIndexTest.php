<?php

namespace Tests\Feature\UserRequests;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserTestSeeder;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;
use Tests\Traits\Database\RefreshDatabaseSkipDropForeign;

class UserRequestsIndexTest extends TestCase
{
    use RefreshDatabaseSkipDropForeign;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $this->seed(UserTestSeeder::class);
    }

    public function test_superadmin_can_view_pending_requests_listing(): void
    {
        $superadmin = User::where('email', 'superadmin@multilab.test')->firstOrFail();

        $pendingUsers = User::factory()->count(3)->create([
            'is_active'  => false,
            'is_blocked' => false,
        ]);

        $response = $this->actingAs($superadmin)->get(route('user-management.index', ['view' => 'pending']));

        $response->assertOk();
        $response->assertViewIs('usermanagement.management');

        $pendingPaginator = $response->viewData('pendingUsers');
        $this->assertInstanceOf(LengthAwarePaginator::class, $pendingPaginator);
        $this->assertSame($pendingUsers->count(), $pendingPaginator->total());

        $returnedIds = $pendingPaginator->pluck('id')->all();
        foreach ($pendingUsers as $pendingUser) {
            $this->assertContains($pendingUser->id, $returnedIds);
        }
    }

    public function test_regular_user_cannot_access_pending_requests(): void
    {
        $docente = User::where('email', 'docente@multilab.test')->firstOrFail();

        $response = $this->actingAs($docente)->get(route('user-management.index', ['view' => 'pending']));

        $response->assertStatus(403);
    }

    public function test_pending_scope_matches_expected_badge_count(): void
    {
        User::factory()->create([
            'is_active'  => false,
            'is_blocked' => false,
        ]);

        User::factory()->create([
            'is_active'  => false,
            'is_blocked' => false,
        ]);

        User::factory()->create([
            'is_active'  => false,
            'is_blocked' => true,
        ]);

        User::factory()->create([
            'is_active'  => true,
            'is_blocked' => false,
        ]);

        $manualCount = User::where('is_active', false)
            ->where('is_blocked', false)
            ->count();

        $scopeCount = User::pending()->count();

        $this->assertSame($manualCount, $scopeCount);
        $this->assertEquals(2, $scopeCount);
    }
}
