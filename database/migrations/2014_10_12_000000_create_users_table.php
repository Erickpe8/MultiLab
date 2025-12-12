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

            // Identificación básica (seccionada)
            $table->string('first_name', 80);          // Primer nombre
            $table->string('middle_name', 80)->nullable(); // Segundo nombre (opcional)
            $table->string('first_surname', 80);       // Primer apellido
            $table->string('second_surname', 80)->nullable(); // Segundo apellido (opcional)

            $table->string('gender', 10)->nullable();  // 'M', 'F', 'Otro' / 'No especifica'

            $table->string('email')->unique();         // Correo institucional (o principal)
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Identificación institucional (genérica)
            $table->string('document_type', 20)->nullable();   // CC, TI, Pasaporte, etc.
            $table->string('document_number', 30)->nullable();

            // Información de contacto (aplica a cualquier rol)
            $table->string('phone', 20)->nullable();           // Teléfono fijo
            $table->string('phone_extension', 10)->nullable(); // Extensión interna
            $table->string('mobile', 20)->nullable();          // Celular / WhatsApp

            // Información organizacional genérica
            $table->string('position', 100)->nullable();       // Cargo: Director de Programa, Docente, etc.
            $table->string('department', 200)->nullable();     // Dependencia / Programa / Área

            // Estado y clasificación en MultiLab
            $table->string('role_name')->nullable();           // Rol "principal" interno (para filtros rápidos)
            $table->string('area')->nullable();                // Área POA: educacion_superior, investigaciones, etc.
            $table->boolean('is_active')->default(true);

            $table->rememberToken();
            $table->timestamps();

            // Índices opcionales útiles para búsquedas
            $table->index('area');
            $table->index('role_name');
            $table->index('department');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
