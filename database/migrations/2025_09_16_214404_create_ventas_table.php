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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id("id_venta");
            $table->date("fechaSolicitud");
            $table->enum("estatus",["pagos","retrazo","liquidado"])->default("pagos");
            $table->string("ticket_path");
            $table->enum("ticket_estatus",["solicitud","rechazado","aceptado"])->default("solicitud");
            $table->double("enganche");
            $table->double("total");
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
        Schema::dropIfExists('ventas');
    }
};
