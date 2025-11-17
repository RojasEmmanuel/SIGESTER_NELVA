<?php

namespace App\Http\Controllers\Ingeniero;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fraccionamiento;
use Illuminate\Support\Str;

class MapasController extends Controller
{

    /**
     * Vista principal con selector de fraccionamientos
     */
    public function index()
    {
        $fraccionamientos = Fraccionamiento::select('id_fraccionamiento', 'nombre')
            ->where('estatus', true)
            ->orderBy('nombre', 'asc')
            ->get();

        return view('ingeniero.mapa-fraccionamientos', compact('fraccionamientos'));
    }

    /**
     * Devuelve el GeoJSON + datos básicos del fraccionamiento seleccionado
     */
    public function geojsonData($id_fraccionamiento)
    {
        $frac = Fraccionamiento::findOrFail($id_fraccionamiento);

        // Normalizar nombre del archivo: sin acentos, espacios → _, minúsculas
        $nombreNormalizado = Str::ascii($frac->nombre);           // Quita acentos
        $nombreNormalizado = strtolower($nombreNormalizado);
        $nombreNormalizado = preg_replace('/[^a-z0-9]+/', '_', $nombreNormalizado); // letras, números y _
        $nombreNormalizado = trim($nombreNormalizado, '_');

        $rutaArchivo = public_path("geojson/{$nombreNormalizado}.geojson");

        if (!file_exists($rutaArchivo)) {
            return response()->json([
                'success' => false,
                'message' => "Archivo no encontrado: geojson/{$nombreNormalizado}.geojson"
            ], 404);
        }

        $geojson = json_decode(file_get_contents($rutaArchivo), true);

        return response()->json([
            'success' => true,
            'fraccionamiento' => [
                'id'   => $frac->id_fraccionamiento,
                'nombre' => $frac->nombre,
            ],
            'geojson' => $geojson
        ]);
    }
}
