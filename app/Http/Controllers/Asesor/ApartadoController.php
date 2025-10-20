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

        $usuario = Auth::user();
        return view('asesor.apartados', compact(
            'apartados',
            'totalApartados',
            'enCurso',
            'vencidos',
            'vendidos',
            'usuario'
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

    public function store(Request $request)
    {
        // Validación de datos
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

        // Obtener datos
        $tipoApartado = $request->tipoApartado === 'apartadoPalabra' ? 'palabra' : 'deposito';
        $fraccionamientoId = $request->id_fraccionamiento;
        $clienteNombre = $request->cliente_nombre;
        $clienteApellidos = $request->cliente_apellidos;
        $cantidad = $request->cantidad;
        $lots = $request->input('lots', []);
        $usuarioId = Auth::user()->id_usuario;

        // Validar que los lotes existen y están disponibles
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

        // Iniciar transacción para garantizar consistencia
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

            // Registrar lotes apartados y actualizar historial
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

                if ($tipoApartado == "palabra") {
                    $lote->estatus = "apartadoPalabra";
                    $lote->save();
                } else {
                    $lote->estatus = "apartadoDeposito";
                    $lote->save();
                }
            }

            // Registrar depósito si aplica
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

            // Respuesta JSON exitosa
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
        // Validar que el usuario esté autenticado
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado.'
            ], 401);
        }

        // Validar datos recibidos
        $request->validate([
            'ticket_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // Máx. 5MB
            'ticket_estatus' => 'required|in:solicitud',
            'observaciones' => 'nullable|string|max:500',
        ], [
            'ticket_file.required' => 'Debe seleccionar un archivo.',
            'ticket_file.mimes' => 'El archivo debe ser PDF, JPG o PNG.',
            'ticket_file.max' => 'El archivo no debe exceder los 5MB.',
            'ticket_estatus.in' => 'El estatus del ticket debe ser solicitud.',
            'observaciones.max' => 'Las observaciones no deben exceder los 500 caracteres.',
        ]);

        DB::beginTransaction();
        try {
            // Buscar el apartado
            $apartado = Apartado::findOrFail($id);

            // Validar que sea de tipo depósito
            if ($apartado->tipoApartado !== 'deposito') {
                return response()->json([
                    'success' => false,
                    'message' => 'Este apartado no es de tipo depósito.'
                ], 400);
            }

            // Buscar el registro en apartados_deposito
            $deposito = ApartadoDeposito::where('id_apartado', $apartado->id_apartado)->first();

            // Manejar la carga del archivo
            $file = $request->file('ticket_file');
            $fileName = 'ticket_' . $apartado->id_apartado . '_' . time() . '.' . $file->getClientOriginalExtension();
            $folderPath = 'tickets/' . $apartado->id_apartado;
            $filePath = $file->storeAs($folderPath, $fileName, 'public');

            // Actualizar o crear el registro de depósito
            if ($deposito) {
                // Si ya existe un archivo, eliminar el anterior
                if ($deposito->path_ticket && Storage::disk('public')->exists($deposito->path_ticket)) {
                    Storage::disk('public')->delete($deposito->path_ticket);
                }

                // Actualizar el registro existente
                $deposito->update([
                    'path_ticket' => $filePath,
                    'ticket_estatus' => $request->ticket_estatus,
                    'observaciones' => $request->observaciones ?? $deposito->observaciones,
                    'fecha_subida' => now('America/Mexico_City'),
                    'fecha_revision' => null, // Resetear fecha de revisión
                ]);
            } else {
                // Crear nuevo registro
                ApartadoDeposito::create([
                    'id_apartado' => $apartado->id_apartado,
                    'cantidad' => $apartado->deposito->cantidad ?? 0,
                    'path_ticket' => $filePath,
                    'ticket_estatus' => $request->ticket_estatus,
                    'observaciones' => $request->observaciones ?? 'Depósito pendiente de revisión',
                    'fecha_subida' => now('America/Mexico_City'),
                    'fecha_revision' => null,
                ]);
            }

            // Actualizar estatus del apartado según fechaVencimiento
            $now = now('America/Mexico_City');
            $fechaVencimiento = Carbon::parse($apartado->fechaVencimiento);
            $apartado->estatus = $fechaVencimiento->isFuture() ? 'en curso' : 'vencido';
            $apartado->save();

            DB::commit();

            // Registrar log
            Log::info('Comprobante de depósito subido:', [
                'id_apartado' => $apartado->id_apartado,
                'ticket_estatus' => $request->ticket_estatus,
                'observaciones' => $request->observaciones,
                'path_ticket' => $filePath,
                'estatus_apartado' => $apartado->estatus,
            ]);

            // Generar URL pública del archivo
            $fileUrl = Storage::url($filePath);

            return response()->json([
                'success' => true,
                'message' => 'Comprobante guardado exitosamente.',
                'file_url' => $fileUrl,
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al subir comprobante:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el comprobante: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }
}