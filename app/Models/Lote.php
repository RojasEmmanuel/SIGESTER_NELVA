<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    use HasFactory;
    
    protected $table = 'lotes';
    protected $primaryKey = 'id_lote';
    public $incrementing = false; // si tu id_lote es string (ej. OCEANICA-1-1)
    protected $keyType = 'string'; // igual si es string

    // ✅ CORREGIDO: Usar el nombre real de la columna en la BD
    protected $fillable = [
        'numeroLote',  // ← CAMBIAR de 'numero_lote' a 'numeroLote'
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


    // ─────── NUEVO: RELACIÓN CON ZONA ───────
    public function zonaRelacion()
    {
        return $this->hasOne(LoteZona::class, 'id_lote', 'id_lote');
    }

    // Magia: $lote->precio_m2
    public function getPrecioM2Attribute()
    {
        return $this->zonaRelacion?->zona?->precio_m2 ?? 0;
    }
}