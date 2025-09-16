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
        Schema::create('lotes', function (Blueprint $table) {
            $table->string('id_lote', 50)->primary(); // nombrefraccionamiento, numero lote, fecha sistema
            $table->int('numeroLote');
            $table->enum('estatus',['disponible','apartadoDeposito','apartadoPalabra','vendido'])->default('disponible');

            $table->unsignedBigInteger("id_fraccionamiento");
            $table->foreign('id_fraccionamiento')->references('id_fraccionamiento')->on('fraccionamientos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lotes');
    }
};
