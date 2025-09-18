<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClienteVenta extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'cliente_venta';

    // Clave primaria
    protected $primaryKey = 'id_cliente';

    // Campos que se pueden asignar en masa
    protected $fillable = [
        'nombres',
        'apellidos',
        'edad',
        'estado_civil',
        'lugar_origen',
        'ocupacion',
        'clave_elector',
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
}
