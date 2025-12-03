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

            // Actualizar el depósito
            $deposito->update([
                'ticket_estatus' => $request->ticket_estatus,
                'observaciones' => $request->observaciones ?? $deposito->observaciones,
                'fecha_revision' => now('America/Mexico_City')
            ]);

            // Si el ticket es aceptado, actualizar fechaVencimiento y estatus en apartados
            if ($request->ticket_estatus === 'aceptado') {
                $apartado->update([
                    'fechaVencimiento' => Carbon::parse($request->fechaVencimiento)->toDateTimeString(),
                    'estatus' => $request->estatus
                ]);

                // Registrar en historial si el estatus cambia
                foreach ($apartado->lotesApartados as $loteApartado) {
                    \App\Models\HistorialCambiosLote::create([
                        'id_lote' => $loteApartado->lote->id_lote,
                        'id_usuario' => Auth::user()->id_usuario,
                        'estatus_anterior' => $loteApartado->lote->estatus,
                        'estatus_actual' => $request->estatus === 'venta' ? 'vendido' : 'apartadoDeposito',
                        'observaciones' => 'Estatus actualizado por aceptación de ticket en apartado ' . $apartado->id_apartado
                    ]);

                    // Actualizar estatus del lote si es necesario
                    $lote = $loteApartado->lote;
                    if ($request->estatus === 'venta') {
                        $lote->estatus = 'vendido';
                        $lote->save();
                    }
                }
            }

            // Si el ticket es rechazado, liberar los lotes y actualizar estatus según fechaVencimiento
            if ($request->ticket_estatus === 'rechazado') {
                foreach ($apartado->lotesApartados as $loteApartado) {
                    $lote = $loteApartado->lote;
                    $lote->estatus = 'disponible';
                    $lote->save();

                    // Registrar en historial
                    \App\Models\HistorialCambiosLote::create([
                        'id_lote' => $lote->id_lote,
                        'id_usuario' => Auth::user()->id_usuario,
                        'estatus_anterior' => 'apartadoDeposito',
                        'estatus_actual' => 'disponible',
                        'observaciones' => 'Lote liberado por rechazo de ticket en apartado ' . $apartado->id_apartado
                    ]);
                }

                // Actualizar estatus del apartado según la fecha de vencimiento
                $now = now('America/Mexico_City');
                $fechaVencimiento = Carbon::parse($apartado->fechaVencimiento);
                $apartado->estatus = $fechaVencimiento->isFuture() ? 'en curso' : 'vencido';
                $apartado->save();
            }

            DB::commit();

            Log::info('Estatus de ticket actualizado:', [
                'id_apartado' => $apartado->id_apartado,
                'ticket_estatus' => $request->ticket_estatus,
                'observaciones' => $request->observaciones,
                'fechaVencimiento' => $request->fechaVencimiento,
                'estatus' => $apartado->estatus
            ]);

            return redirect()->route('admin.apartados-pendientes.index')
                ->with('success', 'Estatus del ticket actualizado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar estatus del ticket:', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Error al actualizar el estatus del ticket: ' . $e->getMessage());
        }
    }
}