<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Apartado;
use Carbon\Carbon;

class UpdateApartadosEstatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        // Obtener todos los apartados que no estén en estado 'venta'
        $apartados = Apartado::whereNotIn('estatus', ['venta'])
            ->with('lotesApartados.lote')
            ->get();

        foreach ($apartados as $apartado) {
            // Actualizar el estatus del apartado
            $apartado->updateEstatus();

            // Determinar el nuevo estatus de los lotes
            $nuevoEstatusLote = 'disponible';
            if ($apartado->estatus === 'en curso') {
                $nuevoEstatusLote = $apartado->tipoApartado === 'palabra' ? 'apartadoPalabra' : 'apartadoDeposito';
            }

            // Actualizar el estatus de los lotes asociados
            foreach ($apartado->lotesApartados as $loteApartado) {
                if ($loteApartado->lote) {
                    $loteApartado->lote->update(['estatus' => $nuevoEstatusLote]);
                }
            }
        }

        // Actualizar lotes de apartados en estado 'venta'
        $apartadosConVenta = Apartado::where('estatus', 'venta')
            ->with('lotesApartados.lote')
            ->get();

        foreach ($apartadosConVenta as $apartado) {
            foreach ($apartado->lotesApartados as $loteApartado) {
                if ($loteApartado->lote) {
                    $loteApartado->lote->update(['estatus' => 'vendido']);
                }
            }
        }
    }
}