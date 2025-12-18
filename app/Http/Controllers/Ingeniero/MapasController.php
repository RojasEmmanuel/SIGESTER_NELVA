<?php

namespace App\Http\Controllers\Ingeniero;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fraccionamiento;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class MapasController extends Controller
{
    /**
     * Vista principal con selector de fraccionamientos
     */
    public function index()
    {
        $fraccionamientos = Fraccionamiento::select('id_fraccionamiento', 'nombre', 'tiene_geojson')
            ->where('tiene_geojson', false)
            ->orderBy('nombre', 'asc')
            ->get();

        return view('ingeniero.mapa-fraccionamientos', compact('fraccionamientos'));
    }

    /**
     * Ruta: GET /ing/fraccionamiento/{id}/geojson-data
     * Usado por el editor del ingeniero y también por la vista pública
     */
    public function getGeoJSONData($id)
    {
        $frac = Fraccionamiento::findOrFail($id);

        // Si el campo dice que NO tiene GeoJSON → respuesta inmediata
        if (!$frac->tiene_geojson) {
            return response()->json([
                'success' => true,
                'fraccionamiento' => [
                    'id' => $frac->id_fraccionamiento,
                    'nombre' => $frac->nombre,
                ],
                'geojson' => null
            ]);
        }

        // Si dice que SÍ tiene → buscamos el archivo con el formato actual
        $slug = Str::slug($frac->nombre, '_');
        $filename = strtolower($slug) . '.geojson';
        $filepath = public_path("geojson/{$filename}");

        if (File::exists($filepath)) {
            $geojson = json_decode(File::get($filepath), true);

            return response()->json([
                'success' => true,
                'fraccionamiento' => [
                    'id' => $frac->id_fraccionamiento,
                    'nombre' => $frac->nombre,
                ],
                'geojson' => $geojson,
                'archivo' => $filename
            ]);
        }

        // Inconsistencia: el campo dice true pero el archivo no existe → corregimos
        $frac->tiene_geojson = false;
        $frac->save();

        return response()->json([
            'success' => true,
            'fraccionamiento' => [
                'id' => $frac->id_fraccionamiento,
                'nombre' => $frac->nombre,
            ],
            'geojson' => null
        ]);
    }

    /**
     * Ruta: POST /ing/fraccionamiento/save-geojson
     * Guardar desde el editor del ingeniero
     */
    public function saveGeoJSON(Request $request)
    {
        $request->validate([
            'id_fraccionamiento' => 'required|exists:fraccionamientos,id_fraccionamiento',
            'geojson'            => 'required|json',
        ]);

        $frac = Fraccionamiento::findOrFail($request->id_fraccionamiento);

        $slug = Str::slug($frac->nombre, '_');
        $filename = strtolower($slug) . '.geojson';
        $path = public_path('geojson');

        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        File::put($path . '/' . $filename, json_encode($request->geojson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // MARCAMOS QUE SÍ TIENE GEOJSON
        $frac->tiene_geojson = true;
        $frac->save();

        return response()->json([
            'success' => true,
            'message' => 'Plano interactivo guardado correctamente',
            'archivo' => $filename
        ]);
    }

    // Puedes eliminar estos métodos si ya no los usas:
    // public function geojsonData()  → formato antiguo, ya no se llama
    // public function saveGeoJSON() sin id → versión vieja
}