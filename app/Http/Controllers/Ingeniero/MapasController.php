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

    public function getGeoJSONData($id)
    {
        $frac = \App\Models\Fraccionamiento::findOrFail($id);
        $nombre = $frac->nombre;
        
        // Intentar con guiones bajos (nuevo formato)
        $slugUnderscore = Str::slug($nombre, '_');
        $pathUnderscore = public_path("geojson/{$slugUnderscore}.geojson");
        
        // Intentar con guiones medios (formato anterior)
        $slugDash = Str::slug($nombre);
        $pathDash = public_path("geojson/{$slugDash}.geojson");

        $path = null;
        $archivo = null;

        if (File::exists($pathUnderscore)) {
            $path = $pathUnderscore;
            $archivo = "{$slugUnderscore}.geojson";
        } elseif (File::exists($pathDash)) {
            $path = $pathDash;
            $archivo = "{$slugDash}.geojson";
        }

        if ($path && File::exists($path)) {
            $geojson = json_decode(File::get($path), true);
            return response()->json([
                'success' => true,
                'fraccionamiento' => [
                    'id' => $frac->id_fraccionamiento,
                    'nombre' => $nombre
                ],
                'geojson' => $geojson,
                'archivo' => $archivo
            ]);
        }

        // No existe → devolvemos null para que abra el modal
        return response()->json([
            'success' => true,
            'fraccionamiento' => [
                'id' => $frac->id_fraccionamiento,
                'nombre' => $nombre
            ],
            'geojson' => null
        ]);
    }

    public function saveGeoJSON(Request $request)
    {
        $request->validate([
            'geojson' => 'required',
            'nombre'  => 'required|string'
        ]);

        // Convertir nombre a minúsculas y reemplazar espacios por guiones bajos
        $nombreSlug = Str::slug($request->nombre, '_'); // El segundo parámetro define el separador
        $nombreSlug = strtolower($nombreSlug); // Asegurar que esté en minúsculas
        
        $path = public_path('geojson');

        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        $archivo = "{$nombreSlug}.geojson";
        File::put($path . '/' . $archivo, json_encode($request->geojson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return response()->json([
            'success' => true,
            'archivo' => $archivo,
            'message' => 'GeoJSON guardado correctamente'
        ]);
    }

}
