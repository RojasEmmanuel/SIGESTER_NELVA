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
        // $message está disponible automáticamente en los templates Blade
        // para usar $message->embed() para imágenes incrustadas (CID)
        return $this->subject('Actualización: Ticket de Enganche #' . $this->venta->id)
                    ->view('emails.ventas.ticket_status')
                    ->with([
                        'venta' => $this->venta,
                        'ticketEstatus' => $this->ticketEstatus,
                        'cliente' => $this->venta->clienteVenta,
                        'asesor' => $this->venta->apartado->usuario,
                    ]);
    }
}