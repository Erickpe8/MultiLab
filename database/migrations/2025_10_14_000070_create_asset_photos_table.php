<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('asset_photos', function (Blueprint $t) {
            $t->id();
            $t->foreignId('asset_id')->constrained('assets')->cascadeOnUpdate()->cascadeOnDelete();
            $t->string('path', 255);
            $t->boolean('is_primary')->default(false);
            $t->timestamps();

            $t->index('asset_id', 'idx_asset_photos_asset');
        });
    }
    public function down(): void {
        Schema::dropIfExists('asset_photos');
    }
};