<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('models', function (Blueprint $t) {
            $t->id();
            $t->foreignId('brand_id')->constrained('brands')->cascadeOnUpdate()->restrictOnDelete();
            $t->foreignId('category_id')->nullable()->constrained('categories')->cascadeOnUpdate()->nullOnDelete();
            $t->string('name', 120);
            $t->unique(['brand_id','name'], 'uk_models_brand_name');
            $t->index('category_id', 'idx_models_category');
        });
    }
    public function down(): void {
        Schema::dropIfExists('models');
    }
};