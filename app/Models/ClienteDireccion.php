<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClienteDireccion extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'cliente_direccion';
    protected $primaryKey = 'id_direccion';
    protected $fillable = [
        'nacionalidad',
        'estado',
        'municipio',
        'localidad',
        'id_cliente',
    ];

    // Relación con ClienteVenta (muchos a uno)
    public function cliente()
    {
        return $this->belongsTo(ClienteVenta::class, 'id_cliente', 'id_cliente');
    }
}