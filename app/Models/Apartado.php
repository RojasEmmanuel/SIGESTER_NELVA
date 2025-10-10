<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

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
        'estatus',
        'id_usuario',
    ];

    protected $casts = [
        'fechaApartado' => 'datetime',
        'fechaVencimiento' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function deposito()
    {
        return $this->hasOne(ApartadoDeposito::class, 'id_apartado', 'id_apartado');
    }

    public function lotesApartados()
    {
        return $this->hasMany(LoteApartado::class, 'id_apartado', 'id_apartado');
    }

    public function venta()
    {
        return $this->hasOne(Venta::class, 'id_apartado', 'id_apartado');
    }

    /**
     * Scope para filtrar apartados por estatus
     */
    public function scopeByEstatus($query, $estatus)
    {
        return $query->where('estatus', $estatus);
    }

    /**
     * Método para actualizar el estatus automáticamente
     */
    public function updateEstatus()
    {
        if ($this->venta) {
            $this->estatus = 'venta';
        } elseif ($this->fechaVencimiento->isPast()) {
            $this->estatus = 'vencido';
        } else {
            $this->estatus = 'en curso';
        }
        $this->save();
    }
}