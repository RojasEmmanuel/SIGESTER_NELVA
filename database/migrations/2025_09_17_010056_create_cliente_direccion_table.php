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
        Schema::create('cliente_direccion', function (Blueprint $table) {
            $table->id("id_direccion");
            $table->string("nacionalidad",200);
            $table->string("estado",200);
            $table->string("municipio",200);
            $table->string("localidad",200);

            $table->unsignedBigInteger("id_cliente");
            $table->foreign("id_cliente")->references("id_cliente")->on("cliente_venta")->onDelete("cascade");
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cliente_direccion');
    }
};
