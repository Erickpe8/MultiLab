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
            $table->string('classroom_code', 20)->default('B202');
            $table->string('code', 20)->unique();
            $table->string('label', 40);
            $table->enum('status', ['disponible', 'mantenimiento', 'fuera_servicio'])->default('disponible');
            $table->unsignedTinyInteger('seat_number')->nullable();
            $table->json('specs')->nullable();
            $table->string('marca')->nullable();
            $table->string('main_card')->nullable();
            $table->string('processor')->nullable();
            $table->string('ram')->nullable();
            $table->string('hard_drive')->nullable();
            $table->string('network_card')->nullable();
            $table->string('graphics_card')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['classroom_code', 'status'], 'idx_workstations_room_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classroom_workstations');
        Schema::table('classroom_workstations', function (Blueprint $table) {
            $table->dropColumn([
                'marca',
                'main_card',
                'processor',
                'ram',
                'hard_drive',
                'network_card',
                'graphics_card',
            ]);
        });
    }
};
