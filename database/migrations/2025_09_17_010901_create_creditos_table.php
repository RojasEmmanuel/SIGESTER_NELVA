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
        Schema::create('creditos', function (Blueprint $table) {
            $table->id("id_credito");
            $table->date("fecha_inicio");
            $table->string("observaciones");
            $table->enum("plazo_financiamiento",["12 meses","24 meses","36 meses","48 meses","otro"]);
            $table->string("otro_plazo")->nullable();
            $table->enum("modalidad_pago",["mensual","bimestral","trimestral","semestral","anual"]);
            $table->enum("formas_pago",["efectivo","transferencia","cheque","tarjeta credito/debito","otro"]);
            $table->timestamps();
            $table->string("dia_pago",2);       
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('creditos');
    }
};
