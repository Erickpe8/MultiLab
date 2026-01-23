<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE `loans` MODIFY COLUMN `status` VARCHAR(50) DEFAULT 'pendiente'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `loans` MODIFY COLUMN `status` ENUM('abierto','devuelto','vencido','con_multa','perdido','rechazado') DEFAULT 'abierto'");
    }
};
