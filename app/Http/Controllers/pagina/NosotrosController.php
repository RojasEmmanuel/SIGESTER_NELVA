<?php

namespace App\Http\Controllers\pagina;

use App\Http\Controllers\Controller; // Añadir esta línea
use Illuminate\Http\Request;

class NosotrosController extends Controller
{
    public function index()
    {
        return view('pagina.nosotros');
    }
}