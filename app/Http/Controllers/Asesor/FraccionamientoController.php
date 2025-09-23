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
            // Validar que el número de lote no esté vacío
            if (empty($numeroLote)) {
                return response()->json([
                    'success' => false,
                    'message' => 'El número de lote no puede estar vacío'
                ], 400);
            }

            // Buscar el lote
            $lote = Lote::where('id_fraccionamiento', $idFraccionamiento)
                        ->where('numeroLote', $numeroLote)
                        ->first();

            if (!$lote) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lote no encontrado en este fraccionamiento'
                ], 404);
            }

            // Buscar medidas del lote
            $medidas = LoteMedida::where('id_lote', $lote->id_lote)->first();

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
                    'numero_lote' => $lote->numeroLote,
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

            return response()->json($response);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }
    public function downloadPlano($idFraccionamiento, $idPlano)
    {
        try {
            $plano = PlanoFraccionamiento::where('id_plano', $idPlano)
                                        ->where('id_fraccionamiento', $idFraccionamiento)
                                        ->firstOrFail();

            $filePath = storage_path('app/' . $plano->plano_path);
            
            if (!file_exists($filePath)) {
                abort(404, 'Archivo no encontrado');
            }

            return response()->download($filePath, $plano->nombre . '.pdf');

        } catch (\Exception $e) {
            abort(404, 'Plano no encontrado');
        }
    }
}