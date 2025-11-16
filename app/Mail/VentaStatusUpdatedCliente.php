<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Venta;

class VentaStatusUpdatedCliente extends Mailable
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
        return $this->subject('Actualización: Estatus de Compra #' . $this->venta->id)
                    ->view('emails.ventas.venta_status_cliente')
                    ->with([
                        'venta' => $this->venta,
                        'ventaEstatus' => $this->ventaEstatus,
                        'cliente' => $this->venta->clienteVenta,
                        'asesor' => $this->venta->apartado->usuario,
                    ]);
    }
}
