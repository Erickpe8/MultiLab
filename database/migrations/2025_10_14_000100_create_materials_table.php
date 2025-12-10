<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('materials', function (Blueprint $t) {
            $t->id();
            $t->char('uuid',36)->nullable();
            $t->foreignId('category_id')->nullable()->constrained('categories')->cascadeOnUpdate()->nullOnDelete();
            $t->string('sku', 64)->unique('uk_materials_sku');
            $t->string('name', 160);
            $t->foreignId('unit_id')->constrained('units')->cascadeOnUpdate()->restrictOnDelete();
            $t->boolean('has_expiry')->default(false);
            $t->date('expiry_date')->nullable();
            $t->integer('min_stock')->default(0);
            $t->integer('max_stock')->nullable();
            $t->integer('current_stock')->default(0);
            $t->timestamps();
            $t->softDeletes();

            $t->index('category_id', 'idx_materials_category');
        });
    }
    public function down(): void {
        Schema::dropIfExists('materials');
    }
};