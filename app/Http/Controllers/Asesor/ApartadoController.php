<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\Apartado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApartadoController extends Controller
{

    public function index()
    {
        $usuarioId = Auth::user()->id_usuario;

        $apartados = Apartado::with([
            'usuario', 
            'lotesApartados.lote.fraccionamiento'
        ])
        ->where('id_usuario', $usuarioId)
        ->orderBy('fechaVencimiento', 'desc')
        ->get();

        return view('asesor.apartados', compact('apartados'));
        
    }

    public function show($id)
    {
        $apartado = Apartado::with([
            'usuario',
            'lotesApartados.lote.fraccionamiento'
        ])->findOrFail($id);

        return view('asesor.showApartado', compact('apartado'));
    }

    public function estadisticas()
    {
        $total = Apartado::count();
        $vigentes = Apartado::whereDate('fechaVencimiento', '>=', now())->count();
        $vencidos = Apartado::whereDate('fechaVencimiento', '<', now())->count();

        return response()->json([
            'total' => $total,
            'vigentes' => $vigentes,
            'vencidos' => $vencidos,
        ]);
    }
}
