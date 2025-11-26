<?php

namespace App\Http\Controllers\pagina;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fraccionamiento;
use App\Models\InfoFraccionamiento;
use App\Models\PlanoFraccionamiento;
use App\Models\AmenidadFraccionamiento;
use App\Models\Lote;
use App\Models\LoteMedida;
use App\Models\ArchivosFraccionamiento;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class fraccClientController extends Controller
{
public function show($id)
{
    try {
        // Obtener el fraccionamiento con todas las relaciones necesarias
        $fraccionamiento = Fraccionamiento::with([
            'infoFraccionamiento',
            'planosFraccionamiento',
            'amenidadesFraccionamiento',
            'lotes',
            'galeria',
            'archivosFraccionamiento'
        ])->findOrFail($id);

        // Obtener información general del fraccionamiento
        $infoFraccionamiento = $fraccionamiento->infoFraccionamiento;

        // Obtener la primera imagen de la galería para el hero
        $heroImage = $fraccionamiento->galeria->first();
        $heroImagePath = $heroImage ? $heroImage->fotografia_path : null;

        // Preparar datos para la vista
        $datosFraccionamiento = [
            'id' => $fraccionamiento->id_fraccionamiento,
            'nombre' => $fraccionamiento->nombre,
            'ubicacion' => $fraccionamiento->ubicacion,
            'path_imagen' => $fraccionamiento->path_imagen,
            'estatus' => $fraccionamiento->estatus,
            'hero_image' => $heroImagePath, // Nueva propiedad para la imagen del hero
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

        // Obtener galería del fraccionamiento
        $galeria = $fraccionamiento->galeria->map(function($foto) {
            return [
                'id' => $foto->id_foto,
                'nombre' => $foto->nombre,
                'fotografia_path' => $foto->fotografia_path,
                'fecha_subida' => $foto->fecha_subida->toDateTimeString(),
            ];
        });

        // Obtener archivos del fraccionamiento
        $archivos = $fraccionamiento->archivosFraccionamiento->map(function($archivo) {
            return [
                'id' => $archivo->id_archivo,
                'nombre_archivo' => $archivo->nombre_archivo,
                'archivo_path' => $archivo->archivo_path,
                'fecha_subida' => $archivo->fecha_subida->toDateTimeString(),
            ];
        });

        // ZONAS DEL FRACCIONAMIENTO
        $zonas = $fraccionamiento->zonas->map(fn($z) =>
            [
                'id' => $z->id_zona,
                'nombre' => $z->nombre,
                'precio_m2' => $z->precio_m2,
            ]
        );

        // Obtener estadísticas de lotes
        $totalLotes = $fraccionamiento->lotes->count();
        $lotesDisponibles = $fraccionamiento->lotes->where('estatus', 'disponible')->count();
        $lotesApartadosPalabra = $fraccionamiento->lotes->where('estatus', 'apartadoPalabra')->count();
        $lotesApartadosVendido = $fraccionamiento->lotes->where('estatus', 'apartadoDeposito')->count();
        $lotesVendidos = $fraccionamiento->lotes->where('estatus', 'vendido')->count();
        
        $lotesApartados = $lotesApartadosPalabra + $lotesApartadosVendido;

        return view('pagina.fraccionamiento', compact(
            'datosFraccionamiento',
            'planos',
            'amenidades',
            'galeria',
            'archivos',
            'totalLotes',
            'lotesDisponibles',
            'lotesApartados',
            'lotesApartadosPalabra',
            'lotesApartadosVendido',
            'lotesVendidos',
            'zonas'
        ));

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error al cargar el fraccionamiento: ' . $e->getMessage());
    }
}

    public function getLoteDetails($idFraccionamiento, $numeroLote)
    {
        try {
            // Validar parámetros de entrada
            if (!$idFraccionamiento || !is_numeric($idFraccionamiento)) {
                Log::warning("ID de fraccionamiento inválido: $idFraccionamiento");
                return response()->json([
                    'success' => false,
                    'message' => 'ID de fraccionamiento inválido.'
                ], 400);
            }

            if (empty($numeroLote) || !is_numeric($numeroLote)) {
                Log::warning("Número de lote inválido: '$numeroLote' para fraccionamiento $idFraccionamiento");
                return response()->json([
                    'success' => false,
                    'message' => 'El número de lote debe ser un número válido (ej: 12).'
                ], 400);
            }

            $numeroLote = (int) $numeroLote; // Forzar entero

            Log::info("Buscando lote", [
                'fraccionamiento_id' => $idFraccionamiento,
                'numero_lote' => $numeroLote
            ]);

            // Buscar lote
            $lote = Lote::where('id_fraccionamiento', $idFraccionamiento)
                        ->where('numeroLote', $numeroLote)
                        ->first();

            if (!$lote) {
                Log::info("Lote no encontrado", [
                    'fraccionamiento_id' => $idFraccionamiento,
                    'numero_lote' => $numeroLote
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Lote no encontrado en este fraccionamiento.'
                ], 404);
            }

            // Cargar medidas (relación definida en el modelo)
            $medidas = $lote->loteMedida;

            if (!$medidas) {
                Log::warning("Medidas no encontradas para lote", ['lote_id' => $lote->id_lote]);
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontraron medidas para este lote.'
                ], 404);
            }

            // Obtener precio por m²
            $infoFracc = $lote->fraccionamiento?->infoFraccionamiento;

            if (!$infoFracc || is_null($infoFracc->precio_metro_cuadrado)) {
                Log::warning("Precio por m² no configurado", ['fraccionamiento_id' => $idFraccionamiento]);
                return response()->json([
                    'success' => false,
                    'message' => 'Precio por m² no disponible.'
                ], 404);
            }

            $precioM2 = (float) $infoFracc->precio_metro_cuadrado;
            $areaMetros = (float) $medidas->area_metros;
            $costoTotal = $precioM2 * $areaMetros;

            // Respuesta exitosa
            $response = [
                'success' => true,
                'lote' => [
                    'id' => $lote->id_lote,
                    'numeroLote' => $lote->numeroLote,
                    'manzana' => $medidas->manzana ?? 'N/A',
                    'area_total' => $areaMetros,
                    'precio_m2' => $precioM2,
                    'costo_total' => $costoTotal,
                    'estatus' => $lote->estatus,
                    'medidas' => [
                        'norte' => (float) ($medidas->norte ?? 0),
                        'sur' => (float) ($medidas->sur ?? 0),
                        'oriente' => (float) ($medidas->oriente ?? 0),
                        'poniente' => (float) ($medidas->poniente ?? 0),
                    ]
                ]
            ];

            Log::info("Lote encontrado y enviado", ['lote_id' => $lote->id_lote]);

            return response()->json($response);

        } catch (\Exception $e) {
            Log::error("Error crítico en getLoteDetails", [
                'fraccionamiento' => $idFraccionamiento,
                'lote' => $numeroLote,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor. Intenta más tarde.'
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

    public function getLoteInfo($id, $numero)
    {
        try {
            
            $lote = Lote::where('id_fraccionamiento', $id)
                        ->where('numeroLote', $numero)
                        ->with('medidas')
                        ->first();
            
            if (!$lote) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lote no encontrado'
                ], 404);
            }
            
            
            return response()->json([
                'success' => true,
                'lote' => $lote
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    public function downloadArchivo($idFraccionamiento, $idArchivo)
    {
        try {
            $archivo = ArchivosFraccionamiento::where('id_fraccionamiento', $idFraccionamiento)
                        ->where('id_archivo', $idArchivo)
                        ->firstOrFail();

            $ruta = storage_path('app/public/' . $archivo->archivo_path);

            if (!file_exists($ruta)) {
                abort(404, 'Archivo no encontrado.');
            }

            $nombreDescarga = $archivo->nombre_archivo; // Ej: "Reglamento del Fraccionamiento"
            $nombreDescarga = preg_replace('/\.pdf$/i', '', $nombreDescarga); // Quita .pdf si ya existe
            $nombreDescarga = $nombreDescarga . '.pdf'; // Añade .pdf al final

            // 5. Forzar tipo MIME PDF
            return response()->download($ruta, $nombreDescarga, [
                'Content-Type' => 'application/pdf',
            ]);

        } catch (\Exception $e) {
            Log::error("Error descarga archivo ID $idArchivo: " . $e->getMessage());
            return redirect()->back()->with('error', 'No se pudo descargar el archivo.');
        }
    }
}

    
