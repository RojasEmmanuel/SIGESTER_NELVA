<?php

namespace App\Http\Controllers\Admin;

use App\Models\Promocion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class AdminPromocionController extends Controller
{
    // === CREAR PROMOCIÓN (desde modal en fraccionamiento) ===
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'fraccionamientos' => 'required|array',
            'fraccionamientos.*' => 'exists:fraccionamientos,id_fraccionamiento',
        ]);

        $path = $request->file('imagen')->store('promociones', 'public');

        $promocion = Promocion::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'imagen_path' => $path,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
        ]);

        $promocion->fraccionamientos()->attach($request->fraccionamientos);

        // Redirigir al fraccionamiento (usa el primero o el actual)
        $fraccionamientoId = $request->fraccionamientos[0] ?? $request->input('current_fraccionamiento');

        return redirect()
            ->route('admin.fraccionamiento.show', $fraccionamientoId)
            ->with('success', 'Promoción creada exitosamente.')
            ->with('active_tab', 'promociones');
    }

    // === ACTUALIZAR PROMOCIÓN (desde modal) ===
    public function update(Request $request, Promocion $promocion)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'fraccionamientos' => 'required|array',
            'fraccionamientos.*' => 'exists:fraccionamientos,id_fraccionamiento',
        ]);

        $data = $request->only(['titulo', 'descripcion', 'fecha_inicio', 'fecha_fin']);

        if ($request->hasFile('imagen')) {
            if ($promocion->imagen_path) {
                Storage::disk('public')->delete($promocion->imagen_path);
            }
            $data['imagen_path'] = $request->file('imagen')->store('promociones', 'public');
        }

        $promocion->update($data);
        $promocion->fraccionamientos()->sync($request->fraccionamientos);

        // Redirigir al fraccionamiento actual
        $fraccionamientoId = $request->fraccionamientos[0] ?? $request->input('current_fraccionamiento');

        return redirect()
            ->route('admin.fraccionamiento.show', $fraccionamientoId)
            ->with('success', 'Promoción actualizada exitosamente.')
            ->with('active_tab', 'promociones');
    }

    public function destroy(Promocion $promocion)
    {
        if ($promocion->imagen_path) {
            Storage::disk('public')->delete($promocion->imagen_path);
        }

        $promocion->delete();

        // Volvemos al último fraccionamiento que el usuario estaba viendo
        $fraccionamientoId = session('current_fraccionamiento_id');

        if ($fraccionamientoId) {
            return redirect()
                ->route('admin.fraccionamiento.show', $fraccionamientoId)
                ->with('success', 'Promoción eliminada correctamente.')
                ->with('active_tab', 'promociones');
        }

        // Si por alguna razón no hay sesión, vamos a un lugar seguro
        return redirect()
            ->route('admin.index')
            ->with('info', 'Promoción eliminada.');
    }
}