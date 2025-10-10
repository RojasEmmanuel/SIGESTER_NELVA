<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClienteVenta extends Model
{
    use HasFactory;

    protected $table = 'cliente_venta';
    protected $primaryKey = 'id_cliente';
    protected $fillable = [
        'nombres',
        'apellidos',
        'edad',
        'estado_civil',
        'lugar_origen',
        'ocupacion',
        'clave_elector',
        'ine_frente',
        'ine_reverso',
        'id_venta',
    ];

    // Casts para tipos de datos
    protected $casts = [
        'edad' => 'integer',
    ];

    // Relación con Venta (muchos a uno)
    public function venta()
    {
        return $this->belongsTo(Venta::class, 'id_venta', 'id_venta');
    }
    
    // Relación con ClienteContacto
    public function contacto()
    {
        return $this->hasOne(ClienteContacto::class, 'id_cliente', 'id_cliente');
    }

    // Relación con ClienteContacto
    public function direccion()
    {
        return $this->hasOne(ClienteDireccion::class, 'id_cliente', 'id_cliente');
    }
}