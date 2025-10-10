<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInePhotosToClienteVentaTable extends Migration
{
    public function up()
    {
        Schema::table('cliente_venta', function (Blueprint $table) {
            $table->string('ine_frente')->after('clave_elector');
            $table->string('ine_reverso')->after('ine_frente');
        });
    }

    public function down()
    {
        Schema::table('cliente_venta', function (Blueprint $table) {
            $table->dropColumn(['ine_frente', 'ine_reverso']);
        });
    }
}