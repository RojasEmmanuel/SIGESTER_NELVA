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
        Schema::create('apartados_deposito', function (Blueprint $table) {
            $table->id("id_deposito");
            $table->double("cantidad");
            $table->enum("ticket_estatus",["solicitud","aceptado","rechazado"])->default("solicitud")->nullable();
            $table->string("path_ticket",400)->nullable();
            $table->string("observaciones",400)->nullable();

            $table->unsignedBigInteger("id_apartado");
            $table->foreign('id_apartado')->references('id_apartado')->on('apartados')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartados_deposito');
    }
};
