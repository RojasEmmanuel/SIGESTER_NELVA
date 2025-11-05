<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fraccionamiento;
use App\Models\InfoFraccionamiento;
use App\Models\AmenidadFraccionamiento;
use App\Models\Galeria;
use App\Models\ArchivosFraccionamiento;
use App\Models\Lote;
use App\Models\Zona;
use App\Models\LoteZona;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AdminFraccionamientoController extends Controller
{
    public function create()
    {
        return view('admin.fraccionamiento-create');
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            Log::info('Intentando crear nuevo fraccionamiento', [
                'has_file' => $request->hasFile('path_imagen'),
                'file_details' => $request->hasFile('path_imagen') ? [
                    'name' => $request->file('path_imagen')->getClientOriginalName(),
                    'extension' => $request->file('path_imagen')->getClientOriginalExtension(),
                    'size' => $request->file('path_imagen')->getSize(),
                    'mime' => $request->file('path_imagen')->getMimeType(),
                ] : 'No file uploaded'
            ]);

            $data = $request->validate([
                // Campos para Fraccionamiento
                'nombre' => 'required|string|max:255',
                'ubicacion' => 'required|string|max:255',
                'estatus' => 'required|boolean',
                'zona' => 'required|in:costa,istmo',
                'path_imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                
                // Campos para InfoFraccionamiento
                'descripcion' => 'nullable|string',
                'precio_metro_cuadrado' => 'nullable|numeric|min:0',
                'tipo_propiedad' => 'nullable|string|max:255',
                'precioGeneral' => 'nullable|numeric|min:0',
                'ubicacionMaps' => 'nullable|string',
            ]);

            // Manejo de la imagen principal
            $pathImagen = null;
            if ($request->hasFile('path_imagen') && $request->file('path_imagen')->isValid()) {
                $pathImagen = $request->file('path_imagen')->store('fraccionamientos', 'public');
                Log::info("Imagen principal guardada en: storage/$pathImagen");
            }

            // Crear Fraccionamiento
            $fraccionamiento = Fraccionamiento::create([
                'nombre' => $data['nombre'],
                'ubicacion' => $data['ubicacion'],
                'path_imagen' => $pathImagen,
                'estatus' => $data['estatus'],
                'zona' => $data['zona'],
            ]);

            // Crear InfoFraccionamiento asociada
            InfoFraccionamiento::create([
                'id_fraccionamiento' => $fraccionamiento->id_fraccionamiento,
                'descripcion' => $data['descripcion'],
                'precio_metro_cuadrado' => $data['precio_metro_cuadrado'],
                'tipo_propiedad' => $data['tipo_propiedad'],
                'precioGeneral' => $data['precioGeneral'],
                'ubicacionMaps' => $data['ubicacionMaps'],
            ]);

            // ==================== NUEVO: CREAR ZONAS ====================
            if ($request->filled('agregar_zonas') && $request->agregar_zonas == '1' && $request->has('zonas')) {
                $request->validate([
                    'zonas.*.nombre'     => 'required|string|max:100',
                    'zonas.*.precio_m2'  => 'required|numeric|min:0',
                ]);

                Log::info('Creando zonas para fraccionamiento', [
                    'id_fraccionamiento' => $fraccionamiento->id_fraccionamiento,
                    'cantidad_zonas' => count($request->zonas)
                ]);

                foreach ($request->zonas as $index => $zonaData) {
                    Zona::create([
                        'nombre'             => $zonaData['nombre'],
                        'precio_m2'          => $zonaData['precio_m2'],
                        'id_fraccionamiento' => $fraccionamiento->id_fraccionamiento,
                    ]);
                }

                Log::info('Zonas creadas exitosamente', ['total' => count($request->zonas)]);
            }

            DB::commit();

            return redirect()->route('admin.fraccionamiento.show', $fraccionamiento->id_fraccionamiento)
                             ->with('success', 'Fraccionamiento creado correctamente. Ahora puedes agregar amenidades, fotos y archivos.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error("Error de validación al crear fraccionamiento: " . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($pathImagen) && Storage::disk('public')->exists($pathImagen)) {
                Storage::disk('public')->delete($pathImagen);
            }
            Log::error("Error al crear fraccionamiento: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error al crear el fraccionamiento: ' . $e->getMessage());
        }
    }

    
    public function show($id)
    {
        try {
            // === CARGAR TODAS LAS RELACIONES ===
            $fraccionamiento = Fraccionamiento::with([
                'infoFraccionamiento',
                'amenidadesFraccionamiento',
                'galeria',
                'archivosFraccionamiento',
                'lotes',
                'promociones.fraccionamientos' // ← Carga fraccionamientos asociados a cada promoción
            ])->findOrFail($id);

            // === DATOS BÁSICOS DEL FRACCIONAMIENTO ===
            $datosFraccionamiento = [
                'id' => $fraccionamiento->id_fraccionamiento,
                'nombre' => $fraccionamiento->nombre,
                'ubicacion' => $fraccionamiento->ubicacion,
                'path_imagen' => $fraccionamiento->path_imagen,
                'estatus' => $fraccionamiento->estatus,
                'zona' => $fraccionamiento->zona,
                'descripcion' => $fraccionamiento->infoFraccionamiento?->descripcion,
                'precio_metro_cuadrado' => $fraccionamiento->infoFraccionamiento?->precio_metro_cuadrado,
                'tipo_propiedad' => $fraccionamiento->infoFraccionamiento?->tipo_propiedad,
                'precioGeneral' => $fraccionamiento->infoFraccionamiento?->precioGeneral,
                'ubicacionMaps' => $fraccionamiento->infoFraccionamiento?->ubicacionMaps,
            ];

            // === AMENIDADES ===
            $amenidades = $fraccionamiento->amenidadesFraccionamiento->map(fn($a) => [
                'id' => $a->id_amenidad,
                'nombre' => $a->nombre,
                'descripcion' => $a->descripcion,
                'tipo' => $a->tipo,
            ]);

            // === GALERÍA ===
            $galeria = $fraccionamiento->galeria->map(fn($f) => [
                'id' => $f->id_foto,
                'nombre' => $f->nombre,
                'fotografia_path' => $f->fotografia_path,
                'fecha_subida' => $f->fecha_subida->toDateTimeString(),
            ]);

            // === ARCHIVOS ===
            $archivos = $fraccionamiento->archivosFraccionamiento->map(fn($a) => [
                'id' => $a->id_archivo,
                'nombre_archivo' => $a->nombre_archivo,
                'archivo_path' => $a->archivo_path,
                'fecha_subida' => $a->fecha_subida->toDateTimeString(),
            ]);

            // ZONAS DEL FRACCIONAMIENTO
            $zonas = $fraccionamiento->zonas->map(fn($z) =>
                [
                    'id' => $z->id_zona,
                    'nombre' => $z->nombre,
                    'precio_m2' => $z->precio_m2,
                ]
            );


            // En tu método show() o en un nuevo método adminShow()
            $lotesSinZona = Lote::where('id_fraccionamiento', $id)
                ->whereDoesntHave('loteZona') // ← Lotes que NO tienen zona asignada
                ->get()
                ->map(fn($l) => [
                    'id' => $l->id_lote,
                    'numero' => $l->numero_lote ?? "Lote #{$l->id_lote}",
            ]);


            // === ESTADÍSTICAS DE LOTES ===
            $lotes = $fraccionamiento->lotes;
            $totalLotes = $lotes->count();
            $lotesDisponibles = $lotes->where('estatus', 'disponible')->count();
            $lotesApartados = $lotes->whereIn('estatus', ['apartadoPalabra', 'apartadoDeposito'])->count();
            $lotesVendidos = $lotes->where('estatus', 'vendido')->count();

            // === PROMOCIONES (SIN MAP → USA ELOQUENT) ===
           // === PROMOCIONES (CORREGIDO) ===
            $hoy = \Carbon\Carbon::now();

            $promociones = $fraccionamiento->promociones()
                ->with('fraccionamientos') // ← Carga fraccionamientos
                ->orderBy('fecha_inicio', 'desc')
                ->get()
                
                ->values(); // ← ¡IMPORTANTE! Reindexa la colección

            // === RETORNAR VISTA ===
            return view('admin.fraccionamiento', compact(
                'datosFraccionamiento',
                'amenidades',
                'galeria',
                'archivos',
                'totalLotes',
                'lotesDisponibles',
                'lotesApartados',
                'lotesVendidos',
                'promociones',
                'zonas',
                'lotesSinZona'
            ));

        } catch (\Exception $e) {
            Log::error("Error al cargar fraccionamiento ID {$id}: " . $e->getMessage());
            return redirect()->route('admin.inicio')
                ->with('error', 'No se pudo cargar el fraccionamiento. Intenta de nuevo.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $fraccionamiento = Fraccionamiento::findOrFail($id);

            Log::info('Intentando actualizar fraccionamiento', [
                'id_fraccionamiento' => $id,
                'has_file' => $request->hasFile('path_imagen'),
                'file_details' => $request->hasFile('path_imagen') ? [
                    'name' => $request->file('path_imagen')->getClientOriginalName(),
                    'extension' => $request->file('path_imagen')->getClientOriginalExtension(),
                    'size' => $request->file('path_imagen')->getSize(),
                    'mime' => $request->file('path_imagen')->getMimeType(),
                ] : 'No file uploaded'
            ]);

            $data = $request->validate([
                'nombre' => 'required|string|max:255',
                'ubicacion' => 'required|string|max:255',
                'estatus' => 'required|boolean',
                'zona' => 'required|in:costa,istmo',
                'path_imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($request->hasFile('path_imagen') && $request->file('path_imagen')->isValid()) {
                if ($fraccionamiento->path_imagen && Storage::disk('public')->exists($fraccionamiento->path_imagen)) {
                    Storage::disk('public')->delete($fraccionamiento->path_imagen);
                }
                $path = $request->file('path_imagen')->store('fraccionamientos', 'public');
                $data['path_imagen'] = $path;
                Log::info("Imagen guardada en: storage/$path");
            }

            $fraccionamiento->update($data);

            return redirect()->back()->with('success', 'Fraccionamiento actualizado correctamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("Error de validación al actualizar fraccionamiento: " . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error("Error al actualizar fraccionamiento: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error al actualizar el fraccionamiento: ' . $e->getMessage());
        }
    }

    public function updateInfo(Request $request, $id)
    {
        try {
            $fraccionamiento = Fraccionamiento::findOrFail($id);
            $infoFraccionamiento = $fraccionamiento->infoFraccionamiento ?? new InfoFraccionamiento(['id_fraccionamiento' => $id]);

            $data = $request->validate([
                'descripcion' => 'nullable|string',
                'precio_metro_cuadrado' => 'nullable|numeric|min:0',
                'tipo_propiedad' => 'nullable|string|max:255',
                'precioGeneral' => 'nullable|numeric|min:0',
                'ubicacionMaps' => 'nullable|string',
            ]);

            $infoFraccionamiento->fill($data)->save();

            return redirect()->back()->with('success_info', 'Información adicional actualizada correctamente.')->with('active_tab', 'basic-info');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("Error de validación al actualizar info fraccionamiento: " . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors())->withInput()->with('error_info', 'Error en la validación de la información.');
        } catch (\Exception $e) {
            Log::error("Error al actualizar info fraccionamiento: " . $e->getMessage());
            return redirect()->back()->with('error_info', 'Error al actualizar la información: ' . $e->getMessage());
        }
    }

    public function addAmenidad(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
                'tipo' => 'nullable|string|max:255',
            ]);

            AmenidadFraccionamiento::create(array_merge($data, ['id_fraccionamiento' => $id]));

            return redirect()->back()->with('success_amenidad', 'Amenidad agregada correctamente.')->with('active_tab', 'amenities');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("Error de validación al agregar amenidad: " . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors())->withInput()->with('error_amenidad', 'Error en la validación de la amenidad.')->with('active_tab', 'amenities');
        } catch (\Exception $e) {
            Log::error("Error al agregar amenidad: " . $e->getMessage());
            return redirect()->back()->with('error_amenidad', 'Error al agregar la amenidad: ' . $e->getMessage())->with('active_tab', 'amenities');
        }
    }

    public function deleteAmenidad($id, $amenidadId)
    {
        try {
            $amenidad = AmenidadFraccionamiento::where('id_fraccionamiento', $id)
                ->where('id_amenidad', $amenidadId)
                ->firstOrFail();
            $amenidad->delete();

            return redirect()->back()->with('success_amenidad', 'Amenidad eliminada correctamente.')->with('active_tab', 'amenities');

        } catch (\Exception $e) {
            Log::error("Error al eliminar amenidad: " . $e->getMessage());
            return redirect()->back()->with('error_amenidad', 'Error al eliminar la amenidad: ' . $e->getMessage())->with('active_tab', 'amenities');
        }
    }

    public function addFoto(Request $request, $id)
    {
        try {
            Log::info('Intentando subir foto', [
                'id_fraccionamiento' => $id,
                'has_file' => $request->hasFile('fotografia_path'),
                'file_details' => $request->hasFile('fotografia_path') ? [
                    'name' => $request->file('fotografia_path')->getClientOriginalName(),
                    'extension' => $request->file('fotografia_path')->getClientOriginalExtension(),
                    'size' => $request->file('fotografia_path')->getSize(),
                    'mime' => $request->file('fotografia_path')->getMimeType(),
                ] : 'No file uploaded'
            ]);

            $data = $request->validate([
                'nombre' => 'nullable|string|max:255',
                'fotografia_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $path = $request->file('fotografia_path')->store('galeria', 'public');
            Log::info('Foto guardada en', ['path' => $path]);

            Galeria::create([
                'id_fraccionamiento' => $id,
                'nombre' => $data['nombre'],
                'fotografia_path' => $path,
                'fecha_subida' => now(),
            ]);

            return redirect()->back()->with('success_foto', 'Foto agregada correctamente.')->with('active_tab', 'gallery');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("Error de validación al agregar foto: " . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors())->withInput()->with('error_foto', 'Error en la validación de la foto.')->with('active_tab', 'gallery');
        } catch (\Exception $e) {
            Log::error("Error al agregar foto: " . $e->getMessage());
            return redirect()->back()->with('error_foto', 'Error al agregar la foto: ' . $e->getMessage())->with('active_tab', 'gallery');
        }
    }

    public function deleteFoto($id, $fotoId)
    {
        try {
            $foto = Galeria::where('id_fraccionamiento', $id)
                ->where('id_foto', $fotoId)
                ->firstOrFail();

            if ($foto->fotografia_path && Storage::disk('public')->exists($foto->fotografia_path)) {
                Storage::disk('public')->delete($foto->fotografia_path);
            }

            $foto->delete();

            return redirect()->back()->with('success_foto', 'Foto eliminada correctamente.')->with('active_tab', 'gallery');

        } catch (\Exception $e) {
            Log::error("Error al eliminar foto: " . $e->getMessage());
            return redirect()->back()->with('error_foto', 'Error al eliminar la foto: ' . $e->getMessage())->with('active_tab', 'gallery');
        }
    }

    public function addArchivo(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'nombre_archivo' => 'nullable|string|max:255',
                'archivo_path' => 'required|file|mimes:pdf|max:5120',
            ]);

            $path = $request->file('archivo_path')->store('archivos', 'public');
            ArchivosFraccionamiento::create([
                'id_fraccionamiento' => $id,
                'nombre_archivo' => $data['nombre_archivo'],
                'archivo_path' => $path,
                'fecha_subida' => now(),
            ]);

            return redirect()->back()->with('success_archivo', 'Archivo agregado correctamente.')->with('active_tab', 'files');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("Error de validación al agregar archivo: " . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors())->withInput()->with('error_archivo', 'Error en la validación del archivo.')->with('active_tab', 'files');
        } catch (\Exception $e) {
            Log::error("Error al agregar archivo: " . $e->getMessage());
            return redirect()->back()->with('error_archivo', 'Error al agregar el archivo: ' . $e->getMessage())->with('active_tab', 'files');
        }
    }

    public function deleteArchivo($id, $archivoId)
    {
        try {
            $archivo = ArchivosFraccionamiento::where('id_fraccionamiento', $id)
                ->where('id_archivo', $archivoId)
                ->firstOrFail();

            if ($archivo->archivo_path && Storage::disk('public')->exists($archivo->archivo_path)) {
                Storage::disk('public')->delete($archivo->archivo_path);
            }

            $archivo->delete();

            return redirect()->back()->with('success_archivo', 'Archivo eliminado correctamente.')->with('active_tab', 'files');

        } catch (\Exception $e) {
            Log::error("Error al eliminar archivo: " . $e->getMessage());
            return redirect()->back()->with('error_archivo', 'Error al eliminar el archivo: ' . $e->getMessage())->with('active_tab', 'files');
        }
    }

    public function downloadArchivo($id, $archivoId)
    {
        try {
            $archivo = ArchivosFraccionamiento::where('id_fraccionamiento', $id)
                ->where('id_archivo', $archivoId)
                ->firstOrFail();

            $path = storage_path('app/public/' . $archivo->archivo_path);

            if (!file_exists($path)) {
                Log::error("El archivo no existe en la ruta: " . $path);
                return redirect()->back()->with('error_archivo', 'El archivo no existe.');
            }

            // Asegurar que el nombre del archivo termine en .pdf
            $nombreArchivo = $archivo->nombre_archivo ?? 'archivo';
            if (!Str::endsWith($nombreArchivo, '.pdf')) {
                $nombreArchivo .= '.pdf';
            }

            return response()->download($path, $nombreArchivo, ['Content-Type' => 'application/pdf']);
        } catch (\Exception $e) {
            Log::error("Error al descargar archivo: " . $e->getMessage());
            return redirect()->back()->with('error_archivo', 'Error al descargar el archivo: ' . $e->getMessage());
        }
    }


    public function showFraccionamiento($id)
    {
        try {
            // Obtener el fraccionamiento con todas las relaciones necesarias
            $fraccionamiento = Fraccionamiento::with([
                'infoFraccionamiento',
                'planosFraccionamiento',
                'amenidadesFraccionamiento',
                'lotes',
                'galeria', // Nueva relación para galería
                'archivosFraccionamiento' // Nueva relación para archivos
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

            // Obtener estadísticas de lotes
            $totalLotes = $fraccionamiento->lotes->count();
            $lotesDisponibles = $fraccionamiento->lotes->where('estatus', 'disponible')->count();
            $lotesApartadosPalabra = $fraccionamiento->lotes->where('estatus', 'apartadoPalabra')->count();
            $lotesApartadosVendido = $fraccionamiento->lotes->where('estatus', 'apartadoDeposito')->count();
            $lotesVendidos = $fraccionamiento->lotes->where('estatus', 'vendido')->count();
            
            $lotesApartados = $lotesApartadosPalabra + $lotesApartadosVendido;

            return view('admin.fraccionamientoIndex', compact(
                'datosFraccionamiento',
                'planos',
                'amenidades',
                'galeria', // Nueva variable para la vista
                'archivos', // Nueva variable para la vista
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


    /**
     * Actualizar una zona existente
     */
    public function updateZona(Request $request, $id, $zonaId)
    {
        try {
            DB::beginTransaction();

            $data = $request->validate([
                'nombre' => 'required|string|max:255',
                'precio_m2' => 'required|numeric|min:0',
            ]);

            Log::info('Actualizando zona del fraccionamiento', [
                'id_fraccionamiento' => $id,
                'id_zona' => $zonaId,
                'nombre' => $data['nombre'],
                'precio_m2' => $data['precio_m2']
            ]);

            // Buscar y actualizar la zona
            $zona = Zona::where('id_zona', $zonaId)
                        ->where('id_fraccionamiento', $id)
                        ->firstOrFail();

            $zona->update([
                'nombre' => $data['nombre'],
                'precio_m2' => $data['precio_m2'],
            ]);

            DB::commit();

            return redirect()
                ->route('admin.fraccionamiento.show', $id)
                ->with('success', 'Zona actualizada correctamente')
                ->with('active_tab', 'basic-info');

                
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error("Error de validación al actualizar zona: " . json_encode($e->errors()));
            return redirect()
                ->route('admin.fraccionamiento.show', $id)
                ->withErrors($e->validator)
                ->with('active_tab', 'basic-info')
                ->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al actualizar zona: " . $e->getMessage());
            return redirect()
                ->route('admin.fraccionamiento.show', $id)
                ->with('error_zona', 'Error al actualizar la zona: ' . $e->getMessage())
                ->with('active_tab', 'basic-info');
        }
    }




    public function asignarLotesAZona(Request $request, $id)
    {
        $request->validate([
            'id_zona' => 'required|exists:zonas,id_zona',
            'lotes'   => 'required|array',
            'lotes.*' => 'exists:lotes,id_lote'
        ]);

        $asignados = 0;
        foreach ($request->lotes as $id_lote) {
            LoteZona::create([
                'id_lote' => $id_lote,
                'id_zona' => $request->id_zona,
            ]);
            $asignados++;
        }

        return back()->with('success', "¡$asignados lote(s) asignados correctamente a la zona!");
    }
}