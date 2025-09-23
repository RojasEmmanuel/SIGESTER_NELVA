<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    use HasFactory;
    
    protected $table = 'lotes';
    protected $primaryKey = 'id_lote';

    protected $fillable = [
        'numero_lote',
        'estatus',
        'id_fraccionamiento',
    ];

    protected $casts = [
        'precio_m2' => 'decimal:2',
        'area_total' => 'decimal:2',
    ];

    public function fraccionamiento()
    {
        return $this->belongsTo(Fraccionamiento::class, 'id_fraccionamiento', 'id_fraccionamiento');
    }

    // Relación con LoteMedida (uno a uno)
    public function loteMedida()
    {
        return $this->hasOne(LoteMedida::class, 'id_lote', 'id_lote');
    }
}