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
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
    ];
    
    public function fraccionamientos()
    {
        return $this->belongsToMany(
            Fraccionamiento::class,
            'fraccionamiento_promocion',
            'id_promocion',
            'id_fraccionamiento'
        )->withTimestamps();
    }
}