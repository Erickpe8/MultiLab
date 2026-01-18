<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('classroom_loan_workstations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_loan_id')->constrained('classroom_loans')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('classroom_workstation_id')->constrained('classroom_workstations')->cascadeOnUpdate()->restrictOnDelete();
            $table->enum('status', ['reservado', 'en_uso', 'liberado', 'inactivo'])->default('reservado');
            $table->json('metrics')->nullable();
            $table->string('assigned_user')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['classroom_loan_id', 'classroom_workstation_id'], 'uk_loan_workstation');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classroom_loan_workstations');
    }
};
