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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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
        // Cargar el apartado con sus relaciones principales
        $apartado = Apartado::with([
            'usuario',
            'lotesApartados.lote.fraccionamiento'
        ])->findOrFail($id);

        $apartadoDeposito = null;

        // Si el apartado fue de tipo "deposito", buscar el registro en la tabla apartados_deposito
        if ($apartado->tipoApartado === 'deposito') {
            $apartadoDeposito = \App\Models\ApartadoDeposito::where('id_apartado', $apartado->id_apartado)
                ->first();
        }

        // Retornar la vista con ambos registros
        return view('asesor.showApartado', compact('apartado', 'apartadoDeposito'));
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
        'tipoApartado'       => $tipoApartado,
        'cliente_nombre'     => $clienteNombre,
        'cliente_apellidos'  => $clienteApellidos,
        'fechaApartado'      => Carbon::now(),               // Fecha y hora actual
        'fechaVencimiento'   => Carbon::now()->addDays(2),   // Fecha y hora actual + 2 días
        'id_usuario'         => $usuarioId
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


    public function uploadTicket(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            // Validar que el apartado existe
            $apartado = Apartado::findOrFail($id);
            
            // Validar que el apartado es de tipo depósito
            if ($apartado->tipoApartado !== 'deposito') {
                return response()->json([
                    'success' => false,
                    'message' => 'Este apartado no es de tipo depósito.'
                ], 400);
            }

            // Validar archivo
            $request->validate([
                'ticket_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120' // 5MB máximo
            ]);

            // Obtener archivo
            $file = $request->file('ticket_file');
            
            // Generar nombre único para el archivo
            $fileName = 'ticket_' . $apartado->id_apartado . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Definir ruta de almacenamiento
            $folderPath = 'public/tickets/' . $apartado->id_apartado;
            $filePath = $file->storeAs($folderPath, $fileName);
            
            // Verificar si ya existe un registro en apartados_deposito
            $deposito = ApartadoDeposito::where('id_apartado', $apartado->id_apartado)->first();
            
            if ($deposito) {
                // Si ya existe, eliminar el archivo anterior
                if (Storage::exists($deposito->path_ticket)) {
                    Storage::delete($deposito->path_ticket);
                }
                
                // Actualizar registro existente
                $deposito->update([
                    'path_ticket' => $filePath,
                    'fecha_subida' => now()
                ]);
            } else {
                // Crear nuevo registro
                ApartadoDeposito::create([
                    'id_apartado' => $apartado->id_apartado,
                    'path_ticket' => $filePath,
                    'fecha_subida' => now()
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Comprobante guardado exitosamente.'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el comprobante. Intente nuevamente.'
            ], 500);
        }
    }
}
