<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venta;
use App\Models\Apartado;
use App\Models\Lote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AdminVentasController extends Controller
{
    /**
     * Mostrar lista de todas las ventas
     */
    public function index(Request $request)
    {
        $query = Venta::with([
            'apartado.lotesApartados.lote.fraccionamiento',
            'apartado.usuario',
            'clienteVenta.contacto',
            'clienteVenta.direccion',
            'beneficiario',
            'credito'
        ]);

        // Filtros
        if ($request->filled('estatus')) {
            $query->where('estatus', $request->estatus);
        }

        if ($request->filled('ticket_estatus')) {
            $query->where('ticket_estatus', $request->ticket_estatus);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('clienteVenta', function($clientQuery) use ($search) {
                    $clientQuery->where('nombres', 'like', "%{$search}%")
                               ->orWhere('apellidos', 'like', "%{$search}%");
                })
                ->orWhereHas('apartado.usuario', function($userQuery) use ($search) {
                    $userQuery->where('nombre', 'like', "%{$search}%")
                              ->orWhere('apellidos', 'like', "%{$search}%");
                });
            });
        }

        $ventas = $query->orderBy('fechaSolicitud', 'desc')->paginate(12);

        $totalVentas = Venta::count();
        $porEstatus = Venta::selectRaw('estatus, count(*) as total')
            ->groupBy('estatus')
            ->pluck('total', 'estatus');

        $ticketEstatus = Venta::selectRaw('ticket_estatus, count(*) as total')
            ->groupBy('ticket_estatus')
            ->pluck('total', 'ticket_estatus');

        return view('admin.ventas.index', compact(
            'ventas',
            'totalVentas',
            'porEstatus',
            'ticketEstatus',
            'request'
        ));
    }

    /**
     * Mostrar detalle completo de una venta
     */
    public function show($id_venta)
    {
        $venta = Venta::with([
            'apartado.lotesApartados.lote.fraccionamiento',
            'apartado.usuario',
            'clienteVenta.contacto',
            'clienteVenta.direccion',
            'beneficiario',
            'credito'
        ])->findOrFail($id_venta);

        $ticketExists = $venta->ticket_path && Storage::disk('public')->exists($venta->ticket_path);

        return view('admin.ventas.show', compact('venta', 'ticketExists'));
    }

    /**
     * Vista específica para gestionar ticket de una venta
     */
    public function ticket($id_venta)
    {
        $venta = Venta::with([
            'apartado.lotesApartados.lote.fraccionamiento',
            'apartado.usuario',
            'clienteVenta.contacto',
            'clienteVenta.direccion',
            'beneficiario',
            'credito'
        ])->findOrFail($id_venta);

        $ticketExists = $venta->ticket_path && Storage::disk('public')->exists($venta->ticket_path);

        return view('admin.ventas.ticket', compact('venta', 'ticketExists'));
    }

    /**
     * Actualizar el estatus del ticket de enganche
     */
    public function updateTicketEstatus(Request $request, $id_venta)
    {
        $request->validate([
            'ticket_estatus' => 'required|in:solicitud,rechazado,aceptado'
        ]);

        try {
            return DB::transaction(function () use ($request, $id_venta) {
                $venta = Venta::with('apartado.lotesApartados.lote')->findOrFail($id_venta);

                $updateData = [
                    'ticket_estatus' => $request->ticket_estatus,
                    'updated_at' => Carbon::now(),
                ];

                // Si se acepta el ticket, actualizar estatus de venta a 'pagos'
                if ($request->ticket_estatus === 'aceptado') {
                    $updateData['estatus'] = 'pagos';
                }

                $venta->update($updateData);

                // Si el ticket es aceptado, actualizar apartado y lotes
                if ($request->ticket_estatus === 'aceptado') {
                    $venta->apartado->update(['estatus' => 'venta']);
                    
                    foreach ($venta->apartado->lotesApartados as $loteApartado) {
                        if ($loteApartado->lote) {
                            $loteApartado->lote->update(['estatus' => 'vendido']);
                        }
                    }
                }

                $newTicketStatus = $request->ticket_estatus;
                $newVentaStatus = $venta->fresh()->estatus;

                return response()->json([
                    'success' => true,
                    'message' => 'Estatus del ticket actualizado correctamente.',
                    'new_ticket_status' => $newTicketStatus,
                    'new_venta_status' => $newVentaStatus,
                    'ticket_status_class' => $this->getTicketStatusClass($newTicketStatus),
                    'venta_status_class' => $this->getVentaStatusClass($newVentaStatus)
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estatus: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar el estatus de la venta
     */
    public function updateVentaEstatus(Request $request, $id_venta)
    {
        $request->validate([
            'estatus' => 'required|in:pagos,retraso,liquidado,cancelado'
        ]);

        $newEstatus = $request->estatus;

        try {
            return DB::transaction(function () use ($newEstatus, $id_venta) {
                $venta = Venta::with(['apartado.lotesApartados.lote'])->findOrFail($id_venta);
                $currentEstatus = $venta->estatus;

                // Validar transiciones permitidas
                $allowedTransitions = [
                    'solicitud' => ['pagos'],
                    'pagos' => ['retraso', 'liquidado', 'cancelado'],
                    'retraso' => ['pagos', 'liquidado', 'cancelado']
                ];

                if (!isset($allowedTransitions[$currentEstatus]) || 
                    !in_array($newEstatus, $allowedTransitions[$currentEstatus])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Transición de estatus no permitida desde "' . $currentEstatus . '"'
                    ], 422);
                }

                // Actualizar estatus de la venta
                $venta->update([
                    'estatus' => $newEstatus,
                    'updated_at' => Carbon::now(),
                ]);

                // Manejar lógica específica por estatus
                switch ($newEstatus) {
                    case 'cancelado':
                        $this->handleCancelacion($venta);
                        break;
                    case 'liquidado':
                        $this->handleLiquidacion($venta);
                        break;
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Estatus de la venta actualizado correctamente.',
                    'new_status' => $newEstatus,
                    'status_class' => $this->getVentaStatusClass($newEstatus)
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estatus: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Manejar cancelación de venta
     */
    private function handleCancelacion($venta)
    {
        $apartado = $venta->apartado;
        
        // Actualizar apartado según fecha de vencimiento
        $nuevoEstatusApartado = $apartado->fechaVencimiento->isPast() ? 'vencido' : 'en curso';
        $apartado->update(['estatus' => $nuevoEstatusApartado]);

        // Liberar lotes como disponibles
        foreach ($apartado->lotesApartados as $loteApartado) {
            if ($loteApartado->lote) {
                $loteApartado->lote->update(['estatus' => 'disponible']);
            }
        }
    }

    /**
     * Manejar liquidación de venta
     */
    private function handleLiquidacion($venta)
    {
        // Lógica adicional para liquidación si es necesaria
        // Ejemplo: generar documento final, actualizar fechas, etc.
    }

    /**
     * Clases CSS para estatus del ticket
     */
    private function getTicketStatusClass($status)
    {
        return match($status) {
            'aceptado' => 'success',
            'rechazado' => 'danger',
            'solicitud' => 'warning',
            default => 'secondary'
        };
    }

    /**
     * Clases CSS para estatus de venta
     */
    private function getVentaStatusClass($status)
    {
        return match($status) {
            'liquidado' => 'success',
            'pagos' => 'warning',
            'retraso' => 'danger',
            'cancelado' => 'secondary',
            default => 'info'
        };
    }
}