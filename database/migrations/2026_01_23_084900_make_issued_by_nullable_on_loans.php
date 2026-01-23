<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropForeign(['issued_by']);
        });

        DB::statement("ALTER TABLE `loans` MODIFY `issued_by` BIGINT UNSIGNED NULL");

        Schema::table('loans', function (Blueprint $table) {
            $table->foreign('issued_by')
                ->references('id')->on('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropForeign(['issued_by']);
        });

        DB::statement("ALTER TABLE `loans` MODIFY `issued_by` BIGINT UNSIGNED NOT NULL");

        Schema::table('loans', function (Blueprint $table) {
            $table->foreign('issued_by')
                ->references('id')->on('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }
};
