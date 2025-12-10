<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('assets', function (Blueprint $t) {
            $t->id();
            $t->char('uuid',36)->nullable();
            $t->foreignId('model_id')->nullable()->constrained('models')->cascadeOnUpdate()->nullOnDelete();
            $t->foreignId('location_id')->nullable()->constrained('locations')->cascadeOnUpdate()->nullOnDelete();
            $t->string('asset_tag', 64)->unique('uk_assets_asset_tag');
            $t->string('serial', 128)->nullable()->unique('uk_assets_serial');
            $t->enum('status', ['activo','reservado','en_prestamo','en_mantenimiento','dado_de_baja'])->default('activo');
            $t->date('purchase_date')->nullable();
            $t->date('warranty_until')->nullable();
            $t->string('qr_text', 255)->nullable();
            $t->text('notes')->nullable();
            $t->timestamps();
            $t->softDeletes();

            $t->index(['location_id','status'], 'idx_assets_location_status');
            $t->index('model_id', 'idx_assets_model');
        });
    }
    public function down(): void {
        Schema::dropIfExists('assets');
    }
};