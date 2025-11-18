<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Zona extends Model
{
    use HasFactory;

    protected $table = 'zonas';
    protected $primaryKey = 'id_zona';

    // ¡¡ESTO ES OBLIGATORIO!!
    public $incrementing = true;      // bigIncrements
    protected $keyType = 'int';       // bigint unsigned

    protected $fillable = [
        'nombre',
        'precio_m2',
        'color',
        'id_fraccionamiento',
    ];

    protected $casts = [
        'precio_m2' => 'decimal:2',
        'color'     => 'string',
    ];

    public function fraccionamiento()
    {
        return $this->belongsTo(Fraccionamiento::class, 'id_fraccionamiento', 'id_fraccionamiento');
    }

    public function lotes()
    {
        return $this->hasMany(LoteZona::class, 'id_zona', 'id_zona');
    }
}