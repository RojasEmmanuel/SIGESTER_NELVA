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
        Schema::create('beneficiario_cliente_venta', function (Blueprint $table) {
            $table->id("id_beneficiario");
            $table->string("nombres",300);
            $table->string("apellidos",300);
            $table->string("telefono",15);
            $table->unsignedBigInteger("id_venta");
            $table->foreign("id_venta")->references("id_venta")->on("ventas")->onDelete("cascade");
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
        Schema::dropIfExists('beneficiario_cliente_venta');
    }
};
