<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('categories', function (Blueprint $t) {
            $t->id();
            $t->foreignId('parent_id')->nullable()->constrained('categories')->cascadeOnUpdate()->restrictOnDelete();
            $t->char('uuid', 36)->nullable();
            $t->string('code', 50)->unique('uk_categories_code');
            $t->string('name', 120);
            $t->text('description')->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
            $t->softDeletes();
            $t->index('parent_id', 'idx_categories_parent');
        });
    }
    public function down(): void {
        Schema::dropIfExists('categories');
    }
};