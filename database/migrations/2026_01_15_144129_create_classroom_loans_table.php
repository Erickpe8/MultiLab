<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('classroom_loans', function (Blueprint $table) {
            $table->id();
            $table->string('classroom_code', 20)->default('B201');
            $table->foreignId('requested_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->string('subject', 120);
            $table->string('purpose', 180)->nullable();
            $table->enum('status', ['pendiente', 'aprobado', 'rechazado', 'en_uso', 'finalizado', 'cancelado'])->default('pendiente');
            $table->dateTime('scheduled_start_at');
            $table->dateTime('scheduled_end_at');
            $table->dateTime('actual_start_at')->nullable();
            $table->dateTime('actual_end_at')->nullable();
            $table->unsignedTinyInteger('pc_required')->default(0);
            $table->unsignedTinyInteger('pc_in_use')->default(0);
            $table->unsignedTinyInteger('pc_unavailable')->default(0);
            $table->json('workstations_snapshot')->nullable();
            $table->unsignedInteger('incidents_count')->default(0);
            $table->text('access_instructions')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['classroom_code', 'status'], 'idx_classroom_loans_room_status');
            $table->index(['scheduled_start_at', 'scheduled_end_at'], 'idx_classroom_loans_schedule');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classroom_loans');
    }
};
