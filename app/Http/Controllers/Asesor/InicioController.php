<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\Fraccionamiento;
use App\Models\Lote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InicioController extends Controller
{
    public function index()
    {
        // Obtener estadísticas generales
        $totalFraccionamientos = Fraccionamiento::where('estatus', true)->count();
        
        // Contar lotes por estatus (asumiendo los valores posibles)
        $lotesVendidos = Lote::where('estatus', 'vendido')->count();
        $lotesApartados = Lote::where('estatus', ['apartadoPalabra','apartadoDeposito'])->count();
        $lotesDisponibles = Lote::where('estatus', 'disponible')->count();
        $totalLotes = Lote::count();

        // Obtener fraccionamientos activos con sus estadísticas de lotes
        $fraccionamientos = Fraccionamiento::where('estatus', true)
            ->withCount([
                'lotes as lotes_disponibles' => function($query) {
                    $query->where('estatus', 'disponible');
                },
                'lotes as lotes_apartados' => function($query) {
                    $query->whereIn('estatus', ['apartado', 'apartadoPalabra', 'apartadoDeposito']);
                },
                'lotes as lotes_vendidos' => function($query) {
                    $query->where('estatus', 'vendido');
                }
            ])
            ->get();

        return view('asesor.inicio', [
            'totalFraccionamientos' => $totalFraccionamientos,
            'lotesVendidos' => $lotesVendidos,
            'lotesApartados' => $lotesApartados,
            'lotesDisponibles' => $lotesDisponibles,
            'totalLotes' => $totalLotes,
            'fraccionamientos' => $fraccionamientos,

            'usuario' => Auth::user() // <--- agregamos el usuario autenticado
        ]);
    }
}