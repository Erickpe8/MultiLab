<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('audit_log', function (Blueprint $t) {
            $t->id();
            $t->string('table_name', 64);
            $t->string('row_pk', 64);
            $t->enum('action', ['insert','update','delete']);
            $t->json('before_json')->nullable();
            $t->json('after_json')->nullable();
            $t->foreignId('user_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $t->string('ip', 45)->nullable();
            $t->string('user_agent', 255)->nullable();
            $t->timestamp('created_at')->useCurrent();

            $t->index(['table_name','row_pk'], 'idx_audit_table_row');
            $t->index('created_at', 'idx_audit_created');
        });
    }
    public function down(): void {
        Schema::dropIfExists('audit_log');
    }
};