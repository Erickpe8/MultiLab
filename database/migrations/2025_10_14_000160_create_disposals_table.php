<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('disposals', function (Blueprint $t) {
            $t->id();
            $t->enum('item_kind', ['asset','material']);
            $t->foreignId('asset_id')->nullable()->constrained('assets')->cascadeOnUpdate()->restrictOnDelete();
            $t->foreignId('material_id')->nullable()->constrained('materials')->cascadeOnUpdate()->restrictOnDelete();
            $t->foreignId('approved_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $t->dateTime('disposed_at');
            $t->string('type', 16);
            $t->text('reason')->nullable();
            $t->string('document_path', 255)->nullable();
            $t->string('status', 16)->default('APPROVED');
            $t->unsignedInteger('qty')->nullable();
            $t->timestamps();

            $t->index('disposed_at', 'idx_disposal_date');
            $t->index('type', 'idx_disposal_type');
        });
    }
    public function down(): void {
        Schema::dropIfExists('disposals');
    }
};