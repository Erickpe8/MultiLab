<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reservations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $t->foreignId('location_id')->nullable()->constrained('locations')->cascadeOnUpdate()->nullOnDelete();
            $t->dateTime('start_at');
            $t->dateTime('end_at');
            $t->enum('status', ['pendiente','confirmada','cancelada','usada'])->default('pendiente');
            $t->string('notes', 255)->nullable();
            $t->timestamps();

            $t->index(['user_id','start_at'], 'idx_resv_user_time');
            $t->index(['location_id','start_at'], 'idx_resv_location_start');
        });

        Schema::create('reservation_assets', function (Blueprint $t) {
            $t->id();
            $t->foreignId('reservation_id')->constrained('reservations')->cascadeOnUpdate()->cascadeOnDelete();
            $t->foreignId('asset_id')->constrained('assets')->cascadeOnUpdate()->restrictOnDelete();
            $t->unique(['reservation_id','asset_id'], 'uk_resv_asset');
            $t->index('asset_id', 'idx_resv_assets_asset');
        });

        Schema::create('reservation_materials', function (Blueprint $t) {
            $t->id();
            $t->foreignId('reservation_id')->constrained('reservations')->cascadeOnUpdate()->cascadeOnDelete();
            $t->foreignId('material_id')->constrained('materials')->cascadeOnUpdate()->restrictOnDelete();
            $t->unsignedInteger('requested_qty');
            $t->unsignedInteger('approved_qty')->default(0);
            $t->unique(['reservation_id','material_id'], 'uk_resv_material');
            $t->index('material_id', 'idx_resv_materials_material');
        });
    }

    public function down(): void {
        Schema::dropIfExists('reservation_materials');
        Schema::dropIfExists('reservation_assets');
        Schema::dropIfExists('reservations');
    }
};