<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsesorInfo extends Model
{
    use HasFactory;

    protected $table = 'asesores_info';
    protected $primaryKey = 'id_asesor';

    // Campos que se pueden asignar en masa
    protected $fillable = [
        'zona',
        'path_facebook',
        'path_fotografia',
        'id_usuario',
    ];

    // Casts para asegurar tipos de datos
    protected $casts = [
        'zona' => 'string',
    ];

    // Relación con Usuario (muchos a uno)
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
