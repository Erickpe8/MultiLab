<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('units', function (Blueprint $t) {
            $t->id();
            $t->string('code', 16)->unique('uk_units_code');
            $t->string('name', 64);
        });
    }
    public function down(): void {
        Schema::dropIfExists('units');
    }
};