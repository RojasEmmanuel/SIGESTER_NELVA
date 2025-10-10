<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInePhotosToBeneficiarioClienteVentaTable extends Migration
{
    public function up()
    {
        Schema::table('beneficiario_cliente_venta', function (Blueprint $table) {
            $table->string('ine_frente')->nullable()->after('telefono');
            $table->string('ine_reverso')->nullable()->after('ine_frente');
        });
    }

    public function down()
    {
        Schema::table('beneficiario_cliente_venta', function (Blueprint $table) {
            $table->dropColumn(['ine_frente', 'ine_reverso']);
        });
    }
}