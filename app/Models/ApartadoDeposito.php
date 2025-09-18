<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApartadoDeposito extends Model
{
    use HasFactory;

    protected $table = 'apartados_deposito';
    protected $primaryKey = 'id_deposito';

    protected $fillable = [
        'cantidad',
        'ticket_estatus',
        'path_ticket',
        'observaciones',
        'id_apartado',
    ];

    protected $casts = [
        'cantidad' => 'double',
    ];

    public function apartado()
    {
        return $this->belongsTo(Apartado::class, 'id_apartado', 'id_apartado');
    }
}
