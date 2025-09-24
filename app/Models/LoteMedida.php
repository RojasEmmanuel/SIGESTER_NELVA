<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoteMedida extends Model
{
    use HasFactory;
    protected $table = 'lote_medidas';
    protected $primaryKey = 'id_medidas';
    public $incrementing = true; // este sí parece entero
    protected $keyType = 'int';

    protected $fillable = [
        'manzana',
        'norte',
        'sur',
        'poniente',
        'oriente',
        'area_metros',
        'id_lote',
    ];

    protected $casts = [
        'manzana' => 'integer',
        'norte' => 'double',
        'sur' => 'double',
        'poniente' => 'double',
        'oriente' => 'double',
        'area_metros' => 'double',
    ];

    public function lote()
    {
        return $this->belongsTo(Lote::class, 'id_lote', 'id_lote');
    }
}
