<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Apartado;
use Carbon\Carbon;

class AddEstatusToApartadosTable extends Migration
{
    public function up()
    {
        // Agregar el campo estatus como ENUM
        Schema::table('apartados', function (Blueprint $table) {
            $table->enum('estatus', ['en curso', 'vencido', 'venta'])->default('en curso')->after('fechaVencimiento');
        });

        // Actualizar el estatus de los apartados existentes
        $apartados = Apartado::all();
        foreach ($apartados as $apartado) {
            $estatus = 'en curso';

            // Si hay una venta asociada, el estatus es 'venta'
            if ($apartado->venta) {
                $estatus = 'venta';
            }
            // Si la fecha de vencimiento es anterior a hoy, el estatus es 'vencido'
            elseif ($apartado->fechaVencimiento->isPast()) {
                $estatus = 'vencido';
            }

            $apartado->update(['estatus' => $estatus]);
        }
    }

    public function down()
    {
        Schema::table('apartados', function (Blueprint $table) {
            $table->dropColumn('estatus');
        });
    }
}