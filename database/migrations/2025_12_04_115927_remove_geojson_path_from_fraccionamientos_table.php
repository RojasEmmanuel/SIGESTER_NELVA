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
        Schema::table('fraccionamientos', function (Blueprint $table) {
            // Verificamos que la columna exista antes de eliminarla (seguridad extra)
            if (Schema::hasColumn('fraccionamientos', 'geojson_path')) {
                $table->dropColumn('geojson_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Si por alguna razón quieres revertir (no recomendado)
        Schema::table('fraccionamientos', function (Blueprint $table) {
            $table->string('geojson_path')->nullable()->after('tiene_geojson');
        });
    }
};