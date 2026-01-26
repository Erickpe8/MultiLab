<?php

namespace App\Http\Controllers;

use App\Models\ClassroomLoan;
use App\Models\Loan;
use App\Models\Material;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ReportsController extends Controller
{
    public function index(): View
    {
        return view('reports.index');
    }

    public function summary(): JsonResponse
    {
        $now = Carbon::now();

        $cards = [
            'pending_users' => [
                'key' => 'pending_users',
                'label' => 'Solicitudes pendientes',
                'value' => 0,
                'hint' => 'Usuarios por aprobar',
                'variant' => 'warning',
            ],
            'active_loans' => [
                'key' => 'active_loans',
                'label' => 'Préstamos activos',
                'value' => 0,
                'hint' => 'En curso',
                'variant' => 'info',
            ],
            'overdue_loans' => [
                'key' => 'overdue_loans',
                'label' => 'Préstamos vencidos',
                'value' => 0,
                'hint' => 'Requieren acción',
                'variant' => 'danger',
            ],
            'active_reservations' => [
                'key' => 'active_reservations',
                'label' => 'Reservas activas',
                'value' => 0,
                'hint' => 'Aula B201',
                'variant' => 'success',
            ],
        ];

        try {
            $cards['pending_users']['value'] = User::pending()->count();
        } catch (\Throwable $exception) {
            Log::warning('reports.summary.pending-users', ['exception' => $exception->getMessage()]);
        }

        try {
            $activeLoans = Loan::query()
                ->whereNull('return_at')
                ->where('due_at', '>=', $now)
                ->whereNotIn('status', ['devuelto', 'perdido'])
                ->count();

            $cards['active_loans']['value'] = $activeLoans;
        } catch (\Throwable $exception) {
            Log::warning('reports.summary.active-loans', ['exception' => $exception->getMessage()]);
        }

        try {
            $overdueLoans = Loan::query()
                ->whereNull('return_at')
                ->where('due_at', '<', $now)
                ->whereNotIn('status', ['devuelto', 'perdido'])
                ->count();

            $cards['overdue_loans']['value'] = $overdueLoans;
        } catch (\Throwable $exception) {
            Log::warning('reports.summary.overdue-loans', ['exception' => $exception->getMessage()]);
        }

        try {
            $activeReservations = ClassroomLoan::query()
                ->whereIn('status', ['aprobado', 'en_uso'])
                ->where('scheduled_start_at', '<=', $now)
                ->where('scheduled_end_at', '>=', $now)
                ->count();

            $cards['active_reservations']['value'] = $activeReservations;
        } catch (\Throwable $exception) {
            Log::warning('reports.summary.active-reservations', ['exception' => $exception->getMessage()]);
        }

        return response()->json([
            'updated_at' => $now->toDateTimeString(),
            'cards' => array_values($cards),
        ]);
    }

    public function activity(): JsonResponse
    {
        $now = Carbon::now();
        $today = Carbon::today();
        $startDate = $today->copy()->subDays(13);
        $rangeStart = $startDate->copy()->startOfDay();
        $rangeEnd = $today->copy()->endOfDay();

        $loanCounts = collect();
        $reservationCounts = collect();

        try {
            $loanCounts = Loan::query()
                ->whereBetween('loan_at', [$rangeStart, $rangeEnd])
                ->selectRaw('DATE(loan_at) as day, COUNT(*) as total')
                ->groupBy('day')
                ->pluck('total', 'day');
        } catch (\Throwable $exception) {
            Log::warning('reports.activity.loans', ['exception' => $exception->getMessage()]);
        }

        try {
            $reservationCounts = ClassroomLoan::query()
                ->whereBetween('scheduled_start_at', [$rangeStart, $rangeEnd])
                ->selectRaw('DATE(scheduled_start_at) as day, COUNT(*) as total')
                ->groupBy('day')
                ->pluck('total', 'day');
        } catch (\Throwable $exception) {
            Log::warning('reports.activity.reservations', ['exception' => $exception->getMessage()]);
        }

        $days = [];

        for ($i = 0; $i < 14; $i++) {
            $day = $startDate->copy()->addDays($i);
            $key = $day->toDateString();

            $days[] = [
                'date' => $key,
                'label' => $day->locale('es')->isoFormat('D MMM'),
                'loans' => (int) ($loanCounts[$key] ?? 0),
                'reservations' => (int) ($reservationCounts[$key] ?? 0),
            ];
        }

        return response()->json([
            'updated_at' => $now->toDateTimeString(),
            'days' => $days,
        ]);
    }

    public function inventory(): JsonResponse
    {
        $now = Carbon::now();
        $thresholdDate = $now->copy()->subDays(29)->startOfDay();

        $lowStock = collect();
        $overdueLoans = collect();
        $topMaterials = collect();

        try {
            $lowStock = Material::query()
                ->whereColumn('current_stock', '<=', 'min_stock')
                ->orderBy('current_stock', 'asc')
                ->limit(6)
                ->get(['id', 'name', 'current_stock', 'min_stock']);
        } catch (\Throwable $exception) {
            Log::warning('reports.inventory.low-stock', ['exception' => $exception->getMessage()]);
        }

        try {
            $overdueLoans = Loan::with(['borrower:id,first_name,middle_name,first_surname,second_surname'])
                ->whereNull('return_at')
                ->where('due_at', '<', $now)
                ->whereNotIn('status', ['devuelto', 'perdido'])
                ->orderBy('due_at', 'asc')
                ->limit(5)
                ->get();
        } catch (\Throwable $exception) {
            Log::warning('reports.inventory.overdue-loans', ['exception' => $exception->getMessage()]);
        }

        try {
            $topMaterials = DB::table('loan_materials')
                ->join('loans', 'loan_materials.loan_id', '=', 'loans.id')
                ->join('materials', 'loan_materials.material_id', '=', 'materials.id')
                ->where('loans.loan_at', '>=', $thresholdDate)
                ->groupBy('loan_materials.material_id', 'materials.name')
                ->selectRaw('loan_materials.material_id as id, materials.name, SUM(loan_materials.loan_qty) as qty')
                ->orderByDesc('qty')
                ->limit(5)
                ->get();

            if ($topMaterials->isEmpty()) {
                $topMaterials = DB::table('loan_materials')
                    ->join('materials', 'loan_materials.material_id', '=', 'materials.id')
                    ->groupBy('loan_materials.material_id', 'materials.name')
                    ->selectRaw('loan_materials.material_id as id, materials.name, SUM(loan_materials.loan_qty) as qty')
                    ->orderByDesc('qty')
                    ->limit(5)
                    ->get();
            }
        } catch (\Throwable $exception) {
            Log::warning('reports.inventory.top-materials', ['exception' => $exception->getMessage()]);
        }

        return response()->json([
            'updated_at' => $now->toDateTimeString(),
            'low_stock' => $lowStock->map(fn (Material $material) => [
                'id' => $material->id,
                'name' => $material->name,
                'stock' => $material->current_stock,
                'min' => $material->min_stock,
            ])->values()->all(),
            'overdue' => $overdueLoans->map(fn (Loan $loan) => [
                'id' => $loan->id,
                'code' => $loan->loan_code,
                'who' => $loan->borrower?->name ?? 'Sin usuario',
                'due' => $loan->due_at?->toDateString() ?? '',
            ])->values()->all(),
            'top_materials' => $topMaterials->map(fn ($item) => [
                'id' => $item->id,
                'name' => $item->name,
                'qty' => (int) $item->qty,
            ])->values()->all(),
        ]);
    }
}
