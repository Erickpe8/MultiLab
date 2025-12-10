<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('locations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('parent_id')
                ->nullable()
                ->constrained('locations')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $t->enum('type', ['laboratorio','habitacion','estanteria','caja','otro']);
            $t->string('code', 64);
            $t->string('name', 128);
            $t->string('path_code', 512);
            $t->unsignedTinyInteger('depth')->default(0);
            $t->integer('capacity')->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();

            $t->unique(['parent_id','code'], 'uk_locations_parent_code');
            $t->index('parent_id', 'idx_locations_parent');
            $t->index('type', 'idx_locations_type');
            $t->index('depth', 'idx_locations_depth');
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('CREATE INDEX idx_locations_path_code ON locations (path_code(191))');
        }

        // ---- TRIGGERS ----
        DB::unprepared(<<<'SQL'
CREATE TRIGGER trg_locations_bi
BEFORE INSERT ON locations
FOR EACH ROW
BEGIN
    DECLARE p_path  VARCHAR(512);
    DECLARE p_depth TINYINT UNSIGNED;

    IF NEW.parent_id IS NOT NULL AND NEW.parent_id = NEW.id THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'parent_id no puede referenciar a la misma fila';
    END IF;

    IF NEW.parent_id IS NULL THEN
        SET NEW.depth = 0;
        SET NEW.path_code = NEW.code;
    ELSE
        SELECT path_code, depth INTO p_path, p_depth
        FROM locations WHERE id = NEW.parent_id;

        IF p_path IS NULL THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'parent_id no existe';
        END IF;

        SET NEW.depth = p_depth + 1;
        SET NEW.path_code = CONCAT(p_path, '/', NEW.code);
    END IF;
END
SQL);

        DB::unprepared(<<<'SQL'
CREATE TRIGGER trg_locations_bu
BEFORE UPDATE ON locations
FOR EACH ROW
BEGIN
    DECLARE p_path  VARCHAR(512);
    DECLARE p_depth TINYINT UNSIGNED;

    IF NEW.parent_id IS NOT NULL AND NEW.parent_id = NEW.id THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'parent_id no puede referenciar a la misma fila';
    END IF;

    IF NEW.parent_id IS NULL THEN
        SET NEW.depth = 0;
        SET NEW.path_code = NEW.code;
    ELSE
        SELECT path_code, depth INTO p_path, p_depth
        FROM locations WHERE id = NEW.parent_id;

        IF p_path IS NULL THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'parent_id no existe';
        END IF;

        SET NEW.depth = p_depth + 1;
        SET NEW.path_code = CONCAT(p_path, '/', NEW.code);
    END IF;
END
SQL);

        DB::unprepared(<<<'SQL'
CREATE TRIGGER trg_locations_au
AFTER UPDATE ON locations
FOR EACH ROW
BEGIN
    IF NEW.path_code <> OLD.path_code THEN
        UPDATE locations
        SET path_code = CONCAT(NEW.path_code, SUBSTRING(path_code, LENGTH(OLD.path_code) + 1)),
            depth     = depth - OLD.depth + NEW.depth
        WHERE path_code LIKE CONCAT(OLD.path_code, '/%');
    END IF;
END
SQL);
    }

    public function down(): void {
        // Eliminar triggers
        DB::unprepared('DROP TRIGGER IF EXISTS trg_locations_au');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_locations_bu');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_locations_bi');

        // Eliminar índice por prefijo (MySQL)
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            // Nota: IF EXISTS está disponible en MySQL 8.0.13+. Si tu versión es anterior, omite IF EXISTS.
            DB::statement('DROP INDEX IF EXISTS idx_locations_path_code ON locations');
        }

        Schema::dropIfExists('locations');
    }
};