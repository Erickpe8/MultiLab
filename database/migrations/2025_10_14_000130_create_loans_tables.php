<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('loans', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $t->foreignId('issued_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $t->string('loan_code', 32)->unique('uk_loan_code');
            $t->dateTime('loan_at');
            $t->dateTime('due_at');
            $t->dateTime('return_at')->nullable();
            $t->enum('status', ['abierto','devuelto','vencido','con_multa','perdido'])->default('abierto');
            $t->text('notes')->nullable();
            $t->timestamps();

            $t->index(['user_id','status','loan_at'], 'idx_loans_user_status_time');
            $t->index(['status','due_at'], 'idx_loans_status_due');
        });

        Schema::create('loan_assets', function (Blueprint $t) {
            $t->id();
            $t->foreignId('loan_id')->constrained('loans')->cascadeOnUpdate()->cascadeOnDelete();
            $t->foreignId('asset_id')->constrained('assets')->cascadeOnUpdate()->restrictOnDelete();
            $t->string('condition_out', 80)->nullable();
            $t->string('condition_in', 80)->nullable();
            $t->string('evidence_in_path', 255)->nullable();
            $t->unique(['loan_id','asset_id'], 'uk_loan_asset');
            $t->index('asset_id', 'idx_loan_assets_asset');
        });

        Schema::create('loan_materials', function (Blueprint $t) {
            $t->id();
            $t->foreignId('loan_id')->constrained('loans')->cascadeOnUpdate()->cascadeOnDelete();
            $t->foreignId('material_id')->constrained('materials')->cascadeOnUpdate()->restrictOnDelete();
            $t->unsignedInteger('loan_qty');
            $t->unsignedInteger('returned_qty')->default(0);
            $t->unique(['loan_id','material_id'], 'uk_loan_material');
            $t->index('material_id', 'idx_loan_materials_material');
        });

        Schema::create('loan_fines', function (Blueprint $t) {
            $t->id();
            $t->foreignId('loan_id')->constrained('loans')->cascadeOnUpdate()->cascadeOnDelete();
            $t->decimal('amount', 12, 2);
            $t->string('reason', 120);
            $t->dateTime('paid_at')->nullable();
            $t->timestamps();
            $t->index('loan_id', 'idx_fines_loan');
        });
    }

    public function down(): void {
        Schema::dropIfExists('loan_fines');
        Schema::dropIfExists('loan_materials');
        Schema::dropIfExists('loan_assets');
        Schema::dropIfExists('loans');
    }
};