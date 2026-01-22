<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->boolean('is_unique_item')->default(false)->after('notes');
            
            // Agregar índice para búsquedas eficientes
            $table->index('is_unique_item', 'idx_assets_is_unique_item');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropIndex('idx_assets_is_unique_item');
            $table->dropColumn('is_unique_item');
        });
    }
};