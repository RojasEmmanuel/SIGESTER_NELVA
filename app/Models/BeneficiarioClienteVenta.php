<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BeneficiarioClienteVenta extends Model
{
    use HasFactory;
    protected $table = 'beneficiario_cliente_venta';
    protected $primaryKey = 'id_beneficiario';
    protected $fillable = [
        'nombres',
        'apellidos',
        'telefono',
        'ine_frente',
        'ine_reverso',
        'id_venta',
        'id_cliente',
    ];

    // Relación con Venta
    public function venta()
    {
        return $this->belongsTo(Venta::class, 'id_venta', 'id_venta');
    }

    // Relación con ClienteVenta
    public function cliente()
    {
        return $this->belongsTo(ClienteVenta::class, 'id_cliente', 'id_cliente');
    }
}