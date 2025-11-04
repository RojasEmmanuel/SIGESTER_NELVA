<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoteZona extends Model
{
    protected $table = 'lote_zona';
    public $timestamps = true;

    protected $fillable = ['id_lote', 'id_zona'];

    // Relación con Lote (string)
    public function lote()
    {
        return $this->belongsTo(Lote::class, 'id_lote', 'id_lote');
    }

    // Relación con Zona (int)
    public function zona()
    {
        return $this->belongsTo(Zona::class, 'id_zona', 'id_zona');
    }
}