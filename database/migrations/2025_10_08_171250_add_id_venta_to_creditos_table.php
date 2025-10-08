<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdVentaToCreditosTable extends Migration
{
    public function up()
    {
        Schema::table('creditos', function (Blueprint $table) {
            $table->unsignedBigInteger('id_venta')->nullable()->after('id_credito');
            $table->foreign('id_venta')->references('id_venta')->on('ventas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('creditos', function (Blueprint $table) {
            $table->dropForeign(['id_venta']);
            $table->dropColumn('id_venta');
        });
    }
}