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
use App\Models\Fraccionamiento;
use App\Models\Lote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ventasController extends Controller
{
    public function index()
    {
        $usuarioId = Auth::user()->id_usuario;

        $ventas = Venta::whereHas('apartado', function ($query) use ($usuarioId) {
                $query->where('id_usuario', $usuarioId);
            })
            ->orderBy('fechaSolicitud', 'desc')
            ->with([
                'apartado.lotesApartados.lote',
                'apartado.usuario',
                'clienteVenta.contacto',
                'clienteVenta.direccion',
                'beneficiario',
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
            'apartado.lotesApartados.lote',
            'apartado.usuario',
            'clienteVenta.contacto',
            'clienteVenta.direccion',
            'beneficiario',
            'credito'
        ])->findOrFail($id_venta);

        // Verificar que el usuario tenga permiso para ver la venta
        if ($venta->apartado->id_usuario !== Auth::user()->id_usuario) {
            return redirect()->route('ventas.index')->with('error', 'No tienes permiso para ver esta venta.');
        }

        return view('asesor.ventas_detalle', compact('venta'));
    }

    public function create()
    {
        // Obtener el ID del usuario autenticado
        $userId = Auth::user()->id_usuario;

        // Consultar los apartados, filtrando por el ID del usuario autenticado
        $apartados = Apartado::where('fechaVencimiento', '>=', Carbon::today())
            ->where('estatus', 'en curso')
            ->where('id_usuario', $userId) // Agregar filtro para el usuario autenticado
            ->with(['usuario', 'lotesApartados.lote'])
            ->get();

        return view('asesor.ventas_create', compact('apartados'));
    }

    public function store(Request $request)
    {
        $tipoPago = $request->input('tipo_pago', 'contado');

        $rules = [
            'id_apartado' => 'required|exists:apartados,id_apartado',
            'ticket_path' => 'required|file|mimes:pdf,jpg,png',
            'total' => 'required|numeric|min:0.01',
            'cliente.nombres' => 'required|string|max:255',
            'cliente.apellidos' => 'required|string|max:255',
            'cliente.edad' => 'required|integer|min:18',
            'cliente.estado_civil' => 'required|in:soltero,casado,divorciado,viudo,unión libre',
            'cliente.lugar_origen' => 'required|string|max:255',
            'cliente.ocupacion' => 'required|string|max:255',
            'cliente.clave_elector' => 'nullable|string|regex:/^[A-Z0-9]{18}$/',
            'cliente.ine_frente' => 'required|file|mimes:jpg,png',
            'cliente.ine_reverso' => 'required|file|mimes:jpg,png',
            'contacto.telefono' => 'required|string|max:20',
            'contacto.email' => 'required|email|max:255',
            'direccion.nacionalidad' => 'required|string|max:255',
            'direccion.estado' => 'required|string|max:255',
            'direccion.municipio' => 'required|string|max:255',
            'direccion.localidad' => 'required|string|max:255',
            'beneficiario.nombres' => 'nullable|string|max:255',
            'beneficiario.apellidos' => 'nullable|string|max:255',
            'beneficiario.telefono' => 'nullable|string|max:20',
            'beneficiario.ine_frente' => 'nullable|file|mimes:jpg,png',
            'beneficiario.ine_reverso' => 'nullable|file|mimes:jpg,png',
        ];

        // === CRÉDITO: solo si es crédito ===
        if ($tipoPago === 'credito') {
            $rules += [
                'enganche' => 'required|numeric|min:0.01|lt:total',
                'credito.fecha_inicio' => 'required|date',
                'credito.plazo_financiamiento' => 'required|in:12 meses,24 meses,36 meses,48 meses,otro',
                'credito.otro_plazo' => 'required_if:credito.plazo_financiamiento,otro|nullable|integer|min:1',
                'credito.modalidad_pago' => 'required|in:mensual,bimestral,trimestral,semestral,anual',
                'credito.formas_pago' => 'required|in:efectivo,transferencia,cheque,tarjeta credito/debito,otro',
                'credito.dia_pago' => 'required|integer|min:1|max:31',
                'credito.monto_Pago' => 'required|numeric|min:1',
                'credito.observaciones' => 'nullable|string',
            ];
        } else {
            $rules['enganche'] = 'required|numeric|same:total';
            // Crédito: todos nullable (como en storeDirect())
            $rules += [
                'credito.fecha_inicio' => 'nullable|date',
                'credito.observaciones' => 'nullable|string',
                'credito.plazo_financiamiento' => 'nullable|in:12 meses,24 meses,36 meses,48 meses,otro',
                'credito.otro_plazo' => 'nullable|integer|min:1',
                'credito.modalidad_pago' => 'nullable|in:mensual,bimestral,trimestral,semestral,anual',
                'credito.formas_pago' => 'nullable|in:efectivo,transferencia,cheque,tarjeta credito/debito,otro',
                'credito.dia_pago' => 'nullable|integer|min:1|max:31',
            ];
        }

        $validated = $request->validate($rules);

        return DB::transaction(function () use ($request, $validated, $tipoPago) {
            $apartado = Apartado::with('lotesApartados.lote')->findOrFail($validated['id_apartado']);

            // Verificar que el apartado esté en curso y pertenezca al usuario autenticado
            if ($apartado->estatus !== 'en curso') {
                return redirect()->route('ventas.create')
                    ->with('error', 'El apartado seleccionado no está en curso y no puede ser usado para una venta.');
            }
            if ($apartado->id_usuario !== Auth::user()->id_usuario) {
                return redirect()->route('ventas.create')
                    ->with('error', 'No tienes permiso para usar este apartado.');
            }

            // Guardar el ticket
            $ticketPath = $request->file('ticket_path')->store('tickets', 'public');

            // Crear la venta
            $venta = Venta::create([
                'fechaSolicitud' => Carbon::now(),
                'estatus' => 'solicitud',
                'ticket_path' => $ticketPath,
                'ticket_estatus' => 'solicitud',
                'enganche' => $validated['enganche'],
                'total' => $validated['total'],
                'id_apartado' => $apartado->id_apartado,
            ]);

            // Actualizar el estatus del apartado a 'venta'
            $apartado->update(['estatus' => 'venta']);

            // Actualizar el estatus de los lotes asociados a 'vendido'
            foreach ($apartado->lotesApartados as $loteApartado) {
                if ($loteApartado->lote) {
                    $loteApartado->lote->update(['estatus' => 'vendido']);
                }
            }

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
                'clave_elector' => $validated['cliente']['clave_elector'] ?? null,
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
                    'telefono' => $validated['beneficiario']['telefono'] ?? null,
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

            // === Crédito (solo si es crédito) ===
            if ($tipoPago === 'credito' && !empty($validated['credito']['fecha_inicio'])) {
                $plazo = $validated['credito']['plazo_financiamiento'];
                $otroPlazo = ($plazo === 'otro') ? $validated['credito']['otro_plazo'] : null;

                if (in_array($plazo, ['12 meses', '24 meses', '36 meses', '48 meses'])) {
                    $otroPlazo = (int) str_replace(' meses', '', $plazo);
                }

                Credito::create([
                    'fecha_inicio' => $validated['credito']['fecha_inicio'],
                    'observaciones' => $validated['credito']['observaciones'] ?? null,
                    'plazo_financiamiento' => $plazo,
                    'otro_plazo' => $otroPlazo,
                    'modalidad_pago' => $validated['credito']['modalidad_pago'],
                    'formas_pago' => $validated['credito']['formas_pago'],
                    'dia_pago' => (string) $validated['credito']['dia_pago'],
                    'pagos' => $validated['credito']['monto_Pago'],
                    'id_venta' => $venta->id_venta,
                ]);
            }

            return redirect()->route('ventas.index')->with('success', 'Venta creada exitosamente.');
        });
    }

    public function updateTicket(Request $request, $id_venta)
    {
        $validated = $request->validate([
            'new_ticket_path' => 'required|file|mimes:pdf,jpg,png',
        ]);

        return DB::transaction(function () use ($request, $id_venta, $validated) {
            $venta = Venta::with('apartado')->findOrFail($id_venta);

            // Verificar que el usuario tenga permiso
            if ($venta->apartado->id_usuario !== Auth::user()->id_usuario) {
                return redirect()->route('ventas.index')->with('error', 'No tienes permiso para actualizar esta venta.');
            }

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

    public function cancel($id_venta)
    {
        return DB::transaction(function () use ($id_venta) {
            $venta = Venta::with('apartado.lotesApartados.lote')->findOrFail($id_venta);

            // Verificar que el usuario tenga permiso
            if ($venta->apartado->id_usuario !== Auth::user()->id_usuario) {
                return redirect()->route('ventas.index')->with('error', 'No tienes permiso para cancelar esta venta.');
            }

            // Verificar que la venta no esté ya cancelada
            if ($venta->estatus === 'cancelado') {
                return redirect()->route('ventas.show', $id_venta)
                    ->with('error', 'La venta ya está cancelada.');
            }

            // Actualizar el estatus de la venta a 'cancelado'
            $venta->update([
                'estatus' => 'cancelado',
                'updated_at' => Carbon::now(),
            ]);

            // Actualizar el estatus del apartado
            $apartado = $venta->apartado;
            $nuevoEstatusApartado = $apartado->fechaVencimiento->isPast() ? 'vencido' : 'en curso';
            $apartado->update(['estatus' => $nuevoEstatusApartado]);

            // Actualizar el estatus de los lotes asociados
            $nuevoEstatusLote = $nuevoEstatusApartado === 'vencido' ? 'disponible' : ($apartado->tipoApartado === 'palabra' ? 'apartadoPalabra' : 'apartadoDeposito');
            foreach ($apartado->lotesApartados as $loteApartado) {
                if ($loteApartado->lote) {
                    $loteApartado->lote->update(['estatus' => $nuevoEstatusLote]);
                }
            }

            return redirect()->route('ventas.show', $id_venta)
                ->with('success', 'Venta cancelada exitosamente.');
        });
    }

    public function createDirect()
    {
        $userId = Auth::user()->id_usuario;

        $fraccionamientos = Fraccionamiento::with(['lotes' => function ($query) {
            $query->where('estatus', 'disponible');
        }])->get();

        return view('asesor.ventas_direct_create', compact('fraccionamientos'));
    }

    public function storeDirect(Request $request)
    {
        $tipoPago = $request->input('tipo_pago', 'contado');

       $rules = [
            'lotes' => 'required|array|min:1',
            'lotes.*' => 'exists:lotes,id_lote',
            'ticket_path' => 'required|file|mimes:pdf,jpg,png',
            'total' => 'required|numeric|min:0.01',
            'cliente.nombres' => 'required|string|max:255',
            'cliente.apellidos' => 'required|string|max:255',
            'cliente.edad' => 'required|integer|min:18',
            'cliente.estado_civil' => 'required|in:soltero,casado,divorciado,viudo,unión libre',
            'cliente.lugar_origen' => 'required|string|max:255',
            'cliente.ocupacion' => 'required|string|max:255',
            'cliente.clave_elector' => 'nullable|string|regex:/^[A-Z0-9]{18}$/',
            'cliente.ine_frente' => 'required|file|mimes:jpg,png',
            'cliente.ine_reverso' => 'required|file|mimes:jpg,png',
            'contacto.telefono' => 'required|string|max:20',
            'contacto.email' => 'required|email|max:255',
            'direccion.nacionalidad' => 'required|string|max:255',
            'direccion.estado' => 'required|string|max:255',
            'direccion.municipio' => 'required|string|max:255',
            'direccion.localidad' => 'required|string|max:255',
            'beneficiario.nombres' => 'nullable|string|max:255',
            'beneficiario.apellidos' => 'nullable|string|max:255',
            'beneficiario.telefono' => 'nullable|string|max:20',
            'beneficiario.ine_frente' => 'nullable|file|mimes:jpg,png',
            'beneficiario.ine_reverso' => 'nullable|file|mimes:jpg,png',
        ];

        // === CRÉDITO: solo si es crédito ===
        if ($tipoPago === 'credito') {
            $rules += [
                'enganche' => 'required|numeric|min:0.01|lt:total',
                'credito.fecha_inicio' => 'required|date',
                'credito.plazo_financiamiento' => 'required|in:12 meses,24 meses,36 meses,48 meses,otro',
                'credito.otro_plazo' => 'required_if:credito.plazo_financiamiento,otro|nullable|integer|min:1',
                'credito.modalidad_pago' => 'required|in:mensual,bimestral,trimestral,semestral,anual',
                'credito.formas_pago' => 'required|in:efectivo,transferencia,cheque,tarjeta credito/debito,otro',
                'credito.dia_pago' => 'required|integer|min:1|max:31',
                'credito.monto_Pago' => 'required|numeric|min:1',
                'credito.observaciones' => 'nullable|string',
            ];
        } else {
            $rules['enganche'] = 'required|numeric|same:total';
            // Crédito: todos nullable (como en store())
            $rules += [
                'credito.fecha_inicio' => 'nullable|date',
                'credito.observaciones' => 'nullable|string',
                'credito.plazo_financiamiento' => 'nullable|in:12 meses,24 meses,36 meses,48 meses,otro',
                'credito.otro_plazo' => 'nullable|integer|min:1',
                'credito.modalidad_pago' => 'nullable|in:mensual,bimestral,trimestral,semestral,anual',
                'credito.formas_pago' => 'nullable|in:efectivo,transferencia,cheque,tarjeta credito/debito,otro',
                'credito.dia_pago' => 'nullable|integer|min:1|max:31',
            ];
        }

        $validated = $request->validate($rules);

        try {
            return DB::transaction(function () use ($request, $validated, $tipoPago) {
                $userId = Auth::user()->id_usuario;

                // === 1. Verificar lotes disponibles ===
                $lotes = Lote::whereIn('id_lote', $validated['lotes'])
                    ->where('estatus', 'disponible')
                    ->get();

                if ($lotes->count() !== count($validated['lotes'])) {
                    throw new \Exception('Uno o más lotes ya no están disponibles.');
                }

                // === 2. Apartado temporal ===
                $apartado = Apartado::create([
                    'fechaApartado' => now(),
                    'fechaVencimiento' => now()->addDay(),
                    'tipoApartado' => 'palabra',
                    'estatus' => 'venta',
                    'cliente_nombre' => $validated['cliente']['nombres'],
                    'cliente_apellidos' => $validated['cliente']['apellidos'],
                    'id_usuario' => $userId,
                ]);

                foreach ($lotes as $lote) {
                    LoteApartado::create([
                        'id_apartado' => $apartado->id_apartado,
                        'id_lote' => $lote->id_lote,
                    ]);
                    $lote->update(['estatus' => 'vendido']);
                }

                // === 3. Ticket ===
                $ticketPath = $request->file('ticket_path')->store('tickets', 'public');

                // === 4. Venta ===
                $venta = Venta::create([
                    'fechaSolicitud' => now(),
                    'estatus' => 'solicitud',
                    'ticket_path' => $ticketPath,
                    'ticket_estatus' => 'solicitud',
                    'enganche' => $validated['enganche'],
                    'total' => $validated['total'],
                    'id_apartado' => $apartado->id_apartado,
                ]);

                // === 5. Cliente ===
                $ineFrentePath = $request->file('cliente.ine_frente')->store('ine_photos', 'public');
                $ineReversoPath = $request->file('cliente.ine_reverso')->store('ine_photos', 'public');

                $cliente = ClienteVenta::create([
                    'nombres' => $validated['cliente']['nombres'],
                    'apellidos' => $validated['cliente']['apellidos'],
                    'edad' => $validated['cliente']['edad'],
                    'estado_civil' => $validated['cliente']['estado_civil'],
                    'lugar_origen' => $validated['cliente']['lugar_origen'],
                    'ocupacion' => $validated['cliente']['ocupacion'],
                    'clave_elector' => $validated['cliente']['clave_elector'] ?? null,
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

                // === 6. Beneficiario (opcional) ===
                if (!empty($validated['beneficiario']['nombres']) && !empty($validated['beneficiario']['apellidos'])) {
                    $beneficiarioData = [
                        'nombres' => $validated['beneficiario']['nombres'],
                        'apellidos' => $validated['beneficiario']['apellidos'],
                        'telefono' => $validated['beneficiario']['telefono'] ?? null,
                        'id_venta' => $venta->id_venta,
                        'id_cliente' => $cliente->id_cliente,
                    ];

                    if ($request->hasFile('beneficiario.ine_frente')) {
                        $beneficiarioData['ine_frente'] = $request->file('beneficiario.ine_frente')->store('ine_photos', 'public');
                    }
                    if ($request->hasFile('beneficiario.ine_reverso')) {
                        $beneficiarioData['ine_reverso'] = $request->file('beneficiario.ine_reverso')->store('ine_photos', 'public');
                    }

                    BeneficiarioClienteVenta::create($beneficiarioData);
                }

                // === 7. Crédito (solo si es crédito) ===
               if ($tipoPago === 'credito' && !empty($validated['credito']['fecha_inicio'])) {
                    $plazo = $validated['credito']['plazo_financiamiento'];
                    $otroPlazo = ($plazo === 'otro') ? $validated['credito']['otro_plazo'] : null;

                    if (in_array($plazo, ['12 meses', '24 meses', '36 meses', '48 meses'])) {
                        $otroPlazo = (int) str_replace(' meses', '', $plazo);
                    }

                    Credito::create([
                        'fecha_inicio' => $validated['credito']['fecha_inicio'],
                        'observaciones' => $validated['credito']['observaciones'] ?? null,
                        'plazo_financiamiento' => $plazo,
                        'otro_plazo' => $otroPlazo,
                        'modalidad_pago' => $validated['credito']['modalidad_pago'],
                        'formas_pago' => $validated['credito']['formas_pago'],
                        'dia_pago' => (string) $validated['credito']['dia_pago'], 
                        'pagos' => $validated['credito']['monto_Pago'],
                        'id_venta' => $venta->id_venta,
                    ]);
                }
                return redirect()->route('ventas.index')->with('success', 'Venta creada exitosamente.');
            });
        } catch (\Exception $e) {
            Log::error('Error en venta directa: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al crear la venta: ' . $e->getMessage()]);
        }
    }
}