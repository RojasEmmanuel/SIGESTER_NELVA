<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fraccionamiento extends Model
{
    use HasFactory;
    protected $table = 'fraccionamientos';
    protected $primaryKey = 'id_fraccionamiento';

    protected $fillable = [
        'nombre',
        'ubicacion',
        'path_imagen',
        'estatus',
    ];

    protected $casts = [
        'estatus' => 'boolean',
    ];

    public function lotes()
    {
        return $this->hasMany(Lote::class, 'id_fraccionamiento', 'id_fraccionamiento');
    }

    // Relación con InfoFraccionamiento (uno a uno)
    public function infoFraccionamiento()
    {
        return $this->hasOne(InfoFraccionamiento::class, 'id_fraccionamiento', 'id_fraccionamiento');
    }

    // Relación con PlanoFraccionamiento (uno a muchos)
    public function planosFraccionamiento()
    {
        return $this->hasMany(PlanoFraccionamiento::class, 'id_fraccionamiento', 'id_fraccionamiento');
    }

    // Relación con AmenidadFraccionamiento (uno a muchos)
    public function amenidadesFraccionamiento()
    {
        return $this->hasMany(AmenidadFraccionamiento::class, 'id_fraccionamiento', 'id_fraccionamiento');
    }

    // Relación con Galeria (uno a muchos)
    public function galeria()
    {
        return $this->hasMany(Galeria::class, 'id_fraccionamiento', 'id_fraccionamiento');
    }

    // Relación con ArchivosFraccionamiento (uno a muchos)
    public function archivosFraccionamiento()
    {
        return $this->hasMany(ArchivosFraccionamiento::class, 'id_fraccionamiento', 'id_fraccionamiento');
    }
}