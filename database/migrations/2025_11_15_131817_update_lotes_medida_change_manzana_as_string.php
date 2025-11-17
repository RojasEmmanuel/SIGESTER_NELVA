<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Agregar columna temporal como VARCHAR
        DB::statement('ALTER TABLE lote_medidas ADD COLUMN manzana_temp VARCHAR(50) NULL AFTER manzana');

        // 2. Copiar datos: INT → STRING
        DB::statement('UPDATE lote_medidas SET manzana_temp = CAST(manzana AS CHAR)');

        // 3. Eliminar columna original
        DB::statement('ALTER TABLE lote_medidas DROP COLUMN manzana');

        // 4. Renombrar columna temporal a manzana
        DB::statement('ALTER TABLE lote_medidas CHANGE COLUMN manzana_temp manzana VARCHAR(50) NULL');

        // 5. Hacerla NOT NULL (si así era originalmente)
        DB::statement('ALTER TABLE lote_medidas MODIFY COLUMN manzana VARCHAR(50) NOT NULL');
    }

    public function down()
    {
        // Revertir: de VARCHAR a INT (solo si el valor es numérico)
        DB::statement('ALTER TABLE lote_medidas ADD COLUMN manzana_temp INT NULL AFTER manzana');

        DB::statement("
            UPDATE lote_medidas 
            SET manzana_temp = CAST(manzana AS SIGNED) 
            WHERE manzana REGEXP '^[0-9]+$' AND manzana IS NOT NULL
        ");

        DB::statement('ALTER TABLE lote_medidas DROP COLUMN manzana');

        DB::statement('ALTER TABLE lote_medidas CHANGE COLUMN manzana_temp manzana INT NOT NULL');
    }
};