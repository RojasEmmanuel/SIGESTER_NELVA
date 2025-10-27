<?php

namespace App\Http\Controllers\pagina;

use App\Models\Fraccionamiento;
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

        // Pasar los datos a la vista
        return view('pagina.inicio', [
            'fraccionamientos' => $fraccionamientos,
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
    
    public function Asesores()
    {
        return view('pagina.asesores', [
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