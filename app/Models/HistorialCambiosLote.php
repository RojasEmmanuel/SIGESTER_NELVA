<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HistorialCambiosLote extends Model
{
    use HasFactory;

    protected $table = 'historial_cambios_lotes';
    protected $primaryKey = 'id_cambio';

    protected $fillable = [
        'estatus_anterior',
        'estatus_actual',
        'observaciones',
        'id_lote',
        'id_usuario',
    ];

    public function lote()
    {
        return $this->belongsTo(Lote::class, 'id_lote', 'id_lote');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
