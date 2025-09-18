<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lote extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'lotes';

    // Clave primaria personalizada
    protected $primaryKey = 'id_lote';
    public $incrementing = false; // porque la PK es string
    protected $keyType = 'string';

    // Campos que se pueden asignar en masa
    protected $fillable = [
        'id_lote',
        'numeroLote',
        'estatus',
        'id_fraccionamiento',
    ];

    // Casts para tipos de datos
    protected $casts = [
        'numeroLote' => 'integer',
    ];

    // Relación con Fraccionamiento (muchos a uno)
    public function fraccionamiento()
    {
        return $this->belongsTo(Fraccionamiento::class, 'id_fraccionamiento', 'id_fraccionamiento');
    }
}
