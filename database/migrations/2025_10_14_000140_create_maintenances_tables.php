<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('maintenances', function (Blueprint $t) {
            $t->id();
            $t->foreignId('asset_id')->constrained('assets')->cascadeOnUpdate()->restrictOnDelete();
            $t->enum('type', ['preventivo','correctivo']);
            $t->dateTime('start_at');
            $t->dateTime('end_at')->nullable();
            $t->string('provider', 160)->nullable();
            $t->decimal('cost', 12, 2)->default(0.00);
            $t->enum('status', ['pendiente','en_proceso','completado'])->default('pendiente');
            $t->text('result')->nullable();
            $t->timestamps();

            $t->index(['asset_id','status'], 'idx_maint_asset_status');
        });

        Schema::create('maintenance_tasks', function (Blueprint $t) {
            $t->id();
            $t->foreignId('maintenance_id')->constrained('maintenances')->cascadeOnUpdate()->cascadeOnDelete();
            $t->string('description', 255);
            $t->boolean('done')->default(false);
        });
    }

    public function down(): void {
        Schema::dropIfExists('maintenance_tasks');
        Schema::dropIfExists('maintenances');
    }
};