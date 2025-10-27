<?php

namespace App\Http\Controllers\pagina;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactoController extends Controller
{
    public function index()
    {
        return view('pagina.contacto');
    }
}