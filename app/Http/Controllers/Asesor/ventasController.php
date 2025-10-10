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
use App\Models\Lote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ventasController extends Controller
{
    public function index()
    {
        $usuarioId = Auth::user()->id_usuario;

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

        $porcentajeLiquidadas = $totalVentas > 0 ? round(($liquidadas / $totalVentas) * 100) : 0;
        $porcentajeEnPagos = $totalVentas > 0 ? round(($enPagos / $totalVentas) * 100) : 0;
        $porcentajeCanceladas = $totalVentas > 0 ? round(($canceladas / $totalVentas) * 100) : 0;

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
        $venta = Venta::with([
            'apartado.lotesApartados',
            'apartado.usuario',
            'clienteVenta.contacto',
            'clienteVenta.direccion',
            'beneficiario',
            'credito'
        ])->findOrFail($id_venta);

        return view('asesor.ventas_detalle', compact('venta'));
    }

    public function create()
    {
        $apartados = Apartado::where('fechaVencimiento', '>=', Carbon::today())
            ->with(['usuario', 'lotesApartados.lote'])
            ->get();

        return view('asesor.ventas_create', compact('apartados'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_apartado' => 'required|exists:apartados,id_apartado',
            'ticket_path' => 'required|file|mimes:pdf,jpg,png|max:5120',
            'enganche' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'cliente.nombres' => 'required|string|max:255',
            'cliente.apellidos' => 'required|string|max:255',
            'cliente.edad' => 'required|integer|min:18',
            'cliente.estado_civil' => 'required|string',
            'cliente.lugar_origen' => 'required|string|max:255',
            'cliente.ocupacion' => 'required|string|max:255',
            'cliente.clave_elector' => 'nullable|string|regex:/^[A-Z0-9]{18}$/',
            'cliente.ine_frente' => 'required|file|mimes:jpg,png|max:5120',
            'cliente.ine_reverso' => 'required|file|mimes:jpg,png|max:5120',
            'contacto.telefono' => 'required|string|max:20',
            'contacto.email' => 'required|email|max:255',
            'direccion.nacionalidad' => 'required|string|max:255',
            'direccion.estado' => 'required|string|max:255',
            'direccion.municipio' => 'required|string|max:255',
            'direccion.localidad' => 'required|string|max:255',
            'beneficiario.nombres' => 'nullable|string|max:255',
            'beneficiario.apellidos' => 'nullable|string|max:255',
            'beneficiario.telefono' => 'nullable|string|max:20',
            'beneficiario.ine_frente' => 'nullable|file|mimes:jpg,png|max:5120',
            'beneficiario.ine_reverso' => 'nullable|file|mimes:jpg,png|max:5120',
            'credito.fecha_inicio' => 'nullable|date',
            'credito.observaciones' => 'nullable|string',
            'credito.plazo_financiamiento' => 'nullable|in:12 meses,24 meses,36 meses,48 meses,otro',
            'credito.otro_plazo' => 'required_if:credito.plazo_financiamiento,otro|nullable|integer|min:1',
            'credito.modalidad_pago' => 'nullable|in:mensual,bimestral,trimestral,semestral,anual',
            'credito.formas_pago' => 'nullable|in:efectivo,transferencia,cheque,tarjeta credito/debito,otro',
            'credito.dia_pago' => 'nullable|integer|min:1|max:31',
        ]);

        return DB::transaction(function () use ($request, $validated) {
            $ticketPath = $request->file('ticket_path')->store('tickets', 'public');
            $apartado = Apartado::findOrFail($validated['id_apartado']);

            $venta = Venta::create([
                'fechaSolicitud' => Carbon::now(),
                'estatus' => 'solicitud',
                'ticket_path' => $ticketPath,
                'ticket_estatus' => 'solicitud',
                'enganche' => $validated['enganche'],
                'total' => $validated['total'],
                'id_apartado' => $apartado->id_apartado,
            ]);

            // Guardar fotos del INE del cliente
            $ineFrentePath = $request->file('cliente.ine_frente')->store('ine_photos', 'public');
            $ineReversoPath = $request->file('cliente.ine_reverso')->store('ine_photos', 'public');

            $cliente = ClienteVenta::create([
                'nombres' => $validated['cliente']['nombres'],
                'apellidos' => $validated['cliente']['apellidos'],
                'edad' => $validated['cliente']['edad'],
                'estado_civil' => $validated['cliente']['estado_civil'],
                'lugar_origen' => $validated['cliente']['lugar_origen'],
                'ocupacion' => $validated['cliente']['ocupacion'],
                'clave_elector' => $validated['cliente']['clave_elector'],
                'ine_frente' => $ineFrentePath,
                'ine_reverso' => $ineReversoPath,
                'id_venta' => $venta->id_venta,
            ]);

            ClienteContacto::create([
                'telefono' => $validated['contacto']['telefono'],
                'email' => $validated['contacto']['email'],
                'id_cliente' => $cliente->id_cliente,
            ]);

            ClienteDireccion::create([
                'nacionalidad' => $validated['direccion']['nacionalidad'],
                'estado' => $validated['direccion']['estado'],
                'municipio' => $validated['direccion']['municipio'],
                'localidad' => $validated['direccion']['localidad'],
                'id_cliente' => $cliente->id_cliente,
            ]);

            if (!empty($validated['beneficiario']['nombres']) && !empty($validated['beneficiario']['apellidos'])) {
                $beneficiarioData = [
                    'nombres' => $validated['beneficiario']['nombres'],
                    'apellidos' => $validated['beneficiario']['apellidos'],
                    'telefono' => $validated['beneficiario']['telefono'],
                    'id_venta' => $venta->id_venta,
                    'id_cliente' => $cliente->id_cliente,
                ];

                // Guardar fotos del INE del beneficiario si se proporcionaron
                if ($request->hasFile('beneficiario.ine_frente')) {
                    $beneficiarioData['ine_frente'] = $request->file('beneficiario.ine_frente')->store('ine_photos', 'public');
                }
                if ($request->hasFile('beneficiario.ine_reverso')) {
                    $beneficiarioData['ine_reverso'] = $request->file('beneficiario.ine_reverso')->store('ine_photos', 'public');
                }

                BeneficiarioClienteVenta::create($beneficiarioData);
            }

            if (!empty($validated['credito']['fecha_inicio'])) {
                $plazoFinanciamiento = $validated['credito']['plazo_financiamiento'];
                $otroPlazo = $plazoFinanciamiento === 'otro' ? $validated['credito']['otro_plazo'] : null;

                Credito::create([
                    'fecha_inicio' => $validated['credito']['fecha_inicio'],
                    'observaciones' => $validated['credito']['observaciones'],
                    'plazo_financiamiento' => $plazoFinanciamiento,
                    'otro_plazo' => $otroPlazo,
                    'modalidad_pago' => $validated['credito']['modalidad_pago'],
                    'formas_pago' => $validated['credito']['formas_pago'],
                    'dia_pago' => $validated['credito']['dia_pago'],
                    'id_venta' => $venta->id_venta,
                ]);
            }

            return redirect()->route('ventas.index')->with('success', 'Venta creada exitosamente.');
        });
    }

    public function updateTicket(Request $request, $id_venta)
    {
        $validated = $request->validate([
            'new_ticket_path' => 'required|file|mimes:pdf,jpg,png|max:5120',
        ]);

        return DB::transaction(function () use ($request, $id_venta, $validated) {
            $venta = Venta::findOrFail($id_venta);

            // Verificar que el ticket_estatus sea 'rechazado'
            if ($venta->ticket_estatus !== 'rechazado') {
                return redirect()->route('ventas.show', $id_venta)
                    ->with('error', 'No se puede actualizar el ticket porque no está en estado rechazado.');
            }

            // Eliminar el ticket anterior si existe
            if ($venta->ticket_path && Storage::disk('public')->exists($venta->ticket_path)) {
                Storage::disk('public')->delete($venta->ticket_path);
            }

            // Guardar el nuevo ticket
            $newTicketPath = $request->file('new_ticket_path')->store('tickets', 'public');

            // Actualizar la venta
            $venta->update([
                'ticket_path' => $newTicketPath,
                'ticket_estatus' => 'solicitud',
                'updated_at' => Carbon::now(),
            ]);

            return redirect()->route('ventas.show', $id_venta)
                ->with('success', 'Ticket actualizado exitosamente.');
        });
    }
}