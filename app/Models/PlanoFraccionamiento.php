<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlanoFraccionamiento extends Model
{
    use HasFactory;
    protected $table = 'planos_fraccionamientos';
    protected $primaryKey = 'id_plano';

    protected $fillable = [
        'nombre',
        'plano_path',
        'id_fraccionamiento',
        'id_usuario',
    ];

    public function fraccionamiento()
    {
        return $this->belongsTo(Fraccionamiento::class, 'id_fraccionamiento', 'id_fraccionamiento');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
