<?php

namespace App\Http\Controllers\asesor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fraccionamiento;
use App\Models\InfoFraccionamiento;
use App\Models\PlanoFraccionamiento;
use App\Models\AmenidadFraccionamiento;
use App\Models\Lote;
use App\Models\LoteMedida;
use Illuminate\Support\Facades\Log;

class FraccionamientoController extends Controller
{
    public function show($id)
    {
        try {
            // Obtener el fraccionamiento con información básica
            $fraccionamiento = Fraccionamiento::with([
                'infoFraccionamiento',
                'planosFraccionamiento',
                'amenidadesFraccionamiento',
                'lotes' // Solo cargar lotes básicos por ahora
            ])->findOrFail($id);

            // Obtener información general del fraccionamiento
            $infoFraccionamiento = $fraccionamiento->infoFraccionamiento;

            // Preparar datos para la vista
            $datosFraccionamiento = [
                'id' => $fraccionamiento->id_fraccionamiento,
                'nombre' => $fraccionamiento->nombre,
                'ubicacion' => $fraccionamiento->ubicacion,
                'path_imagen' => $fraccionamiento->path_imagen,
                'estatus' => $fraccionamiento->estatus,
            ];

            // Si existe información adicional, agregarla
            if ($infoFraccionamiento) {
                $datosFraccionamiento['descripcion'] = $infoFraccionamiento->descripcion;
                $datosFraccionamiento['precio_metro_cuadrado'] = $infoFraccionamiento->precio_metro_cuadrado;
                $datosFraccionamiento['tipo_propiedad'] = $infoFraccionamiento->tipo_propiedad;
                $datosFraccionamiento['precioGeneral'] = $infoFraccionamiento->precioGeneral;
                $datosFraccionamiento['ubicacionMaps'] = $infoFraccionamiento->ubicacionMaps;
            }

            // Obtener planos del fraccionamiento
            $planos = $fraccionamiento->planosFraccionamiento->map(function($plano) {
                return [
                    'id' => $plano->id_plano,
                    'nombre' => $plano->nombre,
                    'plano_path' => $plano->plano_path,
                ];
            });

            // Obtener amenidades del fraccionamiento
            $amenidades = $fraccionamiento->amenidadesFraccionamiento->map(function($amenidad) {
                return [
                    'id' => $amenidad->id_amenidad,
                    'nombre' => $amenidad->nombre,
                    'descripcion' => $amenidad->descripcion,
                    'tipo' => $amenidad->tipo,
                ];
            });
            
            // Obtener estadísticas de lotes (sin medidas por ahora)
            $totalLotes = $fraccionamiento->lotes->count();
            $lotesDisponibles = $fraccionamiento->lotes->where('estatus', 'disponible')->count();
            $lotesApartadosPalabra = $fraccionamiento->lotes->where('estatus', 'apartadoPalabra')->count();
            $lotesApartadosVendido = $fraccionamiento->lotes->where('estatus', 'apartadoVendido')->count();
            $lotesVendidos = $fraccionamiento->lotes->where('estatus', 'vendido')->count();
            
            $lotesApartados = $lotesApartadosPalabra + $lotesApartadosVendido;

            return view('asesor.fraccionamiento', compact(
                'datosFraccionamiento',
                'planos',
                'amenidades',
                'totalLotes',
                'lotesDisponibles',
                'lotesApartados',
                'lotesApartadosPalabra',
                'lotesApartadosVendido',
                'lotesVendidos'
            ));

        } catch (\Exception $e) {
            // Manejar error de manera más elegante
            return redirect()->back()->with('error', 'Error al cargar el fraccionamiento: ' . $e->getMessage());
        }
    }

    public function getLoteDetails($idFraccionamiento, $numeroLote)
    {
        try {
            Log::info("🔍 Buscando lote - Fraccionamiento: $idFraccionamiento, Número: $numeroLote");

            // Validar que el número de lote no esté vacío
            if (empty($numeroLote)) {
                return response()->json([
                    'success' => false,
                    'message' => 'El número de lote no puede estar vacío'
                ], 400);
            }

            // ✅ CORRECTO: La columna se llama 'numeroLote' en la BD
            $lote = Lote::where('id_fraccionamiento', $idFraccionamiento)
                        ->where('numeroLote', $numeroLote)  // ← ESTO ESTÁ BIEN
                        ->first();

            Log::info("📊 Resultado de búsqueda de lote:", [$lote ? $lote->toArray() : 'NO ENCONTRADO']);

            if (!$lote) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lote no encontrado en este fraccionamiento'
                ], 404);
            }

            // ✅ Usar la relación definida en el modelo
            $medidas = $lote->loteMedida;

            Log::info("📐 Medidas encontradas:", [$medidas ? $medidas->toArray() : 'NO ENCONTRADAS']);

            if (!$medidas) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontraron medidas para este lote'
                ], 404);
            }

            // Obtener el precio por m² del fraccionamiento
            $fraccionamiento = Fraccionamiento::with('infoFraccionamiento')
                                            ->find($idFraccionamiento);
            
            if (!$fraccionamiento || !$fraccionamiento->infoFraccionamiento) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró información de precios del fraccionamiento'
                ], 404);
            }

            $precioM2 = $fraccionamiento->infoFraccionamiento->precio_metro_cuadrado ?? 0;
            $areaMetros = $medidas->area_metros ?? 0;
            $costoTotal = $precioM2 * $areaMetros;

            // Preparar respuesta
            $response = [
                'success' => true,
                'lote' => [
                    'id' => $lote->id_lote,
                    'numero_lote' => $lote->numeroLote,  // ← Propiedad del modelo
                    'manzana' => $medidas->manzana ?? 'N/A',
                    'area_total' => (float) $areaMetros,
                    'precio_m2' => (float) $precioM2,
                    'estatus' => $lote->estatus,
                    'costo_total' => (float) $costoTotal,
                    'medidas' => [
                        'norte' => (float) $medidas->norte,
                        'sur' => (float) $medidas->sur,
                        'oriente' => (float) $medidas->oriente,
                        'poniente' => (float) $medidas->poniente,
                        'area_metros' => (float) $areaMetros,
                    ]
                ]
            ];

            Log::info("✅ Respuesta final:", $response);

            return response()->json($response);

        } catch (\Exception $e) {
            Log::error("❌ Error en getLoteDetails: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadPlano($id, $planoId)
    {
        // Buscar el plano en la base de datos
        $plano = PlanoFraccionamiento::where('id_fraccionamiento', $id)
                    ->where('id_plano', $planoId)
                    ->firstOrFail();

        // Ruta correcta al archivo dentro de storage/app/public/planos/
        $ruta = storage_path('app\public\planos\'' . $plano->archivo);

        // Verificar que el archivo exista
        if (!file_exists($ruta)) {
            abort(404, 'El archivo no existe.');
        }

        // Descargar el archivo con su nombre original
        return response()->download($ruta, $plano->archivo);
    }

    public function getLotes($idFraccionamiento)
    {
        try {
            $lotes = Lote::with('loteMedida')
                ->where('id_fraccionamiento', $idFraccionamiento)
                ->get()
                ->map(function($lote) {
                    return [
                        'id_lote' => $lote->id_lote,
                        'numeroLote' => $lote->numeroLote,
                        'estatus' => $lote->estatus,
                        'manzana' => $lote->loteMedida->manzana ?? 'N/A',
                        'area_total' => $lote->loteMedida->area_metros ?? 0,
                        'medidas' => $lote->loteMedida ? [
                            'norte' => $lote->loteMedida->norte,
                            'sur' => $lote->loteMedida->sur,
                            'oriente' => $lote->loteMedida->oriente,
                            'poniente' => $lote->loteMedida->poniente,
                            'area_metros' => $lote->loteMedida->area_metros
                        ] : null
                    ];
                });

            return response()->json([
                'success' => true,
                'lotes' => $lotes
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los lotes: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getGeoJsonConEstatus($idFraccionamiento)
    {
        $path = storage_path('app/public/geojson/lotes.geojson'); // tu GeoJSON base
        $geojson = json_decode(file_get_contents($path), true);

        $estatus = Lote::where('id_fraccionamiento', $idFraccionamiento)
                    ->pluck('estatus', 'numero_lote');

        foreach ($geojson['features'] as &$feature) {
            $loteNumero = $feature['properties']['lote'];
            $feature['properties']['estatus'] = $estatus[$loteNumero] ?? 'desconocido';
        }

        return response()->json($geojson);
    }


}