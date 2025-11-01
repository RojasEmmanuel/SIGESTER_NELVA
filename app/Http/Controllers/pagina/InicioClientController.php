<?php

namespace App\Http\Controllers\pagina;

use App\Models\Fraccionamiento;
use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class InicioClientController extends Controller
{
    public function index()
    {
        // Obtener fraccionamientos activos agrupados por zona
        $fraccionamientos = Fraccionamiento::where('estatus', 1)
            ->select('id_fraccionamiento', 'nombre', 'ubicacion', 'path_imagen', 'zona')
            ->get()
            ->groupBy('zona');

        // Obtener promociones activas de los fraccionamientos basadas en fechas
        $promocionesActivas = Fraccionamiento::where('estatus', 1)
            ->whereHas('promociones', function($query) {
                $query->where('fecha_inicio', '<=', now())
                    ->where(function($q) {
                        $q->where('fecha_fin', '>=', now())
                            ->orWhere('fecha_fin', null);
                    });
            })
            ->with(['promociones' => function($query) {
                $query->where('fecha_inicio', '<=', now())
                    ->where(function($q) {
                        $q->where('fecha_fin', '>=', now())
                            ->orWhere('fecha_fin', null);
                    })
                    ->select('id_promocion', 'id_fraccionamiento', 'titulo', 'descripcion', 'imagen_path', 'fecha_inicio', 'fecha_fin');
            }])
            ->get()
            ->filter(function($fraccionamiento) {
                return $fraccionamiento->promociones->count() > 0;
            });

        // Pasar los datos a la vista
        return view('pagina.inicio', [
            'fraccionamientos' => $fraccionamientos,
            'promocionesActivas' => $promocionesActivas,
            'title' => 'Nelva Bienes Raíces'
        ]);
    }    
    public function Atractivos()
    {
        return view('pagina.atractivos', [
            'title' => 'Atractivos - Nelva Bienes Raíces'
        ]);
    }   

    public function Servicios()
    {
        return view('pagina.servicios', [
            'title' => 'Servicios - Nelva Bienes Raíces'
        ]);
    }
    
   // Método para la página de asesores
    public function asesores()
    {
        $asesores = Usuario::where('estatus', 1)
            ->has('asesorInfo') 
            ->with('asesorInfo') 
            ->get()
            ->map(function ($usuario) {
                return [
                    'nombre' => $usuario->nombre,
                    'apellido_paterno' => $usuario->apellido_paterno ?? '',
                    'apellido_materno' => $usuario->apellido_materno ?? '',
                    'telefono' => $usuario->telefono,
                    'zona' => $usuario->asesorInfo->zona,
                    'facebook_url' => $usuario->asesorInfo->path_facebook,
                    'foto_url' => $usuario->asesorInfo->path_fotografia ? asset('storage/' . $usuario->asesorInfo->path_fotografia) : null,
                ];
            });

        return view('pagina.asesores', [
            'asesores' => $asesores,
            'title' => 'Asesores - Nelva Bienes Raíces'
        ]);
    }

    public function MapaInteractivo()
    {
        return view('pagina.mapaInteractivo', [
            'title' => 'Mapa Interactivo - Nelva Bienes Raíces'
        ]);
    }
    
    public function Contacto()
    {
        return view('pagina.contacto', [
            'title' => 'Contacto - Nelva Bienes Raíces'
        ]);
    }

    public function Mas()
    {
        return view('pagina.mas', [
            'title' => 'Más - Nelva Bienes Raíces'
        ]);
    }

    public function Nosotros()
    {
        return view('pagina.nosotros', [
            'title' => 'Nosotros - Nelva Bienes Raíces'
        ]);
    }

    public function mazunte()
    {
        return view('inversiones.mazunte', [
            'title' => 'Mazunte - Nelva Bienes Raíces'
        ]);
    }

    public function salinaCruz()
    {
        return view('inversiones.salinaCruz', [
            'title' => 'Salina Cruz - Nelva Bienes Raíces'
        ]);
    }

    public function tonameca()
    {
        return view('inversiones.tonameca', [
            'title' => 'Tonameca - Nelva Bienes Raíces'
        ]);
    }
}