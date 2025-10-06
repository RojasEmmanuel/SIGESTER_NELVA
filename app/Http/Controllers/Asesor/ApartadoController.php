<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\Apartado;
use App\Models\Lote;
use App\Models\LoteApartado;
use App\Models\HistorialCambiosLote;
use App\Models\ApartadoDeposito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


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


     // ==========================
    // MÉTODO PARA REGISTRAR APARTADO
    // ==========================
public function store(Request $request)
{
    // 1️⃣ Validación de datos
    $request->validate([
        'tipoApartado' => 'required|in:apartadoPalabra,apartadoDeposito',
        'id_fraccionamiento' => 'required|integer',
        'cliente_nombre' => 'required|string|max:100',
        'cliente_apellidos' => 'required|string|max:100',
        'lots' => 'required|array',
        'lots.*' => 'required|string',
        'cantidad' => 'nullable|numeric|min:1000'
    ]);

    // 2️⃣ Obtener datos y asegurar que $lots sea siempre un array
    $tipoApartado = $request->tipoApartado === 'apartadoPalabra' ? 'palabra' : 'deposito';
    $fraccionamientoId = $request->id_fraccionamiento;
    $clienteNombre = $request->cliente_nombre;
    $clienteApellidos = $request->cliente_apellidos;
    $cantidad = $request->cantidad;
    $lots = $request->input('lots', []);
    if (!is_array($lots)) $lots = [$lots];

    $usuarioId = \Illuminate\Support\Facades\Auth::user()->id_usuario;

    // 3️⃣ Validar que los lotes existen y están disponibles
    $lotesModel = \App\Models\Lote::where('id_fraccionamiento', $fraccionamientoId)
                    ->whereIn('numeroLote', $lots)
                    ->get();

    if ($lotesModel->count() !== count($lots)) {
        return response()->json([
            'success' => false,
            'message' => 'Uno o más lotes no existen en el fraccionamiento seleccionado.'
        ], 422);
    }

    foreach ($lotesModel as $lote) {
        if ($lote->estatus !== 'disponible') {
            return response()->json([
                'success' => false,
                'message' => "El lote {$lote->numero_lote} no está disponible."
            ], 422);
        }
    }

    // 4️⃣ Crear el apartado
    $apartado = \App\Models\Apartado::create([
        'tipoApartado' => $tipoApartado,
        'cliente_nombre' => $clienteNombre,
        'cliente_apellidos' => $clienteApellidos,
        'fechaApartado' => now(),
        'fechaVencimiento' => now()->addDays(2),
        'id_usuario' => $usuarioId
    ]);

    // 5️⃣ Registrar lotes apartados y actualizar historial
foreach ($request->lots as $lotNumber) {
    // Buscar lote por número y fraccionamiento
    $lote = Lote::where('numeroLote', $lotNumber)
                ->where('id_fraccionamiento', $request->id_fraccionamiento)
                ->first();

    // Depuración
    if (!$lote) {
        dd("Lote no encontrado", $lotNumber, $request->id_fraccionamiento);
    }

    if (!$lote || $lote->estatus !== 'disponible') {
        return response()->json([
            'success' => false,
            'message' => "El lote {$lotNumber} no está disponible."
        ], 422);
    }

    LoteApartado::create([
        'id_apartado' => $apartado->id_apartado,
        'id_lote' => $lote->id_lote, // ✅ Aquí va el ID correcto
    ]);

    // Opcional: registrar historial
    HistorialCambiosLote::create([
        'id_lote' => $lote->id_lote,
        'id_usuario' => Auth::id(),
        'estatus_anterior' => $lote->estatus,
        'estatus_actual' => $request->tipoApartado === 'apartadoPalabra' ? 'apartadoPalabra' : 'apartadoDeposito',
        'observaciones' => 'Apartado desde formulario',
    ]);

    // Actualizar estatus del lote
    $lote->estatus = $request->tipoApartado === 'apartadoPalabra' ? 'apartadoPalabra' : 'apartadoDeposito';
    $lote->save();
}


    // 6️⃣ Registrar depósito si aplica
    if ($tipoApartado === 'deposito' && $cantidad) {
        \App\Models\ApartadoDeposito::create([
            'cantidad' => $cantidad,
            'ticket_estatus' => 'solicitud',
            'path_ticket' => "",
            'observaciones' => "Depósito pendiente de cliente {$clienteNombre} {$clienteApellidos}",
            'id_apartado' => $apartado->id_apartado
        ]);
    }

    // 7️⃣ Respuesta JSON exitosa
    return response()->json([
        'success' => true,
        'message' => 'Apartado registrado correctamente.',
        'apartado' => $apartado
    ]);
}

}
