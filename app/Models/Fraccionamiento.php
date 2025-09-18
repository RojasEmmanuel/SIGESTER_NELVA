<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fraccionamiento extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'fraccionamientos';

    // Clave primaria
    protected $primaryKey = 'id_fraccionamiento';

    // Campos que se pueden asignar en masa
    protected $fillable = [
        'nombre',
        'ubicacion',
        'path_imagen',
        'estatus',
    ];

    // Casts para tipos de datos específicos
    protected $casts = [
        'estatus' => 'boolean',
    ];

    // Relación con Lotes (uno a muchos)
    public function lotes()
    {
        return $this->hasMany(Lote::class, 'id_fraccionamiento', 'id_fraccionamiento');
    }
}
