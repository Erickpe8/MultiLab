<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('incidents', function (Blueprint $t) {
            $t->id();
            $t->foreignId('asset_id')->nullable()->constrained('assets')->cascadeOnUpdate()->nullOnDelete();
            $t->foreignId('material_id')->nullable()->constrained('materials')->cascadeOnUpdate()->nullOnDelete();
            $t->enum('severity', ['baja','media','alta','crítica']);
            $t->enum('status', ['abierta','en_progreso','resuelta','cerrada'])->default('abierta');
            $t->dateTime('reported_at');
            $t->dateTime('resolved_at')->nullable();
            $t->text('description');
            $t->string('evidence_path', 255)->nullable();
            $t->timestamps();

            $t->index(['status','severity'], 'idx_incidents_status_sev');
            $t->index(['asset_id','reported_at'], 'idx_incidents_asset_time');
        });

        Schema::create('incident_resolutions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('incident_id')->constrained('incidents')->cascadeOnUpdate()->cascadeOnDelete();
            $t->text('resolution');
            $t->foreignId('resolved_by')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $t->dateTime('resolved_at');
        });
    }

    public function down(): void {
        Schema::dropIfExists('incident_resolutions');
        Schema::dropIfExists('incidents');
    }
};