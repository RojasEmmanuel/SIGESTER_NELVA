<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fraccionamiento;
use App\Models\InfoFraccionamiento;
use App\Models\PlanoFraccionamiento;
use App\Models\AmenidadFraccionamiento;
use App\Models\Lote;
use App\Models\LoteMedida;
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
                'lotes',
                'galeria',
                'archivosFraccionamiento',
                'promociones' // ← NUEVA RELACIÓN
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
                'promocionesActivas' // ← NUEVA VARIABLE
            ));

        } catch (\Exception $e) {
            Log::error("Error al cargar fraccionamiento {$id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'No se pudo cargar el fraccionamiento.');
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