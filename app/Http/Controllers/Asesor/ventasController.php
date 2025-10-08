<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venta;

class ventasController extends Controller
{
    public function index()
    {
        // Cargar las ventas con sus relaciones, incluyendo usuario en apartado
        $ventas = Venta::with([
            'apartado.lotesApartados',
            'apartado.usuario', // Cargar la relación con el modelo Usuario
            'clienteVenta',
            'credito'
        ])->paginate(10);

        // Calcular estadísticas
        $totalVentas = Venta::count();
        $liquidadas = Venta::where('estatus', 'liquidado')->count();
        $enPagos = Venta::where('estatus', 'pagos')->count();
        $retrasadas = Venta::where('estatus', 'retraso')->count();
        $canceladas = Venta::where('estatus', 'cancelado')->count();

        // Porcentajes
        $porcentajeLiquidadas = $totalVentas > 0 ? round(($liquidadas / $totalVentas) * 100) : 0;
        $porcentajeEnPagos = $totalVentas > 0 ? round(($enPagos / $totalVentas) * 100) : 0;
        $porcentajeCanceladas = $totalVentas > 0 ? round(($canceladas / $totalVentas) * 100) : 0;

        // Pasar los datos a la vista
        return view('asesor.ventas', compact(
            'ventas',
            'totalVentas',
            'liquidadas',
            'enPagos',
            'retrasadas',
            'canceladas',
            'porcentajeLiquidadas',
            'porcentajeEnPagos',
            'porcentajeCanceladas'
        ));
    }

    public function show($id_venta)
    {
        // Cargar la venta específica con sus relaciones
        $venta = Venta::with([
            'apartado.lotesApartados',
            'apartado.usuario', // Cargar la relación con el modelo Usuario
            'clienteVenta.contacto',
            'clienteVenta.direccion',
            'beneficiario',
            'credito'
        ])->findOrFail($id_venta);

        return view('asesor.ventas_detalle', compact('venta'));
    }
}
