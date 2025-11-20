<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Venta;

class VentaStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $venta;
    public $ventaEstatus;

    public function __construct(Venta $venta, $ventaEstatus)
    {
        $this->venta = $venta;
        $this->ventaEstatus = $ventaEstatus;
    }

    public function build()
    {
        // $message está disponible automáticamente en los templates Blade
        // para usar $message->embed() para imágenes incrustadas (CID)
        return $this->subject('Actualización: Estatus de Compra #' . $this->venta->id)
                    ->view('emails.ventas.venta_status')
                    ->with([
                        'venta' => $this->venta,
                        'ventaEstatus' => $this->ventaEstatus,
                        'cliente' => $this->venta->clienteVenta,
                        'asesor' => $this->venta->apartado->usuario,
                    ]);
    }
}