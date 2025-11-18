<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fraccionamiento;
use App\Models\InfoFraccionamiento;
use App\Models\PlanoFraccionamiento;
use App\Models\AmenidadFraccionamiento;
use App\Models\Lote;
use App\Models\Zona;
use App\Models\LoteMedida;
use App\Models\LoteZona; // ← Asegúrate de importar
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class FraccionamientoController extends Controller
{
    public function show($id)
    {
        try {
            $hoy = \Carbon\Carbon::now();

            // Cargar fraccionamiento con todas las relaciones
            $fraccionamiento = Fraccionamiento::with([
                'infoFraccionamiento',
                'planosFraccionamiento',
                'amenidadesFraccionamiento',
                'lotes.loteMedida',
                'lotes.loteZona.zona', // ← NUEVA RELACIÓN CARGADA
                'galeria',
                'archivosFraccionamiento',
                'promociones',
                'zonas'
            ])->findOrFail($id);

            // === Información básica ===
            $infoFraccionamiento = $fraccionamiento->infoFraccionamiento;

            $datosFraccionamiento = [
                'id' => $fraccionamiento->id_fraccionamiento,
                'nombre' => $fraccionamiento->nombre,
                'ubicacion' => $fraccionamiento->ubicacion,
                'path_imagen' => $fraccionamiento->path_imagen,
                'estatus' => $fraccionamiento->estatus,
            ];

            if ($infoFraccionamiento) {
                $datosFraccionamiento = array_merge($datosFraccionamiento, [
                    'descripcion' => $infoFraccionamiento->descripcion,
                    'precio_metro_cuadrado' => $infoFraccionamiento->precio_metro_cuadrado,
                    'tipo_propiedad' => $infoFraccionamiento->tipo_propiedad,
                    'precioGeneral' => $infoFraccionamiento->precioGeneral,
                    'ubicacionMaps' => $infoFraccionamiento->ubicacionMaps,
                ]);
            }

            // === Planos ===
            $planos = $fraccionamiento->planosFraccionamiento->map(fn($p) => [
                'id' => $p->id_plano,
                'nombre' => $p->nombre,
                'plano_path' => $p->plano_path,
            ]);

            // === Amenidades ===
            $amenidades = $fraccionamiento->amenidadesFraccionamiento->map(fn($a) => [
                'id' => $a->id_amenidad,
                'nombre' => $a->nombre,
                'descripcion' => $a->descripcion,
                'tipo' => $a->tipo,
            ]);

            // === Galería ===
            $galeria = $fraccionamiento->galeria->map(fn($f) => [
                'id' => $f->id_foto,
                'nombre' => $f->nombre,
                'fotografia_path' => $f->fotografia_path,
                'fecha_subida' => $f->fecha_subida->toDateTimeString(),
            ]);

            // === Archivos ===
            $archivos = $fraccionamiento->archivosFraccionamiento->map(fn($a) => [
                'id' => $a->id_archivo,
                'nombre_archivo' => $a->nombre_archivo,
                'archivo_path' => $a->archivo_path,
                'fecha_subida' => $a->fecha_subida->toDateTimeString(),
            ]);

            // === Estadísticas de lotes ===
            $totalLotes = $fraccionamiento->lotes->count();
            $lotesDisponibles = $fraccionamiento->lotes->where('estatus', 'disponible')->count();
            $lotesApartadosPalabra = $fraccionamiento->lotes->where('estatus', 'apartadoPalabra')->count();
            $lotesApartadosDeposito = $fraccionamiento->lotes->where('estatus', 'apartadoDeposito')->count();
            $lotesVendidos = $fraccionamiento->lotes->where('estatus', 'vendido')->count();
            $lotesApartados = $lotesApartadosPalabra + $lotesApartadosDeposito;

            // === PROMOCIONES ACTIVAS ===
            $promocionesActivas = $fraccionamiento->promociones
                ->where('fecha_inicio', '<=', $hoy)
                ->where(function ($promo) use ($hoy) {
                    return is_null($promo->fecha_fin) || $promo->fecha_fin >= $hoy;
                })
                ->map(fn($p) => [
                    'id' => $p->id_promocion,
                    'titulo' => $p->titulo,
                    'descripcion' => $p->descripcion,
                    'imagen_path' => $p->imagen_path,
                    'fecha_inicio' => $p->fecha_inicio->format('d/m/Y'),
                    'fecha_fin' => $p->fecha_fin?->format('d/m/Y') ?? 'Indefinida',
                ])
                ->values();

            // ZONAS DEL FRACCIONAMIENTO
            $zonas = $fraccionamiento->zonas->map(fn($z) => [
                'id' => $z->id_zona,
                'nombre' => $z->nombre,
                'precio_m2' => $z->precio_m2,
            ]);

            return view('asesor.fraccionamiento', compact(
                'datosFraccionamiento',
                'planos',
                'amenidades',
                'galeria',
                'archivos',
                'totalLotes',
                'lotesDisponibles',
                'lotesApartados',
                'lotesApartadosPalabra',
                'lotesApartadosDeposito',
                'lotesVendidos',
                'promocionesActivas',
                'zonas'
            ));

        } catch (\Exception $e) {
            Log::error("Error al cargar fraccionamiento {$id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'No se pudo cargar el fraccionamiento.');
        }
    }    

    public function getLoteDetails($idFraccionamiento, $numeroLote)
    {
        try {
            Log::info("Buscando lote - Fraccionamiento: $idFraccionamiento, Número: $numeroLote");

            if (empty($numeroLote)) {
                return response()->json([
                    'success' => false,
                    'message' => 'El número de lote no puede estar vacío'
                ], 400);
            }

            // Cargar lote con medidas y zona
            $lote = Lote::with(['loteMedida', 'loteZona.zona'])
                ->where('id_fraccionamiento', $idFraccionamiento)
                ->where('numeroLote', $numeroLote)
                ->first();

            if (!$lote) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lote no encontrado en este fraccionamiento'
                ], 404);
            }

            $medidas = $lote->loteMedida;
            if (!$medidas) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontraron medidas para este lote'
                ], 404);
            }

            // Usar el accesor del modelo: $lote->precio_m2
            $precioM2 = $lote->precio_m2; // ← Viene de zona o 0
            $areaMetros = $medidas->area_metros ?? 0;
            $costoTotal = $precioM2 * $areaMetros;

            // Opcional: obtener precio del fraccionamiento como fallback (no necesario ya que el accesor lo maneja)
            $fraccionamiento = Fraccionamiento::with('infoFraccionamiento')->find($idFraccionamiento);
            $precioGeneralM2 = $fraccionamiento?->infoFraccionamiento?->precio_metro_cuadrado ?? 0;

            $response = [
                'success' => true,
                'lote' => [
                    'id' => $lote->id_lote,
                    'numero_lote' => $lote->numeroLote,
                    'manzana' => $medidas->manzana ?? 'N/A',
                    'area_total' => (float) $areaMetros,
                    'precio_m2' => (float) $precioM2,
                    'costo_total' => (float) $costoTotal,
                    'estatus' => $lote->estatus,
                    'zona' => $lote->loteZona?->zona ? [
                        'id' => $lote->loteZona->zona->id_zona,
                        'nombre' => $lote->loteZona->zona->nombre,
                        'precio_m2' => (float) $lote->loteZona->zona->precio_m2,
                    ] : null,
                    'medidas' => [
                        'norte' => (float) $medidas->norte,
                        'sur' => (float) $medidas->sur,
                        'oriente' => (float) $medidas->oriente,
                        'poniente' => (float) $medidas->poniente,
                        'area_metros' => (float) $areaMetros,
                    ]
                ],
                'debug' => [
                    'precio_zona' => $lote->loteZona?->zona?->precio_m2,
                    'precio_general' => $precioGeneralM2,
                    'usado' => $precioM2 > 0 ? 'zona' : 'general (o 0)'
                ]
            ];

            Log::info("Respuesta getLoteDetails:", $response);

            return response()->json($response);

        } catch (\Exception $e) {
            Log::error("Error en getLoteDetails: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    public function downloadPlano($id, $planoId)
    {
        $plano = PlanoFraccionamiento::where('id_fraccionamiento', $id)
                    ->where('id_plano', $planoId)
                    ->firstOrFail();

        $ruta = storage_path('app/public/planos/' . $plano->archivo); // ← Corregido: usar / no \

        if (!file_exists($ruta)) {
            abort(404, 'El archivo no existe.');
        }

        return response()->download($ruta, $plano->archivo);
    }

   public function getLotes($idFraccionamiento)
    {
        try {
            $lotes = Lote::with(['loteMedida', 'loteZona.zona', 'fraccionamiento.infoFraccionamiento'])
                ->where('id_fraccionamiento', $idFraccionamiento)
                ->get()
                ->map(function($lote) {
                    $area = (float) ($lote->loteMedida->area_metros ?? 0);
                    $precioM2 = $lote->precio_m2; // ← Usa el accesor
                    $costoTotal = $area > 0 ? $precioM2 * $area : 0;

                    return [
                        'id_lote' => $lote->id_lote,
                        'numeroLote' => $lote->numeroLote,
                        'estatus' => $lote->estatus,
                        'manzana' => $lote->loteMedida->manzana ?? 'N/A',
                        'area_total' => $area,
                        'precio_m2' => $precioM2,
                        'costo_total' => $costoTotal,
                        'zona' => $lote->loteZona?->zona ? [
                            'id' => $lote->loteZona->zona->id_zona,
                            'nombre' => $lote->loteZona->zona->nombre,
                            'precio_m2' => (float) $lote->loteZona->zona->precio_m2,
                        ] : null,
                        'medidas' => $lote->loteMedida ? [
                            'norte' => (float) $lote->loteMedida->norte,
                            'sur' => (float) $lote->loteMedida->sur,
                            'oriente' => (float) $lote->loteMedida->oriente,
                            'poniente' => (float) $lote->loteMedida->poniente,
                            'area_metros' => $area,
                        ] : null
                    ];
                });

            return response()->json([
                'success' => true,
                'lotes' => $lotes,
                'info' => [
                    'precio_m2_base' => (float) Fraccionamiento::find($idFraccionamiento)?->infoFraccionamiento?->precio_metro_cuadrado ?? 0
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Error getLotes: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar lotes'
            ], 500);
        }
    }

    public function getGeoJsonConEstatus($idFraccionamiento)
    {
        $path = storage_path('app/public/geojson/lotes.geojson');
        $geojson = json_decode(file_get_contents($path), true);

        $lotes = Lote::where('id_fraccionamiento', $idFraccionamiento)
                    ->with('loteZona.zona')
                    ->get()
                    ->keyBy('numeroLote');

        foreach ($geojson['features'] as &$feature) {
            $loteNumero = $feature['properties']['lote'];
            $lote = $lotes->get($loteNumero);

            $feature['properties']['estatus'] = $lote?->estatus ?? 'desconocido';
            $feature['properties']['precio_m2'] = $lote?->precio_m2 ?? 0;
            $feature['properties']['zona'] = $lote?->loteZona?->zona?->nombre ?? null;
        }

        return response()->json($geojson);
    }


    public function getZonas($idFraccionamiento)
    {
        try {
            $zonas = Zona::where('id_fraccionamiento', $idFraccionamiento)
                ->select('id_zona', 'nombre', 'precio_m2','color')
                ->get();

            return response()->json([
                'success' => true,
                'zonas' => $zonas
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar zonas'
            ], 500);
        }
    }
}