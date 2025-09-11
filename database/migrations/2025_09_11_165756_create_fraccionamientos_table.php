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
        Schema::create('fraccionamientos', function (Blueprint $table) {
            $table->id('id_fraccionamiento');
            $table->string('nombre', 150);
            $table->string('ubicacion', 400);
            $table->string('path_imagen', 400)->nullable();
            $table->boolean('estatus')->default(true);
            $table->timestamps();
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fraccionamientos');
    }
};
