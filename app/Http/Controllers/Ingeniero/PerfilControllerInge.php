<?php

namespace App\Http\Controllers\Ingeniero;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use App\Models\AsesorInfo;

class PerfilControllerInge extends Controller
{
    /**
     * Mostrar la vista del perfil
     */
    public function index()
    {
        $usuario = Auth::user();

        // Cargar también la info del asesor relacionada
        $asesorInfo = AsesorInfo::where('id_usuario', $usuario->id_usuario)->first();

        return view('asesor.perfil', compact('usuario', 'asesorInfo'));
    }

    /**
     * Actualizar la información del perfil
     */
    public function update(Request $request)
    {
        $usuario = Auth::user();

        // Validación
        $request->validate([
            'nombre'          => 'required|string|max:255',
            'usuario_nombre'  => 'required|string|max:255|unique:usuarios,usuario_nombre,' . $usuario->id_usuario . ',id_usuario',
            'telefono'        => 'nullable|string|max:20',
            'email'           => 'required|email|max:255|unique:usuarios,email,' . $usuario->id_usuario . ',id_usuario',
            'path_facebook'   => 'nullable|url|max:255',
            'foto'            => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        // Actualizar datos de Usuario
        $usuario->update($request->only(['nombre', 'usuario_nombre', 'telefono', 'email']));

        // Obtener o crear registro de AsesorInfo
        $asesorInfo = AsesorInfo::firstOrNew(['id_usuario' => $usuario->id_usuario]);
        $asesorInfo->path_facebook = $request->path_facebook;

        // Manejar foto de perfil
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('FotosAsesores', 'public');
            $asesorInfo->path_fotografia = $path; // Guardar solo la ruta
        }

        $asesorInfo->save();

        return redirect()->route('ing.perfil.index')->with('success', 'Perfil actualizado correctamente.');
    }
}
