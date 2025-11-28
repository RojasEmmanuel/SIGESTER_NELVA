<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Apartado;
use Carbon\Carbon;

class VencerApartadosCommand extends Command
{
    protected $signature = 'apartados:vencer {--test : Solo simula, no hace cambios}';
    protected $description = 'Vence apartados expirados y libera lotes SIN borrar el historial en lotes_apartados';

    public function handle()
    {
        $ahora = Carbon::now();
        $esTest = $this->option('test');

        $this->info("Buscando apartados vencidos al: " . $ahora->format('Y-m-d H:i:s'));

        $apartados = Apartado::where('estatus', '!=', 'vencido')
            ->where('estatus', '!=', 'venta')
            ->where('fechaVencimiento', '<=', $ahora)
            ->doesntHave('venta')
            ->with('lotesApartados.lote') // carga lotes para actualizar
            ->get();

        if ($apartados->isEmpty()) {
            $this->info("No hay apartados vencidos en este momento.");
            return 0;
        }

        if ($esTest) {
            $this->warn("MODO PRUEBA: Se procesarían " . $apartados->count() . " apartado(s):");
            foreach ($apartados as $a) {
                $lotes = $a->lotesApartados->pluck('id_lote')->join(', ');
                $this->line("→ ID {$a->id_apartado} | {$a->cliente_nombre} {$a->cliente_apellidos} | Venció: {$a->fechaVencimiento} | Lotes: {$lotes}");
            }
            return 0;
        }

        $totalApartados = 0;
        $totalLotesLiberados = 0;

        DB::transaction(function () use ($apartados, &$totalApartados, &$totalLotesLiberados) {
            foreach ($apartados as $apartado) {
                // 1. Marcar el apartado como vencido
                $apartado->estatus = 'vencido';
                $apartado->save();
                $totalApartados++;

                // 2. Liberar todos los lotes asociados (pero NO borrar el registro en lotes_apartados)
                $lotesIds = $apartado->lotesApartados->pluck('id_lote')->toArray();

                if (!empty($lotesIds)) {
                    // Liberar lotes que estaban en apartado (ya sea por palabra o depósito)
                    $actualizados = \App\Models\Lote::whereIn('id_lote', $lotesIds)
                        ->whereIn('estatus', ['apartadoPalabra', 'apartadoDeposito']) // ← Aquí está la clave
                        ->update(['estatus' => 'disponible']);

                    $totalLotesLiberados += $actualizados; // ← Mejor: contar solo los que realmente se actualizaron
                }

                // Registro en log para auditoría
                Log::info("Apartado vencido automáticamente - historial conservado", [
                    'id_apartado' => $apartado->id_apartado,
                    'cliente' => trim($apartado->cliente_nombre . ' ' . $apartado->cliente_apellidos),
                    'fecha_vencimiento' => $apartado->fechaVencimiento->toDateTimeString(),
                    'lotes_liberados' => $lotesIds,
                ]);
            }
        });

        $this->info("Proceso completado con éxito:");
        $this->info("   Apartados vencidos: {$totalApartados}");
        $this->info("   Lotes liberados y disponibles nuevamente: {$totalLotesLiberados}");
        $this->info("   Historial en lotes_apartados: 100% conservado");

        return 0;
    }
}