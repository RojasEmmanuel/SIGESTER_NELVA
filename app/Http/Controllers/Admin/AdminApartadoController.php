<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apartado;
use App\Models\ApartadoDeposito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminApartadoController extends Controller
{
    /**
     * Lista todos los apartados con depósito cuyo ticket está en estado 'solicitud'.
     */
    public function index()
    {
        $apartados = Apartado::with([
            'usuario',
            'lotesApartados.lote.fraccionamiento',
            'deposito'
        ])
        ->where('tipoApartado', 'deposito')
        ->where('estatus', 'en curso')
        ->whereHas('deposito', function ($query) {
            $query->where('ticket_estatus', 'solicitud');
        })
        ->orderBy('fechaApartado', 'desc')
        ->get();

        $totalSolicitudes = $apartados->count();

        return view('admin.apartados.index', compact('apartados', 'totalSolicitudes'));
    }

    /**
     * Muestra los detalles de un apartado específico.
     */
    public function show($id)
    {
        $apartado = Apartado::with([
            'usuario',
            'lotesApartados.lote.fraccionamiento',
            'deposito'
        ])
        ->where('tipoApartado', 'deposito')
        ->findOrFail($id);

        if (!$apartado->deposito || $apartado->deposito->ticket_estatus !== 'solicitud') {
            return redirect()->route('admin.apartados-pendientes.index')
                ->with('error', 'El apartado no tiene un ticket en estado solicitud.');
        }

        return view('admin.apartados.show', compact('apartado'));
    }

    /**
     * Actualiza el estatus del ticket, observaciones, fecha de vencimiento y estatus del apartado.
     */
    public function updateTicketStatus(Request $request, $id)
    {
        // Validar que el usuario esté autenticado
        if (!Auth::check()) {
            throw new \Exception('Usuario no autenticado.');
        }

        $request->validate([
            'ticket_estatus' => 'required|in:solicitud,aceptado,rechazado',
            'observaciones' => 'nullable|string|max:500',
            'fechaVencimiento' => 'nullable|date|after_or_equal:today|required_if:ticket_estatus,aceptado',
            'estatus' => 'nullable|in:en curso,venta,vencido|required_if:ticket_estatus,aceptado'
        ], [
            'ticket_estatus.in' => 'El estatus del ticket debe ser solicitud, aceptado o rechazado.',
            'observaciones.max' => 'Las observaciones no deben exceder los 500 caracteres.',
            'fechaVencimiento.date' => 'La fecha de vencimiento debe ser una fecha válida.',
            'fechaVencimiento.after_or_equal' => 'La fecha de vencimiento no puede ser anterior a hoy.',
            'fechaVencimiento.required_if' => 'La fecha de vencimiento es requerida cuando el ticket es aceptado.',
            'estatus.in' => 'El estatus del apartado debe ser en curso, venta o vencido.',
            'estatus.required_if' => 'El estatus del apartado es requerido cuando el ticket es aceptado.'
        ]);

        DB::beginTransaction();
        try {
            $apartado = Apartado::where('tipoApartado', 'deposito')->findOrFail($id);
            $deposito = ApartadoDeposito::where('id_apartado', $apartado->id_apartado)->firstOrFail();

            // Actualizar el depósito (siempre se actualiza el estatus del ticket)
            $deposito->update([
                'ticket_estatus' => $request->ticket_estatus,
                'observaciones' => $request->observaciones ?? $deposito->observaciones,
                'fecha_revision' => now('America/Mexico_City')
            ]);

            $now = now('America/Mexico_City');

            // Caso: Ticket ACEPTADO
            if ($request->ticket_estatus === 'aceptado') {
                $apartado->update([
                    'fechaVencimiento' => Carbon::parse($request->fechaVencimiento)->toDateTimeString(),
                    'estatus' => $request->estatus
                ]);

                // Registrar en historial y actualizar estatus de lotes si corresponde
                foreach ($apartado->lotesApartados as $loteApartado) {
                    $nuevoEstatusLote = $request->estatus === 'venta' ? 'vendido' : 'apartadoDeposito';

                    \App\Models\HistorialCambiosLote::create([
                        'id_lote' => $loteApartado->lote->id_lote,
                        'id_usuario' => Auth::user()->id_usuario,
                        'estatus_anterior' => $loteApartado->lote->estatus,
                        'estatus_actual' => $nuevoEstatusLote,
                        'observaciones' => 'Estatus actualizado por aceptación de ticket en apartado ' . $apartado->id_apartado
                    ]);

                    // Si se marca como venta, cambiar el lote a vendido
                    if ($request->estatus === 'venta') {
                        $loteApartado->lote->update(['estatus' => 'vendido']);
                    }
                }
            }

            // Caso: Ticket RECHAZADO
            if ($request->ticket_estatus === 'rechazado') {
                $fechaVencimiento = Carbon::parse($apartado->fechaVencimiento);

                // Solo liberar lotes si ya venció el apartado
                if ($now->greaterThan($fechaVencimiento)) {
                    foreach ($apartado->lotesApartados as $loteApartado) {
                        $lote = $loteApartado->lote;

                        \App\Models\HistorialCambiosLote::create([
                            'id_lote' => $lote->id_lote,
                            'id_usuario' => Auth::user()->id_usuario,
                            'estatus_anterior' => $lote->estatus,
                            'estatus_actual' => 'disponible',
                            'observaciones' => 'Lote liberado por rechazo de ticket y vencimiento del apartado ' . $apartado->id_apartado
                        ]);

                        $lote->update(['estatus' => 'disponible']);
                    }

                    $apartado->estatus = 'vencido';
                } else {
                    // Aún vigente: mantener lotes reservados y apartado activo
                    $apartado->estatus = 'en curso'; // Puedes cambiarlo por otro estatus más descriptivo si lo prefieres (ej. 'pendiente_deposito')
                }

                $apartado->save();

                // Opcional: agregar nota en observaciones del depósito para auditoría
                $observacionExtra = ' | Ticket rechazado el ' . $now->format('d/m/Y H:i');
                $deposito->update([
                    'observaciones' => ($deposito->observaciones ?? '') . $observacionExtra
                ]);
            }

            DB::commit();

            Log::info('Estatus de ticket actualizado:', [
                'id_apartado' => $apartado->id_apartado,
                'ticket_estatus' => $request->ticket_estatus,
                'observaciones' => $request->observaciones,
                'fechaVencimiento' => $request->fechaVencimiento ?? $apartado->fechaVencimiento,
                'estatus_apartado' => $apartado->estatus
            ]);

            return redirect()->route('admin.apartados-pendientes.index')
                ->with('success', 'Estatus del ticket actualizado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar estatus del ticket:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()
                ->with('error', 'Error al actualizar el estatus del ticket: ' . $e->getMessage());
        }
    }
}