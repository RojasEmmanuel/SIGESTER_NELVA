<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InfoFraccionamiento extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'info_fraccionamiento';

    // Clave primaria
    protected $primaryKey = 'id_info';

    // Campos que se pueden asignar en masa
    protected $fillable = [
        'ubicacionMaps',
        'descripcion',
        'precio_metro_cuadrado',
        'tipo_propiedad',
        'precioGeneral',
        'id_fraccionamiento',
    ];

    // Casts para tipos de datos
    protected $casts = [
        'precio_metro_cuadrado' => 'double',
        'precioGeneral' => 'double',
        'descripcion' => 'string',
    ];

    // Relación con Fraccionamiento (muchos a uno)
    public function fraccionamiento()
    {
        return $this->belongsTo(Fraccionamiento::class, 'id_fraccionamiento', 'id_fraccionamiento');
    }
}