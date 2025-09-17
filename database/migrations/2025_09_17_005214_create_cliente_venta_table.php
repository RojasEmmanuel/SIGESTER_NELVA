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
        Schema::create('cliente_venta', function (Blueprint $table) {
            $table->id("id_cliente");
            $table->string("nombres",300);
            $table->string("apellidos",300);
            $table->integer("edad");
            $table->enum("estado_civil",["soltero","casado","divorciado","viudo"]);
            $table->string("lugar_origen",200);
            $table->string("ocupacion",200);
            $table->string("clave_elector",200);
            $table->unsignedBigInteger("id_venta");
            $table->foreign("id_venta")->references("id_venta")->on("ventas")->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cliente_venta');
    }
};
