<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Venta;

class VentaTicketStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $venta;
    public $ticketEstatus;

    public function __construct(Venta $venta, $ticketEstatus)
    {
        $this->venta = $venta;
        $this->ticketEstatus = $ticketEstatus;
    }

    public function build()
    {
        return $this->subject('Actualización: Ticket de Enganche #' . $this->venta->id)
                    ->view('emails.ventas.ticket_status') // ✅ CAMBIADO: markdown() por view()
                    ->with([
                        'venta' => $this->venta,
                        'ticketEstatus' => $this->ticketEstatus,
                        'cliente' => $this->venta->clienteVenta,
                        'asesor' => $this->venta->apartado->usuario,
                    ]);
    }
}