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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdminFraccionamientoController extends Controller
{
    public function show($id)
    {
        try {
            $fraccionamiento = Fraccionamiento::with([
                'infoFraccionamiento',
                'amenidadesFraccionamiento',
                'galeria',
                'archivosFraccionamiento',
                'lotes'
            ])->findOrFail($id);

            $datosFraccionamiento = [
                'id' => $fraccionamiento->id_fraccionamiento,
                'nombre' => $fraccionamiento->nombre,
                'ubicacion' => $fraccionamiento->ubicacion,
                'path_imagen' => $fraccionamiento->path_imagen,
                'estatus' => $fraccionamiento->estatus,
                'descripcion' => $fraccionamiento->infoFraccionamiento->descripcion ?? null,
                'precio_metro_cuadrado' => $fraccionamiento->infoFraccionamiento->precio_metro_cuadrado ?? null,
                'tipo_propiedad' => $fraccionamiento->infoFraccionamiento->tipo_propiedad ?? null,
                'precioGeneral' => $fraccionamiento->infoFraccionamiento->precioGeneral ?? null,
                'ubicacionMaps' => $fraccionamiento->infoFraccionamiento->ubicacionMaps ?? null,
            ];

            $amenidades = $fraccionamiento->amenidadesFraccionamiento->map(function($amenidad) {
                return [
                    'id' => $amenidad->id_amenidad,
                    'nombre' => $amenidad->nombre,
                    'descripcion' => $amenidad->descripcion,
                    'tipo' => $amenidad->tipo,
                ];
            });

            $galeria = $fraccionamiento->galeria->map(function($foto) {
                return [
                    'id' => $foto->id_foto,
                    'nombre' => $foto->nombre,
                    'fotografia_path' => $foto->fotografia_path,
                    'fecha_subida' => $foto->fecha_subida->toDateTimeString(),
                ];
            });

            $archivos = $fraccionamiento->archivosFraccionamiento->map(function($archivo) {
                return [
                    'id' => $archivo->id_archivo,
                    'nombre_archivo' => $archivo->nombre_archivo,
                    'archivo_path' => $archivo->archivo_path,
                    'fecha_subida' => $archivo->fecha_subida->toDateTimeString(),
                ];
            });

            $totalLotes = $fraccionamiento->lotes->count();
            $lotesDisponibles = $fraccionamiento->lotes->where('estatus', 'disponible')->count();
            $lotesApartados = $fraccionamiento->lotes->whereIn('estatus', ['apartadoPalabra', 'apartadoDeposito'])->count();
            $lotesVendidos = $fraccionamiento->lotes->where('estatus', 'vendido')->count();

            return view('admin.fraccionamiento', compact(
                'datosFraccionamiento',
                'amenidades',
                'galeria',
                'archivos',
                'totalLotes',
                'lotesDisponibles',
                'lotesApartados',
                'lotesVendidos'
            ));

        } catch (\Exception $e) {
            Log::error("Error al cargar fraccionamiento: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar el fraccionamiento: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $fraccionamiento = Fraccionamiento::findOrFail($id);

            $data = $request->validate([
                'nombre' => 'required|string|max:255',
                'ubicacion' => 'required|string|max:255',
                'estatus' => 'required|boolean',
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

            return redirect()->back()->with('success_info', 'Información adicional actualizada correctamente.');

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

            return redirect()->back()->with('success_amenidad', 'Amenidad agregada correctamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("Error de validación al agregar amenidad: " . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors())->withInput()->with('error_amenidad', 'Error en la validación de la amenidad.');
        } catch (\Exception $e) {
            Log::error("Error al agregar amenidad: " . $e->getMessage());
            return redirect()->back()->with('error_amenidad', 'Error al agregar la amenidad: ' . $e->getMessage());
        }
    }

    public function deleteAmenidad($id, $amenidadId)
    {
        try {
            $amenidad = AmenidadFraccionamiento::where('id_fraccionamiento', $id)
                ->where('id_amenidad', $amenidadId)
                ->firstOrFail();
            $amenidad->delete();

            return redirect()->back()->with('success_amenidad', 'Amenidad eliminada correctamente.');

        } catch (\Exception $e) {
            Log::error("Error al eliminar amenidad: " . $e->getMessage());
            return redirect()->back()->with('error_amenidad', 'Error al eliminar la amenidad: ' . $e->getMessage());
        }
    }

    public function addFoto(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'nombre' => 'nullable|string|max:255',
                'fotografia_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $path = $request->file('fotografia_path')->store('galeria', 'public');
            Galeria::create([
                'id_fraccionamiento' => $id,
                'nombre' => $data['nombre'],
                'fotografia_path' => $path,
                'fecha_subida' => now(),
            ]);

            return redirect()->back()->with('success_foto', 'Foto agregada correctamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("Error de validación al agregar foto: " . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors())->withInput()->with('error_foto', 'Error en la validación de la foto.');
        } catch (\Exception $e) {
            Log::error("Error al agregar foto: " . $e->getMessage());
            return redirect()->back()->with('error_foto', 'Error al agregar la foto: ' . $e->getMessage());
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

            return redirect()->back()->with('success_foto', 'Foto eliminada correctamente.');

        } catch (\Exception $e) {
            Log::error("Error al eliminar foto: " . $e->getMessage());
            return redirect()->back()->with('error_foto', 'Error al eliminar la foto: ' . $e->getMessage());
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

            return redirect()->back()->with('success_archivo', 'Archivo agregado correctamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("Error de validación al agregar archivo: " . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors())->withInput()->with('error_archivo', 'Error en la validación del archivo.');
        } catch (\Exception $e) {
            Log::error("Error al agregar archivo: " . $e->getMessage());
            return redirect()->back()->with('error_archivo', 'Error al agregar el archivo: ' . $e->getMessage());
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

            return redirect()->back()->with('success_archivo', 'Archivo eliminado correctamente.');

        } catch (\Exception $e) {
            Log::error("Error al eliminar archivo: " . $e->getMessage());
            return redirect()->back()->with('error_archivo', 'Error al eliminar el archivo: ' . $e->getMessage());
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
                return redirect()->back()->with('error_archivo', 'El archivo no existe.');
            }

            return response()->download($path, $archivo->nombre_archivo ?? 'archivo.pdf');

        } catch (\Exception $e) {
            Log::error("Error al descargar archivo: " . $e->getMessage());
            return redirect()->back()->with('error_archivo', 'Error al descargar el archivo: ' . $e->getMessage());
        }
    }
}