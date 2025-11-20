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
        'pagos',
        'id_venta',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'dia_pago' => 'integer',
        'pagos' => 'decimal:2',
    ];

    // Relación con Venta
    public function venta()
    {
        return $this->belongsTo(Venta::class, 'id_venta', 'id_venta'); // Asumiendo que id_venta es la llave foránea
    }
}
