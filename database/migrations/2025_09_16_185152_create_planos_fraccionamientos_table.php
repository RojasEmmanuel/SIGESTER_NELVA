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
        Schema::create('planos_fraccionamientos', function (Blueprint $table) {
            $table->id("id_plano");
            $table->string("nombre",150);
            $table->string("plano_path",150)->nullable();
                       
            $table->unsignedBigInteger("id_fraccionamiento");
            $table->foreign("id_fraccionamiento")->references("id_fraccionamiento")->on('fraccionamientos')
            ->onDelete('cascade');

            $table->unsignedBigInteger("id_usuario");
            $table->foreign("id_usuario")->references("id_usuario")->on('usuarios')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planos_fraccionamientos');
    }
};
