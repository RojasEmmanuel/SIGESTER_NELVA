<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Credito extends Model
{
    use HasFactory;
    protected $table = 'creditos';
    protected $primaryKey = 'id_credito';

    protected $fillable = [
        'fecha_inicio',
        'observaciones',
        'plazo_financiamiento',
        'otro_plazo',
        'modalidad_pago',
        'formas_pago',
        'dia_pago',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
    ];
}
