<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('inventory_movements', function (Blueprint $t) {
            $t->id();
            $t->enum('item_kind', ['asset','material']);
            $t->foreignId('asset_id')->nullable()->constrained('assets')->cascadeOnUpdate()->nullOnDelete();
            $t->foreignId('material_id')->nullable()->constrained('materials')->cascadeOnUpdate()->nullOnDelete();
            $t->enum('type', ['ingreso','salida','ajuste','reserva','prestamo','devolucion','mantenimiento','incidente','baja','traslado']);
            $t->dateTime('moved_at');
            $t->integer('quantity')->default(0);
            $t->foreignId('unit_id')->nullable()->constrained('units')->cascadeOnUpdate()->nullOnDelete();
            $t->string('reason', 255)->nullable();
            $t->string('ref_type', 40)->nullable();
            $t->unsignedBigInteger('ref_id')->nullable();
            $t->timestamps();

            $t->index(['item_kind','moved_at'], 'idx_inv_mov_item_time');
            $t->index('asset_id', 'idx_inv_mov_asset');
            $t->index('material_id', 'idx_inv_mov_material');
            $t->index(['type','moved_at'], 'idx_inv_mov_type_time');
            $t->index(['ref_type','ref_id'], 'idx_inv_mov_ref');
        });
    }
    public function down(): void {
        Schema::dropIfExists('inventory_movements');
    }
};