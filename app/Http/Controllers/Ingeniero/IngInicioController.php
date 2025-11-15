<?php

namespace App\Http\Controllers\Ingeniero;

use App\Http\Controllers\Controller;
use App\Models\Fraccionamiento;
use App\Models\Lote;
use App\Models\LoteMedida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IngInicioController extends Controller
{
    public function index()
    {
        // Obtener estadísticas generales
        $totalFraccionamientos = Fraccionamiento::where('estatus', true)->count();
        $totalLotes = Lote::count();
        $lotesDisponibles = Lote::where('estatus', 'disponible')->count();
        
        // Contar número único de manzanas considerando el fraccionamiento
        $totalManzanas = LoteMedida::join('lotes', 'lote_medidas.id_lote', '=', 'lotes.id_lote')
            ->select(DB::raw('COUNT(DISTINCT CONCAT(lotes.id_fraccionamiento, "-", lote_medidas.manzana)) as total'))
            ->value('total');

        // Obtener fraccionamientos activos con estadísticas de lotes
        $fraccionamientos = Fraccionamiento::where('estatus', true)
            ->withCount([
                'lotes as total_lotes',
                'lotes as lotes_disponibles' => function($query) {
                    $query->where('estatus', 'disponible');
                }
            ])
            ->get();

        // Obtener conteo de manzanas por fraccionamiento en una sola consulta
        $manzanasPorFraccionamiento = LoteMedida::join('lotes', 'lote_medidas.id_lote', '=', 'lotes.id_lote')
            ->whereIn('lotes.id_fraccionamiento', $fraccionamientos->pluck('id_fraccionamiento'))
            ->select('lotes.id_fraccionamiento', DB::raw('COUNT(DISTINCT lote_medidas.manzana) as total_manzanas'))
            ->groupBy('lotes.id_fraccionamiento')
            ->pluck('total_manzanas', 'lotes.id_fraccionamiento');

        // Asignar el conteo de manzanas a cada fraccionamiento
        $fraccionamientos->each(function ($fraccionamiento) use ($manzanasPorFraccionamiento) {
            $fraccionamiento->total_manzanas = $manzanasPorFraccionamiento[$fraccionamiento->id_fraccionamiento] ?? 0;
        });

        return view('ingeniero.inicio', [
            'totalFraccionamientos' => $totalFraccionamientos,
            'totalLotes' => $totalLotes,
            'totalManzanas' => $totalManzanas,
            'lotesDisponibles' => $lotesDisponibles,
            'fraccionamientos' => $fraccionamientos,
        ]);
    }
}