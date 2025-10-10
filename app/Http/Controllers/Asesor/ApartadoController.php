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

        // Contar apartados por estado
        $totalApartados = $apartados->count();
        $enCurso = $apartados->where('estatus', 'en curso')->count();
        $vencidos = $apartados->where('estatus', 'vencido')->count();
        $vendidos = $apartados->where('estatus', 'venta')->count();

        return view('asesor.apartados', compact(
            'apartados',
            'totalApartados',
            'enCurso',
            'vencidos',
            'vendidos'
        ));
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
            'id_fraccionamiento' => 'required|integer|exists:fraccionamientos,id_fraccionamiento',
            'cliente_nombre' => 'required|string|max:100',
            'cliente_apellidos' => 'required|string|max:100',
            'lots' => 'required|array|min:1',
            'lots.*' => 'required|string|regex:/^\d+$/',
            'cantidad' => 'nullable|numeric|min:1000|required_if:tipoApartado,apartadoDeposito'
        ], [
            'id_fraccionamiento.exists' => 'El fraccionamiento seleccionado no existe.',
            'lots.*.regex' => 'Cada número de lote debe ser un valor numérico válido.',
            'cantidad.required_if' => 'El monto es requerido para apartados con depósito.'
        ]);

        // 2️⃣ Obtener datos
        $tipoApartado = $request->tipoApartado === 'apartadoPalabra' ? 'palabra' : 'deposito';
        $fraccionamientoId = $request->id_fraccionamiento;
        $clienteNombre = $request->cliente_nombre;
        $clienteApellidos = $request->cliente_apellidos;
        $cantidad = $request->cantidad;
        $lots = $request->input('lots', []);
        $usuarioId = Auth::user()->id_usuario;

        // 3️⃣ Validar que los lotes existen y están disponibles
        $lotesModel = Lote::where('id_fraccionamiento', $fraccionamientoId)
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
                    'message' => "El lote {$lote->numeroLote} no está disponible."
                ], 422);
            }
        }

        // 4️⃣ Iniciar transacción para garantizar consistencia
        DB::beginTransaction();
        try {
            // Crear el apartado
            $apartado = Apartado::create([
                'tipoApartado' => $tipoApartado,
                'cliente_nombre' => $clienteNombre,
                'cliente_apellidos' => $clienteApellidos,
                'fechaApartado' => Carbon::now('America/Mexico_City')->toDateTimeString(),
                'fechaVencimiento' => Carbon::now('America/Mexico_City')->addDays(2)->toDateTimeString(),
                'id_usuario' => $usuarioId
            ]);

            // 5️⃣ Registrar lotes apartados y actualizar historial
            foreach ($lots as $lotNumber) {
                $lote = $lotesModel->firstWhere('numeroLote', $lotNumber);
                
                if (!$lote) {
                    throw new \Exception("Lote {$lotNumber} no encontrado.");
                }

                LoteApartado::create([
                    'id_apartado' => $apartado->id_apartado,
                    'id_lote' => $lote->id_lote,
                ]);

                HistorialCambiosLote::create([
                    'id_lote' => $lote->id_lote,
                    'id_usuario' => $usuarioId,
                    'estatus_anterior' => $lote->estatus,
                    'estatus_actual' => $tipoApartado,
                    'observaciones' => 'Apartado registrado desde formulario',
                ]);


                if($tipoApartado == "palabra"){
                    $lote->estatus = "apartadoPalabra";
                    $lote->save();
                }else{
                    $lote->estatus = "apartadoDeposito";
                    $lote->save();
                }
                
            }

            // 6️⃣ Registrar depósito si aplica
            if ($tipoApartado === 'deposito' && $cantidad) {
                ApartadoDeposito::create([
                    'cantidad' => $cantidad,
                    'ticket_estatus' => 'solicitud',
                    'path_ticket' => '',
                    'observaciones' => "Depósito pendiente de cliente {$clienteNombre} {$clienteApellidos}",
                    'id_apartado' => $apartado->id_apartado
                ]);
            }

            // Confirmar transacción
            DB::commit();

            // Registrar log para depuración
            Log::info('Apartado registrado:', [
                'id' => $apartado->id_apartado,
                'fechaApartado' => $apartado->fechaApartado->toDateTimeString(),
                'fechaVencimiento' => $apartado->fechaVencimiento->toDateTimeString(),
            ]);

            // 7️⃣ Respuesta JSON exitosa
            return response()->json([
                'success' => true,
                'message' => 'Apartado registrado correctamente.',
                'apartado' => [
                    'id_apartado' => $apartado->id_apartado,
                    'tipoApartado' => $apartado->tipoApartado,
                    'cliente_nombre' => $apartado->cliente_nombre,
                    'cliente_apellidos' => $apartado->cliente_apellidos,
                    'fechaApartado' => $apartado->fechaApartado->toDateTimeString(),
                    'fechaVencimiento' => $apartado->fechaVencimiento->toDateTimeString(),
                    'id_usuario' => $apartado->id_usuario
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al registrar apartado:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar el apartado: ' . $e->getMessage()
            ], 500);
        }
    }


    public function uploadTicket(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            // Buscar el apartado
            $apartado = Apartado::findOrFail($id);

            // Validar que sea de tipo depósito
            if ($apartado->tipoApartado !== 'deposito') {
                return response()->json([
                    'success' => false,
                    'message' => 'Este apartado no es de tipo depósito.'
                ], 400);
            }

            // Validar archivo recibido
            $request->validate([
                'ticket_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120' // máx. 5 MB
            ]);

            $file = $request->file('ticket_file');

            // Generar nombre único
            $fileName = 'ticket_' . $apartado->id_apartado . '_' . time() . '.' . $file->getClientOriginalExtension();

            // ✅ Guardar en la carpeta correcta dentro de storage/app/public
            $folderPath = 'tickets/' . $apartado->id_apartado;
            $filePath = $file->storeAs($folderPath, $fileName, 'public');

            // Buscar si ya hay registro en apartados_deposito
            $deposito = ApartadoDeposito::where('id_apartado', $apartado->id_apartado)->first();

            if ($deposito) {
                // Si ya existe, eliminar archivo anterior si existe
                if ($deposito->path_ticket && Storage::disk('public')->exists($deposito->path_ticket)) {
                    Storage::disk('public')->delete($deposito->path_ticket);
                }

                // Actualizar registro existente
                $deposito->update([
                    'path_ticket' => $filePath,
                    'fecha_subida' => now(),
                ]);
            } else {
                // Crear nuevo registro
                ApartadoDeposito::create([
                    'id_apartado' => $apartado->id_apartado,
                    'path_ticket' => $filePath,
                    'fecha_subida' => now(),
                ]);
            }

            DB::commit();

            // Generar URL pública del archivo
            $fileUrl = Storage::url($filePath);

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
                'message' => 'Error al guardar el comprobante. Intente nuevamente.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
