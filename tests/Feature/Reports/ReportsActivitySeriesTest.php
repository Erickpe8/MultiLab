<?php

namespace Tests\Feature\Reports;

use App\Models\ClassroomLoan;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Support\Carbon;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;
use Tests\Traits\Database\RefreshDatabaseSkipDropForeign;

class ReportsActivitySeriesTest extends TestCase
{
    use RefreshDatabaseSkipDropForeign;
    use CreatesTestUsers;

    public function test_activity_endpoint_returns_series_for_two_weeks(): void
    {
        $superadmin = $this->actingAsSuperAdmin();
        $borrower = $this->createActiveUser();

        $dates = [
            Carbon::today(),
            Carbon::today()->subDays(3),
        ];

        foreach ($dates as $date) {
            Loan::factory()->create([
                'user_id' => $borrower->id,
                'issued_by' => $superadmin->id,
                'loan_code' => 'ACT-' . $date->format('Ymd'),
                'loan_at' => $date->copy()->setTime(10, 0),
                'due_at' => $date->copy()->addDays(4),
                'status' => 'abierto',
            ]);

            ClassroomLoan::factory()->create([
                'status' => 'en_uso',
                'scheduled_start_at' => $date->copy()->setTime(9, 0),
                'scheduled_end_at' => $date->copy()->setTime(12, 0),
                'requested_by' => $borrower->id,
                'approved_by' => $superadmin->id,
            ]);
        }

        $response = $this->getJson(route('reports.activity'));

        $response->assertOk();

        $days = collect($response->json('days'));
        $this->assertCount(14, $days);

        $keyed = $days->keyBy('date');
        $todayKey = Carbon::today()->toDateString();
        $threeDaysAgoKey = Carbon::today()->subDays(3)->toDateString();

        $this->assertSame(1, (int) $keyed[$todayKey]['loans']);
        $this->assertSame(1, (int) $keyed[$todayKey]['reservations']);
        $this->assertSame(1, (int) $keyed[$threeDaysAgoKey]['loans']);
        $this->assertSame(1, (int) $keyed[$threeDaysAgoKey]['reservations']);
    }
}
