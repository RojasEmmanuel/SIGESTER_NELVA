<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\Venta;
use App\Models\Apartado;
use App\Models\LoteApartado;
use App\Models\ClienteVenta;
use App\Models\ClienteContacto;
use App\Models\ClienteDireccion;
use App\Models\BeneficiarioClienteVenta;
use App\Models\Credito;
use App\Models\Usuario;
use App\Models\Lote; // Asumiendo que existe un modelo Lote
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class ventasController extends Controller
{
    public function index()
{
    // Obtener el asesor en sesión
    $usuarioId = Auth::user()->id_usuario;

    // Consultar solo las ventas cuyos apartados pertenecen al asesor en sesión
    $ventas = Venta::whereHas('apartado', function ($query) use ($usuarioId) {
            $query->where('id_usuario', $usuarioId);
        })
        ->with([
            'apartado.lotesApartados',
            'apartado.usuario',
            'clienteVenta',
            'credito'
        ])
        ->paginate(10);

    // Calcular estadísticas filtradas solo por el asesor en sesión
    $totalVentas = Venta::whereHas('apartado', function ($query) use ($usuarioId) {
        $query->where('id_usuario', $usuarioId);
    })->count();

    $liquidadas = Venta::whereHas('apartado', function ($query) use ($usuarioId) {
        $query->where('id_usuario', $usuarioId);
    })->where('estatus', 'liquidado')->count();

    $enPagos = Venta::whereHas('apartado', function ($query) use ($usuarioId) {
        $query->where('id_usuario', $usuarioId);
    })->where('estatus', 'pagos')->count();

    $retrasadas = Venta::whereHas('apartado', function ($query) use ($usuarioId) {
        $query->where('id_usuario', $usuarioId);
    })->where('estatus', 'retraso')->count();

    $canceladas = Venta::whereHas('apartado', function ($query) use ($usuarioId) {
        $query->where('id_usuario', $usuarioId);
    })->where('estatus', 'cancelado')->count();

    // Porcentajes
    $porcentajeLiquidadas = $totalVentas > 0 ? round(($liquidadas / $totalVentas) * 100) : 0;
    $porcentajeEnPagos = $totalVentas > 0 ? round(($enPagos / $totalVentas) * 100) : 0;
    $porcentajeCanceladas = $totalVentas > 0 ? round(($canceladas / $totalVentas) * 100) : 0;

    // Retornar la vista con los datos
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

    public function create()
    {
        // Cargar datos para el formulario
        $asesores = Usuario::where('tipo_usuario', 'asesor')->get(); // Asumiendo que los asesores tienen tipo_usuario = 'asesor'
        $lotes = Lote::all(); // Cargar todos los lotes disponibles

        return view('asesor.ventas_create', compact('asesores', 'lotes'));
    }

    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'fechaSolicitud' => 'required|date',
            'estatus' => 'required|in:solicitud,pagos,retraso,liquidado,cancelado',
            'ticket_path' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'enganche' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'tipoApartado' => 'required|string',
            'cliente_nombre' => 'required|string|max:255',
            'cliente_apellidos' => 'required|string|max:255',
            'fechaApartado' => 'required|date',
            'fechaVencimiento' => 'required|date|after_or_equal:fechaApartado',
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'lotes' => 'required|array',
            'lotes.*' => 'exists:lotes,id_lote',
            'cliente.nombres' => 'required|string|max:255',
            'cliente.apellidos' => 'required|string|max:255',
            'cliente.edad' => 'required|integer|min:18',
            'cliente.estado_civil' => 'required|string',
            'cliente.lugar_origen' => 'required|string|max:255',
            'cliente.ocupacion' => 'required|string|max:255',
            'cliente.clave_elector' => 'nullable|string|max:18',
            'contacto.telefono' => 'required|string|max:20',
            'contacto.email' => 'required|email|max:255',
            'direccion.nacionalidad' => 'required|string|max:255',
            'direccion.estado' => 'required|string|max:255',
            'direccion.municipio' => 'required|string|max:255',
            'direccion.localidad' => 'required|string|max:255',
            'beneficiario.nombres' => 'nullable|string|max:255',
            'beneficiario.apellidos' => 'nullable|string|max:255',
            'beneficiario.telefono' => 'nullable|string|max:20',
            'credito.fecha_inicio' => 'nullable|date',
            'credito.observaciones' => 'nullable|string',
            'credito.plazo_financiamiento' => 'nullable|integer|min:1',
            'credito.modalidad_pago' => 'nullable|string',
            'credito.formas_pago' => 'nullable|string',
            'credito.dia_pago' => 'nullable|integer|min:1|max:31',
        ]);

        // Iniciar una transacción para garantizar la integridad de los datos
        return DB::transaction(function () use ($request, $validated) {
            // Manejar la carga del archivo ticket_path
            $ticketPath = null;
            if ($request->hasFile('ticket_path')) {
                $ticketPath = $request->file('ticket_path')->store('tickets', 'public');
            }

            // Crear el apartado
            $apartado = Apartado::create([
                'tipoApartado' => $validated['tipoApartado'],
                'cliente_nombre' => $validated['cliente_nombre'],
                'cliente_apellidos' => $validated['cliente_apellidos'],
                'fechaApartado' => $validated['fechaApartado'],
                'fechaVencimiento' => $validated['fechaVencimiento'],
                'id_usuario' => $validated['id_usuario'],
            ]);

            // Crear los lotes apartados
            foreach ($validated['lotes'] as $loteId) {
                LoteApartado::create([
                    'id_apartado' => $apartado->id_apartado,
                    'id_lote' => $loteId,
                ]);
            }

            // Crear la venta
            $venta = Venta::create([
                'fechaSolicitud' => $validated['fechaSolicitud'],
                'estatus' => $validated['estatus'],
                'ticket_path' => $ticketPath,
                'ticket_estatus' => $request->ticket_estatus ?? 'pendiente', // Asumiendo un valor por defecto
                'enganche' => $validated['enganche'],
                'total' => $validated['total'],
                'id_apartado' => $apartado->id_apartado,
            ]);

            // Crear el cliente
            $cliente = ClienteVenta::create([
                'nombres' => $validated['cliente']['nombres'],
                'apellidos' => $validated['cliente']['apellidos'],
                'edad' => $validated['cliente']['edad'],
                'estado_civil' => $validated['cliente']['estado_civil'],
                'lugar_origen' => $validated['cliente']['lugar_origen'],
                'ocupacion' => $validated['cliente']['ocupacion'],
                'clave_elector' => $validated['cliente']['clave_elector'],
                'id_venta' => $venta->id_venta,
            ]);

            // Crear el contacto del cliente
            ClienteContacto::create([
                'telefono' => $validated['contacto']['telefono'],
                'email' => $validated['contacto']['email'],
                'id_cliente' => $cliente->id_cliente,
            ]);

            // Crear la dirección del cliente
            ClienteDireccion::create([
                'nacionalidad' => $validated['direccion']['nacionalidad'],
                'estado' => $validated['direccion']['estado'],
                'municipio' => $validated['direccion']['municipio'],
                'localidad' => $validated['direccion']['localidad'],
                'id_cliente' => $cliente->id_cliente,
            ]);

            // Crear el beneficiario (si se proporcionó)
            if ($validated['beneficiario']['nombres'] && $validated['beneficiario']['apellidos']) {
                BeneficiarioClienteVenta::create([
                    'nombres' => $validated['beneficiario']['nombres'],
                    'apellidos' => $validated['beneficiario']['apellidos'],
                    'telefono' => $validated['beneficiario']['telefono'],
                    'id_venta' => $venta->id_venta,
                    'id_cliente' => $cliente->id_cliente,
                ]);
            }

            // Crear el crédito (si se proporcionó)
            if ($validated['credito']['fecha_inicio']) {
                Credito::create([
                    'fecha_inicio' => $validated['credito']['fecha_inicio'],
                    'observaciones' => $validated['credito']['observaciones'],
                    'plazo_financiamiento' => $validated['credito']['plazo_financiamiento'],
                    'modalidad_pago' => $validated['credito']['modalidad_pago'],
                    'formas_pago' => $validated['credito']['formas_pago'],
                    'dia_pago' => $validated['credito']['dia_pago'],
                    'id_venta' => $venta->id_venta,
                ]);
            }

            return redirect()->route('ventas.index')->with('success', 'Venta creada exitosamente.');
        });
    }
}
