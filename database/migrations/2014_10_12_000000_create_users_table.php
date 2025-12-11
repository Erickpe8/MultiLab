<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Nombre segmentado
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('first_surname')->nullable();
            $table->string('second_surname')->nullable();

            // Identificación
            $table->string('document_type')->nullable();
            $table->string('document_number')->nullable();
            $table->string('phone')->nullable();

            // Credenciales
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Estado del usuario
            $table->boolean('is_active')->default(true);

            // Tokens y timestamps
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};