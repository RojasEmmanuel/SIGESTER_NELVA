<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Creación de la tabla galeria
        Schema::create('galeria', function (Blueprint $table) {
            $table->id('id_foto');
            $table->string('nombre')->nullable(); // Descripción de la foto
            $table->string('fotografia_path'); // Ruta de la fotografía
            $table->dateTime('fecha_subida')->default(now()); // Fecha de subida
            $table->unsignedBigInteger('id_fraccionamiento'); // Llave foránea
            $table->foreign('id_fraccionamiento')
                  ->references('id_fraccionamiento')
                  ->on('fraccionamientos')
                  ->onDelete('cascade'); // Elimina fotos si se elimina el fraccionamiento
            $table->timestamps();
        });

        // Creación de la tabla archivos_fraccionamiento
        Schema::create('archivos_fraccionamiento', function (Blueprint $table) {
            $table->id('id_archivo');
            $table->string('nombre_archivo')->nullable(); // Nombre del archivo
            $table->string('archivo_path'); // Ruta del archivo
            $table->dateTime('fecha_subida')->default(now()); // Fecha de subida
            $table->unsignedBigInteger('id_fraccionamiento'); // Llave foránea
            $table->foreign('id_fraccionamiento')
                  ->references('id_fraccionamiento')
                  ->on('fraccionamientos')
                  ->onDelete('cascade'); // Elimina archivos si se elimina el fraccionamiento
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('archivos_fraccionamiento');
        Schema::dropIfExists('galeria');
    }
};