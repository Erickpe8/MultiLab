<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('material_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')
                ->constrained('materials')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->unsignedInteger('quantity');
            $table->dateTime('needed_at');
            $table->dateTime('planned_return_at');
            $table->enum('status', ['pendiente', 'aprobada', 'rechazada'])
                ->default('pendiente');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['material_id', 'status'], 'idx_material_req_material_status');
            $table->index(['user_id', 'status'], 'idx_material_req_user_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_requests');
    }
};
