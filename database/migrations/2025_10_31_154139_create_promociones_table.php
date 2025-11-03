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
        Schema::create('promociones', function (Blueprint $table) {
            $table->id("id_promocion");
            $table->string("titulo")->nullable(); // Título de la promoción
            $table->text("descripcion")->nullable(); // Descripción de la promoción
            $table->string("imagen_path"); // Ruta de la imagen de la promoción
            $table->dateTime("fecha_inicio")->default(now()); // Fecha de inicio de la
            $table->dateTime("fecha_fin")->nullable(); // Fecha de fin de la promoción
            $table->unsignedBigInteger("id_fraccionamiento"); // Llave foránea
            $table->foreign("id_fraccionamiento")
                    ->references("id_fraccionamiento")
                    ->on("fraccionamientos")
                    ->onDelete("cascade"); // Elimina promociones si se elimina el fraccionamiento

            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promociones');
    }
};
