<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('asset_movements', function (Blueprint $t) {
            $t->id();
            $t->foreignId('asset_id')->constrained('assets')->cascadeOnUpdate()->restrictOnDelete();
            $t->foreignId('from_location_id')->nullable()->constrained('locations')->cascadeOnUpdate()->nullOnDelete();
            $t->foreignId('to_location_id')->constrained('locations')->cascadeOnUpdate()->restrictOnDelete();
            $t->foreignId('by_user_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $t->timestamp('moved_at');
            $t->text('notes')->nullable();
            $t->timestamps();

            $t->index(['asset_id','moved_at'], 'idx_asset_moves_asset_time');
            $t->index('to_location_id', 'idx_asset_moves_to_loc');
        });
    }
    public function down(): void {
        Schema::dropIfExists('asset_movements');
    }
};