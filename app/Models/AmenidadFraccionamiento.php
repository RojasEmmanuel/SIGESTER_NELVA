<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AmenidadFraccionamiento extends Model
{
    use HasFactory;
    protected $table = 'amenidades_fraccionamiento';
    protected $primaryKey = 'id_amenidad';

    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo',
        'id_fraccionamiento',
    ];

    protected $casts = [
        'tipo' => 'string',
    ];

    public function fraccionamiento()
    {
        return $this->belongsTo(Fraccionamiento::class, 'id_fraccionamiento', 'id_fraccionamiento');
    }
}
