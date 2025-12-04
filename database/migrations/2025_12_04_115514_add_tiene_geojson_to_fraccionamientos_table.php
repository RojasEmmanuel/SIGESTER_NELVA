<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fraccionamientos', function (Blueprint $table) {
            // Campo booleano que indica si el fraccionamiento tiene GeoJSON activo
            $table->boolean('tiene_geojson')
                  ->default(false)
                  ->after('path_imagen')
                  ->comment('Indica si el fraccionamiento cuenta con plano interactivo (GeoJSON)');

            // Opcional: si quieres guardar la ruta o nombre del archivo GeoJSON
            $table->string('geojson_path')->nullable()->after('tiene_geojson');
        });
    }

    public function down(): void
    {
        Schema::table('fraccionamientos', function (Blueprint $table) {
            $table->dropColumn(['tiene_geojson', 'geojson_path']);
        });
    }
};