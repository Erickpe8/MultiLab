<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('computers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('serial_number')->unique();
            $table->enum('status', ['disponible', 'no_disponible'])->default('disponible');
            $table->text('notes')->nullable();
            $table->string('marca')->nullable();
            $table->string('main_card')->nullable();
            $table->string('processor')->nullable();
            $table->string('ram')->nullable();
            $table->string('hard_drive')->nullable();
            $table->string('network_card')->nullable();
            $table->string('graphics_card')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('computers');
        Schema::table('computers', function (Blueprint $table) {
            $table->dropColumn([
                'marca',
                'main_card',
                'processor',
                'ram',
                'hard_drive',
                'network_card',
                'graphics_card',
            ]);
        });
    }
};
