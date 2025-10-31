<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    protected $table = 'promociones';
    protected $primaryKey = 'id_promocion';

    protected $fillable = [
        'titulo',
        'descripcion',
        'imagen_path',
        'fecha_inicio',
        'fecha_fin',
        'id_fraccionamiento',
    ];

    public function fraccionamiento()
    {
        return $this->belongsTo(Fraccionamiento::class, 'id_fraccionamiento', 'id_fraccionamiento');
    }
    
}
