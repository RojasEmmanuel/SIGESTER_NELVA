<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApartadoController extends Controller
{
    public function index()
    {
        return view('asesor.apartados');
    }
}
