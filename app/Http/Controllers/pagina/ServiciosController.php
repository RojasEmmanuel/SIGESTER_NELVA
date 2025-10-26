<?php

namespace App\Http\Controllers\pagina;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiciosController extends Controller
{
    public function index()
    {
        return view('pagina.servicios');
    }
}