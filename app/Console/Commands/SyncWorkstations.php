<?php

namespace App\Console\Commands;

use App\Models\ClassroomWorkstation;
use Illuminate\Console\Command;

class SyncWorkstations extends Command
{
    protected $signature = 'workstations:sync';

    protected $description = 'Sincroniza los puestos de trabajo con el inventario de computadores';

    public function handle(): int
    {
        ClassroomWorkstation::syncFromComputers();

        $this->info('Estaciones sincronizadas exitosamente.');

        return self::SUCCESS;
    }
}
