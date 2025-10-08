<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Venta extends Model
{
     use HasFactory;

    // Nombre de la tabla
    protected $table = 'ventas';
    protected $primaryKey = 'id_venta';
    protected $fillable = [
        'fechaSolicitud',
        'estatus',
        'ticket_path',
        'ticket_estatus',
        'enganche',
        'total',
        'id_apartado',
    ];

    // Casts para tipos de datos
    protected $casts = [
        'fechaSolicitud' => 'datetime',
        'enganche' => 'double',
        'total' => 'double',
    ];


    // Relación con Apartado
    public function apartado()
    {
        return $this->belongsTo(Apartado::class, 'id_apartado', 'id_apartado');
    }

    // Relación con ClienteVenta
    public function clienteVenta()
    {
        return $this->hasOne(ClienteVenta::class, 'id_venta', 'id_venta');
    }

    // Relación con Beneficiario
    public function beneficiario()
    {
        return $this->hasOne(BeneficiarioClienteVenta::class, 'id_venta', 'id_venta');
    }

    // Relación con Credito
    public function credito()
    {
        return $this->hasOne(Credito::class, 'id_venta', 'id_venta'); // Asumiendo que existe una relación con creditos
    }
}
