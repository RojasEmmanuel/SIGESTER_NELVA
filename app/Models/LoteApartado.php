<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoteApartado extends Model
{
     use HasFactory;

    // Nombre de la tabla
    protected $table = 'lotes_apartados';

    // Clave primaria
    protected $primaryKey = 'id';

    // Campos que se pueden asignar en masa
    protected $fillable = [
        'id_apartado',
        'id_lote',
    ];

    // Relación con Apartado (muchos a uno)
    public function apartado()
    {
        return $this->belongsTo(Apartado::class, 'id_apartado', 'id_apartado');
    }

    // Relación con Lote (muchos a uno)
    public function lote()
    {
        return $this->belongsTo(Lote::class, 'id_lote', 'id_lote');
    }
}
