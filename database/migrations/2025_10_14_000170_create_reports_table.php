<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reports', function (Blueprint $t) {
            $t->id();
            $t->string('ref_type', 40);
            $t->unsignedBigInteger('ref_id');
            $t->string('folio', 40)->unique('uk_reports_folio');
            $t->string('title', 160);
            $t->string('pdf_path', 255);
            $t->foreignId('created_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $t->timestamp('created_at')->useCurrent();

            $t->index(['ref_type','ref_id'], 'idx_reports_ref');
        });
    }
    public function down(): void {
        Schema::dropIfExists('reports');
    }
};