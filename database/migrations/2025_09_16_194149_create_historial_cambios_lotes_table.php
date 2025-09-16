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
        Schema::create('historial_cambios_lotes', function (Blueprint $table) {
            $table->id("id_cambio");
            $table->string('estatus_anterior');
            $table->string('estatus_actual');
            $table->string('observaciones');
        
            $table->string('id_lote', 50);
            $table->foreign('id_lote')->references('id_lote')->on('lotes')->onDelete('cascade');
            $table->unsignedBigInteger('id_usuario');
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_cambios_lotes');
    }
};
