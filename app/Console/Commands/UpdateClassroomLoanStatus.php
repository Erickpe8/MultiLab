<?php

namespace App\Console\Commands;

use App\Models\ClassroomLoan;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class UpdateClassroomLoanStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-classroom-loan-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the status of classroom loans to "en_uso" if their scheduled start time has passed.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $updatedCountEnUso = ClassroomLoan::query()
            ->where('scheduled_start_at', '<=', $now)
            ->whereIn('status', ['aprobado', 'pendiente'])
            ->update(['status' => 'en_uso']);

        $this->info("Updated {$updatedCountEnUso} classroom loan statuses to 'en_uso'.");

        $updatedCountFinalizado = ClassroomLoan::query()
            ->where('scheduled_end_at', '<=', $now)
            ->where('status', 'en_uso')
            ->update(['status' => 'finalizado']);

        $this->info("Updated {$updatedCountFinalizado} classroom loan statuses to 'finalizado'.");
    }
}