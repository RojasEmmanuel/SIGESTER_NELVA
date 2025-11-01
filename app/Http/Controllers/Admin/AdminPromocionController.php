<?php

namespace App\Http\Controllers\Admin;

use App\Models\Promocion;
use App\Models\Fraccionamiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class AdminPromocionController extends Controller
{
    public function index()
    {
        $hoy = Carbon::now();

        $promociones = Promocion::where('fecha_inicio', '<=', $hoy)
            ->where(function ($query) use ($hoy) {
                $query->whereNull('fecha_fin')
                      ->orWhere('fecha_fin', '>=', $hoy);
            })
            ->with('fraccionamiento')
            ->orderBy('fecha_inicio', 'desc')
            ->paginate(10);

        return view('admin.promociones.index', compact('promociones'));
    }

    public function create()
    {
        $fraccionamientos = Fraccionamiento::where('estatus', 1)->get();
        return view('admin.promociones.create', compact('fraccionamientos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'id_fraccionamiento' => 'required|exists:fraccionamientos,id_fraccionamiento',
        ]);

        $path = $request->file('imagen')->store('promociones', 'public');

        Promocion::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'imagen_path' => $path,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'id_fraccionamiento' => $request->id_fraccionamiento,
        ]);

        $fraccionamientoId = $request->id_fraccionamiento;

        return redirect()
            ->route('admin.fraccionamiento.show', $fraccionamientoId)
            ->with('success', 'Promoción creada exitosamente.')
            ->with('active_tab', 'promociones');
    }

    public function show(Promocion $promocion)
    {
        $promocion->load('fraccionamiento');
        return view('admin.promociones.show', compact('promocion'));
    }

    
    public function update(Request $request, Promocion $promocion)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        $data = $request->only(['titulo', 'descripcion', 'fecha_inicio', 'fecha_fin']);

        if ($request->hasFile('imagen')) {
            if ($promocion->imagen_path) {
                Storage::disk('public')->delete($promocion->imagen_path);
            }
            $data['imagen_path'] = $request->file('imagen')->store('promociones', 'public');
        }

        $promocion->update($data);

        return redirect()
            ->route('admin.fraccionamiento.show', $promocion->id_fraccionamiento)
            ->with('success', 'Promoción actualizada exitosamente.')
            ->with('active_tab', 'promociones');
    }

    public function destroy(Promocion $promocion)
    {
        $fraccionamientoId = $promocion->id_fraccionamiento;

        if ($promocion->imagen_path) {
            Storage::disk('public')->delete($promocion->imagen_path);
        }

        $promocion->delete();

        return redirect()
            ->route('admin.fraccionamiento.show', $fraccionamientoId)
            ->with('success', 'Promoción eliminada exitosamente.')
            ->with('active_tab', 'promociones');
    }
}