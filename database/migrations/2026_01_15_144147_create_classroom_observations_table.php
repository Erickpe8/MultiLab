<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('classroom_observations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_loan_id')->constrained('classroom_loans')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('recorded_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->enum('type', ['inicio', 'durante', 'cierre', 'incidente', 'reporte']);
            $table->text('description');
            $table->unsignedTinyInteger('severity')->default(1);
            $table->json('metadata')->nullable();
            $table->string('evidence_path')->nullable();
            $table->timestamps();

            $table->index(['classroom_loan_id', 'type'], 'idx_classroom_observations_loan_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classroom_observations');
    }
};
