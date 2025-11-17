<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    use HasFactory;
    
    protected $table = 'lotes';
    protected $primaryKey = 'id_lote';
    public $incrementing = false; 
    protected $keyType = 'string'; 

    protected $fillable = [
        'id_lote',        // ← SOLUCIÓN AL BUG 10.2
        'numeroLote',
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
   
    
    // app/Models/Lote.php
    public function loteZona()
    {
        return $this->hasOne(LoteZona::class, 'id_lote', 'id_lote');
    }


    // app/Models/Lote.php
    public function getPrecioM2Attribute()
    {
        // Si tiene zona → usa precio_m2 de la zona
        if ($this->loteZona?->zona?->precio_m2) {
            return (float) $this->loteZona->zona->precio_m2;
        }

        // Si no, usa el precio general del fraccionamiento
        return (float) $this->fraccionamiento?->infoFraccionamiento?->precio_metro_cuadrado ?? 0;
    }
}