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
        Schema::create('apartados', function (Blueprint $table) {
            $table->id("id_apartado");
            $table->enum('tipoApartado',['palabra','deposito'])->default('palabra');
            $table->string("cliente_nombre",300);
            $table->string("cliente_apellidos",300);
            $table->date("fechaApartado");
            $table->date("fechaVencimiento");
            
            $table->unsignedBigInteger("id_usuario");
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartados');
    }
};
