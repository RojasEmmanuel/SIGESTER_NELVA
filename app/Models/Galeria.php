<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeria extends Model
{
    use HasFactory;

    protected $table = 'galeria';
    protected $primaryKey = 'id_foto';

    protected $fillable = [
        'nombre',
        'fotografia_path',
        'fecha_subida',
        'id_fraccionamiento',
    ];

    protected $casts = [
        'fecha_subida' => 'datetime',
    ];

    // Relación inversa con Fraccionamiento (muchos a uno)
    public function fraccionamiento()
    {
        return $this->belongsTo(Fraccionamiento::class, 'id_fraccionamiento', 'id_fraccionamiento');
    }
}