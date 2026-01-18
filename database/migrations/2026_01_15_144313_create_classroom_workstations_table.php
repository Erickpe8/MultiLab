<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('classroom_workstations', function (Blueprint $table) {
            $table->id();
            $table->string('classroom_code', 20)->default('B201');
            $table->string('code', 20)->unique();
            $table->string('label', 40);
            $table->enum('status', ['disponible', 'mantenimiento', 'fuera_servicio'])->default('disponible');
            $table->unsignedTinyInteger('seat_number')->nullable();
            $table->json('specs')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['classroom_code', 'status'], 'idx_workstations_room_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classroom_workstations');
    }
};
