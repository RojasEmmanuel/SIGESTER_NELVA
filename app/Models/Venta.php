<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Venta extends Model
{
     use HasFactory;

    // Nombre de la tabla
    protected $table = 'ventas';

    // Clave primaria
    protected $primaryKey = 'id_venta';

    // Campos que se pueden asignar en masa
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
        'fechaSolicitud' => 'date',
        'enganche' => 'double',
        'total' => 'double',
    ];

    // Relación con Apartado (muchos a uno)
    public function apartado()
    {
        return $this->belongsTo(Apartado::class, 'id_apartado', 'id_apartado');
    }
}
