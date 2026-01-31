<?php

namespace Tests\Feature\Reports;

use App\Models\ClassroomLoan;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Support\Carbon;
use Tests\Concerns\CreatesTestUsers;
use Tests\TestCase;
use Tests\Traits\Database\RefreshDatabaseSkipDropForeign;

class ReportsSummaryDataTest extends TestCase
{
    use RefreshDatabaseSkipDropForeign;
    use CreatesTestUsers;

    public function test_summary_cards_reflect_pending_loans_and_reservations(): void
    {
        $superadmin = $this->actingAsSuperAdmin();

        $this->createPendingUser(['email' => 'reports-pending@multilab.test']);

        $borrower = $this->createActiveUser();

        Loan::factory()->create([
            'user_id' => $borrower->id,
            'issued_by' => $superadmin->id,
            'loan_code' => 'SUM-ACTIVE-1',
            'loan_at' => Carbon::now()->subDays(3),
            'due_at' => Carbon::now()->addDays(5),
            'status' => 'abierto',
        ]);

        Loan::factory()->create([
            'user_id' => $borrower->id,
            'issued_by' => $superadmin->id,
            'loan_code' => 'SUM-ACTIVE-2',
            'loan_at' => Carbon::now()->subDays(4),
            'due_at' => Carbon::now()->addDays(2),
            'status' => 'abierto',
        ]);

        Loan::factory()->create([
            'user_id' => $borrower->id,
            'issued_by' => $superadmin->id,
            'loan_code' => 'SUM-OVERDUE',
            'loan_at' => Carbon::now()->subDays(10),
            'due_at' => Carbon::now()->subDays(2),
            'status' => 'vencido',
        ]);

        ClassroomLoan::factory()->create([
            'status' => 'aprobado',
            'scheduled_start_at' => Carbon::now()->subHour(),
            'scheduled_end_at' => Carbon::now()->addHour(),
            'requested_by' => $borrower->id,
            'approved_by' => $superadmin->id,
        ]);

        $response = $this->getJson(route('reports.summary'));

        $response->assertOk();
        $cards = collect($response->json('cards'));

        $this->assertSame(1, $this->cardValue($cards, 'pending_users'));
        $this->assertSame(2, $this->cardValue($cards, 'active_loans'));
        $this->assertSame(1, $this->cardValue($cards, 'overdue_loans'));
        $this->assertSame(1, $this->cardValue($cards, 'active_reservations'));
    }

    public function test_summary_cards_are_returned_in_the_expected_order(): void
    {
        $this->actingAsSuperAdmin();

        $response = $this->getJson(route('reports.summary'));

        $response->assertOk();
        $keys = collect($response->json('cards'))->pluck('key')->values()->all();

        $this->assertSame([
            'pending_users',
            'active_loans',
            'overdue_loans',
            'active_reservations',
        ], $keys);
    }

    private function cardValue($cards, string $key): int
    {
        foreach ($cards as $card) {
            if ($card['key'] === $key) {
                return (int) $card['value'];
            }
        }

        $this->fail("Report card [{$key}] not found.");
    }
}
