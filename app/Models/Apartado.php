<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Apartado extends Model
{
    use HasFactory;

    protected $table = 'apartados';

    protected $primaryKey = 'id_apartado';

    protected $fillable = [
        'tipoApartado',
        'cliente_nombre',
        'cliente_apellidos',
        'fechaApartado',
        'fechaVencimiento',
        'id_usuario',
    ];

    protected $casts = [
        'fechaApartado' => 'date',
        'fechaVencimiento' => 'date',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
