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
        Schema::create('info_fraccionamiento', function (Blueprint $table) {
            $table->id("id_info");
            $table->string("ubicacionMaps",400)->nullable();
            $table->string("descripcion",400)->nullable();
            $table->double("precio_metro_cuadrado", 10, 2);
            $table->enum('tipo_propiedad', ['Comunal', 'Ejidal','Privada'])->default('Comunal');
            $table->timestamps();
            $table->double("precioGeneral", 10, 2)->nullable();

            $table->unsignedBigInteger("id_fraccionamiento");
            $table->foreign("id_fraccionamiento")->references("id_fraccionamiento")->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('info_fraccionamiento');
    }
};
