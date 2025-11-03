<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    
    public function up(): void
    {
        // 1. Crear la tabla pivote
        Schema::create('fraccionamiento_promocion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_promocion');
            $table->unsignedBigInteger('id_fraccionamiento');
            $table->timestamps();

            $table->foreign('id_promocion')
                  ->references('id_promocion')
                  ->on('promociones')
                  ->onDelete('cascade');

            $table->foreign('id_fraccionamiento')
                  ->references('id_fraccionamiento')
                  ->on('fraccionamientos')
                  ->onDelete('cascade');

            $table->unique(['id_promocion', 'id_fraccionamiento']);
        });

        // 2. Migrar datos existentes (si hubiera) de promociones a la tabla pivote
        DB::statement("
            INSERT INTO fraccionamiento_promocion (id_promocion, id_fraccionamiento, created_at, updated_at)
            SELECT id_promocion, id_fraccionamiento, created_at, updated_at
            FROM promociones
            WHERE id_fraccionamiento IS NOT NULL
        ");

        // 3. Eliminar la columna y la foreign key de promociones
        Schema::table('promociones', function (Blueprint $table) {
            $table->dropForeign(['id_fraccionamiento']);
            $table->dropColumn('id_fraccionamiento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
